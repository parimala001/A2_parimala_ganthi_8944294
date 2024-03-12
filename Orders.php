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

            // Set the Content-Type header to application/json
            header('Content-Type: application/json');

            // Return orders as JSON
            echo json_encode($orders);
        } else {
            // Error message
            echo json_encode(["error" => "Failed to fetch orders"]);
        }
    } catch(Exception $e) {
        // Handle errors 
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Function to create a new order
function createOrder($data) {
    global $db;

    try {
        // Extract data from the request
        $record_of_sale = $data['record_of_sale'];

        // SQL to insert a new order
        $sql = "INSERT INTO orders (record_of_sale) VALUES ('$record_of_sale')";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            // Set the Content-Type header to application/json
            header('Content-Type: application/json');
            echo json_encode(["message" => "Order created successfully"]);
        } else {
            // Set the Content-Type header to application/json
            header('Content-Type: application/json');
            echo json_encode(["error" => "Failed to create order"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        header('Content-Type: application/json');
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Function to update an order
function updateOrder($data) {
    global $db;

    try {
        // Extract data from the request
        $orders_id = $data['orders_id'];
        $record_of_sale = $data['record_of_sale'];

        // SQL to update the order
        $sql = "UPDATE orders SET record_of_sale='$record_of_sale' WHERE orders_id='$orders_id'";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            // Set the Content-Type header to application/json
            header('Content-Type: application/json');
            echo json_encode(["message" => "Order updated successfully"]);
        } else {
            // Set the Content-Type header to application/json
            header('Content-Type: application/json');
            echo json_encode(["error" => "Failed to update order"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        header('Content-Type: application/json');
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Function to delete an order
function deleteOrder($orders_id) {
    global $db;

    try {
        // SQL to delete the order
        $sql = "DELETE FROM orders WHERE orders_id='$orders_id'";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            // Set the Content-Type header to application/json
            header('Content-Type: application/json');
            echo json_encode(["message" => "Order deleted successfully"]);
        } else {
            // Set the Content-Type header to application/json
            header('Content-Type: application/json');
            echo json_encode(["error" => "Failed to delete order"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        header('Content-Type: application/json');
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Determine the HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle POST request to create a new order
if ($method == 'POST') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to create order
    createOrder($data);
} 
// Handle PUT request to update an existing order
else if ($method == 'PUT') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to update order
    updateOrder($data);
} 
// Handle DELETE request to delete an existing order
else if ($method == 'DELETE') {
    // Get the order ID from the request body
    $orders_id = $_GET['orders_id'] ?? null;

    if ($orders_id !== null) {
        // Call function to delete order
        deleteOrder($orders_id);
    } else {
        // Set the Content-Type header to application/json
        header('Content-Type: application/json');
        echo json_encode(["error" => "Order ID is required"]);
    }
} 
// Handle GET request to fetch all orders
else {
    // Call function to fetch all orders
    getOrders();
}
?>
