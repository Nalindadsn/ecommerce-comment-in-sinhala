<?php

include 'components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිශීලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

if(isset($_SESSION['user_id'])){ // 3. පරිශීලකයා ලොග් වී ඇත්නම් user_id එක ලබා ගනී.
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = ''; // 4. ලොග් වී නොමැති නම් user_id එක හිස්ව තබයි.
};

if(isset($_POST['submit'])){ // 5. 'update now' බොත්තම එබූ විට වෙනස්කම් සිදු කිරීම ආරම්භ කරයි.

   // 6. පෝරමයෙන් (Form) ලැබෙන නම සහ ඊමේල් ලබාගෙන පිරිසිදු කරයි (Sanitize).
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   // 7. පරිශීලකයාගේ නම සහ ඊමේල් ලිපිනය යාවත්කාලීන කරයි.
   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $user_id]);

   // 8. මුරපදය (Password) වෙනස් කිරීමට අදාළ විචල්‍යයන්.
   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709'; // 9. හිස් මුරපදයක SHA1 අගය.
   $prev_pass = $_POST['prev_pass'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // 10. මුරපදය වෙනස් කිරීමේදී අවශ්‍ය පරීක්ෂාවන් සිදු කරයි.
   if($old_pass == $empty_pass){
      $message[] = 'please enter old password!'; // පැරණි මුරපදය ඇතුළත් කර නොමැති නම්.
   }elseif($old_pass != $prev_pass){
      $message[] = 'old password not matched!'; // පැරණි මුරපදය දත්ත සමුදායේ ඇති මුරපදයට නොගැලපේ නම්.
   }elseif($new_pass != $cpass){
      $message[] = 'confirm password not matched!'; // අලුත් මුරපදය සහ එය තහවුරු කිරීමට ගැසූ මුරපදය නොගැලපේ නම්.
   }else{
      if($new_pass != $empty_pass){
         // 11. සියලු දත්ත නිවැරදි නම් නව මුරපදය දත්ත සමුදායේ යාවත්කාලීන කරයි.
         $update_admin_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_admin_pass->execute([$cpass, $user_id]);
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
   <title>register</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container" style="background:url('images/login.jpg');background-size: cover;padding-top: 50px;padding-bottom: 50px;">

   <!-- 12. පරිශීලක තොරතුරු යාවත්කාලීන කිරීමේ පෝරමය -->
   <form action="" method="post" style="background-color:rgba(0, 0, 0, .4);">
      <h3 class="text-white">Update Profile</h3>
      
      <!-- 13. දැනට ඇති මුරපදය සැඟවුණු (hidden) input එකක් ලෙස තබා ගනී. -->
      <input type="hidden" name="prev_pass" value="<?= $fetch_profile["password"]; ?>">
      
      <!-- 14. පරිශීලක නාමය සහ ඊමේල් වෙනස් කිරීමට (දැනට ඇති අගයන් මෙහි දිස්වේ). -->
      <input type="text" name="name" required placeholder="enter your username" maxlength="20" class="box" value="<?= $fetch_profile["name"]; ?>">
      <input type="email" name="email" required placeholder="enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $fetch_profile["email"]; ?>">
      
      <!-- 15. මුරපදය වෙනස් කිරීමට අවශ්‍ය input කොටස්. -->
      <input type="password" name="old_pass" placeholder="enter your old password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="enter your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" placeholder="confirm your new password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <input type="submit" value="update now" class="btn" name="submit">
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>