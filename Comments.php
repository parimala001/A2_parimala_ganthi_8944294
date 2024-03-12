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

            // Return comments as JSON
            return json_encode($comments);
        } else {
            // Error message
            return json_encode(["error" => "Failed to fetch comments"]);
        }
    } catch(Exception $e) {
        // Handle errors 
        return json_encode(["error" => $e->getMessage()]);
    }
}

// Function to create a new comment
function createComment($data) {
    global $db;

    try {
        // Extract data from the request
        $product_id = $data['product_id'];
        $product_name = $data['product_name'];
        $user = $data['user'];
        $rating = $data['rating'];
        $images = $data['images'];
        $text = $data['text'];

        // SQL to insert a new comment
        $sql = "INSERT INTO comments (product_id, product_name, user, rating, images, text) VALUES ('$product_id', '$product_name', '$user', '$rating', '$images', '$text')";

        // Execute query
        $result = $db->query($sql);

        // Check the query was successful
        if ($result) {
            // Get the last inserted comment ID
            $comment_id = $db->insert_id;
            // Construct the response JSON
            $response = [
                "comment_id" => $comment_id,
                "message" => "Comment created successfully"
            ];
            // Return the response
            return json_encode($response);
        } else {
            // Return an error response
            return json_encode(["error" => "Failed to create comment"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        return json_encode(["error" => $e->getMessage()]);
    }
}

// Function to update a comment
function updateComment($data) {
    global $db;

    try {
        // Extract data from the request
        $product_id = $data['product_id'];
        $product_name = $data['product_name'];
        $user = $data['user'];
        $rating = $data['rating'];
        $images = $data['images'];
        $text = $data['text'];

        // SQL to update the comment
        $sql = "UPDATE comments SET product_name='$product_name', user='$user', rating='$rating', images='$images', text='$text' WHERE product_id='$product_id'";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            return json_encode(["message" => "Comment updated successfully"]);
        } else {
            return json_encode(["error" => "Failed to update comment"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        return json_encode(["error" => $e->getMessage()]);
    }
}

// Function to delete a comment
function deleteComment($product_id) {
    global $db;

    try {
        // SQL to delete the comment
        $sql = "DELETE FROM comments WHERE product_id='$product_id'";

        // Execute query
        $result = $db->query($sql);

        // Check if the query was successful
        if ($result) {
            return json_encode(["message" => "Comment deleted successfully"]);
        } else {
            return json_encode(["error" => "Failed to delete comment"]);
        }
    } catch(Exception $e) {
        // Handle any errors 
        return json_encode(["error" => $e->getMessage()]);
    }
}

// Determine the HTTP request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle POST request to create a new comment
if ($method == 'POST') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to create comment and echo the response
    echo createComment($data);
} 
// Handle PUT request to update an existing comment
else if ($method == 'PUT') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Call function to update comment, encode the response, and echo it
    echo json_encode(updateComment($data));
} 
// Handle DELETE request to delete an existing comment
else if ($method == 'DELETE') {
    // Get the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Get the product ID from the request body
    $product_id = $data['product_id'] ?? null;

    if ($product_id !== null) {
        // Call function to delete comment, encode the response, and echo it
        echo json_encode(deleteComment($product_id));
    } else {
        echo json_encode(["error" => "Product ID is required"]);
    }
} 
else {
    // Call function to fetch all comments, encode the response, and echo it
    echo json_encode(getComments());
}


?>
