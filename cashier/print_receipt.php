<?php session_start(); include 'db.php';  // Get the order_id from the GET request 
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;  // Query to fetch order details for the specific order_id 
$query = "     
    SELECT o.order_id, o.order_date, o.total_amount, o.payment_mode, o.cashier_name, oi.food_id, oi.quantity, oi.price, oi.total_price      
    FROM orders o     
    JOIN order_items oi ON o.order_id = oi.order_id     
    WHERE o.order_id = $order_id 
"; 
$result = mysqli_query($conn, $query);  

// Store the order details in an array 
$order = []; 
while ($row = mysqli_fetch_assoc($result)) {     
    $order['order_id'] = $row['order_id'];     
    $order['order_date'] = $row['order_date'];     
    $order['cashier_name'] = $row['cashier_name'];     
    $order['total_amount'] = $row['total_amount'];     
    $order['payment_mode'] = $row['payment_mode'];     
    $order['items'][] = [         
        'food_id' => $row['food_id'],         
        'quantity' => $row['quantity'],         
        'price' => $row['price'],         
        'total_price' => $row['total_price']     
    ]; 
} 
?>  

<!DOCTYPE html> 
<html lang="en">  
<head>     
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">     
    <title>Receipt</title>     
    <?php include '../cdn.php'; ?>     
    <link rel="stylesheet" href="../css/base.css">     
    <link rel="stylesheet" href="../css/auth.css"> 
    <link rel="stylesheet" href="../css/print.css"> 
    
</head>  

<body>     
    <div class="receipt"> 
      <div class="restaurant_info">
      <div class="logo"></div> 
        <h3>TELL AFAR RESTAURANT</h3>
        <p><strong>Location:</strong>Devtraco Courts Rd, Tema</p> 
        <p><strong>Contact No: </strong></p> 
      </div>  
      <br>
   
      <div class="dashed"></div> 
        <h2>RECEIPT</h2> 
        <div class="dashed"></div> 
        <br>          
        <p><strong>Cashier Name:</strong> <?php echo $order['cashier_name']; ?></p>         
        <p><strong>Order Date:</strong> <?php echo date('Y-m-d H:i', strtotime($order['order_date'])); ?></p>          
        <p><strong>Payment Mode:</strong> <?php echo $order['payment_mode']; ?></p>    
        <br>
        <div class="items">             
                   
            <table>                 
                <thead>                     
                    <tr>                         
                        <th>Food</th>                         
                        <th>Price</th>                         
                        <th>Qty</th>                         
                        <th>Subtotal</th>                     
                    </tr>                 
                </thead>                 
                <tbody>                     
                    <?php foreach ($order['items'] as $item) {                          
                        $food_query = "SELECT food_name FROM food_menu WHERE id = " . $item['food_id'];                         
                        $food_result = mysqli_query($conn, $food_query);                         
                        $food = mysqli_fetch_assoc($food_result);                     
                    ?>                         
                        <tr>                             
                            <td><?php echo $food['food_name']; ?></td>                             
                            <td><?php echo number_format($item['price'], 2); ?></td>                             
                            <td><?php echo $item['quantity']; ?></td>                             
                            <td><?php echo number_format($item['total_price'], 2); ?></td>                         
                        </tr>                     
                    <?php } ?>                 
                </tbody>             
            </table>         
        </div>          

        <h3 class="total-row"><strong>Total Amount:</strong> 
        <br>
        <?php echo number_format($order['total_amount'], 2); ?></h3>         
  <br>
        <div class="dashed"></div>
  <h2>   ORDER ID:<?php echo $order['order_id']; ?></h2>
  <div class="dashed"></div>

  <div class="qr">
    <img src="../images/qrcode.png" alt="">
    
  </div>
  <div class="powred">
    <p>Powered by Nathstack Tech <br> +233 541 987 478</p>
  </div>
        <div class="forms">             
            <button id="printButton">Print</button>         
        </div>     
    </div>      

    <script>         
        // Event listener for print button to trigger print dialog
        document.getElementById('printButton').addEventListener('click', function() {
            window.print();
        });
    </script> 
</body>  

</html>
