<?php

// delete client
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['dc_id'])) {
        $dc_id = intval($_GET['dc_id']); // Sanitize the input to prevent SQL injection

        // Prepare the SQL query
        $sql = "DELETE FROM clients WHERE client_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $dc_id);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect back to the same page after successful deletion
                header("Location: /");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error deleting client: " . $stmt->error . "</div>";
            }

            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Error preparing the statement: " . $conn->error . "</div>";
        }
    }
// end delete client


?>