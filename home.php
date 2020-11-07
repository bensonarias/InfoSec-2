<?php

if(!isset($_SESSION)) {
    session_start();
}

include_once "connections/connection.php";

$con = connection();
$id = $_SESSION['ID'];
$postSQL =  "SELECT users.userID, users.firstName, users.lastName, users.email, posts.postID, posts.subject, posts.body, posts.dateAdded ".
            "FROM users JOIN posts ".
            "ON users.userID = posts.userID ".
            "ORDER BY posts.dateAdded DESC"; 
$posts = $con->query($postSQL) or die($con->error);
$postRow = $posts->fetch_assoc();

if(!isset($_SESSION['UserLogin'])) {
    echo header("Location: login.php");
}

if(isset($_SESSION['UserLogin'])) {
    echo "<div class='float-right'> Welcome <b> ".$_SESSION['UserLogin']." </b> | Role: <b> ".$_SESSION['Access']."</b></div> <br>";
} else {
    echo "Welcome guest!";
}

?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <title> CCIT Forum </title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>

    <body>

        <div class="container">

            <h1 class="text-center"> The CCIT Wall </h1>
            <h3 class="text-center"> Homepage </h3>

           
            <h1>&nbsp; News Feed </h1>
            <small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  View the latest post.</small>
            <div class="btn-group float-right font-weight-bold" role="group" aria-label="Basic example">
                <a class="btn btn-primary float-left font-weight-bold" href="/ccitforum/home.php"> News Feed </a>&nbsp;
                <a class="btn btn-secondary float-left font-weight-bold" href="/ccitforum/myPosts.php"> My Posts </a>&nbsp;
                <a class="btn btn-success float-left font-weight-bold" href="/ccitforum/accounts.php"> Accounts </a>&nbsp;
                <a class="btn btn-danger float-left font-weight-bold" href="/ccitforum/logout.php"> Logout </a>
            </div>
            <br>
            <br>
            <hr>

            
            <h3>&nbsp;&nbsp; Compose Post </h3>
            <div class="card">
                <div class="card-body">
                    <form action="addPost.php" method="post" onSubmit="return alert('Your post was posted!')" accept-charset="utf-8">
                        <div class="form-group">
                            <label for="name">&nbsp;&nbsp;Topic </label>
                            <input type="text" class="form-control" name="postSubject" required>
                        </div>
                        <div class="form-group">
                            <label for="address">&nbsp;&nbsp;Body</label>
                            <textarea class="form-control" id="addressTA" rows="3" name="postBody" required"
                                style="resize:none;"></textarea>
                        </div>
                        <input type="submit" class="btn btn-primary float-right" value="Post" name="addPost"/>
                    </form>
                </div>
            </div>

            <br>


            
            <h3> &nbsp;&nbsp;News Feed </h3>
            <?php if($posts->num_rows > 0) { ?>
            <h3>&nbsp;&nbsp; Recent Posts </h3>
            <?php do { ?>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-primary"> <?php echo $postRow['subject'] ?></h4>
                    <small class="card-subtitle">
                        <?php echo "Posted by <b>".$postRow['firstName'].' '.$postRow['lastName'].' </b> | '.'  '.$postRow['dateAdded']  ?>
                    </small>
                </div>
                <div class="card-body">
                    <?php echo $postRow['body'] ?>
                </div>
            </div> <br>
            <?php } while($postRow = $posts->fetch_assoc()) ?>
            <?php } else { echo "<div class='display-4'> No posts yet! </div>"; } ?>
    
    </body>
<html>