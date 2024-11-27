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

// delete engagement
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['me_id'])) {
        $me_id = intval($_GET['me_id']); // Sanitize the input to prevent SQL injection

        // Prepare the SQL query
        $sql = "DELETE FROM engagement WHERE engagement_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $me_id);

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
// end delete engagement

// edit engagement
    // Check if the form is submitted
    if (isset($_POST['edit_engagement'])) {
        // Get the form data
        $engagement_id = $_POST['me_edit_engagement_id'];
        $client_name = empty($_POST['e_client_name']) ? null : $_POST['e_client_name'];
        $engagement_type = empty($_POST['e_engagement_type']) ? null : $_POST['e_engagement_type'];
        $year = empty($_POST['year']) ? null : $_POST['year'];
        $report_start = empty($_POST['report_start']) ? null : $_POST['report_start'];
        $report_end = empty($_POST['report_end']) ? null : $_POST['report_end'];
        $report_as_of = empty($_POST['report_as_of']) ? null : $_POST['report_as_of'];
        $manager = empty($_POST['manager']) ? null : $_POST['manager'];
        $senior = empty($_POST['senior']) ? null : $_POST['senior'];
        $staff = empty($_POST['staff']) ? null : $_POST['staff'];
        $leadsheets_due = empty($_POST['leadsheet_due']) ? null : $_POST['leadsheet_due'];
        $field_work_week = empty($_POST['field_work_week']) ? null : $_POST['field_work_week'];
        $senior_dol = empty($_POST['senior_dol']) ? null : $_POST['senior_dol'];
        $staff_1_dol = empty($_POST['staff_1_dol']) ? null : $_POST['staff_1_dol'];
        $staff_2_dol = empty($_POST['staff_2_dol']) ? null : $_POST['staff_2_dol'];

        // Validate required fields
        if (empty($engagement_id) || empty($client_name) || empty($engagement_type) || empty($year)) {
            echo "Engagement ID, Client Name, Engagement Type, and Year are required!";
            exit;
        }

        // Prepare the update query
        $sql = "UPDATE engagement
                SET client_name = ?, engagement_type = ?, year = ?, 
                    report_start = ?, report_end = ?, report_as_of = ?, 
                    manager = ?, senior = ?, staff = ?, 
                    leadsheets_due = ?, field_work_week = ?, 
                    senior_dol = ?, staff_1_dol = ?, staff_2_dol = ? 
                WHERE engagement_id = ?";

        $stmt = $conn->prepare($sql);

        // Check if prepare() failed
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind the parameters
        $stmt->bind_param(
            "ssssssssssssssi", 
            $client_name, $engagement_type, $year, 
            $report_start, $report_end, $report_as_of, 
            $manager, $senior, $staff, 
            $leadsheets_due, $field_work_week, 
            $senior_dol, $staff_1_dol, $staff_2_dol, 
            $engagement_id
        );

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            header("Location: /");
        } else {
            echo "Error executing statement: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
// end edit engagement


// delete qa_comment
    if (isset($_GET['action']) && $_GET['action'] === 'delete_qa_comment' && isset($_GET['qa_id'])) {
        $qa_id = intval($_GET['qa_id']); // Sanitize the input to prevent SQL injection

        // Prepare the SQL query
        $sql = "DELETE FROM qa_comments WHERE qa_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $qa_id);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect back to the same page after successful deletion
                header("Location: /");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error deleting qa_comment: " . $stmt->error . "</div>";
            }

            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Error preparing the statement: " . $conn->error . "</div>";
        }
    }
// end delete qa_comment


?>