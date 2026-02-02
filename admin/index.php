<?php

include '../components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිපාලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

$admin_id = $_SESSION['admin_id']; // 3. ලොග් වී සිටින පරිපාලකයාගේ ID එක ලබා ගනී.

if(!isset($admin_id)){ // 4. පරිපාලකයා ලොග් වී නොමැති නම් ඔහුව login පිටුවට යොමු කරයි.
header('location:admin_login.php');
}

?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>dashboard</title>

<!-- font awesome cdn link  -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<!-- bootstrap cdn link -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<!-- custom admin css file link -->
<link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?> <!-- 5. පරිපාලක හෙඩර් කොටස ඇතුළත් කරයි. -->

<section class="dashboard">

<h1 class="heading">dashboard</h1>

<div class="box-container">

  <!-- 6. පරිපාලකයාගේ නම පෙන්වන සහ තොරතුරු යාවත්කාලීන කිරීමට යොමු කරන කොටස. -->
  <div class="box">
     <h3>welcome!</h3>
     <p><?= $fetch_profile['name']; ?></p>
     <a href="update_profile.php" class="btn">update profile</a>
  </div>

  <!-- 7. ගෙවීම් අවසන් නොකළ (Pending) ඇණවුම්වල මුළු වටිනාකම ගණනය කර පෙන්වීම. -->
  <div class="box">
     <?php
        $total_pendings = 0;
        $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
        $select_pendings->execute(['pending']);
        if($select_pendings->rowCount() > 0){
           while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
              $total_pendings += $fetch_pendings['total_price'];
           }
        }
     ?>
     <h3><span>LKR </span> <?= $total_pendings; ?><span>/-</span></h3>
     <p>total pendings</p>
     <a href="placed_orders.php" class="btn">see orders</a>
  </div>

  <!-- 8. සාර්ථකව අවසන් කළ (Completed) ඇණවුම්වල මුළු වටිනාකම පෙන්වීම. -->
  <div class="box">
     <?php
        $total_completes = 0;
        $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
        $select_completes->execute(['completed']);
        if($select_completes->rowCount() > 0){
           while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
              $total_completes += $fetch_completes['total_price'];
           }
        }
     ?>
     <h3><span>LKR </span><?= $total_completes; ?><span>/-</span></h3>
     <p>completed orders</p>
     <a href="placed_orders.php" class="btn">see orders</a>
  </div>

  <!-- 9. පද්ධතියට ලැබී ඇති මුළු ඇණවුම් සංඛ්‍යාව පෙන්වීම. -->
  <div class="box">
     <?php
        $select_orders = $conn->prepare("SELECT * FROM `orders`");
        $select_orders->execute();
        $number_of_orders = $select_orders->rowCount()
     ?>
     <h3><?= $number_of_orders; ?></h3>
     <p>orders placed</p>
     <a href="placed_orders.php" class="btn">see orders</a>
  </div>

  <!-- 10. දැනට ඇතුළත් කර ඇති මුළු භාණ්ඩ (Products) සංඛ්‍යාව පෙන්වීම. -->
  <div class="box">
     <?php
        $select_products = $conn->prepare("SELECT * FROM `products`");
        $select_products->execute();
        $number_of_products = $select_products->rowCount()
     ?>
     <h3><?= $number_of_products; ?></h3>
     <p>products added</p>
     <a href="products.php" class="btn">see products</a>
  </div>

  <!-- 11. පවතින මුළු ප්‍රවර්ග (Categories) සංඛ්‍යාව පෙන්වීම. -->
  <div class="box">
     <?php
        $select_cats = $conn->prepare("SELECT * FROM `categories`");
        $select_cats->execute();
        $number_of_cats = $select_cats->rowCount()
     ?>
     <h3><?= $number_of_cats; ?></h3>
     <p>categories added</p>
     <a href="categories.php" class="btn">see categories</a>
  </div>

  <!-- 12. ලියාපදිංචි වී සිටින සාමාන්‍ය පරිශීලකයන් (Users) ගණන. -->
  <div class="box">
     <?php
        $select_users = $conn->prepare("SELECT * FROM `users`");
        $select_users->execute();
        $number_of_users = $select_users->rowCount()
     ?>
     <h3><?= $number_of_users; ?></h3>
     <p>normal users</p>
     <a href="users_accounts.php" class="btn">see users</a>
  </div>

  <!-- 13. පද්ධතියේ සිටින මුළු පරිපාලකයන් (Admins) ගණන. -->
  <div class="box">
     <?php
        $select_admins = $conn->prepare("SELECT * FROM `admins`");
        $select_admins->execute();
        $number_of_admins = $select_admins->rowCount()
     ?>
     <h3><?= $number_of_admins; ?></h3>
     <p>admin users</p>
     <a href="admin_accounts.php" class="btn">see admins</a>
  </div>

  <!-- 14. පරිශීලකයන්ගෙන් ලැබී ඇති පණිවිඩ (Messages) සංඛ්‍යාව. -->
  <div class="box">
     <?php
        $select_messages = $conn->prepare("SELECT * FROM `messages`");
        $select_messages->execute();
        $number_of_messages = $select_messages->rowCount()
     ?>
     <h3><?= $number_of_messages; ?></h3>
     <p>new messages</p>
     <a href="messages.php" class="btn">see messages</a>
  </div>


</div>

</section>

<script src="../js/admin_script.js"></script>

</body>
</html>