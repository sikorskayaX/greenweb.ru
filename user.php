<?php 

require "db.php";

if($_GET['id'] == ''){
    header('Location: /user?id='.$_SESSION['login_user']->id);
}



if($_GET['id'] == $_SESSION['login_user']->id){
    $position = 'access';
}
else{
    $position = 'view';
}

$user = R::findOne('users', 'id = ?', array($_GET['id']));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User page</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <h1><?php echo $user->firstname." ".$user->lastname; ?></h1>

    <?php if($position == 'view') :?>
        <div>
            <button>write message</button>
            <button>add to friends</button>
        </div>
    <?php endif; ?>

    <a href="/logout.php">Log out</a>
</body>
</html>