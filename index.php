<?php 

require "db.php";

$data = $_POST;

if(isset($data["signup"])) { //sign up user
    $error = array();
    
    if(trim($data["firstname"]) == ""){
        $error[] = "Empty firstname";
    }
    if(trim($data["lastname"]) == ""){
        $error[] = "Empty lastname";
    }
    if(trim($data["login"]) == ""){
        $error[] = "Empty login";
    }
    if(trim($data["password"]) == ""){
        $error[] = "Empty password";
    }
    if(trim($data["password_2"]) == ""){
        $error[] = "Confirm password";
    }

    if(R::count('users', 'login = ?', array($data['login'])) > 0){
        $error[] = "user already registred";
    }
    if(trim($data["password"]) != trim($data["password_2"])){
        $error[] = "wrong password";
    }

    if(empty($error)){
        //sign up user
        $user = R::dispense('users');
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->login = $data['login'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);

        $user->ip = $_SERVER['REMOTE_ADDR'];
        $user->d_date_reg = date('d');
        $user->m_date_reg = date('m');
        $user->y_date_reg = date('Y');
        $user->h_time_reg = date('H');
        $user->m_time_reg = date('i');

        R::store($user);
    }
    else{
        echo "<div>".array_shift($error)."</div>";
    }

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenWeb</title>
</head>
<body>
    hello
    <form action="/" method="POST">
        <input type = "text" name = "firstname" placeholder="Firstname"> <br>
        <input type = "text" name = "lastname" placeholder="Lastname"><br>
        <input type = "text" name = "login" placeholder="Login"><br>
        <input type = "password" name = "password" placeholder="Password"><br>
        <input type = "password" name = "password_2" placeholder="Confirm"><br>
        <button type = "submit" name = "signup">Sign up</button> 


    </form> 
</body>
</html>