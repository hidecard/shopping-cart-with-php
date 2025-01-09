<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    

    <div class="container p-5">
        <div class="row" id="row">
            
        </div>

        <h1>Total amount: <span id="total_amount">0</span> </h1>
    </div>


    <script>
        let row = document.querySelector("#row");
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        cart.forEach(item => {
            row.innerHTML += `
                    <div class="cart d-flex align-items-center justify-content-between" data-id="${item.pro_id}">
                        <img src="./image/${item.pro_img}" alt="" style="width: 50px;">
                        <h4>${item.pro_name}</h4> 
                        <span id="o_price">${item.pro_price}</span>
                        <div class="qty">
                            <button id="minus" class="btn btn-outline-warning mx-1">-</button>
                            <input type="number" value="1" id="qty">
                            <button id="plus" class="btn btn-outline-primary mx-1">+</button>
                        </div>
                        <div class="amu d-flex align-items-center">
                            Amount: $<h4 id="show_total">${item.pro_price}</h4>
                        </div>
                        <button class="btn btn-danger" id="remove" onclick="remove(${item.pro_id})">Remove</button>
                    </div>
            `
        });

    </script>

    <script>
        let plus = document.querySelectorAll("#plus");
        let minus = document.querySelectorAll("#minus");
        let show_total = document.querySelectorAll("#show_total");

        for (let i = 0; i < plus.length; i++) {
            plus[i].addEventListener("click", function(){

                let qty_element = document.querySelectorAll('#qty');
                let qty = Number(qty_element[i].value);

                let total_qty = qty + 1
                qty_element[i].value = total_qty;

                let o_price_ele = document.querySelectorAll("#o_price");
                let o_price = Number(o_price_ele[i].textContent);

                let total_amount = total_qty * o_price;
                // console.log(total_amount);

                show_total[i].textContent = total_amount;
                
                get_total();
            })
        }

        for (let i = 0; i < minus.length; i++) {
            minus[i].addEventListener("click", function(){
                
                let qty_element = document.querySelectorAll('#qty');
                let qty = Number(qty_element[i].value);
                let total_qty =qty - 1
                qty_element[i].value = total_qty;


                let o_price_ele = document.querySelectorAll("#o_price");
                let o_price = Number(o_price_ele[i].textContent);

                let total_amount = total_qty * o_price;
                // console.log(total_amount);

                show_total[i].textContent = total_amount;

                get_total();
                
            })
        }

        function remove(pro_id){
            let index = cart.findIndex(item => item.pro_id === pro_id);
        
            cart.splice(index, 1);

            localStorage.setItem('cart', JSON.stringify(cart));

            document.querySelector(`[data-id="${pro_id}"]`).remove();

            get_total();
            
        }

        function get_total(){
            let total_amount = 0;
            show_total.forEach(element => {
                total_amount += Number(element.textContent) || 0; // Convert to number and handle NaN

                let total_amount_element = document.querySelector("#total_amount");
                total_amount_element.textContent = total_amount;
            });
        }

        get_total();

        

        
    </script>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>