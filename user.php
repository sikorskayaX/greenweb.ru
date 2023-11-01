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
        $add_f->status = 0;
        R::store($add_f);
    }
}

$all_friends = R::findAll('friends');
$friend_status = 2;

foreach($all_friends as $row){
    if($row['id_add_user'] == $_SESSION['login_user']->id){
        if ($row['id_friend'] = $_GET['id']){
            if ($row['status'] == 0){
                $friend_status = 0;
            }
            if ($row['status'] == 1){
                $friend_status = 1;
            }
        }
    }
}

$add_request = 2;
$id_request = '';

foreach($all_friends as $row){
    if($row['id_friend'] == $_SESSION['login_user']->id){
        if ($row['id_add_user'] = $_GET['id']){
            if ($row['status'] == 0){
                $add_request = 0;
                $id_request = $row['id'];
            }
            if($row['status'] == 1){
                $friend_status = 1;
            }
        }
    }
}

if(isset($data['accept_request'])){
    $id_request = $data['id_request'];
    if($id_request){
        $request = R::findOne('friends', 'id = ?', array($id_request));
        $request->status = 1;
        R::store($request);
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
    
    <?php require "nav.php"?>
    
    <div class = "user">
        <h1><?php echo $user->firstname." ".$user->lastname; ?></h1>
    </div>
    <?php if($position == 'view') :?>
    <?php else: ?>
    <div>
        <form action = "user?id = <?php echo $_GET['id']; ?>" method="POST">
            <input type="text" name = "post" placeholder=" write your post" >
            <button type="submit" name = "send_post">save</button>

        </form>
    </div>
    <?php endif; ?>
    <?php for($i = 0; $i < count($user_posts); $i++) : ?>
        <div>
            <p class = "post">
                <?php echo htmlspecialchars($user_posts[$i]['post']); ?>
            </p>
        </div>
    <?php endfor; ?>

    <?php if($position == 'view') :?>
        <div >
            <button class = "centrbutton">write message</button>

            <?php if($friend_status == 2 & $add_request == 2) : ?>
            <form action = "/user?id=<?php echo $_GET['id'];?>" method="POST">
                <input type="hidden" name = "id_user" value ="<?php echo $_GET['id'];?>">
                <button type ="submit" name = "add_friend">friend request</button>
            </form>
            <?php endif; ?>

            <?php if($add_request == 0) : ?>
            <form action = "/user?id=<?php echo $_GET['id'];?>" method="POST">
                <input type="hidden" name = "id_request" value ="<?php echo $id_request;?>">
                <button type ="submit" name = "accept_request">accept</button>
            </form>
            <?php endif; ?>

            <?php if($friend_status == 0) : ?>
            <form action = "/user?id=<?php echo $_GET['id'];?>" method="POST">
                <button type ="submit" name = "add_friend">cancel a friend request</button>
            </form>
            <?php endif; ?>

            <?php if($friend_status == 1) : ?>
            <form action = "/user?id=<?php echo $_GET['id'];?>" method="POST">
                <button type ="submit" name = "add_friend">delete friend</button>
            </form>
            <?php endif; ?>

        </div>
    <?php else: ?>
        <button class = "centrbutton">edit profile</button> 
    <?php endif; ?>
    <br>
</body>
</html>