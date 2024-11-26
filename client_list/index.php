<?php
date_default_timezone_set('America/Denver');
require_once "../app/database/connection.php";
require_once "../path.php";
session_start();

$files = glob("../app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}

// Trigger the logout function
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    logoutUser($conn);
}

redirectIfNotLoggedIn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Client List - ARC Platform</title>
</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sub_header.php"); ?>

    <!-- main-container -->
    <div class="" style="padding: 0 20px 0 20px;">
            <h2 class="mt-4">
                Client List
            </h2>
            <form style="width: 350px;">
                <div class="input-group">
                    
                    <input 
                        type="text" 
                        class="form-control border-start-0" 
                        id="search_bar" 
                        placeholder="Search clients..."

                        style="border: 1px solid black;"
                    >
                    <span class="input-group-text bg-white" style="margin-right: -10px; border: 1px solid black;">
                        <i class="bi bi-search text-secondary"></i>
                    </span>
                </div>
            </form>
            <hr>

            <table class="table table-hover">
            <thead style="bg-dark text-white">
                <tr>
                    <!-- <th scope="col">ID</th> -->
                    <th scope="col">Client</th>
                    <th scope="col">Primary Contact</th>
                    <th scope="col">Current Frameworks</th>
                    <!-- <th scope="col">Open QA Comments</th> -->
                    <th style="width: 100px; text-align: center;"></th>
                    <!-- <th style="width: 100px; text-align: center;">Edit</th> -->
                    <!-- <th style="width: 100px; text-align: center;">Delete</th> -->
                </tr>
            </thead>

            <tbody>
                    <?php
                    // Pagination variables
                    $limit = 10; 
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    $offset = ($page - 1) * $limit;

                    // Query to get clients, the total number of engagements, and open QA comments for each client
                    $sql = "SELECT * FROM clients ORDER BY client_created DESC LIMIT $limit OFFSET $offset";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        $num_rows = mysqli_num_rows($result);
                        if ($num_rows > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $idno = $row['idno'];
                                $id = $row['client_id'];
                                $client_name = $row['client_name'];
                                $logo = $row['logo'];
                                $primary_contact = $row['primary_contact'];
                                $contact_email = $row['contact_email'];
                                $random_color = $row['random_color'];

                                ?>

                                <tr class="client-list-row" onclick="window.location.href='<?php echo BASE_URL; ?>/client_list/details/?id=<?php echo $id; ?>'">
                                <td>
                                    <?php
                                    // Check if the logo exists and display it, otherwise show a circle with the first letter
                                    if (!empty($logo)) { ?>
                                        <img class="me-2" src="<?php echo BASE_URL; ?>/assets/images/client_images/<?php echo $logo; ?>.png" width="50" style="border-radius: 15px;">
                                        <?php echo $client_name ? $client_name : '-'; ?>
                                    <?php } else {
                                        $first_letter = strtoupper(substr($client_name, 0, 1));
                                    ?>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2" style="background-color: <?php echo $random_color; ?>; width: 50px; height: 50px; border-radius: 15px; display: flex; justify-content: center; align-items: center; font-size: 24px; color: white;">
                                                <?php echo $first_letter; ?>
                                            </div>
                                            <span><?php echo $client_name ? $client_name : '-'; ?></span>
                                        </div>
                                    <?php } ?>
                                </td>


                                    <td>
                                        <?php echo $primary_contact ? $primary_contact : '-'; ?><br>
                                        <a style="color: #3c6caa; text-decoration: none;" href="mailto:<?php echo $contact_email; ?>">
                                            <?php echo $contact_email ? $contact_email : '-'; ?>
                                        </a>
                                    </td>
                                    <td class="">
                                        <?php 
                                        $e_sql = "SELECT * FROM engagement WHERE client_name='$client_name'";
                                        $e_result = mysqli_query($conn, $e_sql);

                                        if ($e_result) {
                                            $e_num_rows = mysqli_num_rows($e_result);
                                            if ($e_num_rows > 0) {
                                                $badges = []; // Array to hold all engagement types as badges
                                                while ($e_row = mysqli_fetch_assoc($e_result)) {
                                                    $engagement_type = $e_row['engagement_type'];

                                                    // Check for specific engagement types and add them as badges
                                                    if (strpos($engagement_type, 'SOC 2') !== false) {
                                                        $badges[] = '<span class="badge soc-2-badge">SOC 2</span>';
                                                    }
                                                    if (strpos($engagement_type, 'SOC 1') !== false) {
                                                        $badges[] = '<span class="badge soc-1-badge">SOC 1</span>';
                                                    }
                                                    if (strpos($engagement_type, 'PCI') !== false) {
                                                        $badges[] = '<span class="badge pci-badge">PCI</span>';
                                                    }
                                                    if (strpos($engagement_type, 'HIPAA') !== false) {
                                                        $badges[] = '<span class="badge hipaa-badge">HIPAA</span>';
                                                    }
                                                }
                                                echo implode(' ', $badges);
                                            } else {
                                                echo "No engagement types found.";
                                            }
                                        } else {
                                            echo "Error executing query.";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-chevron-right text-secondary"></i>
                                    </td>
                                </tr>

                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='7'>No records found.</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Error fetching data: " . mysqli_error($conn) . "</td></tr>";
                    }
                    ?>
            </tbody>
        </table>
        <br>
        <?php
    // Pagination logic
    $sql_total = "SELECT COUNT(*) as total FROM clients";
    $result_total = mysqli_query($conn, $sql_total);
    if ($result_total) {
        $row = mysqli_fetch_assoc($result_total);
        $total_records = $row["total"];
        $total_pages = ceil($total_records / $limit);
    } else {
        $total_pages = 1; // If there is an error, default to 1 page
    }

    // Display pagination if there are more than 10 records
    if ($total_records > $limit) {
        echo '<ul class="pagination justify-content-center">';
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($page == $i) ? "active" : "";
            echo "<li class='page-item {$active}'><a class='page-link' href='?page={$i}'>{$i}</a></li>";
        }
        echo '</ul>';
    }
?>


        </div>
    <!-- END main-container -->

</body>
</html>
