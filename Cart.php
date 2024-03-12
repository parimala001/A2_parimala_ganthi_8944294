<?php
session_start();
include_once 'db_connect.php';

// Function to fetch all items in the cart
function getCartItems() {
    global $db; 

    try {
        // SQL to fetch items in the cart
        $sql = "SELECT * FROM cart";
        
        // Execute query
        $result = $db->query($sql);

        // Check the query 
        if ($result) {
            //  array to store cart items
            $cartItems = array();

            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                // Add the row to the cart items array
                $cartItems[] = $row;
            }

            // Return cart items
            echo json_encode($cartItems);
        } else {
            //  error message
            echo json_encode(["error" => "Failed to fetch cart items"]);
        }
    } catch(Exception $e) {
        // Handle errors 
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Function to add an item to the cart
function addToCart($data) {
    global $db;

    try {
        // Extract data from the request
        $cart_id = isset($data['cart_id']) ? $data['cart_id'] : null;
        $products = $data['products'];
        $quantities = $data['quantities'];
        $user = $data['user'];

        // SQL to insert an item into the cart
        $sql = "INSERT INTO cart (cart_id, products, quantities, user) VALUES ('$cart_id', '$products', '$quantities', '$user')";

        // Execute query
        $result = $db->query($sql);

        // Check the query was successful
        if ($result) {
            echo json_encode(["message" => "Item added to cart successfully"]);
        } else {
            echo json_encode(["error" => "Failed to add item to cart"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Function to update an item in the cart
function updateCartItem($data) {
    global $db;

    try {
        // Extract data from the request
        $cart_id = $data['cart_id'];
        $products = $data['products'];
        $quantities = $data['quantities'];
        $user = $data['user'];

        // SQL to update the cart item
        $sql = "UPDATE cart SET products='$products', quantities='$quantities', user='$user' WHERE cart_id='$cart_id'";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            echo json_encode(["message" => "Cart item updated successfully"]);
        } else {
            echo json_encode(["error" => "Failed to update cart item"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Function to delete an item from the cart
function deleteCartItem($cart_id) {
    global $db;

    try {
        // SQL to delete the cart item
        $sql = "DELETE FROM cart WHERE cart_id='$cart_id'";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            echo json_encode(["message" => "Cart item deleted successfully"]);
        } else {
            echo json_encode(["error" => "Failed to delete cart item"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Determine the HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle POST request to add an item to the cart
if ($method == 'POST') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to add item to cart
    addToCart($data);
} 
// Handle PUT request to update an item in the cart
else if ($method == 'PUT') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to update cart item
    updateCartItem($data);
} 
// Determine the HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle DELETE request to delete an item from the cart
if ($method == 'DELETE') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Get the cart ID from the request body
    $cart_id = $data['cart_id'] ?? null;

    if ($cart_id !== null) {
        // Call function to delete cart item
        deleteCartItem($cart_id);
    } else {
        echo json_encode(["error" => "Cart ID is required"]);
    }
} 
else {
    // Call function to fetch all items in the cart
    getCartItems();
}
?>
