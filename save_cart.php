<?php
session_start();
$conn = mysqli_connect("localhost", "root", "hidecard", "fashion");

// Handle Order Save Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = json_decode(file_get_contents('php://input'), true);

    // Replace this with the actual logged-in user's ID from session
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
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <div class="container p-5">
        <div class="row" id="row"></div>
        <h2 class="mt-4">Total Amount: $<span id="total_amount">0</span></h2>
    </div>
    <div class="container text-center my-4">
        <button class="btn btn-success mt-3" onclick="showSaveOrderModal()">Save Order</button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Order Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to save this order?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmOrderBtn">Confirm Order</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const row = document.getElementById("row");
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        let totalAmount = 0;

        if (cart.length === 0) {
            row.innerHTML = `<p class="text-center">Your cart is empty.</p>`;
        } else {
            cart.forEach((item, index) => {
                row.innerHTML += `
                    <div class="col-lg-12 d-flex align-items-center justify-content-between border-bottom py-3" data-id="${item.pro_id}">
                        <img src="./image/${item.pro_img}" alt="${item.pro_name}" class="img-thumbnail" style="width: 50px;">
                        <h5>${item.pro_name}</h5>
                        <span class="fw-bold">$<span class="original-price">${item.pro_price}</span></span>
                        <div class="input-group" style="width: 120px;">
                            <button class="btn btn-outline-secondary" onclick="updateQuantity(${index}, -1)">-</button>
                            <input type="number" class="form-control text-center" value="1" min="1" onchange="updateAmount(${index}, this)">
                            <button class="btn btn-outline-secondary" onclick="updateQuantity(${index}, 1)">+</button>
                        </div>
                        <span class="fw-bold">Amount: $<span class="item-total">${item.pro_price}</span></span>
                        <button class="btn btn-danger" onclick="removeItem(${index})">Remove</button>
                    </div>
                `;
                totalAmount += item.pro_price;
            });
            document.getElementById("total_amount").textContent = totalAmount;
        }

        function updateAmount(index, input) {
            const item = cart[index];
            const quantity = Number(input.value);
            const itemTotal = document.querySelectorAll(".item-total")[index];
            itemTotal.textContent = (item.pro_price * quantity);
            calculateTotal();
        }

        function removeItem(index) {
            cart.splice(index, 1);
            localStorage.setItem("cart", JSON.stringify(cart));
            location.reload();
        }

        function calculateTotal() {
            totalAmount = 0;
            document.querySelectorAll(".item-total").forEach(item => {
                totalAmount += parseFloat(item.textContent);
            });
            document.getElementById("total_amount").textContent = totalAmount;
        }

        function showSaveOrderModal() {
            const orderModal = new bootstrap.Modal(document.getElementById('orderModal'));
            orderModal.show();

            document.getElementById('confirmOrderBtn').onclick = function () {
                saveOrder();
            };
        }

        function saveOrder() {
            const cart = JSON.parse(localStorage.getItem("cart")) || [];
            let totalAmount = 0;

            cart.forEach(item => {
                totalAmount += item.pro_price * (item.quantity || 1);
            });

            if (cart.length === 0) {
                alert("Your cart is empty!");
                return;
            }

            const data = {
                total_price: totalAmount,
                items: cart
            };

            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    document.getElementById('modal-status').textContent = "Order saved successfully!";
                    setTimeout(() => {
                        localStorage.removeItem("cart");
                        window.location.reload();
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('modal-status').textContent = "An error occurred.";
            });
        }

        function updateQuantity(index, delta) {
            const input = document.querySelectorAll('input[type="number"]')[index];
            const newQuantity = Math.max(1, Number(input.value) + delta);
            input.value = newQuantity;

            const cart = JSON.parse(localStorage.getItem("cart")) || [];
            cart[index].quantity = newQuantity;
            localStorage.setItem("cart", JSON.stringify(cart));
            updateAmount(index, input);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
