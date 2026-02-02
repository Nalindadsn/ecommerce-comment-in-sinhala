<?php
   // 1. පණිවිඩ (Messages) පෙන්වීම සඳහා වන කේතය.
   // $message විචල්‍යයේ අගයක් තිබේ නම්, එම පණිවිඩ එකින් එක (Loop) පෙන්වනු ලබයි.
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <!-- Close icon එක මත click කළ විට පණිවිඩ කොටුව (div) ඉවත් වේ -->
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<!-- 2. වෙබ් අඩවියේ හිස (Header) කොටස ආරම්භය -->
<header class="header">

   <!-- 3. කළු පැහැති පාරදෘශ්‍ය (Semi-transparent) පසුබිමක් සහිත තීරුව -->
   <section class="flex" style="background:rgba(0, 0, 0, .9); ">

      <!-- 4. වෙබ් අඩවියේ නම (Logo) පෙන්වන ස්ථානය -->
      <a href="home.php" class="logo text-white">Ecommerce<span></span></a>

      <!-- 5. ප්‍රධාන මෙනුව (Navigation Bar) - විවිධ පිටුවලට යාමට අවශ්‍ය Link මෙහි ඇත -->
      <nav class="navbar">
         <a href="home.php" class="text-white"> HOME</a>
         <a href="about.php" class="text-white">ABOUT</a>
         <a href="orders.php" class="text-white">ORDERS</a>
         <a href="shop.php" class="text-white">SHOP</a>
         <a href="contact.php" class="text-white">CONTACT</a>
      </nav>

      <!-- 6. Icons සහ Cart තොරතුරු පෙන්වන කොටස -->
      <div class="icons">
         <?php
            // 7. මෙම පරිශීලකයාගේ Cart එකේ ඇති භාණ්ඩ ගණන (Cart Count) දත්ත සමුදායෙන් ගණනය කරයි.
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_counts = $count_cart_items->rowCount();
         ?>
         <!-- Mobile Menu Button එක (කුඩා තිර සඳහා) -->
         <div id="menu-btn" class="fas fa-bars"></div>
         
         <!-- සෙවුම් (Search) Icon එක -->
         <a href="search_page.php"><i class="fas fa-search text-white"></i></a>
         
         <!-- සාප්පු සවාරි කරත්තය (Shopping Cart) සහ එහි අඩංගු භාණ්ඩ ගණන පෙන්වීම -->
         &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;
         <a class="text-primary" href="cart.php" style="position: relative;">
            <i class="fas fa-shopping-cart"></i>
            <span>&nbsp;<span class="bg-primary text-white text-small" style="font-size: 13px; margin-top: -3px; position: absolute;top: 8px;padding:2px 3px 0; border-radius: 5px;" >
               <?= $total_cart_counts; ?>
            </span></span>
         </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         
         <!-- පරිශීලක Profile එක පෙන්වන Button එක -->
         <div id="user-btn" class="fas fa-user text-white"></div>
      </div>

      <!-- 8. පරිශීලකයාගේ පුද්ගලික තොරතුරු (Profile Dropdown) පෙන්වන කොටස -->
      <div class="profile">
         <?php          
            // 9. දත්ත සමුදායෙන් පරිශීලකයාගේ නම ඇතුළු තොරතුරු ලබා ගැනීම.
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            
            // පරිශීලකයා Login වී ඇත්නම්:
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <!-- පරිශීලක නම පෙන්වීම -->
         <p><?= $fetch_profile["name"]; ?></p>
         <!-- තොරතුරු යාවත්කාලීන කිරීමට අවශ්‍ය Link එක -->
         <a href="update_user.php" class="btn">update profile</a>
         
         <!-- Logout වීමට පෙර තහවුරු කිරීමක් (Confirmation) අසයි -->
         <a href="components/user_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a> 
         
         <?php
            // 10. පරිශීලකයා Login වී නොමැති නම් (Guest User):
            }else{
         ?>
         <p>please login or register first!</p>
         <div class="flex-btn">
            <a href="user_register.php" class="option-btn">register</a>
            <a href="user_login.php" class="option-btn">login</a>
         </div>
         <?php
            }
         ?>      
      </div>

   </section>

</header>