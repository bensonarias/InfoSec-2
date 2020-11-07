<?php

if(!isset($_SESSION)) {
    session_start();
}

include_once "connections/connection.php";

$con = connection();

$con = connection();
$search = $_GET['search'];
$sql = "SELECT * FROM users WHERE firstName LIKE '$search%' OR lastName LIKE '$search%' OR email LIKE '$search%' ORDER BY userID";
$users = $con->query($sql) or die($con->error);
$row = $users->fetch_assoc();

if(!isset($_SESSION['UserLogin'])) {
    echo header("Location: login.php");
}

if(isset($_SESSION['UserLogin'])) {
    echo "<div class='text-center'> Welcome ".$_SESSION['UserLogin']." Role: ".$_SESSION['Access']."</div>";
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

        <h1 class="text-center"> CCIT Forum Admin </h1>
        <h3 class="text-center"> Users Account </h3>
        
         
         <h1> Accounts </h1>
            <small> View All Users.</small>
            <div class="btn-group float-right" role="group" aria-label="Basic example">
                <a class="btn btn-info float-left" href="/ccitforum/home.php"> News Feed </a>
                <a class="btn btn-primary float-left" href="/ccitforum/myPosts.php"> My Posts </a>
                <a class="btn btn-success float-left" href="/ccitforum/accounts.php"> Accounts </a>
                <a class="btn btn-danger float-left" href="/ccitforum/logout.php"> Logout </a>
            </div>
            <hr>
        
        
        <a id="loginBtn" class="btn btn-link float-right" href="/ccitforum/update.php?ID=<?php echo $id?>"> Edit My Account. </a>

        <?php if($_SESSION['Access'] == "admin") { ?>
        <a class="btn btn-link float-right" href="/ccitforum/add.php"> Add New Account </a> <br> <br>
        <?php } ?>
        
        
        <form action="result.php" method="get" accept-charset="utf-8">
            <div class="input-group mb-3">
            <input type="text" name="search" id="search" class="form-control" placeholder="Search for user's name or email" autocomplete="off">
            <div class="input-group-append float-right">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </div>
            </div>
        </form>

        
        <table class="table table-striped">

        <?php if($users->num_rows > 0) { ?>
            <thead>
                    <tr class="bg-primary" style="color:white;">
                        <th scope="col">View Profile</th>
                        <th scope="col">id</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>

                        
                        <?php if($_SESSION['Access'] == "admin") { ?>
                        <th scope="col">Password</th>
                        <th scope="col">Access</th>
                  
                        <th scope="col">Update</th>
                        <th scope="col">Delete</th>
                        <?php } ?>
                </thead>
    
            <tbody>
          
                    <?php do {?>
                        <?php if($row['userID'] != $_SESSION['ID']) { ?>
                    <tr>
                        <td>
                            <a class="view btn btn-info btn-sm" name="view"
                                href="/ccitforum/details.php?ID=<?php echo $row['userID']?>">View Profile</a>
                        </td>
                        <td> <b> <?php echo $row['userID'];?> </b> </td>
                        <td> <?php echo $row['firstName'];?> </td>
                        <td> <?php echo $row['lastName'];?> </td>
                        <td> <?php echo $row['email'];?> </td>

                        
                        <?php if($_SESSION['Access'] == "admin") { ?>
                        <td> <?php echo $row['password'];?> </td>
                        <td> <?php echo $row['access'];?> </td>
                        
                        <td>
                            <a class="view btn btn-warning btn-sm" name="update"
                                href="/ccitforum/update.php?ID=<?php echo $row['userID']?>">Update</a>
                        </td>
                        <td>
                            <form action="delete.php" onSubmit="return confirm('Do you really want to delete this user?')"
                                method="post" accept-charset="utf-8">
                                <button type="submit" class="view btn btn-danger btn-sm" name="delete">Delete</button>
                                <input type="hidden" class="<style>hidden" name="ID" value="<?php echo $row['userID']?>">
                            </form>
                        </td>
                        <?php } ?>
                    </tr>
                        <?php } ?>
                    <?php } while ($row = $users->fetch_assoc()) ?>
                    <?php } else { echo "<div class='display-4'> No result! </div>"; } ?>
                </tbody>
        </table>
        <a id="loginBtn" class="btn btn-link float-left" href="/ccitforum/accounts.php"> View All User's List. </a>
        </div>
</body>
<html>