<?php

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Access']) && $_SESSION['Access'] == "admin") {
    echo header("Location: home.php");
}

include_once "connections/connection.php";

$con = connection();

if(isset($_POST['deleteUser'])) {
    $id = $_POST['ID'];
    $sql = "DELETE FROM users WHERE userID = '$id'";
    $con->query($sql) or die ($con->error);
    insertLog("WARNING", 1, " User ID ".$_SESSION['ID']." deleted an account with an ID of ".$id);

    echo header("Location: accounts.php");
}

if(isset($_POST['deletePost'])) {
    $id = $_POST['ID'];
    $sql = "DELETE FROM posts WHERE postID = '$id'";
    $con->query($sql) or die ($con->error);
    insertLog("WARNING", 1, " User ID ".$_SESSION['ID']." deleted a post with an ID of ".$id);
    echo header("Location: myPosts.php");
}
?>
