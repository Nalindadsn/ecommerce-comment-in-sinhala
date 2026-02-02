<?php

include '../components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පවතින session එක ආරම්භ කරයි.

$admin_id = $_SESSION['admin_id']; // 3. ලොග් වී සිටින පරිපාලකයාගේ (Admin) ID එක ලබා ගනී.

if(!isset($admin_id)){ // 4. පරිපාලකයා ලොග් වී නොමැති නම් ඔහුව login පිටුවට යොමු කරයි.
   header('location:admin_login.php');
}

if(isset($_POST['submit'])){ // 5. 'register now' බොත්තම එබූ විට ලියාපදිංචි කිරීමේ ක්‍රියාවලිය අරඹයි.

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); // නම පිරිසිදු (Sanitize) කරයි.
   
   $pass = sha1($_POST['pass']); // 6. මුරපදය ආරක්ෂිතව තබා ගැනීමට sha1 මඟින් encrypt කරයි.
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   
   $cpass = sha1($_POST['cpass']); // 7. තහවුරු කිරීමේ මුරපදයද encrypt කරයි.
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // 8. ඇතුළත් කළ පරිශීලක නාමය (Username) දැනටමත් භාවිතා කර ඇත්දැයි පරීක්ෂා කරයි.
   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
   $select_admin->execute([$name]);

   if($select_admin->rowCount() > 0){
      $message[] = 'username already exist!'; // දැනටමත් පවතින නමක් නම් දැනුම් දෙයි.
   }else{
      // 9. මුරපද දෙකම එකිනෙකට සමාන දැයි පරීක්ෂා කරයි.
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         // 10. මුරපද සමාන නම් නව පරිපාලකයාගේ දත්ත 'admins' වගුවට ඇතුළත් කරයි.
         $insert_admin = $conn->prepare("INSERT INTO `admins`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'new admin registered successfully!';
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
   <title>register admin</title>

   <!-- Font Awesome සහ Bootstrap CSS ලබා ගැනීම -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?> <!-- 11. පරිපාලක මෙනුව/Header එක ඇතුළත් කිරීම -->

<section class="form-container">

   <!-- 12. නව පරිපාලකයා ලියාපදිංචි කිරීමේ පෝරමය (Form) -->
   <form action="" method="post">
      <h3>register now</h3>
      
      <!-- පරිශීලක නාමය ඇතුළත් කිරීම (හිස්තැන් ඉවත් කිරීමට JS භාවිතා කර ඇත) -->
      <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- මුරපදය ඇතුළත් කිරීම -->
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- මුරපදය තහවුරු කිරීම -->
      <input type="password" name="cpass" required placeholder="confirm your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- ලියාපදිංචි කිරීමේ බොත්තම -->
      <input type="submit" value="register now" class="btn" name="submit">
   </form>

</section>

<script src="../js/admin_script.js"></script> <!-- 13. අදාළ JavaScript ගොනුව සම්බන්ධ කිරීම -->
   
</body>
</html>