<?php

include '../components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිපාලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

$admin_id = $_SESSION['admin_id']; // 3. ලොග් වී සිටින පරිපාලකයාගේ ID එක ලබා ගනී.

if(!isset($admin_id)){ // 4. පරිපාලකයා ලොග් වී නොමැති නම් ඔහුව login පිටුවට යොමු කරයි.
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){ // 5. 'add product' බොත්තම එබූ විට නව භාණ්ඩයක් ඇතුළත් කිරීම ආරම්භ කරයි.

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); // නම පිරිසිදු කරයි.
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING); // මිල පිරිසිදු කරයි.
   $details = $_POST['details'];
   $category_id = $_POST['category_id']; // තෝරාගත් ප්‍රවර්ගයේ (Category) ID එක.
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   // 6. පින්තූර 03ක් සඳහා දත්ත ලබා ගැනීම සහ පිරිසිදු කිරීම.
   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   // 7. එම නමින්ම භාණ්ඩයක් දැනටමත් තිබේදැයි පරීක්ෂා කරයි.
   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exist!';
   }else{
      // 8. භාණ්ඩයේ විස්තර දත්ත සමුදායට ඇතුළත් කිරීම.
      $insert_products = $conn->prepare("INSERT INTO `products`(name, details, category_id, price, image_01, image_02, image_03) VALUES(?,?,?,?,?,?,?)");
      $insert_products->execute([$name, $details, $category_id, $price, $image_01, $image_02, $image_03]);

      if($insert_products){
         // 9. පින්තූරවල ප්‍රමාණය 2MB ට වඩා අඩු දැයි පරීක්ෂා කර ෆෝල්ඩරයට උඩුගත (Upload) කරයි.
         if($image_size_01 > 2000000 OR $image_size_02 > 2000000 OR $image_size_03 > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'new product added!';
         }
      }
   }  
};

if(isset($_GET['delete'])){ // 10. භාණ්ඩයක් ඉවත් කිරීමේ (Delete) ඉල්ලීමක් ලැබුණු විට.

   $delete_id = $_GET['delete'];
   // 11. භාණ්ඩයට අදාළ පින්තූර ෆෝල්ඩරයෙන් ඉවත් (Delete files) කරයි.
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_03']);

   // 12. භාණ්ඩය දත්ත සමුදායෙන් මකා දමන අතර කරත්තයෙන් (Cart) සහ පැතුම් ලැයිස්තුවෙන්ද (Wishlist) ඉවත් කරයි.
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:products.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="../css/admin_style.css">
   <style type="text/css">
      .pagination li{ margin-left: 10px; }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- 13. නව භාණ්ඩයක් එක් කිරීමේ පෝරමය (Add Product Form) -->
<section class="add-products container">
   <h1 class="heading">add product</h1>
   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>product name (required)</span>
            <input type="text" class="form-control" required maxlength="100" placeholder="enter product name" name="name">
         </div>
         <div class="inputBox">
            <span>product price (required)</span>
            <input type="number" min="0" class="form-control" required max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>
         <div class="inputBox">
            <span>image 01 (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
         </div>
         <div class="inputBox">
            <span>image 02 (required)</span>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
         </div>
         <div class="inputBox">
            <span>image 03 (required)</span>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control" required>
         </div>
         <div class="inputBox">
            <span>product categories (required)</span>
            <select name="category_id" class="form-control" style="width:100%">
               <?php
                  // 14. පවතින ප්‍රවර්ග (Categories) ලැයිස්තුව Dropdown එකට ලබා ගැනීම.
                  $select_cats = $conn->prepare("SELECT * FROM `categories`");
                  $select_cats->execute();
                  if($select_cats->rowCount() > 0){
                     while($fetch_cats = $select_cats->fetch(PDO::FETCH_ASSOC)){ 
               ?>
               <option value="<?php echo $fetch_cats['id'] ?>"><?php echo $fetch_cats['name'] ?></option>
               <?php
                     }
                  }else{
                     echo '<option disabled>no categories added yet!</option>';
                  }
               ?>
            </select>
         </div>
         <div class="inputBox">
            <span>product details (required)</span>
            <textarea name="details" placeholder="enter product details" class="form-control" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      <input type="submit" value="add product" class="option-btn" name="add_product">
   </form>
</section>

<!-- 15. ඇතුළත් කර ඇති භාණ්ඩ ප්‍රදර්ශනය කිරීම සහ පිටු වෙන් කිරීම (Pagination) -->
<section class="show-products">
   <h1 class="heading">products added</h1>
   <div class="box-container">

<?php 
    // 16. Pagination සැකසුම්: එක පිටුවක භාණ්ඩ 9ක් පෙන්වීමට සකසා ඇත.
    $start = 0; $per_page = 9;
    $page_counter = 0;
    
    if(isset($_GET['start'])){
       $page_counter = $_GET['start'];
       $start = $page_counter * $per_page;
    }

    // 17. LIMIT භාවිතා කර අදාළ පිටුවට අවශ්‍ය භාණ්ඩ පමණක් ලබා ගනී.
    $q = "SELECT * FROM products LIMIT $start, $per_page";
    $query = $conn->prepare($q);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    // 18. මුළු භාණ්ඩ ගණන අනුව අවශ්‍ය වන පිටු ගණන (Paginations) ගණනය කරයි.
    $count_query = "SELECT * FROM products";
    $query = $conn->prepare($count_query);
    $query->execute();
    $count = $query->rowCount();
    $paginations = ceil($count / $per_page);

    if($count > 0){
       foreach($result as $data) { 
?>
   <!-- 19. එක් එක් භාණ්ඩය Card එකක් ලෙස පෙන්වීම -->
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $data['id']; ?>">
      <img src="../uploaded_img/<?= $data['image_01']; ?>" alt="">
      <h3 class="name"><a href="quick_view.php?pid=<?= $data['id']; ?>" class="text-dark"><?= $data['name']; ?></a> </h3>
      <a href="products.php?delete=<?= $data['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
   </form>
<?php
       }
    }else{
       echo '<p class="empty">no products added yet!</p>';
    }
?>
   </div>

   <!-- 20. Pagination Navigation: පිටු අතර මාරු වීමට අවශ්‍ය බොත්තම් -->
   <div class="container text-center">
      <ul class="pagination" style="padding:50px 100px">
         <?php
            $next = $page_counter + 1;
            $previous = $page_counter - 1;

            if($page_counter > 0){
               echo "<li><a class='btn btn-primary option-btn border-0' href=?start=$previous>Previous</a></li>";
            }
            
            for($j=0; $j < $paginations; $j++) {
               $active = ($j == $page_counter) ? 'active' : '';
               echo "<li><a class='$active btn btn-primary option-btn border-0' href=?start=$j>$j</a></li>";
            }

            if($next < $paginations){
               echo "<li><a class='btn btn-primary option-btn border-0' href=?start=$next>Next</a></li>";
            }
         ?>
      </ul>
   </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>