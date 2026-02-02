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
   <title>orders</title> <!-- 6. පිටුවේ මාතෘකාව. -->
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> <!-- 7. අයිකන සඳහා Font Awesome. -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"> <!-- 8. Bootstrap CSS සම්බන්ධ කිරීම. -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css"> <!-- 9. අපගේ ප්‍රධාන CSS ගොනුව. -->

</head>
<body>
   
<?php include 'components/user_header.php'; ?> <!-- 10. හෙඩර් එක ඇතුළත් කරයි. -->

<section class="orders"> <!-- 11. ඇණවුම් විස්තර පෙන්වන කොටස ආරම්භය. -->

   <h1 class="heading">My Orders</h1> <!-- 12. ප්‍රධාන මාතෘකාව. -->

   <div class="box-container"> <!-- 13. ඇණවුම් කොටු (Boxes) ආකාරයට පෙන්වන container එක. -->

   <?php
      if($user_id == ''){
         echo '<p class="empty">please login to see your orders</p>'; // 14. පරිශීලකයා ලොග් වී නැතිනම් පෙන්වන පණිවිඩය.
      }else{
         // 15. අදාළ පරිශීලකයා විසින් සිදු කරන ලද සියලුම ඇණවුම් දත්ත සමුදායෙන් තෝරා ගනී.
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){ // 16. ඇණවුම් එකින් එක loop එකක් මගින් පෙන්වයි.
   ?>
   <div class="box"> <!-- 17. එක් එක් ඇණවුම සඳහා වෙනම පෙට්ටියක්. -->
      <p>Created at : <span><?= $fetch_orders['placed_on']; ?></span></p> <!-- 18. ඇණවුම සිදු කළ දිනය සහ වේලාව. -->
      <p>Name : <span><?= $fetch_orders['name']; ?></span></p> <!-- 19. පාරිභෝගිකයාගේ නම. -->
      <p>Email : <span><?= $fetch_orders['email']; ?></span></p> <!-- 20. ඊමේල් ලිපිනය. -->
      <p>Number : <span><?= $fetch_orders['number']; ?></span></p> <!-- 21. දුරකථන අංකය. -->
      <p>Address : <span><?= $fetch_orders['address']; ?></span></p> <!-- 22. භාණ්ඩ ලැබිය යුතු ලිපිනය. -->
      <p>Payment method : <span><?= $fetch_orders['method']; ?></span></p> <!-- 23. ගෙවීම් සිදු කළ ආකාරය. -->
      <p>Your orders : <span><?= $fetch_orders['total_products']; ?></span></p> <!-- 24. ඇණවුම් කළ භාණ්ඩ ලැයිස්තුව. -->
      <p>Total price : <span>$<?= $fetch_orders['total_price']; ?>/-</span></p> <!-- 25. මුළු එකතුව. -->
      <p> Payment status :<?php 
         // 26. ගෙවීම් තත්ත්වය අනුව වර්ණ ගැන්වූ ලේබලයක් පෙන්වයි.
         if($fetch_orders['payment_status'] == 'pending'){ 
            echo '<span class="badge rounded-pill bg-danger text-white">'.$fetch_orders['payment_status'].'</span>'; // ගෙවීම් ඉතිරි නම් රතු පාටින්.
         }else{ 
            echo '<span class="badge rounded-pill bg-success text-white">'.$fetch_orders['payment_status'].'</span>'; // ගෙවීම් අවසන් නම් කොළ පාටින්.
         }; 
      ?>
      <span style=""></span> </p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">no orders placed yet!</p>'; // 27. කිසිදු ඇණවුමක් කර නොමැති නම් පෙන්වන පණිවිඩය.
      }
      }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?> <!-- 28. පාදකය (Footer) ඇතුළත් කරයි. -->

<script src="js/script.js"></script> <!-- 29. JS ගොනුව සම්බන්ධ කරයි. -->

</body>
</html>