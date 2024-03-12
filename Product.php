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
            //  array to store products
            $products = array();

            // Fetch each row from the result set
            while ($row = $result->fetch_assoc()) {
                // Add the row to the products array
                $products[] = $row;
            }

            // Return products
            return ["data" => $products];
        } else {
            //  error message
            return ["error" => "Failed to fetch products"];
        }
    } catch(Exception $e) {
        // Handle errors 
        return ["error" => $e->getMessage()];
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

        // Check the query was successful
        if ($result) {
            // Return JSON response
            echo json_encode(["message" => "Product created successfully"]);
        } else {
            // Return JSON response
            echo json_encode(["error" => "Failed to create product"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        // Return JSON response
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
            return ["message" => "Product updated successfully"];
        } else {
            return ["error" => "Failed to update product"];
        }
    } catch(Exception $e) {
        // Handle any errors 
        return ["error" => $e->getMessage()];
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
            return ["message" => "Product deleted successfully"];
        } else {
            return ["error" => "Failed to delete product"];
        }
    } catch(Exception $e) {
        // Handle any errors 
        return ["error" => $e->getMessage()];
    }
}

// Determine the HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle POST request to create a new product
if ($method == 'POST') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to create product
    $response = createProduct($data);
    echo json_encode($response);
} 
else if ($method == 'PUT') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to update product
    $response = updateProduct($data);
    echo json_encode($response);
} 
else if ($method == 'DELETE') {
    // Get the product ID from the request URL or request body
    $product_id = $_GET['product_id'] ?? null;

    if ($product_id !== null) {
        // Call function to delete product
        $response = deleteProduct($product_id);
        echo json_encode($response);
    } else {
        // If product_id is not found in the URL, try to get it from the request body
        $data = json_decode(file_get_contents('php://input'), true);
        $product_id = $data['product_id'] ?? null;

        if ($product_id !== null) {
            // Call function to delete product
            $response = deleteProduct($product_id);
            echo json_encode($response);
        } else {
            echo json_encode(["error" => "Product ID is required"]);
        }
    }
} 
else {
    // Call function to fetch all products
    $response = getProducts();
    echo json_encode($response);
}
?>
