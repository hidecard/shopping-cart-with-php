<?php
$conn = mysqli_connect("localhost", "root", "hidecard", "fashion");

// Check the connection
if (!$conn) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . mysqli_connect_error()]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $user_id = intval($_POST['user_id']);
    $cart = json_decode($_POST['cart'], true);

    if (!$cart || empty($cart)) {
        echo json_encode(["status" => "error", "message" => "Cart is empty or invalid!"]);
        exit;
    }

    $order_date = date("Y-m-d H:i:s");
    $total_price = 0;

    // Calculate total order price
    foreach ($cart as $item) {
        if (!isset($item['pro_price'], $item['quantity']) || $item['quantity'] <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid cart data!"]);
            exit;
        }
        $total_price += $item['pro_price'] * $item['quantity'];
    }

    // Use prepared statements to insert into `orders`
    $order_sql = "INSERT INTO orders (user_id, total_price, order_date) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $order_sql);
    mysqli_stmt_bind_param($stmt, "ids", $user_id, $total_price, $order_date);

    if (mysqli_stmt_execute($stmt)) {
        $order_id = mysqli_insert_id($conn);

        // Insert into `order_products`
        $product_sql = "INSERT INTO order_products (order_id, product_id, quantity, price, total_price) 
                        VALUES (?, ?, ?, ?, ?)";
        $product_stmt = mysqli_prepare($conn, $product_sql);

        foreach ($cart as $item) {
            $product_id = $item['pro_id'];
            $quantity = $item['quantity'];
            $price = $item['pro_price'];
            $total_item_price = $price * $quantity;

            mysqli_stmt_bind_param($product_stmt, "iiidd", $order_id, $product_id, $quantity, $price, $total_item_price);
            mysqli_stmt_execute($product_stmt);
        }

        echo json_encode(["status" => "success", "message" => "Order placed successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to place order!"]);
    }

    // Close the statements
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($product_stmt);
}

// Close the database connection
mysqli_close($conn);
?>
