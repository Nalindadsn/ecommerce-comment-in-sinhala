<?php

include '../components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පවතින session එක ආරම්භ කරයි.

$admin_id = $_SESSION['admin_id']; // 3. ලොග් වී සිටින පරිපාලකයාගේ ID එක ලබා ගනී.

if(!isset($admin_id)){ // 4. පරිපාලකයා ලොග් වී නොමැති නම් ඔහුව login පිටුවට යොමු කරයි.
   header('location:admin_login.php');
}

if(isset($_POST['submit'])){ // 5. 'submit' බොත්තම එබූ විට පැතිකඩ යාවත්කාලීන කිරීමේ ක්‍රියාවලිය අරඹයි.

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); // නම ආරක්ෂිතව පිරිසිදු (Sanitize) කරයි.

   // 6. පරිපාලකයාගේ නම යාවත්කාලීන කිරීම සඳහා SQL Query එක ක්‍රියාත්මක කරයි.
   $update_profile_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
   $update_profile_name->execute([$name, $admin_id]);

   $empty_pass = '7c4a8d09ca3762af61e59520943dc26494f8941b'; // 7. හිස් මුරපදයක sha1 අගය (හිස් මුරපද හඳුනා ගැනීමට).
   $prev_pass = $_POST['prev_pass']; // දැනට පවතින මුරපදය.
   $old_pass = sha1($_POST['old_pass']); // ඇතුළත් කළ පැරණි මුරපදය sha1 මගින් hash කරයි.
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']); // නව මුරපදය.
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']); // නව මුරපදය තහවුරු කිරීම.
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   // 8. මුරපද වල නිරවද්‍යතාවය පරීක්ෂා කිරීම.
   if($old_pass == $empty_pass){
      $message[] = 'please enter old password!'; // පැරණි මුරපදය ඇතුළත් කර නැතිනම්.
   }elseif($old_pass != $prev_pass){
      $message[] = 'old password not matched!'; // පැරණි මුරපදය දත්ත සමුදායේ ඇති මුරපදයට නොගැළපේ නම්.
   }elseif($new_pass != $confirm_pass){
      $message[] = 'confirm password not matched!'; // නව මුරපද දෙක නොගැළපේ නම්.
   }else{
      if($new_pass != $empty_pass){
         // 9. සියල්ල නිවැරදි නම් නව මුරපදය දත්ත සමුදායේ යාවත්කාලීන කරයි.
         $update_admin_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$confirm_pass, $admin_id]);
         $message[] = 'password updated successfully!';
      }else{
         $message[] = 'please enter a new password!';
      }
   }
   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update profile</title>

   <!-- CSS සහ අයිකන සම්බන්ධ කිරීම -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">

   <!-- 10. පැතිකඩ යාවත්කාලීන කිරීමේ පෝරමය (Update Profile Form) -->
   <form action="" method="post">
      <h3>update profile</h3>
      <!-- දැනට පවතින මුරපදය hidden input එකක් ලෙස තබා ගනී -->
      <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
      
      <!-- පරිශීලක නාමය (Username) -->
      <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" required placeholder="enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- පැරණි මුරපදය -->
      <input type="password" name="old_pass" placeholder="enter old password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- නව මුරපදය -->
      <input type="password" name="new_pass" placeholder="enter new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- නව මුරපදය තහවුරු කිරීම -->
      <input type="password" name="confirm_pass" placeholder="confirm new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <input type="submit" value="update now" class="btn" name="submit">
   </form>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>