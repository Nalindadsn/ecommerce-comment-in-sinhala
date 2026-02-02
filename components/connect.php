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