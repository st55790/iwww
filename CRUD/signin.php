<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/signin.css">
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<?php
session_start();
include ("loginToDB.php");
?>
<?php
$msg = "";
if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    if($email != "" && $password != ""){
        try{
            $query = "SELECT * FROM user WHERE email=:email and password=:password";
            $stmt = $conn->prepare($query);
            $stmt->bindParam('email', $email, PDO::PARAM_STR);
            $stmt->bindParam('password', $password, PDO:: PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($count == 1 && !empty($row)){
                $_SESSION['sess_user_id'] = $row['id'];
                $_SESSION['sess_user_email'] = $row['email'];
                $_SESSION['sess_name'] = $row['name'];
                header('location:userPage.php');
            }else{
                $msg = "Invalid username and psw!";
            }
        }catch(PDOException $e){
            echo "Error: " .$e->getMessage();
        }
    }else{
        $msg= "Both field are required!";
    }
    echo $msg;
}
?>

<div class="login-page">
    <div class="form">

        <form class="login-form" method="post">
            <input type="email" name="email" placeholder="email address"/>
            <input type="password" name="password" placeholder="password"/>
            <button type="submit" name="login">login</button>
            <p class="message">Not registered? <a href="registered.php">Create an account</a></p>
        </form>

    </div>
</div>

</body>
</html>