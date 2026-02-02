<?php

include 'components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිශීලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

if(isset($_SESSION['user_id'])){ // 3. පරිශීලකයා ලොග් වී ඇත්නම් user_id එක ලබා ගනී.
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = ''; // 4. ලොග් වී නොමැති නම් ලොගින් පිටුවට (login page) යොමු කරයි.
   header('location:user_login.php');
};

if(isset($_POST['order'])){ // 5. 'order' බොත්තම එබූ විට ඇණවුම ලබා ගැනීමේ ක්‍රියාවලිය ආරම්භ කරයි.

   // 6. පෝරමයෙන් (Form) ලැබෙන දත්ත ලබාගෙන ඒවා ආරක්ෂිතව පිරිසිදු කරයි (Sanitize).
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   
   // 7. ලිපිනයෙහි සියලුම කොටස් එකට එකතු කර සම්පූර්ණ ලිපිනය සාදා ගනී.
   $address = 'flat no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   // 8. කරත්තය (Cart) හිස්දැයි පරීක්ෂා කරයි.
   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){ // 9. කරත්තයේ භාණ්ඩ තිබේ නම් පමණක් ඇණවුම ඇතුළත් කරයි.

      // 10. 'orders' වගුවට නව ඇණවුම ඇතුළත් කිරීමේ SQL විධානය.
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      // 11. ඇණවුම සාර්ථක වූ පසු කරත්තය හිස් කරයි.
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'order placed successfully!'; // 12. සාර්ථක පණිවිඩය.
   }else{
      $message[] = 'your cart is empty'; // 13. කරත්තය හිස් නම් දෙන පණිවිඩය.
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8"> <!-- 14. අකුරු නිවැරදිව පෙන්වීමට character encoding සකසයි. -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title> <!-- 15. පිටුවේ මාතෘකාව. -->
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> <!-- 16. අයිකන සඳහා Font Awesome. -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css"> <!-- 17. CSS සම්බන්ධ කිරීම. -->

</head>
<body>
   
<?php include 'components/user_header.php'; ?> <!-- 18. හෙඩර් එක ඇතුළත් කරයි. -->

<section class="checkout-orders">

   <form action="" method="POST"> <!-- 19. ඇණවුම් තොරතුරු යැවීමේ පෝරමය. -->

   <h3>your orders</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         // 20. පරිශීලකයාගේ කරත්තයේ ඇති භාණ්ඩ තෝරා ගනී.
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               // 21. භාණ්ඩවල නම් සහ මිල ගණන් දත්ත සමුදායට යැවීමට එක පෙළකට (String) සකස් කරයි.
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <p> <?= $fetch_cart['name']; ?> <span>(<?= '$'.$fetch_cart['price'].'/- x '. $fetch_cart['quantity']; ?>)</span> </p> <!-- 22. එක් එක් භාණ්ඩය තිරයේ පෙන්වයි. -->
      <?php
            }
         }else{
            echo '<p class="empty">your cart is empty!</p>'; // 23. කරත්තය හිස් නම් පෙන්වන පණිවිඩය.
         }
      ?>
         <!-- 24. මුළු මුදල සහ භාණ්ඩ විස්තර රහසිගතව (hidden) පෝරමයට ඇතුළත් කරයි. -->
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
         <div class="grand-total">grand total : <span>$<?= $grand_total; ?>/-</span></div> <!-- 25. මුළු එකතුව. -->
      </div>

      <h3>place your orders</h3>

      <div class="flex">
         <!-- 26. පාරිභෝගිකයාගේ පුද්ගලික තොරතුරු ලබාගන්නා කොටස්. -->
         <div class="inputBox">
            <span>your name :</span>
            <input type="text" name="name" placeholder="enter your name" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>your number :</span>
            <input type="number" name="number" placeholder="enter your number" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>your email :</span>
            <input type="email" name="email" placeholder="enter your email" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>payment method :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">cash on delivery</option>
               <option value="credit card">credit card</option>
               <option value="paytm">paytm</option>
               <option value="paypal">paypal</option>
            </select>
         </div>
         <!-- 27. ලිපිනය ලබාගන්නා කොටස්. -->
         <div class="inputBox">
            <span>address line 01 :</span>
            <input type="text" name="flat" placeholder="e.g. flat number" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>address line 02 :</span>
            <input type="text" name="street" placeholder="e.g. street name" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>city :</span>
            <input type="text" name="city" placeholder="e.g. colombo" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>state :</span>
            <input type="text" name="state" placeholder="e.g. western" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>country :</span>
            <input type="text" name="country" placeholder="e.g. Sri Lanka" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>pin code :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 12345" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div>
      </div>

      <!-- 28. ඇණවුම ස්ථිර කරන බොත්තම (කරත්තය හිස් නම් මෙය අක්‍රිය වේ). -->
      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="place order">

   </form>

</section>

<?php include 'components/footer.php'; ?> <!-- 29. පාදකය ඇතුළත් කරයි. -->

<script src="js/script.js"></script> <!-- 30. JS ගොනුව සම්බන්ධ කිරීම. -->

</body>
</html>