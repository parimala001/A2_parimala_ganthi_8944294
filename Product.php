<?php
session_start();
include_once 'db_connect.php';

// Function to fetch all products
function getProducts() {
    global $db; 

    try {
        // SQL to fetch products
        $sql = "SELECT * FROM product";
        
        // Execute query
        $result = $db->query($sql);

        // Check the query 
        if ($result) {
            // Initialize an array to store products
            $products = array();

            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                // Add the row to the products array
                $products[] = $row;
            }

            // Return products as JSON
            echo json_encode($products);
        } else {
            // If the query fails, output an error message
            echo "Error: " . $db->error;
        }
    } catch(Exception $e) {
        // Handle any errors 
        echo "Error: " . $e->getMessage();
    }
}
// Function to create a new product
function createProduct($data) {
    global $db;

    try {
        // Extract data from the request
        $product_id = isset($data['product_id']) ? $data['product_id'] : null;
        $description = $data['description'];
        $image = $data['image'];
        $pricing = $data['pricing'];
        $shipping_cost = $data['shipping_cost'];

        // SQL to insert a new product
        $sql = "INSERT INTO product (product_id, description, image, pricing, shipping_cost) VALUES ('$product_id', '$description', '$image', '$pricing', '$shipping_cost')";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            echo json_encode(["message" => "Product created successfully"]);
        } else {
            echo json_encode(["error" => "Failed to create product"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        echo json_encode(["error" => $e->getMessage()]);
    }
}


// Function to update a product
function updateProduct($data) {
    global $db;

    try {
        // Extract data from the request
        $product_id = $data['product_id'];
        $description = $data['description'];
        $image = $data['image'];
        $pricing = $data['pricing'];
        $shipping_cost = $data['shipping_cost'];

        // SQL to update the product
        $sql = "UPDATE product SET description='$description', image='$image', pricing='$pricing', shipping_cost='$shipping_cost' WHERE product_id='$product_id'";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            echo json_encode(["message" => "Product updated successfully"]);
        } else {
            echo json_encode(["error" => "Failed to update product"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Function to delete a product
function deleteProduct($product_id) {
    global $db;

    try {
        // SQL to delete the product
        $sql = "DELETE FROM product WHERE product_id='$product_id'";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            echo json_encode(["message" => "Product deleted successfully"]);
        } else {
            echo json_encode(["error" => "Failed to delete product"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Determine the HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle POST request to create a new product
if ($method == 'POST') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to create product
    createProduct($data);
} 
// Handle PUT request to update an existing product
else if ($method == 'PUT') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to update product
    updateProduct($data);
} 
// Handle DELETE request to delete an existing product
else if ($method == 'DELETE') {
    // Get the product ID from the request URL or request body
    $product_id = $_GET['product_id'] ?? null;

    if ($product_id !== null) {
        // Call function to delete product
        deleteProduct($product_id);
    } else {
        echo json_encode(["error" => "Product ID is required"]);
    }
} 
// Handle other HTTP methods (e.g., GET)
else {
    // Call function to fetch all products
    getProducts();
}
?>
