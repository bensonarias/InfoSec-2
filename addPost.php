<?php

if(!isset($_SESSION)) {
    session_start();
}

include_once "connections/connection.php";
include "validation/validation.php";
include "errorhandler/errorhandler.php";
include "errorhandler/sql_logging.php";

$con = connection();

if(isset($_POST['addPost'])) {


    $userID = $_SESSION['ID'];
    $subject = "";
    $body = "";
    $dateAdded = date('Y-m-d H:i:s');;
    $sql = "INSERT INTO `posts` (`userID`,`subject`,`body`,`dateAdded`) VALUES ('$userID','$subject','$body', '$dateAdded')";

    $con->query($sql) or die($con->error);
    $last_id = $con->insert_id;	
    insertLog("INFO", 1, " User ID ".$_SESSION['ID']." add a new post with an ID of ".$last_id);

    
    try{
    if(isSubjectValid($_POST['postSubject']) == 1) {
        $firstName = formValidate($_POST['postSubject']);
    } else {
        echo "Error: Invalid Subject Name!";
        insertLog("User ID: ".$_SESSION['ID']." Post Subject Input Validation Error",1);
    }


<<<<<<< HEAD
    
    if(isBodytValid($_POST['postBody']) == 1) {
=======
    // Body
    if(isBodyValid($_POST['postBody']) == 1) {
>>>>>>> f4269ff8a9c04b925d100c6a135be3fb87bebfe2
        $firstName = formValidate($_POST['postBody']);
    } else {
        echo "Error: Invalid Body Content!";
        insertLog("User ID: ".$_SESSION['ID']." Post Body Input Validation Error",1);
        
    }
    }catch(customException $e){
        insertLog("ERROR", $e->errorCode(),$e->errorMessage());
    }


    echo header("Location: home.php");
}

if(isset($_POST['myPost'])) {
    $userID = $_SESSION['ID'];
    $subject = $_POST['postSubject'];
    $body = $_POST['postBody'];
    $dateAdded = date('Y-m-d H:i:s');
    $sql = "INSERT INTO `posts` (`userID`,`subject`,`body`,`dateAdded`) VALUES ('$userID','$subject','$body', '$dateAdded')";

    $con->query($sql) or die($con->error);

    $last_id = $con->insert_id;	
    insertLog("INFO", 1, " User ID ".$_SESSION['ID']." add a new user with an ID of ".$last_id);
    
    
    
    try{
    if(isSubjectValid($_POST['postSubject']) == 1) {
        $firstName = formValidate($_POST['postSubject']);
    } else {
        echo "Error: Invalid Subject Name!";
        insertLog("ERROR", "User ID: ".$_SESSION['ID']." Post Subject Input Validation Error",1);
    }


<<<<<<< HEAD
    
    if(isBodytValid($_POST['postBody']) == 1) {
=======
    // Body
    if(isBodyValid($_POST['postBody']) == 1) {
>>>>>>> f4269ff8a9c04b925d100c6a135be3fb87bebfe2
        $firstName = formValidate($_POST['postBody']);
    } else {
        echo "Error: Invalid Body Content!";
        insertLog("ERROR", "User ID: ".$_SESSION['ID']." Post Body Input Validation Error",1);
    }


    }catch(customException $e){
        insertLog("ERROR", $e->errorCode(),$e->errorMessage());
    }
    echo header("Location: myPosts.php");
}
?>