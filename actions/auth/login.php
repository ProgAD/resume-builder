<?php
require '../../config/db.php';
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $phone = $_POST['phone'];

    $sql = "SELECT * FROM users WHERE phone = '$phone'";
    $result = $conn->query($sql);
    if($result->num_rows === 1){
        // update last login2
        $sql2 = "UPDATE users SET last_login = NOW() WHERE phone = '$phone'";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $stmt2->close();
        

        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['id'];
        $_SESSION['phone'] = $row['phone'];
        header("Location: ../../dashboard.php");
    }else{
        // create new user
        $sql = "INSERT INTO users (phone, last_login) VALUES (?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $_SESSION['id'] = $stmt->insert_id;
        $_SESSION['phone'] = $phone;
        header("Location: ../../dashboard.php");
    }
    $conn->close();
}else{
    header("Location: ../../login.html?error=invalid_method");
    exit();
}
?>