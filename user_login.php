<?php

include 'components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිශීලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

if(isset($_SESSION['user_id'])){ // 3. පරිශීලකයා දැනටමත් ලොග් වී ඇත්නම් ඔහුගේ user_id එක ලබා ගනී.
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = ''; // 4. ලොග් වී නොමැති නම් user_id එක හිස්ව තබයි.
};

if(isset($_POST['submit'])){ // 5. 'login now' බොත්තම එබූ විට පිවිසීමේ ක්‍රියාවලිය ආරම්භ කරයි.

   // 6. පෝරමයෙන් (Form) ලැබෙන ඊමේල් ලිපිනය ලබාගෙන පිරිසිදු කරයි (Sanitize).
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   
   // 7. මුරපදය (Password) ලබාගෙන එය SHA1 ක්‍රමයට hash කර පිරිසිදු කරයි.
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // 8. ලබාදුන් ඊමේල් සහ මුරපදය සහිත පරිශීලකයෙකු දත්ත සමුදායේ සිටීදැයි පරීක්ෂා කරයි.
   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      // 9. පරිශීලකයා සිටී නම්, ඔහුගේ ID එක session එකට ඇතුළත් කර home.php පිටුවට යොමු කරයි.
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   }else{
      // 10. තොරතුරු වැරදි නම් පෙන්වන පණිවිඩය.
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- bootstrap cdn link -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
   
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- 11. ලොගින් පෝරමය පෙන්වන කොටස (Background image එකක් සමඟ) -->
<section class="form-container" style="background:url('images/login.jpg');background-size: cover;padding-top: 50px;padding-bottom: 50px;">

   <form action="" method="post" style="background-color:rgba(0, 0, 0, .4);">
      <h3 class="text-white">login now</h3>
      
      <!-- 12. ඊමේල් ඇතුළත් කරන කොටස (හිස්තැන් ඉවත් කිරීමට oninput භාවිතා කර ඇත). -->
      <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- 13. මුරපදය ඇතුළත් කරන කොටස. -->
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <input type="submit" value="login now" class="btn" name="submit">
      
      <p class="text-white">don't have an account?</p>
      
      <!-- 14. ගිණුමක් නොමැති අයට ලියාපදිංචි වීම සඳහා වන සබැඳිය (Link). -->
      <a href="user_register.php" class="option-btn">register now</a>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>