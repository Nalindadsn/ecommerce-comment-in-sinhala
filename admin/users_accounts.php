<?php

include '../components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පවතින session එක ආරම්භ කරයි.

$admin_id = $_SESSION['admin_id']; // 3. ලොග් වී සිටින පරිපාලකයාගේ ID එක ලබා ගනී.

if(!isset($admin_id)){ // 4. පරිපාලකයා ලොග් වී නොමැති නම් ඔහුව login පිටුවට යොමු කරයි.
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){ // 5. 'delete' පරාමිතිය URL එක හරහා ලැබුණු විට පරිශීලකයෙකු ඉවත් කිරීම අරඹයි.
   $delete_id = $_GET['delete'];

   // 6. පරිශීලක ගිණුම මකා දැමීම.
   $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
   $delete_user->execute([$delete_id]);

   // 7. එම පරිශීලකයාට අදාළ ඇණවුම් (Orders) මකා දැමීම.
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
   $delete_orders->execute([$delete_id]);

   // 8. එම පරිශීලකයා එවා ඇති පණිවිඩ (Messages) මකා දැමීම.
   $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
   $delete_messages->execute([$delete_id]);

   // 9. එම පරිශීලකයාගේ කරත්තයේ (Cart) ඇති දත්ත මකා දැමීම.
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart->execute([$delete_id]);

   // 10. එම පරිශීලකයාගේ මනාප ලැයිස්තුවේ (Wishlist) ඇති දත්ත මකා දැමීම.
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist->execute([$delete_id]);

   header('location:users_accounts.php'); // 11. අවසානයේ නැවත පරිශීලක ගිණුම් පිටුවට යොමු කරයි.
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users accounts</title>

   <!-- CSS සහ Font Awesome සම්බන්ධ කිරීම -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">

   <h1 class="heading">user accounts</h1>

   <div class="container">
      <!-- 12. පරිශීලක දත්ත පෙන්වීම සඳහා වගුවක් (Table) භාවිතා කරයි -->
      <table class="table">
         <tr>
            <th>User Id</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
         </tr>
         <?php
            // 13. සියලුම පරිශීලකයින් තෝරාගෙන පෙන්වීමට SQL Query එක ක්‍රියාත්මක කරයි.
            $select_accounts = $conn->prepare("SELECT * FROM `users`");
            $select_accounts->execute();
            if($select_accounts->rowCount() > 0){
               while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
         ?>
         <tr>
            <td><?= $fetch_accounts['id']; ?></td>
            <td><?= $fetch_accounts['name']; ?></td>
            <td><?= $fetch_accounts['email']; ?></td>
            <!-- 14. මකා දැමීමට පෙර පරිශීලකයාගෙන් ස්ථිර කිරීමක් (Confirm) ලබා ගනී -->
            <td><a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('delete this account? the user related information will also be delete!')" class="delete-btn">delete</a></td>
         </tr>
         <?php
               }
            }else{
               echo '<p class="empty">no accounts available!</p>';
            }
         ?>
      </table>
   </div>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>