<?php

include '../components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිපාලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

$admin_id = $_SESSION['admin_id']; // 3. ලොග් වී සිටින පරිපාලකයාගේ ID එක ලබා ගනී.

if(!isset($admin_id)){ // 4. පරිපාලකයා ලොග් වී නොමැති නම් ඔහුව login පිටුවට යොමු කරයි.
header('location:admin_login.php');
};

if(isset($_POST['add_category'])){ // 5. 'add category' බොත්තම එබූ විට ක්‍රියාවලිය ආරම්භ කරයි.

$name = $_POST['name']; // 6. ප්‍රවර්ගයේ නම ලබාගෙන පිරිසිදු කරයි (Sanitize).
$name = filter_var($name, FILTER_SANITIZE_STRING);

// 7. උඩුගත කරන රූපයේ (Image) විස්තර ලබා ගනී.
$image_01 = $_FILES['image_01']['name'];
$image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
$image_size_01 = $_FILES['image_01']['size'];
$image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
$image_folder_01 = '../uploaded_cat/'.$image_01; // රූපය ගබඩා කරන ෆෝල්ඩරය.

// 8. එම නමින්ම ප්‍රවර්ගයක් දැනටමත් තිබේදැයි පරීක්ෂා කරයි.
$select_categories = $conn->prepare("SELECT * FROM categories WHERE name = ?");
$select_categories->execute([$name]);

if($select_categories->rowCount() > 0){
$message[] = 'category name already exist!';
}else{
// 9. ප්‍රවර්ගය දත්ත සමුදායට ඇතුළත් කරයි.
$insert_categories = $conn->prepare("INSERT INTO categories(name, file) VALUES(?,?)");
$insert_categories->execute([$name, $image_01]);

  if($insert_categories){
     // 10. රූපයේ ප්‍රමාණය 2MB ට වඩා වැඩි දැයි පරීක්ෂා කරයි.
     if($image_size_01 &gt; 2000000 ){
        $message[] = &#39;image size is too large!&#39;;
     }else{
        // 11. රූපය නියමිත ෆෝල්ඩරයට උඩුගත කරයි.
        move_uploaded_file($image_tmp_name_01, $image_folder_01);
        $message[] = &#39;new category added!&#39;;
     }
  }


}

};

if(isset($_GET['delete'])){ // 12. ප්‍රවර්ගයක් ඉවත් කිරීමට (Delete) ඉල්ලීමක් ලැබුණු විට.

$delete_id = $_GET['delete'];
// 13. ප්‍රවර්ගයට අදාළ රූපය ලබාගෙන එය ෆෝල්ඩරයෙන් ඉවත් (Unlink) කරයි.
$delete_category_image = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$delete_category_image->execute([$delete_id]);
$fetch_delete_image = $delete_category_image->fetch(PDO::FETCH_ASSOC);
unlink('../uploaded_cat/'.$fetch_delete_image['file']);

// 14. ප්‍රවර්ගය දත්ත සමුදායෙන් ඉවත් කරයි.
$delete_category = $conn->prepare("DELETE FROM categories WHERE id = ?");
$delete_category->execute([$delete_id]);

// 15. එම ප්‍රවර්ගයට අදාළ කරත්ත (Cart) සහ පැතුම් ලැයිස්තු (Wishlist) දත්තද ඉවත් කරයි.
$delete_cart = $conn->prepare("DELETE FROM cart WHERE pid = ?");
$delete_cart->execute([$delete_id]);
$delete_wishlist = $conn->prepare("DELETE FROM wishlist WHERE pid = ?");
$delete_wishlist->execute([$delete_id]);

header('location:categories.php');
}

?>

<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>categories</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- 16. නව ප්‍රවර්ග එකතු කිරීමේ පෝරමය -->

<section class="add-categories container">
<h1 class="heading">add category</h1>
<form action="" method="post" enctype="multipart/form-data">
<div class="flex">
<div class="inputBox">
<label>category name (required)</label>
<input type="text" class="form-control" required maxlength="100" placeholder="enter category name" name="name">
</div>
<div class="inputBox">
<span>image 01 (required)</span>
<input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
</div>
</div>
<input type="submit" value="add category" class="btn" name="add_category">
</form>
</section>

<!-- 17. දැනට ඇති ප්‍රවර්ග ප්‍රදර්ශනය කරන කොටස -->

<section class="show-categories container">
<h1 class="heading">categories added</h1>
<div class="box-container">
<div class="row">
<?php
$select_categories = $conn->prepare("SELECT * FROM categories");
$select_categories->execute();
if($select_categories->rowCount() > 0){
while($fetch_categories = $select_categories->fetch(PDO::FETCH_ASSOC)){
?>
<div class="col-md-4">
<div class="card mb-3">
<!-- 18. ප්‍රවර්ගයේ රූපය සහ නම පෙන්වීම -->
<img class="card-img-top" src="../uploaded_cat/<?php echo $fetch_categories['file']; ?>" height="380" alt="Category image">
<div class="card-body text-center">
<h5 class="card-title"><?= $fetch_categories['name']; ?></h5>
<!-- 19. මකා දැමීමේ බොත්තම සහ තහවුරු කිරීමේ පණිවිඩය -->
<a href="categories.php?delete=<?= $fetch_categories['id']; ?>" class="delete-btn" onclick="return confirm('delete this category?');">delete</a>
</div>
</div>
</div>
<?php
}
}else{
echo '<p class="empty">no categories added yet!</p>';
}
?>
</div>
</div>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>