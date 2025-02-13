<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Insert into SQL using Ajax and jQuery</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
    <div class="container mt-5">
      <h1 class="text-center mb-4">Insert into SQL using Ajax and jQuery</h1>
      <div class="table-responsive">
        <table class="table table-bordered" id="crud_table">
          <tr>
            <th width="30%">Item Name</th>
            <th width="10%">Item Code</th>
            <th width="45%">Description</th>
            <th width="10%">Price</th>
            <th width="5%">Action</th>
          </tr>
          <tr>
            <td contenteditable="true" class="item_name"></td>
            <td contenteditable="true" class="item_code"></td>
            <td contenteditable="true" class="item_desc"></td>
            <td contenteditable="true" class="item_price"></td>
            <td></td>
          </tr>
        </table>
        <div class="d-flex justify-content-between">
          <button type="button" id="add" class="btn btn-success">Add Row</button>
          <button type="button" id="save" class="btn btn-info">Save Data</button>
        </div>
        <div id="inserted_item_data" class="mt-4"></div>
      </div>
    </div>

    <script>
      $(document).ready(function () {
        var count = 1;

        // Add new row
        $('#add').click(function(){
            count = count + 1;
            var html_code = '<tr id="row'+count+'">';
            html_code += '<td contenteditable="true" class="item_name"></td>';
            html_code += '<td contenteditable="true" class="item_code"></td>';
            html_code += '<td contenteditable="true" class="item_desc"></td>';
            html_code += '<td contenteditable="true" class="item_price"></td>';
            html_code += '<td><button type="button" name="remove" data-row="row'+count+'" class="btn btn-danger btn-xs remove">X</button></td>';
            html_code += '</tr>';
            $('#crud_table').append(html_code);
        });

        // Remove row
        $(document).on("click", ".remove", function () {
          var row_id = $(this).data("row");
          $("#" + row_id).remove();
        });

        // Save data
        $("#save").click(function () {
          var item_name = [];
          var item_code = [];
          var item_desc = [];
          var item_price = [];

          $(".item_name").each(function () {
            item_name.push($(this).text());
          });
          $(".item_code").each(function () {
            item_code.push($(this).text());
          });
          $(".item_desc").each(function () {
            item_desc.push($(this).text());
          });
          $(".item_price").each(function () {
            item_price.push($(this).text());
          });

          $.ajax({
            url: "insert.php",
            method: "POST",
            data: {
              item_name: item_name,
              item_code: item_code,
              item_desc: item_desc,
              item_price: item_price,
            },
            success: function (data) {
              $("td[contenteditable=true]").text("");
              for (var i = 2; i <= count; i++) {
                $("#row" + i).remove();
              }
              fetch_data();
              alert("Data inserted successfully!");
            },
          });
        });

        // Fetch data
        function fetch_data() {
          $.ajax({
            url: "fetch.php",
            method: "POST",
            success: function (data) {
              $("#inserted_item_data").html(data);
            },
          });
        }

        fetch_data();
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>
