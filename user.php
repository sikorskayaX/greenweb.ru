<?php 

require "db.php";

$data = $_POST;

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


if(isset($data["send_post"])) { //sign up user
    $post = $data['post'];
    if($post){
        $db_post = R::dispense('posts');
        $db_post->id_user = $_SESSION['login_user']->id; 
        $db_post->post = $post;
        $db_post->ip = $_SERVER['REMOTE_ADDR'];
        $db_post->d_date_post = date('d');
        $db_post->m_date_post = date('m');
        $db_post->y_date_post = date('Y');
        $db_post->h_time_post = date('H');
        $db_post->m_time_post = date('i');

    }
    R::store($db_post);
}

$all_post = R::findAll('posts');
$user_posts = array();

foreach($all_post as $row){
    if($row['id_user'] == $_GET['id']){
        $user_posts[] = $row;
    }
}

if(isset($data['add_friend'])){
    $id_user = $data["id_user"];
    if ($id_user){
        $add_f = R::dispense('friends');
        $add_f->id_add_user = $_SESSION['login_user']->id;
        $add_f->id_friend = $id_user;
        R::store($add_f);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>green web - <?php echo $user->firstname." ".$user->lastname; ?></title>
    <link rel="stylesheet" type="text/css" href="css/new_style.css">
</head>
<body>
    <h1><?php echo $user->firstname." ".$user->lastname; ?></h1>
    <div>
        <form action = "user?id = <?php echo $_GET['id']; ?>" method="POST">
            <input type="text" name = "post" placeholder=" write your post" >
            <button type="submit" name = "send_post">save</button>

        </form>
    </div>

    <?php for($i = 0; $i < count($user_posts); $i++) : ?>
        <div class = "post">
            <p>
                <?php echo htmlspecialchars($user_posts[$i]['post']); ?>
            </p>
        </div>
    <?php endfor; ?>

    <?php if($position == 'view') :?>
        <div >
            <button>write message</button>
            <form action = "/user?id=<?php echo $_GET['id'];?>" method="POST">
                <input type="hidden" name = "id_user" value ="<?php echo $_GET['id'];?>">
                <button type ="submit" name = "add_friend">add to friends</button>
            </form>
        </div>
    <?php else: ?>
        <button>edit profile</button> 
    <?php endif; ?>
    <br>
    <?php require "nav.php"?>
</body>
</html>