<?php

include 'components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිශීලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

if(isset($_SESSION['user_id'])){ // 3. පරිශීලකයා ලොග් වී ඇත්නම් user_id එක ලබා ගනී.
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = ''; // 4. ලොග් වී නොමැති නම් ලොගින් පිටුවට (login page) යොමු කරයි.
   header('location:user_login.php');
};

if(isset($_POST['delete'])){ // 5. 'delete' බොත්තම එබූ විට අදාළ භාණ්ඩය කරත්තයෙන් ඉවත් කරයි.
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
}

if(isset($_GET['delete_all'])){ // 6. 'delete_all' ලින්ක් එක එබූ විට කරත්තයේ ඇති සියලුම දේ ඉවත් කරයි.
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   header('location:cart.php');
}

if(isset($_POST['update_qty'])){ // 7. භාණ්ඩයක ප්‍රමාණය (quantity) වෙනස් කළ විට එය update කරයි.
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING); // 8. ඇතුළත් කළ දත්ත ආරක්ෂිතව පිරිසිදු කරයි.
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = 'cart quantity updated'; // 9. සාර්ථකව යාවත්කාලීන වූ බව පණිවිඩයක් ලෙස ගබඩා කරයි.
}

?>

<!DOCTYPE html>
<html lang="en"> <!-- 10. භාෂාව ඉංග්‍රීසි ලෙස සලකුණු කරයි. -->
<head>
   <meta charset="UTF-8"> <!-- 11. අකුරු නිවැරදිව පෙන්වීමට මෙය භාවිතා කරයි. -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- 12. පැරණි බ්‍රව්සර් සමඟ ගැළපීම සඳහා. -->
   <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- 13. මොබයිල් ෆෝන් වලට ගැළපෙන ලෙස සැකසීම. -->
   <title>shopping cart</title> <!-- 14. පිටුවේ නම. -->
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> <!-- 15. අයිකන සඳහා Font Awesome සම්බන්ධ කරයි. -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css"> <!-- 16. අපගේ CSS ගොනුව සම්බන්ධ කරයි. -->

</head>
<body>
   
<?php include 'components/user_header.php'; ?> <!-- 17. හෙඩර් (Header) කොටස ඇතුළත් කරයි. -->

<section class="products shopping-cart"> <!-- 18. කරත්තයේ අන්තර්ගතය පෙන්වන කොටස ආරම්භය. -->

   <h3 class="heading">shopping cart</h3> <!-- 19. ප්‍රධාන මාතෘකාව. -->

   <div class="box-container"> <!-- 20. භාණ්ඩ පෙට්ටි (Boxes) ආකාරයට පෙන්වන container එක. -->

   <?php
      $grand_total = 0; // 21. සම්පූර්ණ මුදල ගණනය කිරීමට variable එකක් සාදයි.
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?"); // 22. අදාළ පරිශීලකයාගේ කරත්තයේ ඇති දේ තෝරා ගනී.
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){ // 23. භාණ්ඩ තිබේ නම් පමණක් පෙන්වයි.
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){ // 24. භාණ්ඩ එකින් එක loop එකක් හරහා පෙන්වයි.
   ?>
   <form action="" method="post" class="box"> <!-- 25. එක් එක් භාණ්ඩය සඳහා වෙනම form එකක්. -->
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>"> <!-- 26. භාණ්ඩයේ අයිඩිය (ID) රහසිගතව යවයි. -->
      <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a> <!-- 27. විස්තර බැලීමට අයිකනය. -->
      <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt=""> <!-- 28. භාණ්ඩයේ පින්තූරය. -->
      <div class="name"><?= $fetch_cart['name']; ?></div> <!-- 29. භාණ්ඩයේ නම. -->
      <div class="flex">
         <div class="price">LKR <?= $fetch_cart['price']; ?>/-</div> <!-- 30. තනි භාණ්ඩයක මිල. -->
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>"> <!-- 31. ප්‍රමාණය තේරීම. -->
         <button type="submit" class="fas fa-edit" name="update_qty"></button> <!-- 32. ප්‍රමාණය වෙනස් කිරීමට බටන් එක. -->
      </div>
      <div class="sub-total"> sub total : <span>LKR <?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div> <!-- 33. අදාළ භාණ්ඩයේ මුළු වටිනාකම. -->
      <input type="submit" value="delete item" onclick="return confirm('delete this from cart?');" class="delete-btn" name="delete"> <!-- 34. භාණ්ඩය ඉවත් කිරීමේ බටන් එක. -->
   </form>
   <?php
   $grand_total += $sub_total; // 35. සියලුම භාණ්ඩවල මුළු එකතුව (Grand Total) ගණනය කරයි.
      }
   }else{
      echo '<p class="empty">your cart is empty</p>'; // 36. කරත්තය හිස් නම් මෙම පණිවිඩය පෙන්වයි.
   }
   ?>
   </div>

   <div class="cart-total"> <!-- 37. මුළු එකතුව පෙන්වන කොටස. -->
      <p>grand total : <span>LKR <?= $grand_total; ?>/-</span></p> <!-- 38. මුළු මුදල. -->
      <a href="shop.php" class="option-btn">continue shopping</a> <!-- 39. නැවත ෂොපින් කිරීමට යාම. -->
      <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">delete all item</a> <!-- 40. සියල්ලම මැකීමේ බොත්තම. -->
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a> <!-- 41. ගෙවීම් කිරීමට යාමට (Checkout). -->
   </div>

</section>

<?php include 'components/footer.php'; ?> <!-- 42. පාදකය (Footer) ඇතුළත් කරයි. -->

<script src="js/script.js"></script> <!-- 43. අපගේ ජාවාස්ක්‍රිප්ට් ගොනුව. -->

</body>
</html>