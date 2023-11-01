<?php 
require "db.php";

$all_friends = R::findAll('friends');
$my_friends = array();

foreach($all_friends as $row){
    if($row['id_add_user'] == $_SESSION['login_user']->id){
        if($row['status'] == 1){
            $my_friends = $row;
        }
    }

    if($row['id_friend'] == $_SESSION['login_user']->id){
        if($row['status'] == 1){
            $my_friends = $row;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>friends</title>
    <link rel="stylesheet" type="text/css" href="css/new_style.css">
</head>
<body>
<?require "nav.php"?>
    <?php for($i = 0; $i < count($my_friends); $i++) :?> 
        <?php
            if($my_friends[$i]['id_add_user'] == $_SESSION['login_user']->id){
                $user = R::findOne('users', 'id = ?', array($my_friends[$i]['id_friend']));
            }
            else{
                $user = R::findOne('users', 'id = ?', array($my_friends[$i]['id_add_user']));
            }
        ?>
            <div>
            <a href = "/user?id = <?php echo $user->id;?>"><h1><?php echo htmlspecialchars($user->firstname. ' '.$user->lastname);?></h1></a>
        </div>
        <?php endfor;?>
    
</body>
</html>