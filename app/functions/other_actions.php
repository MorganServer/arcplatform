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

// update client
    // Check if the form is submitted
    if (isset($_POST['edit_client'])) {
        // Get the form data
        $client_id = $_POST['edit_client_id'];
        $client_name = $_POST['edit_client_name'];
        $primary_contact = $_POST['edit_primary_contact'];
        $contact_email = $_POST['edit_contact_email'];
        $has_logo = isset($_POST['edit_has_logo']) ? 1 : 0; // 1 if checked, 0 if not

        // Validate input data (simple example, you can add more validation as needed)
        if (empty($client_name) || empty($primary_contact) || empty($contact_email)) {
            echo "All fields are required!";
            exit;
        }

        // If 'has_logo' is checked, modify client_name (lowercase and replace spaces with underscores)
        if ($has_logo == 1) {
            $logo = strtolower(str_replace(' ', '_', $client_name));
        } else {
            // If 'has_logo' is unchecked, set client_name to null
            $logo = null;
        }

        // Update query to modify client data
        $sql = "UPDATE clients SET client_name = ?, primary_contact = ?, contact_email = ?, logo = ? WHERE client_id = ?";
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ssssi", $client_name, $primary_contact, $contact_email, $logo, $client_id);

        // Execute the query and check if the update was successful
        if ($stmt->execute()) {
            header("Location: /");
        } else {
            echo "Error updating client: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }



// end update client


?>