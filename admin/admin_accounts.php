<?php

include '../components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පද්ධතියට ඇතුළු වී සිටින පරිපාලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

$admin_id = $_SESSION['admin_id']; // 3. දැනට ලොග් වී සිටින පරිපාලකයාගේ (Admin) ID එක ලබා ගනී.

if(!isset($admin_id)){ // 4. පරිපාලකයා ලොග් වී නොමැති නම් ඔහුව login පිටුවට යොමු කරයි.
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){ // 5. 'delete' අගයක් URL එක හරහා ලැබුණහොත් එම ගිණුම ඉවත් කිරීම ආරම්භ කරයි.
   $delete_id = $_GET['delete'];
   // 6. අදාළ ID එක සහිත පරිපාලක ගිණුම දත්ත සමුදායෙන් ඉවත් කරයි.
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php'); // 7. ඉවත් කිරීමෙන් පසු නැවත මෙම පිටුවටම යොමු කරයි.
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin accounts</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- bootstrap cdn link -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
   <!-- custom admin css file link -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?> <!-- 8. පරිපාලක හෙඩර් (Header) කොටස ඇතුළත් කරයි. -->

<section class="accounts"> <!-- 9. පරිපාලක ගිණුම් කළමනාකරණ කොටස. -->

   <h1 class="heading">admin accounts</h1>

   <div class="container">

   <div class="box">
      <!-- 10. නව පරිපාලකයෙකු ලියාපදිංචි කිරීම සඳහා වන බොත්තම. -->
      <a href="register_admin.php" class="btn" style="width:200px;float: right;">+ register admin</a>
   </div>

<table class="table"> <!-- 11. ගිණුම් විස්තර පෙන්වන වගුව (Table). -->
   <tr>
      <th>Admin Id</th>
      <th>Admin Name</th>
      <th>Actions</th>
   </tr>
   <?php
      // 12. දත්ත සමුදායේ සිටින සියලුම පරිපාලකයන්ගේ විස්තර ලබා ගනී.
      $select_accounts = $conn->prepare("SELECT * FROM `admins`");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
         while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
   ?>

   <tr>
      <td><?= $fetch_accounts['id']; ?></td> <!-- 13. පරිපාලකයාගේ ID අංකය. -->
      <td><?= $fetch_accounts['name']; ?></td> <!-- 14. පරිපාලකයාගේ නම. -->
      <td>
      <div class="flex-btn">
         <!-- 15. ගිණුම ඉවත් කිරීමට පෙර තහවුරු කිරීමේ (Confirm) පණිවිඩයක් පෙන්වයි. -->
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('delete this account?')" class="delete-btn">delete</a>
         <?php
            // 16. ලොග් වී සිටින පරිපාලකයාගේම ගිණුම නම් පමණක් 'update' බොත්තම පෙන්වයි.
            if($fetch_accounts['id'] == $admin_id){
               echo '<a href="update_profile.php" class="option-btn">update</a>';
            }
         ?>
      </div></td>
   </tr>
   <?php
         }
      }else{
         echo '<tr><td colspan="3" class="empty">no accounts available!</td></tr>'; // 17. කිසිදු ගිණුමක් නැතිනම් පෙන්වන පණිවිඩය.
      }
   ?>
</table>

   </div>

</section>

<!-- 18. පරිපාලක JS ගොනුව සම්බන්ධ කරයි. -->
<script src="../js/admin_script.js"></script>
   
</body>
</html>