<?php

$conn = mysqli_connect("localhost","root","hidecard","testJQ");
if(isset($_POST['item_name'])){
    $item_name = $_POST['item_name'];
    $item_code = $_POST['item_code'];
    $item_desc = $_POST['item_desc'];
    $item_price = $_POST['item_price'];
    $query = '';
    for($count=0; $count<count($item_name); $count++){
        $item_name_clean = mysqli_real_escape_string($conn, $item_name[$count]);
        $item_code_clean = mysqli_real_escape_string($conn, $item_code[$count]);
        $item_desc_clean = mysqli_real_escape_string($conn, $item_desc[$count]);
        $item_price_clean = mysqli_real_escape_string($conn, $item_price[$count]);
        if($item_name_clean != '' && $item_code_clean != '' && $item_desc_clean != '' && $item_price_clean != ''){
            $query .= "INSERT INTO item (item_name, item_code, item_desc, item_price) VALUES ('$item_name_clean', '$item_code_clean', '$item_desc_clean', '$item_price_clean');";
        }
        
    }
    if($query != ''){
        if(mysqli_multi_query($conn, $query)){
            echo 'Item Data Inserted';
        }else{
            echo 'Error';
        }
    }else{
        echo 'All Fields are Required';
    }
}
?>