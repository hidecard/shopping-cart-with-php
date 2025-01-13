<?php 

$conn = mysqli_connect("localhost", "root", "hidecard", "fashion");

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
        <div class="row">

            <?php 
                $sql = "SELECT * FROM product";
                $res = mysqli_query($conn, $sql);
                if (mysqli_num_rows($res) > 0):
                    while($data = mysqli_fetch_assoc($res)):
            ?>

            <div class="col-lg-4 my-3">
                <div class="card">
                    <img src="./image/<?php echo htmlspecialchars($data['pro_img']); ?>" class="card-img-top w-50 mx-auto" alt="Product Image">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo htmlspecialchars($data['pro_name']); ?></h5>
                        <p class="card-text">$<?php echo htmlspecialchars($data['pro_price']); ?></p>
                        <button class="btn btn-primary" onclick="addToCart('<?php echo htmlspecialchars($data['pro_img']); ?>',
                                                                            '<?php echo htmlspecialchars($data['pro_name']); ?>',
                                                                            <?php echo htmlspecialchars($data['pro_price']); ?>,
                                                                            <?php echo htmlspecialchars($data['pro_id']); ?>)">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            <?php 
                    endwhile;
                else:
            ?>
            <p class="text-center">No products found.</p>
            <?php endif; ?>

        </div>
    </div>

    <script>
            function addToCart(pro_img, pro_name, pro_price, pro_id) {
                    let cart = JSON.parse(localStorage.getItem("cart")) || [];
                    const existingProductIndex = cart.findIndex(item => item.pro_id === pro_id);

                    if (existingProductIndex !== -1) {
                        cart[existingProductIndex].quantity += 1;
                    } else {
                        cart.push({ pro_img, pro_name, pro_price, pro_id, quantity: 1 });
                    }

                    localStorage.setItem("cart", JSON.stringify(cart));
                    alert("Product added to cart!");
                    window.location.href = "cart.php";
}

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  </body>
</html>