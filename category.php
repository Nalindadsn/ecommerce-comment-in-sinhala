<?php

include 'components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිශීලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

if(isset($_SESSION['user_id'])){ // 3. පරිශීලකයා ලොග් වී ඇත්නම් user_id එක ලබා ගනී.
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = ''; // 4. ලොග් වී නොමැති නම් user_id එක හිස්ව තබයි.
};

   // 5. URL එක හරහා ලැබෙන category ID එකට අදාළ වර්ගය (Category) දත්ත සමුදායෙන් තෝරා ගනී.
   $select_cat = $conn->prepare("SELECT * FROM `categories` WHERE id = ?");
   $select_cat->execute([$_GET['category']]);
   $row = $select_cat->fetch(PDO::FETCH_ASSOC); // 6. තෝරාගත් category එකේ විස්තර row එකකට ලබා ගනී.
 
?>

<!DOCTYPE html>
<html lang="en"> <!-- 7. වෙබ් පිටුව ඉංග්‍රීසි භාෂාවෙන් බව දක්වයි. -->
<head>
   <meta charset="UTF-8"> <!-- 8. අකුරු නිවැරදිව පෙන්වීමට character encoding එක සකසයි. -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- 9. Internet Explorer සමඟ ගැළපීම සඳහා. -->
   <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- 10. මොබයිල් ෆෝන් වලට ගැළපෙන ලෙස සැකසීම. -->
   <title>category</title> <!-- 11. පිටුවේ නම. -->
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> <!-- 12. අයිකන සඳහා Font Awesome සම්බන්ධ කරයි. -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css"> <!-- 13. අපගේ CSS ගොනුව සම්බන්ධ කරයි. -->

</head>
<body>
   
<?php include 'components/user_header.php'; ?> <!-- 14. හෙඩර් (Header) කොටස ඇතුළත් කරයි. -->

<section class="products"> <!-- 15. භාණ්ඩ පෙන්වන කොටස ආරම්භය. -->

   <h1 class="heading"><?php echo $row['name']; ?></h1> <!-- 16. තෝරාගත් category එකේ නම මාතෘකාව ලෙස පෙන්වයි. -->

   <div class="box-container"> <!-- 17. භාණ්ඩ පෙන්වන container එක. -->

   <?php
      $category = $_GET['category']; // 18. URL එකෙන් ලැබෙන category ID එක variable එකකට ගනී.
      // 19. එම category එකට අදාළ සියලුම භාණ්ඩ දත්ත සමුදායෙන් තෝරා ගනී.
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE category_id='{$category}%'"); 
      $select_products->execute();
      if($select_products->rowCount() > 0){ // 20. භාණ්ඩ තිබේ නම් පමණක් පෙන්වයි.
       while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){ // 21. භාණ්ඩ එකින් එක පෙන්වීමට loop එකක් භාවිතා කරයි.
   ?>
   <form action="" method="post" class="box"> <!-- 22. සෑම භාණ්ඩයක් සඳහාම form එකක්. -->
      <!-- 23. භාණ්ඩයේ විස්තර රහසිගතව (hidden) යැවීමට භාවිතා කරන input. -->
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button> <!-- 24. Wishlist එකට එකතු කිරීමේ අයිකනය. -->
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a> <!-- 25. භාණ්ඩයේ විස්තර බැලීමට අයිකනය. -->
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt=""> <!-- 26. භාණ්ඩයේ පින්තූරය. -->
      <div class="name"><?= $fetch_product['name']; ?></div> <!-- 27. භාණ්ඩයේ නම. -->
      <div class="flex">
         <div class="price"><span>$</span><?= $fetch_product['price']; ?><span>/-</span></div> <!-- 28. මිල පෙන්වීම. -->
         <!-- 29. ප්‍රමාණය (Quantity) තේරීමේ input එක. -->
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <!-- 30. කරත්තයට (Cart) එකතු කිරීමේ බොත්තම. -->
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
       }
    }else{
       echo '<p class="empty">no products found!</p>'; // 31. කිසිදු භාණ්ඩයක් නැතිනම් පෙන්වන පණිවිඩය.
    }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?> <!-- 32. පාදකය (Footer) ඇතුළත් කරයි. -->

<script src="js/script.js"></script> <!-- 33. ජාවාස්ක්‍රිප්ට් ගොනුව සම්බන්ධ කරයි. -->

</body>
</html>