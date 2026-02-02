<?php

include 'components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිශීලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

if(isset($_SESSION['user_id'])){ // 3. පරිශීලකයා දැනටමත් ලොග් වී ඇත්නම් ඔහුගේ user_id එක ලබා ගනී.
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = ''; // 4. ලොග් වී නොමැති නම් user_id එක හිස්ව තබයි.
};

if(isset($_POST['submit'])){ // 5. 'register now' බොත්තම එබූ විට ලියාපදිංචි වීමේ ක්‍රියාවලිය ආරම්භ කරයි.

   // 6. පෝරමයෙන් (Form) ලැබෙන නම ලබාගෙන පිරිසිදු කරයි (Sanitize).
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   
   // 7. ඊමේල් ලිපිනය ලබාගෙන පිරිසිදු කරයි.
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   
   // 8. මුරපදය (Password) ලබාගෙන SHA1 ක්‍රමයට hash කර පිරිසිදු කරයි.
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   
   // 9. තහවුරු කිරීමේ මුරපදය (Confirm Password) ලබාගෙන hash කර පිරිසිදු කරයි.
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // 10. ලබාදුන් ඊමේල් ලිපිනය දැනටමත් දත්ත සමුදායේ තිබේදැයි පරීක්ෂා කරයි.
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      // 11. ඊමේල් එක දැනටමත් පද්ධතියේ තිබේ නම් පෙන්වන පණිවිඩය.
      $message[] = 'email already exists!';
   }else{
      // 12. මුරපද දෙක එකිනෙකට ගැලපේදැයි පරීක්ෂා කරයි.
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         // 13. සියල්ල නිවැරදි නම් නව පරිශීලකයා දත්ත සමුදායට ඇතුළත් කරයි (Insert).
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'registered successfully, login now please!';
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
   <title>register</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   
   <!-- bootstrap cdn link -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- 14. ලියාපදිංචි වීමේ පෝරමය පෙන්වන කොටස (Background image එකක් සමඟ) -->
<section class="form-container" style="background:url('images/contact.jpg');background-size: cover;">

   <form action="" method="post" style="background-color:rgba(0, 0, 0, .5);">
      <h3 class="text-white">register now</h3>
      
      <!-- 15. පරිශීලක නාමය ඇතුළත් කරන කොටස. -->
      <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box">
      
      <!-- 16. ඊමේල් ඇතුළත් කරන කොටස (හිස්තැන් ඉවත් කිරීමට oninput භාවිතා කර ඇත). -->
      <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- 17. මුරපදය ඇතුළත් කරන කොටස. -->
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- 18. මුරපදය තහවුරු කරන කොටස. -->
      <input type="password" name="cpass" required placeholder="confirm your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <input type="submit" value="register now" class="btn" name="submit">
      
      <p class="text-white">already have an account?</p>
      
      <!-- 19. දැනටමත් ගිණුමක් ඇති අයට ලොග් වීම සඳහා වන සබැඳිය. -->
      <a href="user_login.php" class="option-btn">login now</a>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>