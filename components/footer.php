<?php

// 1. දත්ත සමුදායේ නම, පෝට් අංකය (Port) සහ හෝස්ට් එක (Host) මෙහි සඳහන් කරයි.
$db_name = 'mysql:host=localhost;port=3307;dbname=ec';

// 2. දත්ත සමුදායට ඇතුළු වීමට අවශ්‍ය පරිශීලක නාමය (Username).
$user_name = 'root';

// 3. දත්ත සමුදායට ඇතුළු වීමට අවශ්‍ය මුරපදය (Password).
$user_password = '';

// 4. PDO (PHP Data Objects) තාක්ෂණය භාවිතයෙන් දත්ත සමුදාය සමඟ සබඳතාවය ගොඩනගයි.
$conn = new PDO($db_name, $user_name, $user_password);

?>

<!-- 5. වෙබ් අඩවියේ පාදක කොටස (Footer) ආරම්භය -->
<footer class="footer bg-dark">

   <!-- 6. අඳුරු පසුබිමක් සහිත Footer Section එකක් නිර්මාණය කිරීම -->
   <section class="footer" style="background-color: #333;">
      <div class="container">
         <div class="row" >
            <div class="col-md-12">
               <!-- 7. Footer එකේ අන්තර්ගතය රඳවා තබා ගන්නා ප්‍රධාන බහාලුම (Container) -->
               <div class="fotr_container" style="background-color: #333;">
                  <div class="ftr_center">
                     <div class="ftr_profile">
                        <!-- 8. වෙබ් අඩවියේ නම හෝ ලාංඡනය (Logo) පෙන්වන ස්ථානය -->
                        <a href="#" class="logo_container ftr_logo">
                            ECOMMERCE
                        </a>
                        <!-- 9. වෙබ් අඩවිය පිළිබඳ කෙටි හැඳින්වීමක් (Description) -->
                        <p class="ftr_dis">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                     </div>
                  </div>
                  
                  <!-- 10. අන්තර්ගතය වෙන් කිරීම සඳහා යොදන තිරස් රේඛාව -->
                  <hr>
               </div>
            </div>
         </div>
      </div>
   </section>

</footer>