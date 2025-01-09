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
            document.getElementById("total_amount").textContent = totalAmount.toFixed(2);
        }

        function updateQuantity(index, delta) {
            const input = document.querySelectorAll('input[type="number"]')[index];
            const newQuantity = Math.max(1, Number(input.value) + delta);
            input.value = newQuantity;
            updateAmount(index, input);
        }

        function updateAmount(index, input) {
            const item = cart[index];
            const quantity = Number(input.value);
            const itemTotal = document.querySelectorAll(".item-total")[index];
            itemTotal.textContent = (item.pro_price * quantity).toFixed(2);
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
            document.getElementById("total_amount").textContent = totalAmount.toFixed(2);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>
