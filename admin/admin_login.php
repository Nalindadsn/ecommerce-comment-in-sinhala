<?php

include '../components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිපාලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

if(isset($_POST['submit'])){ // 3. 'login now' බොත්තම එබූ විට පිවිසීමේ ක්‍රියාවලිය ආරම්භ කරයි.

   // 4. පෝරමයෙන් (Form) ලැබෙන පරිපාලක නාමය ලබාගෙන පිරිසිදු කරයි (Sanitize).
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   
   // 5. මුරපදය (Password) ලබාගෙන එය SHA1 ක්‍රමයට hash කර පිරිසිදු කරයි.
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // 6. ලබාදුන් නම සහ මුරපදය සහිත පරිපාලකයෙකු දත්ත සමුදායේ සිටීදැයි පරීක්ෂා කරයි.
   $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   if($select_admin->rowCount() > 0){
      // 7. පරිපාලකයා සිටී නම්, ඔහුගේ ID එක session එකට ඇතුළත් කර index.php පිටුවට යොමු කරයි.
      $_SESSION['admin_id'] = $row['id'];
      header('location:index.php');
   }else{
      // 8. තොරතුරු වැරදි නම් පෙන්වන පණිවිඩය.
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
   <!-- custom admin css file link -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <!-- bootstrap cdn link -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>
<body>

<?php
   // 9. පද්ධතිය විසින් ලබාදෙන පණිවිඩ (Messages) පෙන්වන කොටස.
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<!-- 10. පරිපාලක ලොගින් පෝරමය පෙන්වන කොටස (Background image එකක් සමඟ) -->
<section class="form-container" style="background:url('../images/login.jpg');background-size: cover;padding-top: 50px;padding-bottom: 50px;">

   <form action="" method="post" style="background:rgba(0, 0, 0, .3);">
      <h3 class="text-white">login now</h3>
      
      <!-- 11. පරිපාලක නාමය ඇතුළත් කරන කොටස. -->
      <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- 12. මුරපදය ඇතුළත් කරන කොටස. -->
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <input type="submit" value="login now" class="option-btn" name="submit">
   </form>

</section>
   
</body>
</html>