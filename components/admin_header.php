<?php
   // 1. පද්ධතියේ කිසියම් දැනුම්දීමක් (Alert/Message) තිබේ නම් ඒවා පෙන්වීම සඳහා මෙම කොටස භාවිතා වේ.
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <!-- පරිශීලකයා 'X' ලකුණ එබූ විට JavaScript මඟින් එම පණිවිඩය ඉවත් කරයි -->
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<header class="header">

   <section class="flex">

      <!-- 2. Dashboard වෙත යන ප්‍රධාන ලාංඡනය (Logo) -->
      <a href="../admin/index.php" class="logo">Dashboard</a>

      <!-- 3. පරිපාලක මණ්ඩලයේ විවිධ අංශ වෙත යාමට ඇති ප්‍රධාන මෙනුව (Navigation Bar) -->
      <nav class="navbar">
         <a href="../admin/index.php">HOME</a>
         <a href="../admin/products.php">PRODUCTS</a>
         <a href="../admin/categories.php">CATEGORIES</a>
         <a href="../admin/placed_orders.php">ORDERS</a>
         <a href="../admin/admin_accounts.php">ADMINS </a>
         <a href="../admin/users_accounts.php">USERS</a>
         <a href="../admin/messages.php">MESSAGES</a>
      </nav>

      <!-- 4. ජංගම දුරකථන සඳහා මෙනු බොත්තම සහ පරිශීලක තොරතුරු පෙන්වන බොත්තම් (Icons) -->
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <!-- 5. දැනට ලොග් වී සිටින පරිපාලකයාගේ තොරතුරු පෙන්වන Profile කොටස -->
      <div class="profile">
         <?php
            // දත්ත සමුදායෙන් (Database) අදාළ පරිපාලකයාගේ නම ලබා ගැනීම
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
            $select_profile->execute([$admin_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile['name']; ?></p>
         
         <!-- Profile එක යාවත්කාලීන කිරීමේ බොත්තම -->
         <a href="../admin/update_profile.php" class="btn">update profile</a>

         <!-- ලියාපදිංචි වීමට හෝ ලොග් වීමට ඇති අමතර බොත්තම් -->
         <div class="flex-btn">
            <a href="../admin/register_admin.php" class="option-btn">register</a>
            <a href="../admin/admin_login.php" class="option-btn">login</a>
         </div>

         <!-- පද්ධතියෙන් ඉවත් වීමේ (Logout) බොත්තම - මෙහිදී ස්ථිර කිරීමේ පණිවිඩයක් පෙන්වයි -->
         <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm('logout from the website?');">logout</a> 
      </div>

   </section>

</header>