<?php

include '../components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිපාලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

$admin_id = $_SESSION['admin_id']; // 3. ලොග් වී සිටින පරිපාලකයාගේ ID එක ලබා ගනී.

if(!isset($admin_id)){ // 4. පරිපාලකයා ලොග් වී නොමැති නම් ඔහුව login පිටුවට යොමු කරයි.
header('location:admin_login.php');
};

if(isset($_GET['delete'])){ // 5. පණිවිඩයක් ඉවත් කිරීමට (Delete) ඉල්ලීමක් URL එක හරහා ලැබුණු විට.
$delete_id = $_GET['delete'];
// 6. අදාළ ID එක සහිත පණිවිඩය දත්ත සමුදායෙන් ඉවත් කරයි.
$delete_message = $conn->prepare("DELETE FROM messages WHERE id = ?");
$delete_message->execute([$delete_id]);
header('location:messages.php'); // 7. ඉවත් කිරීමෙන් පසු පිටුව නැවත යාවත්කාලීන කරයි.
}

?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>messages</title>

<!-- font awesome cdn link  -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<!-- bootstrap cdn link -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<!-- custom admin css file link -->
<link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?> <!-- 8. පරිපාලක හෙඩර් කොටස ඇතුළත් කරයි. -->

<section>

<h1 class="heading">MESSAGES</h1>

<div class="container">
<!-- 9. පණිවිඩ විස්තර පෙන්වන වගුව (Table) ආරම්භය -->
<table class="table" style="width: 100%;">
<tr>
<th>user id</th>
<th>Name</th>
<th>Email</th>
<th>Mobile Number</th>
<th>Message</th>
<th>Action</th>
</tr>

<?php
// 10. දත්ත සමුදායේ ඇති සියලුම පණිවිඩ ලබා ගනී.
$select_messages = $conn->prepare("SELECT * FROM messages");
$select_messages->execute();
if($select_messages->rowCount() > 0){
while($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)){
?>

<tr>
<!-- 11. එක් එක් පණිවිඩයේ විස්තර වගුවේ පේළි ලෙස පෙන්වීම -->
<td><?= $fetch_message['user_id']; ?></td>
<td><?= $fetch_message['name']; ?></td>
<td><?= $fetch_message['email']; ?></td>
<td><?= $fetch_message['number']; ?></td>
<td><?= $fetch_message['message']; ?></td>
<td>
<!-- 12. පණිවිඩය මැකීමට පෙර තහවුරු කිරීමේ (Confirm) පණිවිඩයක් පෙන්වයි. -->
<a href="messages.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="btn btn-sm btn-primary">delete</a>
</td>
</tr>
<?php
}
}else{
// 13. පණිවිඩ කිසිවක් නොමැති නම් පෙන්වන පණිවිඩය.
echo '<tr><td colspan="6" class="empty">you have no messages</td></tr>';
}
?>
</table>

</div>

</section>

<!-- 14. පරිපාලක JS ගොනුව සම්බන්ධ කරයි. -->

<script src="../js/admin_script.js"></script>

</body>
</html>