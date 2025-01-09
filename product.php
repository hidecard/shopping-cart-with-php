<?php 

    $conn = mysqli_connect("localhost", "root", "hidecard", "fashion");


?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>


    <div class="container">
        <div class="row">

            <?php 
                $sql = "select * from product";
                $res = mysqli_query($conn, $sql);
                while($data = mysqli_fetch_assoc($res)):
            
            ?>

                    <div class="col-lg-4 my-3">
                        <div class="card">
                            <img src="./image/<?php echo $data['pro_img'] ?>" class="w-50" alt="">
                            <h3><?php echo $data['pro_name'] ?></h3>
                            <span><?php echo $data['pro_price'] ?></span>
                            <button onclick="addToCart('<?php echo $data['pro_img'] ?>','<?php echo $data['pro_name'] ?>',<?php echo $data['pro_price'] ?>,<?php echo $data['pro_id'] ?> )">Add to cart</button>
                        </div>
                    </div>


            <?php endwhile; ?>

        </div>
    </div>

    <script>

        let cart_obj = {};

        function addToCart(pro_img,pro_name,pro_price,pro_id){

            let cart = JSON.parse(localStorage.getItem("cart")) || []; //
            cart_obj = {
                pro_img: pro_img,
                pro_name: pro_name,
                pro_price: pro_price,
                pro_id: pro_id
            };
            cart.push(cart_obj);

            localStorage.setItem('cart', JSON.stringify(cart));
            alert("Product added to cart");
        }

    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>