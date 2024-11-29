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
$retentionPeriod = intval($configs['retention_period']); // Example: 7 days
$backupSchedule = $configs['backup_schedule']; // Example: 'daily'

// Optional: Validate values
if ($retentionPeriod <= 0) {
    die("Error: Invalid retention_period value. It must be greater than 0.");
}
if (!in_array($backupSchedule, ['daily', 'weekly', 'monthly'])) {
    die("Error: Invalid backup_schedule value. Allowed values are 'daily', 'weekly', or 'monthly'.");
}

// Backup storage location
$backupDir = __DIR__ . '/backup_files/';
if (!is_dir($backupDir)) {
    if (!mkdir($backupDir, 0777, true)) {
        die("Error: Failed to create backup directory.");
    }
}

// Generate backup file name
$date = date('Y-m-d_H-i-s');
$backupFile = $backupDir . "backup_$date.sql";

// Create backup using mysqldump
$command = "mysqldump --host=$host --user=$user --password=$password $dbname > $backupFile";
exec($command, $output, $result);

// Record backup status
$status = $result === 0 ? 'success' : 'failed';

// Insert backup info into the database
$backupTime = date('Y-m-d H:i:s');
$stmt = $conn->prepare("INSERT INTO backups (backup_time, file_path, status) VALUES (?, ?, ?)");
if (!$stmt) {
    die("Error: Failed to prepare statement. " . $conn->error);
}
$stmt->bind_param("sss", $backupTime, $backupFile, $status);
$stmt->execute();
$stmt->close();

if ($status === 'success') {
    echo "Backup successfully created: $backupFile\n";
} else {
    echo "Backup failed!\n";
}

// Enforce retention policy
$result = $conn->query("SELECT id, file_path FROM backups ORDER BY backup_time DESC");
if (!$result) {
    die("Error: Failed to fetch backups for retention policy. " . $conn->error);
}

$backups = [];
while ($row = $result->fetch_assoc()) {
    $backups[] = $row;
}

// Remove older backups beyond retention period
if (count($backups) > $retentionPeriod) {
    $toDelete = array_slice($backups, $retentionPeriod);
    foreach ($toDelete as $backup) {
        // Delete backup file
        if (file_exists($backup['file_path'])) {
            unlink($backup['file_path']);
        } else {
            echo "Warning: Backup file not found: " . $backup['file_path'] . "\n";
        }

        // Remove entry from database
        $stmt = $conn->prepare("DELETE FROM backups WHERE id = ?");
        if (!$stmt) {
            echo "Error: Failed to prepare deletion statement for backup ID " . $backup['id'] . ". " . $conn->error . "\n";
            continue;
        }
        $stmt->bind_param("i", $backup['id']);
        $stmt->execute();
        $stmt->close();
    }
}

// Close the database connection
$conn->close();

echo "Backup process completed.\n";

// Schedule script execution using a cron job
// Example cron: 15 1 * * * php /path/to/backup_script.php
?>
