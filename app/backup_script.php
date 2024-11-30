<?php

// Database connection parameters
$host = 'localhost';  // Database host
$user = 'dbuser';     // Database username
$password = 'DBuser123!'; // Database password
$dbname = 'arcplatform'; // Database name

// Connect to the database
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve backup configurations
$configs = [];
$result = $conn->query("SELECT config_name, value FROM backup_configs");
if (!$result) {
    die("Error: Failed to retrieve backup configurations. " . $conn->error);
}
while ($row = $result->fetch_assoc()) {
    $configs[$row['config_name']] = $row['value'];
}

// Ensure both required configurations exist
if (!isset($configs['retention_period']) || !isset($configs['backup_schedule'])) {
    die("Error: Backup configurations (retention_period and backup_schedule) are not set in the database.");
}

// Parse configurations from the database
$retentionPeriod = intval(preg_replace('/\D/', '', $configs['retention_period']));
$backupSchedule = $configs['backup_schedule'];

// Backup storage location
$backupDir = __DIR__ . '/backup_files/';
if (!is_dir($backupDir)) {
    if (!mkdir($backupDir, 0777, true)) {
        die("Error: Failed to create backup directory.");
    }
}

// Generate backup file name
$date = date('Y-m-d');
$backup_name = "arcplatform-backup-$date"; // <-- Added semicolon here
$backupFile = $backupDir . "$backup_name.sql";

// Create backup using mysqldump
$command = "mysqldump --host=$host --user=$user --password=$password $dbname > $backupFile";
exec($command, $output, $result);

// Record backup status
$status = ucfirst($result === 0 ? 'success' : 'failed');

// Insert backup info into the database
$backupTime = date('Y-m-d H:i:s');
$stmt = $conn->prepare("INSERT INTO backups (backup_name, backup_time, file_path, status) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("Error: Failed to prepare statement. " . $conn->error);
}
$stmt->bind_param("ssss", $backup_name, $backupTime, $backupFile, $status);
$stmt->execute();
$stmt->close();

// Notify via Slack if configured
$slackResult = $conn->query("SELECT webhook FROM backup_notifications WHERE notification_type = 'slack'");
if ($slackResult && $slackResult->num_rows > 0) {
    while ($row = $slackResult->fetch_assoc()) {
        $webhookUrl = $row['webhook'];

        // Determine color based on backup status
        $color = $status === 'Success' ? '#36a64f' : '#ff0000'; // Green for success, red for failure

        // Prepare the Slack message payload
        $payload = [
            'attachments' => [
                [
                    'color' => $color, // Sets the vertical line color
                    'blocks' => [
                        [
                            'type' => 'header',
                            'text' => [
                                'type' => 'plain_text',
                                'text' => "🚨 Backup Notification",
                                'emoji' => true
                            ]
                        ],
                        [
                            'type' => 'section',
                            'fields' => [
                                [
                                    'type' => 'mrkdwn',
                                    'text' => "*Time:*\n$backupTime"
                                ],
                                [
                                    'type' => 'mrkdwn',
                                    'text' => "*Status:*\n$status"
                                ]
                            ]
                        ],
                        [
                            'type' => 'section',
                            'text' => [
                                'type' => 'mrkdwn',
                                'text' => $status === 'Success' 
                                    ? "*File Path:*\n`$backupFile`" 
                                    : "*Error:*\nBackup failed!"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Initialize cURL for the Slack webhook
        $ch = curl_init($webhookUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload)); // Slack expects JSON payload
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Capture the response
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json', // Slack expects application/json
        ]);

        // Execute and capture the response
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Check for errors
        if (curl_errno($ch)) {
            echo "Slack notification failed: " . curl_error($ch) . "\n";
        } elseif ($httpCode !== 200) {
            echo "Slack notification failed with HTTP code $httpCode. Response: $response\n";
        } else {
            echo "";
        }

        curl_close($ch);
    }
}



// Enforce retention policy
$result = $conn->query("SELECT backup_id, file_path FROM backups ORDER BY backup_time DESC");
if (!$result) {
    die("Error: Failed to fetch backups for retention policy. " . $conn->error);
}

$backups = [];
while ($row = $result->fetch_assoc()) {
    $backups[] = $row;
}


// Remove older backups beyond retention period
if (count($backups) > $retentionPeriod) {
    $toDelete = array_slice($backups, $retentionPeriod); // Get backups beyond retention period
    
    foreach ($toDelete as $backup) {
        // Delete backup file
        if (file_exists($backup['file_path'])) {
            if (unlink($backup['file_path'])) {
                // echo "Successfully deleted backup file: " . $backup['file_path'] . "\n";
            } else {
                echo "Error: Failed to delete backup file: " . $backup['file_path'] . "\n";
            }
        } else {
            echo "Warning: Backup file not found: " . $backup['file_path'] . "\n";
        }

        // Remove entry from database
        $stmt = $conn->prepare("DELETE FROM backups WHERE backup_id = ?");
        if (!$stmt) {
            echo "Error: Failed to prepare deletion statement for backup ID " . $backup['backup_id'] . ". " . $conn->error . "\n";
            continue;
        }
        $stmt->bind_param("i", $backup['backup_id']);
        $stmt->execute();
        $stmt->close();
        
        // echo "Successfully deleted backup record from database for ID: " . $backup['backup_id'] . "\n";
    }
}


// Close the database connection
$conn->close();

// echo "Backup process completed.\n";


?>
