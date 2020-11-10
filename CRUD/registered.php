<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/signin.css">
    <link rel="stylesheet" href="css/registered.css">
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php
include "loginToDB.php";



if(isset($_POST['insert'])){
    try {
    $username = $_POST["username"];
    $secondName = $_POST["secondName"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $emailCheck = "SELECT COUNT(*) AS num FROM user WHERE email = :email";
    $stmt = $conn->prepare($emailCheck);
    $stmt->bindValue(':email', $email);
    $stmt->execute();
    $rowEmail = $stmt->fetch(PDO::FETCH_ASSOC);

    $phoneCheck = "SELECT COUNT(*) AS num FROM user WHERE phone = :phone";
    $stmt = $conn->prepare($phoneCheck);
    $stmt->bindValue(':phone', $phone);
    $stmt->execute();
    $rowPhone = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "INSERT INTO user (name , secondname, phone, email, password, role) VALUES ('$username', '$secondName', '$phone', '$email', '$password', 0)";
    if($rowEmail['num'] > 0 || $rowPhone['num'] > 0){
        echo 'Check your email and phone, we have in database this data!';
    } else{
        $conn->exec($sql);
    }

    }catch (PDOException $e){
        echo "Some error: " . $e->getMessage();

    }
}
$conn = null;
?>


<div class="login-page">
    <div class="form">
        <form class="register-form" method="post">
            <input name="username" type="text" placeholder="name">
            <input name="secondName" type="text" placeholder="second name">
            <input name="phone" type="text" placeholder="phone">
            <input name="email" type="email" placeholder="email address"/>
            <input name="password" type="password" placeholder="password"/>
            <button type="submit" name="insert">create</button>
            <p class="message">Already registered? <a href="signin.php">Sign In</a></p>
        </form>
    </div>
</div>


</body>
</html>