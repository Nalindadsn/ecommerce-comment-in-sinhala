<?php

include 'components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිශීලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

if(isset($_SESSION['user_id'])){ // 3. පරිශීලකයා ලොග් වී ඇත්නම් user_id එක ලබා ගනී.
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = ''; // 4. ලොග් වී නොමැති නම් user_id එක හිස්ව තබයි.
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8"> <!-- 5. අකුරු නිවැරදිව පෙන්වීමට character encoding සකසයි. -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>search page</title> <!-- 6. පිටුවේ මාතෘකාව. -->
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> <!-- 7. අයිකන සඳහා Font Awesome. -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css"> <!-- 8. CSS ගොනුව සම්බන්ධ කිරීම. -->

</head>
<body>
   
<?php include 'components/user_header.php'; ?> <!-- 9. හෙඩර් (Header) කොටස ඇතුළත් කරයි. -->

<section class="search-form"> <!-- 10. භාණ්ඩ සෙවීම සඳහා වන පෝරමය (Search Form). -->
   <form action="" method="post">
      <!-- 11. සෙවිය යුතු වචනය ඇතුළත් කරන තැන (Input box). -->
      <input type="text" name="search_box" placeholder="search here..." maxlength="100" class="box" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button> <!-- 12. සෙවීමේ බොත්තම. -->
   </form>
</section>

<section class="products" style="padding-top: 0; min-height:100vh;"> <!-- 13. සෙවුම් ප්‍රතිඵල පෙන්වන කොටස. -->

   <div class="box-container">

   <?php
     // 14. පරිශීලකයා යමක් සෙවූ විට (Search button හෝ input එක හරහා) ක්‍රියාත්මක වේ.
     if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
     $search_box = $_POST['search_box'];
     // 15. ඇතුළත් කළ වචනයට සමාන නමක් ඇති භාණ්ඩ දත්ත සමුදායෙන් සොයා ගනී (LIKE ක්‍රමය භාවිතා කරයි).
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE '%{$search_box}%'"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){ // 16. සොයාගත් භාණ්ඩ එකින් එක loop එකක් මගින් පෙන්වයි.
   ?>
   <form action="" method="post" class="box"> <!-- 17. සෑම භාණ්ඩයක් සඳහාම වෙනම පෝරමයක්. -->
      <!-- 18. කරත්තයට හෝ Wishlist එකට යැවීමට අවශ්‍ය දත්ත රහසිගතව තබා ගනී. -->
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button> <!-- 19. Wishlist බොත්තම. -->
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a> <!-- 20. භාණ්ඩය විස්තරාත්මකව බැලීමට. -->
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt=""> <!-- 21. භාණ්ඩයේ පින්තූරය. -->
      <div class="name"><?= $fetch_product['name']; ?></div> <!-- 22. නම. -->
      <div class="flex">
         <div class="price"><span>$</span><?= $fetch_product['price']; ?><span>/-</span></div> <!-- 23. මිල. -->
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1"> <!-- 24. ප්‍රමාණය. -->
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart"> <!-- 25. කරත්තයට එකතු කිරීමේ බොත්තම. -->
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no products found!</p>'; // 26. කිසිදු භාණ්ඩයක් හමු නොවූයේ නම් පෙන්වන පණිවිඩය.
      }
   }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?> <!-- 27. පාදකය (Footer) ඇතුළත් කරයි. -->

<script src="js/script.js"></script> <!-- 28. JS ගොනුව සම්බන්ධ කරයි. -->

</body>
</html>