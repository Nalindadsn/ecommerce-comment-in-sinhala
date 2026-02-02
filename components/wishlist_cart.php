<?php

// 1. 'add_to_cart' නමින් යුත් Button එකක් හෝ Form එකක් Submit කර ඇත්දැයි පරීක්ෂා කිරීම.
if(isset($_POST['add_to_cart'])){

   // 2. පරිශීලකයා ලොග් වී ඇත්දැයි පරීක්ෂා කිරීම ($user_id හිස් නම් ඔහු ලොග් වී නැත).
   if($user_id == ''){
      // ලොග් වී නැතිනම් ලොගින් පිටුවට (user_login.php) යොමු කරයි.
      header('location:user_login.php');
   }else{

      // 3. Form එක හරහා ලැබෙන දත්ත ලබා ගැනීම සහ ඒවා පිරිසිදු කිරීම (Sanitization).
      // මෙහිදී HTML tags හෝ අනවශ්‍ය අක්ෂර ඉවත් කර ආරක්ෂාව තහවුරු කරයි.
      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING); // භාණ්ඩයේ ID එක
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING); // භාණ්ඩයේ නම
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING); // මිල
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING); // පින්තූරයේ නම
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING); // ප්‍රමාණය (Quantity)

      // 4. මෙම භාණ්ඩය දැනටමත් මෙම පරිශීලකයාගේ කරත්තයට (Cart) එක් කර ඇත්දැයි පරීක්ෂා කිරීම.
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->execute([$name, $user_id]);

      // 5. භාණ්ඩය දැනටමත් තිබේ නම් පණිවිඩයක් පෙන්වීම.
      if($check_cart_numbers->rowCount() > 0){
         $message[] = 'දැනටමත් කරත්තයට එක් කර ඇත!';
      }else{

         // 6. (අවශ්‍ය නම්) භාණ්ඩය Wishlist එකේ තිබේ නම් එය මකා දැමීමේ කේතය (දැනට මෙය අක්‍රීය කර ඇත).
         // $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
         // $check_wishlist_numbers->execute([$name, $user_id]);

         // if($check_wishlist_numbers->rowCount() > 0){
         //    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         //    $delete_wishlist->execute([$name, $user_id]);
         // }

         // 7. භාණ්ඩය කරත්තයට (Cart table එකට) අලුතින් ඇතුළත් කිරීම.
         $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
         $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
         
         // සාර්ථකව ඇතුළත් කළ පසු ලැබෙන පණිවිඩය.
         $message[] = 'සාර්ථකව කරත්තයට එක් කළා!';
         
      }

   }

}

?>