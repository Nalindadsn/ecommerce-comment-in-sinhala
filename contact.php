<?php

include 'components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිශීලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

if(isset($_SESSION['user_id'])){ // 3. පරිශීලකයා ලොග් වී ඇත්නම් user_id එක ලබා ගනී.
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = ''; // 4. ලොග් වී නොමැති නම් user_id එක හිස්ව තබයි.
};

if(isset($_POST['send'])){ // 5. 'send' බොත්තම එබූ විට පණිවිඩය යැවීමේ ක්‍රියාවලිය ආරම්භ කරයි.

   // 6. පෝරමයෙන් (Form) ලැබෙන දත්ත ලබාගෙන ඒවා ආරක්ෂිතව පිරිසිදු කරයි (Sanitize).
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   // 7. එකම පණිවිඩය කලින් යවා ඇත්දැයි පරීක්ෂා කරයි.
   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'already sent message!'; // 8. පණිවිඩය දැනටමත් යවා තිබේ නම් පෙන්වන පණිවිඩය.
   }else{

      // 9. 'messages' වගුවට නව පණිවිඩය ඇතුළත් කරයි.
      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message[] = 'sent message successfully!'; // 10. සාර්ථකව යැවූ බව පෙන්වන පණිවිඩය.

   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8"> <!-- 11. අකුරු නිවැරදිව පෙන්වීමට character encoding සකසයි. -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title> <!-- 12. පිටුවේ මාතෘකාව. -->
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"> <!-- 13. අයිකන සඳහා Font Awesome. -->
   <!-- bootstrap cdn link -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"> <!-- 14. Bootstrap CSS සම්බන්ධ කිරීම. -->

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css"> <!-- 15. අපගේ ප්‍රධාන CSS ගොනුව. -->

</head>
<body>
   
<?php include 'components/user_header.php'; ?> <!-- 16. හෙඩර් එක ඇතුළත් කරයි. -->

<section class="contact" style="background:url('images/contact.jpg');background-size: cover;"> <!-- 17. පසුබිම් පින්තූරය සමඟ Contact කොටස. -->

   <form action="" method="post" style="background:rgba(0, 0, 0, .3);"> <!-- 18. පණිවිඩ ලබාගන්නා පෝරමය. -->
      <h3 class="text-white">get in touch</h3> <!-- 19. මාතෘකාව. -->
      <input type="text" name="name" placeholder="enter your name" required maxlength="20" class="box"> <!-- 20. නම ඇතුළත් කිරීමට. -->
      <input type="email" name="email" placeholder="enter your email" required maxlength="50" class="box"> <!-- 21. ඊමේල් ලිපිනය. -->
      <input type="number" name="number" min="0" max="9999999999" placeholder="enter your number" required onkeypress="if(this.value.length == 10) return false;" class="box"> <!-- 22. දුරකථන අංකය. -->
      <textarea name="msg" class="box" placeholder="enter your message" cols="30" rows="10"></textarea> <!-- 23. පණිවිඩය ලිවීමට. -->
      <input type="submit" value="send message" name="send" class="btn"> <!-- 24. පණිවිඩය යැවීමේ බොත්තම. -->
   </form>

</section>

<?php include 'components/footer.php'; ?> <!-- 25. පාදකය (Footer) ඇතුළත් කරයි. -->

<script src="js/script.js"></script> <!-- 26. JS ගොනුව සම්බන්ධ කිරීම. -->

</body>
</html>