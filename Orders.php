<?php
session_start();
include_once 'db_connect.php';

// Function to fetch all orders
function getOrders() {
    global $db; 

    try {
        // SQL to fetch orders
        $sql = "SELECT * FROM orders";
        
        // Execute query
        $result = $db->query($sql);

        // Check the query 
        if ($result) {
            // Array to store orders
            $orders = array();

            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                // Add the row to the orders array
                $orders[] = $row;
            }

            // Return orders as JSON
            return json_encode($orders);
        } else {
            // Error message
            return json_encode(["error" => "Failed to fetch orders"]);
        }
    } catch(Exception $e) {
        // Handle errors 
        return json_encode(["error" => $e->getMessage()]);
    }
}



    // Call function to fetch all orders, encode the response, and echo it
    echo json_encode(getOrders());


?>