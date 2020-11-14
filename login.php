<?php
    
 $loginErrorMsg = "";

if(!isset($_SESSION)) {
    session_start();
}

include_once "connections/connection.php";
include "validation/validation.php";
include "errorhandler/errorhandler.php";
include "errorhandler/sql_logging.php";
$con = connection();



try{
if(isset($_POST['login'])) {
   
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(empty($email)||empty($password)){
        $loginErrorMsg="Fill all the Fields";
        throw new customException("EmptyField",1);
          }
    $sql = "SELECT * FROM users WHERE email = '$email'";

    $user = $con->query($sql) or die ($con->error);
    $row = $user -> fetch_assoc();
    $total = $user->num_rows;
   
    if ($total > 0) {

        $db_password = $row['password'];

        if(password_verify($password, $db_password)) {
            session_destroy();
            session_start();
            session_regenerate_id(true); 
            $_SESSION['UserLogin'] = $row['email'];
            $_SESSION['Access'] = $row['access'];
            $_SESSION['ID'] = $row['userID'];
            echo header("Location: home.php");    
        
            
         insertLog("Success", 0, "Successful login");
        } else{
            $loginErrorMsg="Invalid username and/or password! Please try again!";
            throw new customException("Invalid Input Credentials During Login",1); 
        }
    } else {
        $loginErrorMsg="Invalid username and/or password! Please try again!";
        throw new customException("Invalid Input Credentials During Login",1); 
    }
    $con->close();
}
}catch(customException $e){
    
    insertLog("ERROR", $e->errorCode(), $e->errorMessage());
}




try{
if(isset($_POST['register'])) {
    
    $firstName = "";
    $lastName = "";
    $email = "";
    $password = '';

    
    if(isFirstNameValid($_POST['firstName']) == 1) {
        $firstName = formValidate($_POST['firstName']);
    } else {
        echo "Error: Invalid First Name!";
        throw new customException("First Name Input Validation Error",1);
    }

     
    if(isLastNameValid($_POST['lastName']) == 1) {
        $lastName = formValidate($_POST['lastName']);
    } else {
        echo "Error: Invalid Last Name!";
        throw new customException("Last Name Input Validation Error",1);
    }

    
    if(isEmailValid($_POST['email']) == 1) {
        $email = formValidate($_POST['email']);
    } else {
        echo "Error: Invalid Email!";
        throw new customException("Email Input Validation Error",1);
    }

    
    if(isPasswordValid($_POST['password']) == 1) {
        $password = $_POST['password'];
    } else {
        throw new customException("Password Input Validation Error",1);
    }

    
    if($password != ""){
        $hash = password_hash($password, PASSWORD_BCRYPT);
    }
    
    
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $user = $con->query($sql) or die ($con->error);
    $row = $user -> fetch_assoc();
    $total = $user->num_rows;

    if($total > 0) {
        throw new customException("Duplicate Email");
    } else {
        $insertSql = "INSERT INTO `users` (`firstName`,`lastName`, `email`,`password`,`access`) VALUES ('$firstName', '$lastName', '$email','$hash','user')";
   
              
        if($firstName == "" || $lastName == "" || $email == "" || $password = "") {	
            throw new customException("Error: Invalid Input!");	
        } else {	
            $con->query($insertSql) or die($con->error);	
            $last_id = $con->insert_id;	
        }

        session_destroy();
        session_start();
        session_regenerate_id(true); 
        $_SESSION['UserLogin'] = $email;
        $_SESSION['Access'] = "user";
        $_SESSION['ID'] = $last_id;
        echo header("Location: home.php");  

             
        insertLog("INFO", 1, " User ID ".$last_id." Register Successful");
        insertLog("INFO", 1, " User ID ".$_SESSION['ID']." successfully login to the system");
    }

    $con->close();
}
}catch(customException $e){
 
    insertLog("ERROR",$e->errorCode(), $e->errorMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <div class="col-6 home-left">
                    <h1 class="brand-title text-center text-white"> <b>The CCIT Forum.</b></h1>
                
                <div class="brand-list text-white">
                    <ul>
                        <li>Share your thoughts!</li>
                        <li>Communicate with other CCIT students!</li>
                        <li>Be as one!</li>
                    </ul>
                </div>

                <!-- <div class="brand-subtitle">
                    <h4>"Insert Subtitle Here"</h3>
                </div> -->

                
            </div>

            <div class="col-6 home-right">
                
                <div class="row">

                    
                    <div class="login">
                        <h5 class="text-muted text-center">National University - Manila</h5>
                        <p class="text-muted">College of Computing and Information Technologies</p>
                        <h1 class="text-center">Sign In.</h1>
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" autocomplete="on" class="form-control" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input id="pass" type="password" autocomplete="off" class="form-control"
                                            name="password"> <input id = "passW" type="checkbox" onclick="unhidePassword()"> Show
                                        Password </input>
                                    </div>

                                    <input type="submit" name="login" class="btn btn-primary float-right"
                                        value="Sign In"></input>
                                </form>
                                
                                

                                <?php if($loginErrorMsg != "") echo "<p> <font color=red  font face='poppins' size='2pt'>$loginErrorMsg</font> </p>" . "<br>"; ?>
                                
                                <p> Not yet a member? <button id="registerBtn" class="btn btn-link"> Sign Up Now!
                                    </button></p>
                            </div>
                        </div>
                    </div>

                    
                    <div class="register">
                        <h5 class="text-muted text-center">National University - Manila</h5>
                        <p class="text-muted">College of Computing and Information Technologies</p>
                        <h1 class="text-center">Sign Up.</h1>
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label for="firstName">First Name</label>
                                        <input type="name" class="form-control" name="firstName">
                                    </div>
                                    <div class="form-group">
                                        <label for="lastName">Last Name</label>
                                        <input type="name" class="form-control" name="lastName">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" autocomplete="off" class="form-control" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input id="pass2" type="password" autocomplete="off" class="form-control"
                                            name="password"> <input id="passW" type="checkbox" onclick="unhidePassword()"> Show
                                        Password </input>
                                        <small id="passwordHelpBlock" class="form-text text-muted">
                                            At least <b>8 characters</b> long, <br> contains at least <b> 1 uppercase, 1 lowercase,
                                            1 number, <br> 1 special character </b> and <b>SHOULD NOT</b> start with a special
                                            character
                                        </small>
                                    </div>
                                    <input type="submit" name="register" class="btn btn-primary float-right"
                                        value="Sign Up"></input>
                                </form>
                                <p> Already a member? <button id="loginBtn" class="btn btn-link"> Sign In Here.
                                    </button></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    
    <footer class="footer bg-light fixed-bottom">
        <div class="container">
            <span class="text-muted text-center footer-text"> The College of Computing and Information Technolgies
                Forum</span>
        </div>
    </footer>



    
    <script src="js/jquery/jquery.min.js"></script>

    
    <script>
        $(".register").hide();

        $("#registerBtn").click(function () {
            $(".login").hide();
            $(".register").show();
            console.log("anyare");
        })

        $("#loginBtn").click(function () {
            $(".register").hide();
            $(".login").show();
            console.log("anyare");
        })

        function unhidePassword() {
            var x = document.getElementById("pass");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }

            var y = document.getElementById("pass2");
            if (y.type === "password") {
                y.type = "text";
            } else {
                y.type = "password";
            }
        }
    </script>
</body>

</html>