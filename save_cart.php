<?php
$conn = mysqli_connect("localhost", "root", "hidecard", "fashion");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = json_decode(file_get_contents('php://input'), true);

    $user_id = 1;
    $total_price = $cart['total_price'];
    $order_date = date("Y-m-d H:i:s");

    // Insert into `ord` table
    $sql = "INSERT INTO ord (user_id, total_price, order_date) VALUES ('$user_id', '$total_price', '$order_date')";
    if (mysqli_query($conn, $sql)) {
        $order_id = mysqli_insert_id($conn);

        foreach ($cart['items'] as $item) {
            $product_id = $item['pro_id'];
            $quantity = $item['quantity'];
            $price = $item['pro_price'];
            $total_price = $price * $quantity;

            // Insert into `ord_pro` table
            $sql_item = "INSERT INTO ord_pro (order_id, product_id, quantity, price, total_price) 
                         VALUES ('$order_id', '$product_id', '$quantity', '$price', '$total_price')";
            mysqli_query($conn, $sql_item);
        }

        echo json_encode(["status" => "success", "message" => "Order saved successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save order."]);
    }
}
?>
