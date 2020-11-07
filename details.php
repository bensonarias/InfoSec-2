<?php

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['Access']) && $_SESSION['Access'] == "admin") {
    echo header("Location: home.php");
}

include_once "connections/connection.php";
$con = connection();

$id = $_GET['ID'];


$sql = "SELECT * FROM users WHERE userID = '$id'";
$users = $con->query($sql) or die($con->error);
$row = $users->fetch_assoc();


$userPostSQL = "SELECT users.userID, users.firstName, users.lastName, users.email, posts.postID, posts.subject, posts.body, posts.dateAdded ".
            "FROM users JOIN posts ".
            "ON users.userID = posts.userID ".
            "WHERE users.userID = ".$id.
            " ORDER BY posts.dateAdded DESC";
$userPosts = $con->query($userPostSQL) or die($con->error);
$userPostRow = $userPosts->fetch_assoc();


if(isset($_SESSION['UserLogin'])) {
    echo "<div class='float-right'> Welcome <b> ".$_SESSION['UserLogin']." </b> Role: <b> ".$_SESSION['Access']."</b></div> <br>";
} else {
    echo "Welcome guest!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=" , initial-scale=1.0">
    <title>View Details</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>
    <div class="container">

        <h1 class="text-center"> CCIT Forum Admin </h1>
        <h3 class="text-center"> View Details </h3>
        <br> <br>
      

        <a id="loginBtn" class="btn btn-dark float-right" href="/ccitforum/accounts.php"> Back to User's List. </a>	
        <br><br>
        <div class="card">
            <div class="card-body" <div class="view">
                <h2> <b>First Name</b> : <?php echo $row['firstName'];?> </h2>
                <h2> <b>Last Name</b> : <?php echo $row['lastName'];?> </h2>
                <h2> <b>Email</b> : <?php echo $row['email'];?> </h2>
                <h2> <b>Access</b>: <?php echo $row['access'];?> </h2>
            </div>
           
        </div>

        <br>

       

        <?php if ($userPosts->num_rows > 0) { ?>	        
            <br>
            <h3> &nbsp;&nbsp;&nbsp;<?php echo $userPostRow['firstName'] ?>'s Posts </h3>
            <?php do { ?>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-primary"> <?php echo $userPostRow['subject'] ?></h4>
                    <small class="card-subtitle">
                        <?php echo "Posted by <b>".$userPostRow['firstName'].' '.$userPostRow['lastName'].' </b>'.' '.$userPostRow['email'].' '.$userPostRow['dateAdded']  ?>
                    </small>
                </div>
                <div class="card-body">
                    <?php echo $userPostRow['body'] ?>
                </div>
            </div> <br>
        
            <?php } while($userPostRow = $userPosts->fetch_assoc()) ?>
        <?php } else { echo "<div class='display-4'> No posts yet! </div>"; } ?>
            
    </div>
</body>
</html>