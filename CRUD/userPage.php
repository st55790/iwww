<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/userpage.css">
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<?php
session_start();
include "loginToDB.php";
if(isset($_SESSION['sess_user_email']) && $_SESSION['sess_user_email'] != "") {
    echo '<h1>Welcome '.$_SESSION['sess_name'].'</h1>';
    echo '<button id="logoutButton" type="submit" name="logout"><a href="logout.php">logout</a></button><br>';

} else {
    header('location:signin.php');
}
?>

<?php
if(isset($_POST['update'])){

    $username = trim($_POST['username']);
    $secondname = trim($_POST['secondName']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $id = trim($_POST['id']);

    $querry = $conn->prepare("UPDATE user SET name=?, secondname=?, phone=?, email=?, password=? WHERE idUser=?");
    $stmt= $querry->execute(array(
        $username,
        $secondname,
        $phone,
        $email,
        $password,
        $id
    ));

}
?>

<?php
if(isset($_POST['delete'])){
    $id = trim($_POST['id']);

    $querry = $conn->prepare("DELETE FROM user WHERE idUser = ?");
    $stmt= $querry->execute(array(
        $id
    ));

}
?>


<?php

echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>Firstname</th><th>Lastname</th><th>Phone</th><th>Email</th><th>Password</th><th>role</th></tr>";

class TableRows extends RecursiveIteratorIterator {
    function __construct($it) {
        parent::__construct($it, self::LEAVES_ONLY);
    }

    function current() {
        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
    }

    function beginChildren() {
        echo "<tr>";
    }

    function endChildren() {
        echo "</tr>" . "\n";
    }
}

$getRole = $conn->prepare("SELECT * FROM user WHERE email=:email");
$getRole->bindParam('email', $_SESSION['sess_user_email']);
$getRole->execute();
$rowUser = $getRole->fetch(PDO::FETCH_ASSOC);

if($rowUser['role'] == 0){
    $stmt = $conn->prepare("SELECT * FROM user WHERE email=:email");
    $stmt->bindParam('email', $_SESSION['sess_user_email']);
    $stmt->execute();
}else{
    $stmt = $conn->prepare("SELECT * FROM user");
    $stmt->execute();
}


$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
}


?>

<div class="login-page">
    <div class="form">
        <form class="register-form" method="post">
            <input name="id" type="text" placeholder="id">
            <input name="username" type="text" placeholder="name">
            <input name="secondName" type="text" placeholder="second name">
            <input name="phone" type="text" placeholder="phone">
            <input name="email" type="email" placeholder="email address"/>
            <input name="password" type="password" placeholder="password"/>
            <button id="updateBtn" type="submit" name="update">UPDATE</button>
            <button type="submit" name="delete">DELETE</button>
        </form>
    </div>
</div>

</body>
</html>