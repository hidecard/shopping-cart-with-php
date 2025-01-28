<?php 

$conn = mysqli_connect("localhost", "root", "hidecard", "testJQ");
$output = '';
$query = "SELECT * FROM item ORDER BY item_id DESC";
$result = mysqli_query($conn, $query);

$output .= '
<br>
<h3 align="center">Item Data</h3>
<table class="table table-bordered table-striped">
    <tr>
        <th>Item Name</th>
        <th>Item Code</th>
        <th>Item Description</th>
        <th>Item Price</th>
    </tr>
';

while ($row = mysqli_fetch_array($result)) {
    $output .= '
    <tr>
        <td>' . $row["item_name"] . '</td>
        <td>' . $row["item_code"] . '</td>
        <td>' . $row["item_desc"] . '</td>
        <td>' . $row["item_price"] . '</td>
    </tr>
    ';
}

$output .= '</table>';
echo $output;

?>
