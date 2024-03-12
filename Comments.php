<?php
session_start();
include_once 'db_connect.php';

// Function to fetch all comments
function getComments() {
    global $db; 

    try {
        // SQL to fetch comments
        $sql = "SELECT * FROM comments";
        
        // Execute query
        $result = $db->query($sql);

        // Check the query 
        if ($result) {
            // Array to store comments
            $comments = array();

            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                // Add the row to the comments array
                $comments[] = $row;
            }

            // Return comments
            echo json_encode($comments);
        } else {
            // Error message
            echo json_encode(["error" => "Failed to fetch comments"]);
        }
    } catch(Exception $e) {
        // Handle errors 
        echo json_encode(["error" => $e->getMessage()]);
    }
}


// Call function to fetch all comments
    getComments();

?>