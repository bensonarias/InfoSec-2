<?php

if(!isset($_SESSION)) {
    session_start();
}

include_once "connections/connection.php";
include "validation/validation.php";
$con = connection();

$id = $_GET['ID'];

$sql = "SELECT * FROM users WHERE userID = '$id'";
$users = $con->query($sql) or die($con->error);
$row = $users->fetch_assoc();

// Can access the page if it is an admin or it is the user's personal account!
if((isset($_SESSION['Access']) && $_SESSION['Access'] == "admin" || $_SESSION['ID'] == $id)) {
    echo "<div class='float-right'> Welcome <b> ".$_SESSION['UserLogin']." </b> | Role: <b> ".$_SESSION['Access']."</b></div> <br>";
 } else {
     echo header("Location: home.php");
}

if(isset($_POST['submit'])) {

    // Empty by default
    $firstName = "";
    $lastName = "";
    $email = "";
    $password = '';

   /// Validation
    //First Name
    try{
    if(isFirstNameValid($_POST['firstName']) == 1) {
        $firstName = formValidate($_POST['firstName']);
    } else {
        echo "Error: Invalid First Name!";
        throw new customException("First Name Input Validation Error",1);
    }

     //Last Name
    if(isLastNameValid($_POST['lastName']) == 1) {
        $lastName = formValidate($_POST['lastName']);
    } else {
        echo "Error: Invalid Last Name!";
        throw new customException("Last Name Input Validation Error",1);
    }

    // Email
    if(isEmailValid($_POST['email']) == 1) {
        $email = formValidate($_POST['email']);
    } else {
        echo "Error: Invalid Email!";
        throw new customException("Email Input Validation Error",1);
    }


      // Changing password
      $oldPassword = $_POST['old-pass'];
      $newPassword = $_POST['new-pass'];
      $confirmPassword = $_POST['confirm-new-pass'];
    
      echo $confirmPassword;

      if(password_verify($oldPassword, $row['password']) && $newPassword == $confirmPassword) {
          if(isPasswordValid($_POST['new-pass']) == 1) {
              $password = $_POST['new-pass'];
              $password = password_hash($password, PASSWORD_BCRYPT);
          } else {
              echo "Error: Invalid Password!";
              throw new customException("Invalid Pasword",1);
          }
      } else {
          echo "Error: Wrong Old Password or New Password doesn't match to the Confirm Password!"; 
           throw new customException("Change Password Validation Error",1);
        }
    }catch(customException $e){
        insertLog("ERROR",$e->errorCode(),$e->errorMessage());
    }


    if($_POST['access'] == "") {
        $access = "user";
    } else {
        $access = $_POST['access'];
    }
    session_regenerate_id(true);// 02/10/2020
    $sql = "UPDATE `users` SET `firstName` = '$firstName', `lastName` = '$lastName', `email` = '$email', `password` = '$password', `access` = '$access' WHERE `userID` = $id";

    $con->query($sql) or die($con->error);

    $last_id = $con->insert_id;	
    insertLog("INFO", 1, " User ID ".$_SESSION['ID']." edit an account with an ID of ".$last_id);

    //logout if the info was change on the own account.
    if($_SESSION['ID'] == $id) {
        echo header("Location: logout.php");
    } else {
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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>

    <div class="container">

        <div class="register">
            <h1 class="text-center"> CCIT Forum Admin </h1>
            <h3 class="text-center">Edit User </h1>
                <a id="loginBtn" class="btn btn-dark float-right" href="/ccitforum/accounts.php"> Back to User's List.
                </a>
                <br><br>
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post"
                            onSubmit="return confirm('Do you really want to update this user? You might be logged out if it is successful!')">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="name" class="form-control" name="firstName"
                                    value="<?php echo $row['firstName']?>">
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="name" class="form-control" name="lastName"
                                    value="<?php echo $row['lastName']?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="<?php echo $row['email']?>">
                            </div>

                            <!-- Edit this part that it doesnt retrieve the hashed password but just ask if they want to apply for new password -->
                            <div class="form-group">

                                <!-- Create 3 input for enter old password, new password, confirm password -->

                                <label for="password">Change Password</label>

                                <input id="pass1" type="password" class="form-control" name="old-pass"
                                    value="" placeholder="Enter Old Password">
                                <input type="checkbox" onclick="unhidePassword1()"> Show Password </input>

                                <input id="pass2" type="password" class="form-control" name="new-pass"
                                    value="" placeholder="Enter New Password">
                                <input type="checkbox" onclick="unhidePassword2()"> Show Password </input>

                                <input id="pass3" type="password" class="form-control" name="confirm-new-pass"
                                    value="" placeholder="Confirm New Password">
                                <input type="checkbox" onclick="unhidePassword3()" > Show Password </input>
                            

                            </div>
                            <!-- Access -->
                            <?php if($_SESSION['Access'] == "admin") { ?>
                            <div class="form-group">
                                <label for="password">Access</label>
                                <select name="access" class="form-control">
                                    <?php if($row['access'] == "user") {  ?>
                                    <option value="user" selected>User</option>
                                    <option value="admin">Admin</option>
                                    <?php } else { ?>
                                    <option value="user">User</option>
                                    <option value="admin" selected>Admin</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php } ?>
                            <input type="submit" name="submit" class="btn btn-success float-right"
                                value="Save Changes"></input>
                        </form>
                        <a id="loginBtn" class="btn btn-link" href="/ccitforum/accounts.php"> Back to User's List. </a>
                    </div>
                </div>
        </div>

    </div>

    <!-- JQuery library -->
    <script src="js/jquery/jquery.min.js"></script>

    <!-- JQuery Script -->
    <script>
        function unhidePassword1() {
            var x = document.getElementById("pass1");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        function unhidePassword2() {
            var y = document.getElementById("pass2");
            if (y.type === "password") {
                y.type = "text";
            } else {
                y.type = "password";
            }
        }

        function unhidePassword3() {
            var z = document.getElementById("pass3");
            if (z.type === "password") {
                z.type = "text";
            } else {
                z.type = "password";
            }
        }
         
            

    </script>
</body>

</html>