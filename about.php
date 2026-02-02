<?php

include 'components/connect.php'; // 1. Database එකට සම්බන්ධ වීමට අදාළ ගොනුව ඇතුළත් කරයි.

session_start(); // 2. Session එක ආරම්භ කරයි (පරිශීලකයා හඳුනා ගැනීමට).

if(isset($_SESSION['user_id'])){ // 3. පරිශීලකයා ලොග් වී ඇත්නම්, ඔහුගේ ID එක ලබා ගනී.
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = ''; // 4. ලොග් වී නොමැති නම් ID එක හිස්ව තබයි.
};

?>

<!DOCTYPE html>
<html lang="en"> <!-- 5. වෙබ් පිටුව ඉංග්‍රීසි භාෂාවෙන් බව දක්වයි. -->
<head>
   <meta charset="UTF-8"> <!-- 6. අකුරු (Characters) නිවැරදිව පෙන්වීමට මෙය භාවිතා කරයි. -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- 7. Internet Explorer බ්‍රව්සරය සමග ගැළපෙන ලෙස සකසයි. -->
   <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- 8. මොබයිල් උපාංග වල තිරයට ගැළපෙන ලෙස සකසයි. -->
   <title>about</title> <!-- 9. බ්‍රව්සර ටැබ් එකේ "about" ලෙස පෙන්වයි. -->

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" /> <!-- 10. Slider සඳහා Swiper CSS සම්බන්ධ කරයි. -->
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> <!-- 11. අයිකන (Icons) සඳහා Font Awesome සම්බන්ධ කරයි. -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"> <!-- 12. Bootstrap CSS Framework එක සම්බන්ධ කරයි. -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css"> <!-- 13. අපගේ ප්‍රධාන CSS ගොනුව සම්බන්ධ කරයි. -->

</head>
<body>
   
<?php include 'components/user_header.php'; ?> <!-- 14. පිටුවේ ඉහළ කොටස (Navigation Bar) වෙනම ගොනුවකින් ඇතුළත් කරයි. -->

<section class="about"> <!-- 15. "About" කොටස ආරම්භය. -->

   <div class="row"> <!-- 16. පේළියක් (Row) ලෙස සකසයි. -->

      <div class="image"> <!-- 17. පින්තූරය සඳහා වෙන් කළ කොටස. -->
         <img src="images/2672335.jpg" alt=""> <!-- 18. පින්තූරය පෙන්වයි. -->
      </div>

      <div class="content"> <!-- 19. විස්තර අන්තර්ගතය සඳහා වෙන් කළ කොටස. -->
         <h3>why choose us?</h3> <!-- 20. මාතෘකාව: "අපව තෝරාගත යුත්තේ ඇයි?". -->
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam veritatis minus et similique doloribus? Harum molestias tenetur eaque illum quas? Obcaecati nulla in itaque modi magnam ipsa molestiae ullam consequuntur.</p> <!-- 21. විස්තරාත්මක ඡේදය. -->
         <a href="contact.php" class="btn">contact us</a> <!-- 22. "Contact Us" පිටුවට යාමට බොත්තම. -->
      </div>

   </div>

</section>

<?php include 'components/footer.php'; ?> <!-- 23. පිටුවේ පහළ කොටස (Footer) ඇතුළත් කරයි. -->

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script> <!-- 24. Swiper JS ලයිබ්‍රරි එක සම්බන්ධ කරයි. -->

<script src="js/script.js"></script> <!-- 25. අපගේ Custom JS ගොනුව සම්බන්ධ කරයි. -->

<script>
// 26. Reviews Slider එක සඳහා සැකසුම් (Reviews කොටස HTML එකේ නොමැති වුවත්, කේතයේ Script එක තිබේ).
var swiper = new Swiper(".reviews-slider", {
   loop:true, // දිගටම කැරකෙන ලෙස (Loop).
   spaceBetween: 20, // Slides අතර පරතරය.
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   // 27. තිරයේ ප්‍රමාණය අනුව පෙන්වන ගණන වෙනස් කිරීම (Responsive breakpoints).
   breakpoints: {
      0: {
        slidesPerView:1, // කුඩා තිරවල 1යි.
      },
      768: {
        slidesPerView: 2, // මධ්‍යම ප්‍රමාණයේ තිරවල 2යි.
      },
      991: {
        slidesPerView: 3, // ලොකු තිරවල 3යි.
      },
   },
});

</script>

</body>
</html>