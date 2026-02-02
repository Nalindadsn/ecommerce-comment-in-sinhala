<?php

include 'components/connect.php'; // 1. දත්ත සමුදාය (Database) සමඟ සම්බන්ධතාවය ඇති කරයි.

session_start(); // 2. පරිශීලකයා හඳුනා ගැනීමට session එක ආරම්භ කරයි.

if(isset($_SESSION['user_id'])){ // 3. පරිශීලකයා ලොග් වී ඇත්නම් user_id එක ලබා ගනී.
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = ''; // 4. ලොග් වී නොමැති නම් user_id එක හිස්ව තබයි.
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- bootstrap cdn link -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   
<style type="text/css">
   .pagination li{
      margin-left: 10px; /* 5. පිටු අංක අතර පරතරය සකසයි. */
   }
</style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="heading">latest products</h1>

   <div class="box-container">

<?php 
    // --- Pagination (පිටු වෙන් කිරීමේ) තාක්ෂණය ---

    $start = 0;  
    $per_page = 1; // 6. එක් පිටුවක පෙන්විය යුතු භාණ්ඩ ප්‍රමාණය (මෙහි 1ක් ලෙස සකසා ඇත).
    $page_counter = 0;
    $next = $page_counter + 1;
    $previous = $page_counter - 1;
    
    // 7. URL එකේ 'start' අගයක් තිබේ නම් එය ලබාගෙන පිටු ගණනය කරයි.
    if(isset($_GET['start'])){
     $start = $_GET['start'];
     $page_counter =  $_GET['start'];
     $start = $start * $per_page;
     $next = $page_counter + 1;
     $previous = $page_counter - 1;
    }

    // 8. LIMIT භාවිතා කර අදාළ පිටුවට අදාළ භාණ්ඩ ප්‍රමාණය පමණක් දත්ත සමුදායෙන් ලබා ගනී.
    $q = "SELECT * FROM products LIMIT $start, $per_page";
    $query = $conn->prepare($q);
    $query->execute();

    if($query->rowCount() > 0){
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // 9. මුළු භාණ්ඩ ප්‍රමාණය ගණනය කර පිටු කීයක් අවශ්‍යදැයි බලයි.
    $count_query = "SELECT * FROM products";
    $query = $conn->prepare($count_query);
    $query->execute();
    $count = $query->rowCount();
    $paginations = ceil($count / $per_page); // 10. මුළු භාණ්ඩ ගණන බෙදා ඉතිරියක් ඇත්නම් ඊළඟ අගයට ගනී.
?>

    <?php 
        // 11. ලබාගත් භාණ්ඩ දත්ත එකින් එක ලැයිස්තුගත කරයි.
        foreach($result as $data) { 
    ?>

   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $data['id']; ?>">
      <input type="hidden" name="name" value="<?= $data['name']; ?>">
      <input type="hidden" name="price" value="<?= $data['price']; ?>">
      <input type="hidden" name="image" value="<?= $data['image_01']; ?>">
      
      <img src="uploaded_img/<?= $data['image_01']; ?>" alt="">
      
      <h3 class="name">
         <a href="quick_view.php?pid=<?= $data['id']; ?>" class="text-dark"><?= $data['name']; ?></a> 
      </h3>
      
      <div class="flex">
         <div class="price"><span>LKR </span><?= $data['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      
      <input type="submit" value="add to cart" class="btn btn-primary option-btn border-0" name="add_to_cart">
   </form>
   
    <?php 
        } 
    ?>

   </div>

   <!-- 12. පිටු අංක පෙන්වන කොටස (Pagination Controls) -->
   <div class="container text-center">
      <ul class="pagination" style="padding:50px 100px">
         <?php
            // 13. පළමු පිටුවේ සිටී නම් පිටු අංක පමණක් පෙන්වයි.
            if($page_counter == 0){
                for($x = 0; $x < $paginations; $x++) { 
                   $y= $x+1;
                   echo "<li><a class='active btn btn-primary option-btn border-0' href=?start=$x>".$y."</a></li>";
                }
            }else{
                // 14. වෙනත් පිටුවක සිටී නම් 'Previous' (පෙර) බොත්තම පෙන්වයි.
                echo "<li><a class=' btn btn-primary option-btn border-0' href=?start=$previous >Previous</a></li>"; 
                for($j=0; $j < $paginations; $j++) {
                   $y= $j+1;
                   if($j == $page_counter) {
                      echo "<li><a class='active btn btn-primary option-btn border-0' href=?start=$j >".$y."</a></li>";
                   }else{
                      echo "<li><a class=' btn btn-primary option-btn border-0' href=?start=$j >".$y."</a></li>";
                   } 
                }
                // 15. අවසන් පිටුව නොවේ නම් 'Next' (ඊළඟ) බොත්තම පෙන්වයි.
                if($page_counter + 1 < $paginations)
                   echo "<li><a class=' btn btn-primary option-btn border-0' href=?start=$next >Next</a></li>"; 
            } 
         ?>
      </ul>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>