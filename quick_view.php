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
   <title>quick view</title> <!-- 6. පිටුවේ මාතෘකාව. -->
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> <!-- 7. අයිකන සඳහා Font Awesome. -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css"> <!-- 8. අපගේ ප්‍රධාන CSS ගොනුව සම්බන්ධ කිරීම. -->

</head>
<body>
   
<?php include 'components/user_header.php'; ?> <!-- 9. හෙඩර් (Header) කොටස ඇතුළත් කරයි. -->

<section class="quick-view"> <!-- 10. භාණ්ඩයේ විස්තර ඉක්මනින් බැලීමට හැකි කොටස. -->

   <h1 class="heading">quick view</h1> <!-- 11. ප්‍රධාන මාතෘකාව. -->

   <?php
      $pid = $_GET['pid']; // 12. URL එක හරහා ලැබෙන භාණ්ඩයේ ID එක (Product ID) ලබා ගනී.
      // 13. එම ID එකට අදාළ භාණ්ඩයේ සියලුම විස්තර දත්ත සමුදායෙන් ලබා ගනී.
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
      $select_products->execute([$pid]);
      if($select_products->rowCount() > 0){
       while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){ // 14. භාණ්ඩයේ දත්ත fetch කර පෙන්වීමට සූදානම් කරයි.
   ?>
   <form action="" method="post" class="box"> <!-- 15. කරත්තයට හෝ Wishlist එකට එකතු කිරීමට පෝරමය (Form). -->
      <!-- 16. භාණ්ඩයේ විස්තර රහසිගතව (hidden) යැවීමට භාවිතා කරන input. -->
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      
      <div class="row">
         <div class="image-container">
            <div class="main-image">
               <!-- 17. භාණ්ඩයේ ප්‍රධාන පින්තූරය පෙන්වයි. -->
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
            </div>
            <div class="sub-image">
               <!-- 18. භාණ්ඩයේ අමතර පින්තූර (Thumbnail images) පෙන්වයි. -->
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
            </div>
         </div>
         <div class="content">
            <div class="name"><h2><?= $fetch_product['name']; ?></h2></div> <!-- 19. භාණ්ඩයේ නම. -->
            <div class="flex">
               <div class="price"><span>$</span><?= $fetch_product['price']; ?><span>/-</span></div> <!-- 20. මිල. -->
               <!-- 21. මිලදී ගැනීමට අවශ්‍ය ප්‍රමාණය (Quantity) තේරීමට. -->
               <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
            </div>
            <div class="flex-btn">
               <!-- 22. කරත්තයට එකතු කිරීමට සහ Wishlist එකට එකතු කිරීමට බොත්තම්. -->
               <input type="submit" value="add to cart" class="btn" name="add_to_cart">
               <input class="option-btn" type="submit" name="add_to_wishlist" value="add to wishlist">
            </div>
            <div class="details"><?= $fetch_product['details']; ?></div> <!-- 23. භාණ්ඩයේ සම්පූර්ණ විස්තරය. -->
         </div>
      </div>
   </form>
   <?php
       }
    }else{
       echo '<p class="empty">no products added yet!</p>'; // 24. අදාළ ID එකෙන් භාණ්ඩයක් නැතිනම් පෙන්වන පණිවිඩය.
    }
   ?>

</section>

<?php include 'components/footer.php'; ?> <!-- 25. පාදකය (Footer) ඇතුළත් කරයි. -->

<script src="js/script.js"></script> <!-- 26. JS ගොනුව සම්බන්ධ කරයි. -->

</body>
</html>