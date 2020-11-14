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
        throw new customException("User ID: ".$_SESSION['ID']." Post Subject Input Validation Error",1);
    }


    // Body
    if(isBodyValid($_POST['postBody']) == 1) {
        $firstName = formValidate($_POST['postBody']);
    } else {
        echo "Error: Invalid Body Content!";
        throw new customException("User ID: ".$_SESSION['ID']." Post Body Input Validation Error",1);
        
    }
    $userID = $_SESSION['ID'];
    $subject = $_POST['postSubject'];
    $body = $_POST['postBody'];
    $dateAdded = date('Y-m-d H:i:s');
    $sql = "INSERT INTO `posts` (`userID`,`subject`,`body`,`dateAdded`) VALUES ('$userID','$subject','$body', '$dateAdded')";

    $con->query($sql) or die($con->error);

    $last_id = $con->insert_id;	
    insertLog("INFO", 1, " User ID ".$_SESSION['ID']." add a new user with an ID of ".$last_id);
<<<<<<< HEAD
    
    
    
=======
    }catch(customException $e){
        insertLog("ERROR", $e->errorCode(),$e->errorMessage());
    }
    echo header("Location: home.php");

  
}

if(isset($_POST['myPost'])) {

    //Subject
>>>>>>> 6d241e409b6f0af4e025f392fc09d0191e50f9f1
    try{
    if(isSubjectValid($_POST['postSubject']) == 1) {
        $firstName = formValidate($_POST['postSubject']);
    } else {
        echo "Error: Invalid Subject Name!";
        throw new customException("User ID: ".$_SESSION['ID']." Post Subject Input Validation Error",1);
    }


    if(isBodyValid($_POST['postBody']) == 1) {
        $firstName = formValidate($_POST['postBody']);
    } else {
        echo "Error: Invalid Body Content!";
        throw new customException("User ID: ".$_SESSION['ID']." Post Body Input Validation Error",1);
    }
    $userID = $_SESSION['ID'];
    $subject = $_POST['postSubject'];
    $body = $_POST['postBody'];
    $dateAdded = date('Y-m-d H:i:s');
    $sql = "INSERT INTO `posts` (`userID`,`subject`,`body`,`dateAdded`) VALUES ('$userID','$subject','$body', '$dateAdded')";

    $con->query($sql) or die($con->error);

    $last_id = $con->insert_id;	
    insertLog("INFO", 1, " User ID ".$_SESSION['ID']." add a new user with an ID of ".$last_id);
    }catch(customException $e){
        insertLog("ERROR", $e->errorCode(),$e->errorMessage());
    }
    echo header("Location: myPosts.php");
}
?>