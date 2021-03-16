<?php
    include 'serverData.php';
    $conn = conFunc();
    $conn->query("DROP DATABASE isizindalwazi");
    initializeDB();
?>