<?php
require '../../config/db.php';
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $phone = $_POST['phone'];

    $sql = "SELECT * FROM users WHERE phone = '$phone'";
    $result = $conn->query($sql);
    if($result->num_rows === 1){
        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['id'];
        $_SESSION['phone'] = $row['phone'];
        header("Location: ../../dashboard.php");
        exit();
    }else{
        header("Location: ../../index.php?error=invalid_credentials");
        exit();
    }
}
?>