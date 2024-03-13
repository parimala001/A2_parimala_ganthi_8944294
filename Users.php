<?php
session_start();
include_once 'db_connect.php';

// Function to fetch all users
function getUsers() {
    global $db; 

    try {
        // SQL to fetch users
        $sql = "SELECT * FROM user";
        
        // Execute query
        $result = $db->query($sql);

        // Check the query 
        if ($result) {
            // Array to store users
            $users = array();

            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                // Add the row to the users array
                $users[] = $row;
            }

            // Set the Content-Type header to application/json
            header('Content-Type: application/json');

            // Return users as JSON
            echo json_encode(["data" => $users]);
        } else {
            // Error message
            header('Content-Type: application/json');
            echo json_encode(["error" => "Failed to fetch users"]);
        }
    } catch(Exception $e) {
        // Handle errors 
        header('Content-Type: application/json');
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Function to create a new user
function createUser($data) {
    global $db;

    try {
        // Extract data from the request
        $email = $data['email'];
        $password = $data['password'];
        $username = $data['username'];
        $purchase_history = $data['purchase_history'];
        $shipping_adrss = $data['shipping_adrss'];

        // SQL to insert a new user
        $sql = "INSERT INTO user (email, password, username, purchase_history, shipping_adrss) VALUES ('$email', '$password', '$username', '$purchase_history', '$shipping_adrss')";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            // Set the Content-Type header to application/json
            header('Content-Type: application/json');
            // Return JSON response
            echo json_encode(["message" => "User created successfully"]);
        } else {
            // Set the Content-Type header to application/json
            header('Content-Type: application/json');
            // Return JSON response
            echo json_encode(["error" => "Failed to create user"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        // Set the Content-Type header to application/json
        header('Content-Type: application/json');
        // Return JSON response
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Function to fetch a user by user_id
function getUserById($user_id) {
    global $db;

    try {
        // SQL to fetch user by user_id
        $sql = "SELECT * FROM user WHERE user_id = $user_id";

        // Execute query
        $result = $db->query($sql);

        // Check if user exists
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // User not found
        }
    } catch (Exception $e) {
        // Handle errors
        return ["error" => $e->getMessage()];
    }
}

// Function to update a user
function updateUser($user_id, $data) {
    global $db;

    try {
        // Extract data from the request
        $email = $data['email'];
        $password = $data['password'];
        $username = $data['username'];
        $purchase_history = $data['purchase_history'];
        $shipping_adrss = $data['shipping_adrss'];

        // SQL to update user
        $sql = "UPDATE user SET email='$email', password='$password', username='$username', purchase_history='$purchase_history', shipping_adrss='$shipping_adrss' WHERE user_id = $user_id";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            // Return JSON response
            return ["message" => "User updated successfully"];
        } else {
            // Return JSON response for error
            return ["error" => "Failed to update user"];
        }
    } catch (Exception $e) {
        // Handle any errors
        return ["error" => $e->getMessage()];
    }
}

// Function to delete a user
function deleteUser($user_id) {
    global $db;

    try {
        // SQL to delete user
        $sql = "DELETE FROM user WHERE user_id = $user_id";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            // Return JSON response
            return ["message" => "User deleted successfully"];
        } else {
            // Return JSON response for error
            return ["error" => "Failed to delete user"];
        }
    } catch (Exception $e) {
        // Handle any errors
        return ["error" => $e->getMessage()];
    }
}

// Determine the HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle POST request to create a new user
if ($method == 'POST') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to create user
    createUser($data);
} 
// Handle PUT request to update a user
else if ($method == 'PUT') {
    // Get user_id from the URL
    $user_id = $_GET['user_id'];

    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to update user
    $response = updateUser($user_id, $data);

    // Set the Content-Type header to application/json
    header('Content-Type: application/json');

    // Return JSON response
    echo json_encode($response);
} 
// Handle DELETE request to delete a user
else if ($method == 'DELETE') {
    // Get user_id from the URL
    $user_id = $_GET['user_id'];

    // Call function to delete user
    $response = deleteUser($user_id);

    // Set the Content-Type header to application/json
    header('Content-Type: application/json');

    // Return JSON response
    echo json_encode($response);
} 
// Handle GET request to fetch all users
else {
    getUsers();
}

?>
