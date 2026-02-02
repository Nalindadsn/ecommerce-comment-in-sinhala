<?php

include '../components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිපාලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

$admin_id = $_SESSION['admin_id']; // 3. ලොග් වී සිටින පරිපාලකයාගේ ID එක ලබා ගනී.

if(!isset($admin_id)){ // 4. පරිපාලකයා ලොග් වී නොමැති නම් ඔහුව login පිටුවට යොමු කරයි.
   header('location:admin_login.php');
}

if(isset($_POST['update_payment'])){ // 5. ඇණවුමක ගෙවීම් තත්ත්වය (Payment Status) යාවත්කාලීන කිරීමට උත්සාහ කරන විට.
   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];
   $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
   
   // 6. දත්ත සමුදායේ අදාළ ඇණවුමේ ගෙවීම් තත්ත්වය 'pending' හෝ 'completed' ලෙස වෙනස් කරයි.
   $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_payment->execute([$payment_status, $order_id]);
   $message[] = 'payment status updated!';
}

if(isset($_GET['delete'])){ // 7. ඇණවුමක් පද්ධතියෙන් ඉවත් කිරීමට (Delete) ඉල්ලීමක් ලැබුණු විට.
   $delete_id = $_GET['delete'];
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->execute([$delete_id]);
   header('location:placed_orders.php'); // 8. ඉවත් කිරීමෙන් පසු පිටුව නැවත යාවත්කාලීන කරයි.
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>placed orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- bootstrap cdn link -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
   <!-- custom admin css file link -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?> <!-- 9. පරිපාලක හෙඩර් කොටස ඇතුළත් කරයි. -->

<section class="orders">

<h1 class="heading">placed orders</h1>

<div class="container">
<!-- 10. ලැබී ඇති ඇණවුම් විස්තර පෙන්වන වගුව (Table) -->
<table class="table">
   <tr>
      <th>Name</th>
      <th>Contact No</th>
      <th>Address</th>
      <th>Total Products</th>
      <th>Total Price</th>
      <th>Payment Method</th>
      <th>Created at</th>
      <th>Action</th>
   </tr>
   <?php
      // 11. දත්ත සමුදායේ ඇති සියලුම ඇණවුම් ලබා ගනී.
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>

   <tr>
      <!-- 12. පාරිභෝගිකයාගේ තොරතුරු සහ ඇණවුම් විස්තර පෙන්වීම. -->
      <td><?= $fetch_orders['name']; ?></td>
      <td><?= $fetch_orders['number']; ?></td>
      <td><?= $fetch_orders['address']; ?></td>
      <td><?= $fetch_orders['total_products']; ?></td>
      <td><?= $fetch_orders['total_price']; ?></td>
      <td><?= $fetch_orders['method']; ?></td>
      <td><?= $fetch_orders['placed_on']; ?></td>
      <td>
      <!-- 13. ගෙවීම් තත්ත්වය යාවත්කාලීන කිරීමට හෝ ඇණවුම මැකීමට පෝරමය (Form). -->
      <form action="" method="post">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="select">
            <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="pending">pending</option>
            <option value="completed">completed</option>
         </select>
        <div class="flex-btn">
         <input type="submit" value="update" class="option-btn" name="update_payment">
         <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
        </div>
      </form></td>
   </tr>
   
   <?php
         }
      }else{
         // 14. කිසිදු ඇණවුමක් ලැබී නොමැති නම් පෙන්වන පණිවිඩය.
         echo '<tr><td colspan="8" class="empty">no orders placed yet!</td></tr>';
      }
   ?>
</table>

</div>

</section>

<!-- 15. පරිපාලක JS ගොනුව සම්බන්ධ කරයි. -->
<script src="../js/admin_script.js"></script>
   
</body>
</html>