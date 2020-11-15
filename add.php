<?php

$errorMsg = "";

if(!isset($_SESSION)) {
    session_start();
}


include_once "connections/connection.php";
include "errorhandler/errorhandler.php";
include "errorhandler/sql_logging.php";
include "validation/validation.php";
$con = connection();

if(isset($_SESSION['Access']) && $_SESSION['Access'] == "admin") {
    echo "<div class='float-right'> Welcome <b> ".$_SESSION['UserLogin']." </b> Role: <b> ".$_SESSION['Access']."</b></div> <br>";
} else {
    echo header("Location: home.php");
}
$firstN = "";
$lastN = "";
$eM = "";
$cond = false;

if(isset($_POST['submit'])) {
   
    try{
    if(isFirstNameValid($_POST['firstName']) == 1) {
        $firstName = formValidate($_POST['firstName']);
    } else {
        $cond = true;
        $firstN = $_POST['firstName'];
        $lastN = $_POST['lastName'];
        $eM = $_POST['email'];
        $errorMsg="Please input all fields!";

        insertLog("ERROR",1,"First Name Input Validation Error");
    }

    
    if(isLastNameValid($_POST['lastName']) == 1) {
        $lastName = formValidate($_POST['lastName']);
    } else {
        $cond = true;
        $firstN = $_POST['firstName'];
        $lastN = $_POST['lastName'];
        $eM = $_POST['email'];
        $errorMsg="Please input all fields!";
        insertLog("ERROR",1,"Last Name Input Validation Error");
    }

    
    if(isEmailValid($_POST['email']) == 1) {
        $email = formValidate($_POST['email']);
    } else {
        $cond = true;
        $firstN = $_POST['firstName'];
        $lastN = $_POST['lastName'];
        $eM = $_POST['email'];
        $errorMsg="Please input all fields!";
        insertLog("ERROR",1,"Email Input Validation Error");
    }

    
    if(isPasswordValid($_POST['password']) == 1) {
        $password = $_POST['password'];
    } else {
        $cond = true;
        $firstN = $_POST['firstName'];
        $lastN = $_POST['lastName'];
        $eM = $_POST['email'];
        $errorMsg="Please input all fields!";
        throw new customException("Password Input Validation Error",1);
        
    }
}catch(customException $e){
    insertLog("ERROR",$e->errorCode(),$e->errorMessage());
}
    if($cond == false){
    $hash = password_hash($password, PASSWORD_BCRYPT);
    session_regenerate_id(true);
    $access = $_POST['access'];
    $sql = "INSERT INTO `users` (`firstName`,`lastName`,`email`,`password`,`access`) VALUES ('$firstName','$lastName','$email','$hash', '$access')";
    
    $con->query($sql) or die($con->error);

    $last_id = $con->insert_id;	
    insertLog("INFO", 1, " User ID ".$_SESSION['ID']." add a new user with an ID of ".$last_id);

   echo header("Location: accounts.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User </title>
    <link rel="stylesheet" href="css/addStyle.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>

    <div class="container">

        <div class="register">
           <h1 class="text-center"> <b>CCIT Forum Admin</b> </h1>
            <h3 class="text-center">Add New User </h1>
            <a id="loginBtn" class="btn btn-dark float-right" href="/ccitforum/"> <b>Back to User's List </b></a><br><br>
            <div class="card">
                <div class="card-body">
                    <form action="" method="post" onSubmit="return confirm('Do you really want to add this user?')">
                    <?php if($errorMsg != "") echo "<p> <font color=red  font face='poppins' size='2pt'>$errorMsg</font> </p>" . "<br>"; ?>

                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="name" class="form-control" value="<?php echo $firstN?>" name="firstName">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="name" class="form-control" value="<?php echo $lastN?>" name="lastName">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" value="<?php echo $eM?>" name="email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="pass" type="password" class="form-control" name="password"> <input id = "passW" type="checkbox" onclick="unhidePassword()" > Show Password </input>
                        </div>
                        <div class="form-group">
                                <label for="password">Access</label>
                                <select name="access" class="form-control">
                                    <option value="user" selected>User</option>
                                    <option value="admin">Admin</option>

                                </select>
                            </div>

                        <input type="submit" name="submit" class="btn btn-success float-right" value="Add New User"></input>
                    </form>
                </div>
            </div>
        </div>

    </div>


    <script src="js/jquery/jquery.min.js"></script>

    
    <script>
        function unhidePassword() {
            var x = document.getElementById("pass");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>

</html>