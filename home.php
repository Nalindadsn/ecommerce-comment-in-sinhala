<?php

include 'components/connect.php'; // 1. Database එකට සම්බන්ධ වීමට අදාළ ගොනුව (file) මෙතැනට ඇතුළත් කරයි.

session_start(); // 2. Session එක ආරම්භ කරයි. (පරිශීලකයා පිවිසී ඇත්දැයි බැලීමට මෙය වැදගත් වේ).

if(isset($_SESSION['user_id'])){ // 3. පරිශීලකයා (User) ලොග් වී ඇත්නම්, ඔහුගේ User ID එක ලබා ගනී.
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = ''; // 4. ලොග් වී නොමැති නම් User ID එක හිස්ව තබයි.
};

include 'components/wishlist_cart.php'; // 5. Cart සහ Wishlist වලට අදාළ කේතයන් අඩංගු ගොනුව සම්බන්ධ කරයි.

?>

<!DOCTYPE html>
<html lang="en"> <!-- 6. වෙබ් පිටුව ඉංග්‍රීසි භාෂාවෙන් බව දක්වයි. -->
<head>
   <meta charset="UTF-8"> <!-- 7. සිංහල වැනි අකුරු නිවැරදිව පෙන්වීමට මෙම කේතය (character set) භාවිතා කරයි. -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- 8. Internet Explorer බ්‍රව්සරය සඳහා ගැළපෙන ලෙස සකසයි. -->
   <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- 9. මොබයිල් ෆෝන් වල වෙබ් පිටුව හොඳින් පෙන්වීමට මෙය භාවිතා කරයි. -->
   <title>home</title> <!-- 10. බ්‍රව්සරයේ ටැබ් එකේ "home" ලෙස නම පෙන්වයි. -->

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" /> <!-- 11. Image Slider එක සඳහා අවශ්‍ය Swiper CSS ගොනුව සම්බන්ධ කරයි. -->
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> <!-- 12. අයිකන (Icons) පෙන්වීමට Font Awesome සම්බන්ධ කරයි. -->
   
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"> <!-- 13. Bootstrap CSS Framework එක සම්බන්ධ කරයි (Design එක ලස්සන කිරීමට). -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css"> <!-- 14. අප විසින් සාදන ලද style.css ගොනුව සම්බන්ධ කරයි. -->
   
   <style type="text/css"> 
      /* 15. මෙම පිටුවට පමණක් අදාළ වන විශේෂිත Styles පහතින් ලියයි. */
      .swiper-slide{
         position: relative;
         padding: 20px;
         border-radius: 5px; /* මුළු රවුම් කරයි */
      }
      .swiper-slide .content {
         position: absolute; /* Slider එකේ content එක රූපය මත පෙන්වීමට */
         top: 0%;
         right: 10px;
         text-align: right;
         text-shadow: 3px 3px 3px rgba(0, 0, 0,.7); /* අකුරු වලට සෙවනැල්ලක් (shadow) එක් කරයි */
      }
   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?> <!-- 16. වෙබ් අඩවියේ ඉහළ කොටස (Navigation Bar) වෙනම ගොනුවකින් ඇතුළත් කරයි. -->

<div class="home-bg1"> <!-- 17. පසුබිම් රූපය (Background Image) සඳහා වන Div එක. -->

<section class="home " style="box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.1);"> <!-- 18. Home Section එක ආරම්භය. -->

<div class="container"> <!-- 19. Bootstrap Container එක (අන්තර්ගතය මැදට ගැනීමට). -->
  <div class="row">
    <div class="col-sm-4"><br><br><br> <!-- 20. වම් පැත්තේ කොටස (Column). -->
      <h1 class="text-white pt-20" style="padding-top:100px; font-size: 40px; text-shadow: 3px 3px 3px rgba(0, 0, 0,.7);">WELCOME TO ECOMMERCE WEBSITE</h1> <!-- 21. සාදරයෙන් පිළිගැනීමේ පණිවිඩය. -->
    </div>
    <div class="col-sm-8 other"> <!-- 22. දකුණු පැත්තේ කොටස (Slider එක පෙන්වීමට). -->
      
   <div class="swiper home-slider " style="background: rgba(0, 0, 0, 0.4); border:1px solid #555 "> <!-- 23. Swiper Slider එකේ ප්‍රධාන කොටස. -->
   
   <div class="swiper-wrapper"> <!-- 24. Slider එකේ Slides අඩංගු වන Wrapper එක. -->

   <?php
      // 25. Products table එකෙන් පළමු භාණ්ඩ 6 තෝරා ගනී.
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
      $select_products->execute(); // 26. Query එක ක්‍රියාත්මක කරයි.
      
      // 27. භාණ්ඩ තිබේ නම් පමණක් මෙම කොටස ක්‍රියාත්මක වේ.
      if($select_products->rowCount() > 0){
       // 28. තෝරාගත් භාණ්ඩ එකින් එක පෙන්වීමට While loop එකක් භාවිතා කරයි.
       while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   
      <div class="swiper-slide slide" > <!-- 29. සෑම භාණ්ඩයක්ම වෙනම Slide එකක් ලෙස පෙන්වයි. -->
         <div class="image">
            <!-- 30. භාණ්ඩයේ පින්තූරය පෙන්වයි. -->
            <img  src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
         </div>
         <div class="content" style="background: rgba(0, 0, 0, 0.4);padding: 10px;">
            <!-- 31. භාණ්ඩයේ නම පෙන්වයි. -->
            <span><?= $fetch_product['name']; ?></span>
            <!-- 32. භාණ්ඩයේ මිල පෙන්වයි. -->
            <h3><?= $fetch_product['price']; ?></h3>
            <!-- 33. Shop පිටුවට යාමට Button එකක්. -->
            <a href="shop.php" class="btn">shop now</a>
         </div>
      </div>

   <?php
       } // While loop එකේ අවසානය.
    }else{
       // 34. භාණ්ඩ කිසිවක් නොමැති නම් මෙම පණිවිඩය පෙන්වයි.
       echo '<p class="empty ">no products added yet!</p>';
    }
   ?>

   </div>

      <div class="swiper-pagination"></div> <!-- 35. Slider එකේ යටින් ඇති තිත් (Dots) පෙන්වයි. -->

   </div>

    </div>
  </div>
</div>

</section>

</div>

<section class="category container"> <!-- 36. Category Section එක ආරම්භය. -->

   <h1 class="heading">shop by category</h1> <!-- 37. මාතෘකාව: "Shop by category". -->

<div class="row">
</div>

   <div class="swiper category-slider"> <!-- 38. Categories පෙන්වීමට තවත් Slider එකක්. -->

   <div class="swiper-wrapper">

         <?php
            // 39. Categories table එකෙන් සියලුම Categories තෝරා ගනී.
            $select_categories = $conn->prepare("SELECT * FROM `categories` "); 
            $select_categories->execute();
            
            if($select_categories->rowCount() > 0){
             while($fetch_category = $select_categories->fetch(PDO::FETCH_ASSOC)){
         ?>
         <!-- 40. Category එකේ Link එක සහ Background Image එක සකසයි. -->
         <a href="category.php?category=<?= $fetch_category['id']; ?>" class="swiper-slide slide" style="background:url(<?php echo "uploaded_cat/".$fetch_category['file']; ?>); background-size: cover;">
            <!-- 41. Category නම පෙන්වයි. -->
            <h2 class="text-white text-shadow p-5 text-bold" style="text-shadow: 2px 2px 2px rgba(0, 0, 0, 0.8);"><?= $fetch_category['name']; ?></h2>
         </a>

         <?php
             }
          }else{
             echo '<p class="empty ">no categories added yet!</p>';
          }
         ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products container"> <!-- 42. අලුත් Products පෙන්වන Section එක. -->

   <h1 class="heading">latest products</h1>

   <div class="swiper products-slider"> <!-- 43. Products සඳහා Slider එක. -->

   <div class="swiper-wrapper">

   <?php
      // 44. අන්තිමට ඇතුලත් කළ (Latest) භාණ්ඩ 6 තෝරා ගනී (ORDER BY id DESC).
      $select_products = $conn->prepare("SELECT * FROM `products` ORDER BY id desc LIMIT 6"); 
      $select_products->execute();
      if($select_products->rowCount() > 0){
       while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <!-- 45. Cart එකට භාණ්ඩ එකතු කිරීමට Form එකක් භාවිතා කරයි. -->
   <form action="" method="post" class="swiper-slide slide">
      <!-- 46. Hidden inputs මගින් භාණ්ඩයේ විස්තර යවයි (ID, නම, මිල, පින්තූරය). -->
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      
      <!-- 47. භාණ්ඩයේ රූපය පෙන්වයි. -->
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      
      <!-- 48. භාණ්ඩයේ නම සහ Quick View ලින්ක් එක. -->
      <h3 class="name"><a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="text-dark"><?= $fetch_product['name']; ?></a> </h3>
      
      <div class="flex">
         <!-- 49. මිල පෙන්වයි. -->
         <div class="price"><span>LKR </span><?= $fetch_product['price']; ?><span>/-</span></div>
         <!-- 50. අවශ්‍ය ප්‍රමාණය (Quantity) තෝරා ගැනීමට Input එකක්. -->
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <!-- 51. Add to Cart බටන් එක. -->
      <input type="submit" value="add to cart" class="btn btn-primary option-btn border-0" name="add_to_cart">
   </form>
   <?php
       }
    }else{
       echo '<p class="empty ">no products added yet!</p>';
    }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>
   <div class="text-center container"><br>
      <!-- 52. තව භාණ්ඩ බැලීමට "See More" බටන් එක. -->
      <a href="shop.php" class="seeMore rounded-pill">See More</a>
   </div>

</section>

<?php include 'components/footer.php'; ?> <!-- 53. වෙබ් අඩවියේ පහළ කොටස (Footer) සම්බන්ධ කරයි. -->

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script> <!-- 54. Swiper JS ලයිබ්‍රරි එක. -->

<script src="js/script.js"></script> <!-- 55. අපගේ custom JS ගොනුව. -->

<script>
// 56. Home Slider එක ක්‍රියාත්මක කිරීමේ සැකසුම් (Settings).
var swiper = new Swiper(".home-slider", {
   loop:true, // දිගටම කැරකෙන ලෙස (Loop).
   spaceBetween: 20, // Slides අතර පරතරය.
   pagination: {
      el: ".swiper-pagination",
      clickable:true, // තිත් මත ක්ලික් කළ විට Slide මාරු වේ.
    },
});

// 57. Category Slider එකේ සැකසුම්.
 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   }, navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
   // 58. තිරයේ ප්‍රමාණය (Screen size) අනුව පෙන්වන ගණන වෙනස් කිරීම (Responsive).
   breakpoints: {
      0: {
         slidesPerView: 4, // කුඩා තිරවල 4ක් පෙන්වයි.
       },
      650: {
        slidesPerView: 4,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5, // ලොකු තිරවල 5ක් පෙන්වයි.
      },
   },
});

// 59. Products Slider එකේ සැකසුම්.
var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2, // කුඩා තිරවල භාණ්ඩ 2ක් පෙන්වයි.
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3, // ලොකු තිරවල භාණ්ඩ 3ක් පෙන්වයි.
      },
   },
});

</script>

</body>
</html>