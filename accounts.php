<?php

if (!isset($_SESSION)) {
    session_start();
}

include_once "connections/connection.php";

$con = connection();

$id = $_SESSION['ID'];
$sql = "SELECT * FROM users ORDER BY userID";
$users = $con->query($sql) or die($con->error);
$row = $users->fetch_assoc();

if (!isset($_SESSION['UserLogin'])) {
    echo header("Location: login.php");
}

if (isset($_SESSION['UserLogin'])) {
    echo "<div class='float-right'> Welcome <b> " . $_SESSION['UserLogin'] . " </b> | Role: <b> " . $_SESSION['Access'] . "</b></div> <br>";
} else {
    echo "Welcome guest!";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title> CCIT Forum </title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>


    <div class="container">
        <h1 class="text-center"><b> The CCIT Wall </b></h1>
        <h3 class="text-center">Homepage</h3>
        

        
        <h1> Accounts </h1>
        <small> View All Users</small>
        <div class="btn-group float-right" role="group" aria-label="">
            <a class="btn btn-info float-left font-weight-bold" href="/ccitforum/home.php"> News Feed </a>&nbsp;
            <a class="btn btn-primary float-left font-weight-bold" href="/ccitforum/myPosts.php"> My Posts </a>&nbsp;
            <a class="btn btn-success float-left font-weight-bold" href="/ccitforum/accounts.php"> Accounts </a>&nbsp;
            <a class="btn btn-danger float-left font-weight-bold" href="/ccitforum/logout.php"> Logout </a>
        </div>
        <br>	
        <br>
        <hr>

        <div class="btn-group float-right" role="group" arial-label="">
   
            <?php if ($_SESSION['Access'] == "admin") { ?>
                <a class="btn btn-link float-right font-weight-bold text-decoration-none" href="/ccitforum/add.php"> Add New Account </a> <br> <br>
            <?php } ?>

            
            <a id="loginBtn" class="btn btn-link float-right font-weight-bold" href="/ccitforum/update.php?ID=<?php echo $id ?>"> Edit my Account </a>

        </div>

        <form action="result.php" method="get">
            <div class="input-group mb-3">
                <input type="text" name="search" id="search" class="form-control" placeholder="Search for user's name or email" autocomplete="off">
                <div class="input-group-append float-right">
                    <button class="btn btn-outline-success font-weight-bold" type="submit">Search</button>
                </div>
            </div>
        </form>


        <table class="table table-striped">

            <thead class="bg-primary" style="color:white;">
                <tr>
                    <th scope="col">View Profile</th>
                    <th scope="col">id</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>

                    
                    <?php if ($_SESSION['Access'] == "admin") { ?>
                      
                        <th scope="col">Access</th>
                        <th scope="col">Update</th>
                        <th scope="col">Delete</th>
                    <?php } ?>
            </thead>

            <tbody>
                <?php do { ?>
                    <?php if ($row['userID'] != $_SESSION['ID']) { ?>
                        <tr>
                            <td>
                                <a class="view btn btn-info btn-sm font-weight-bold" name="view" href="/ccitforum/details.php?ID=<?php echo $row['userID'] ?>">View Profile</a>
                            </td>
                            <td> <b> <?php echo $row['userID']; ?> </b> </td>

                            <td> <?php echo $row['firstName']; ?> </td>
                            <td> <?php echo $row['lastName']; ?> </td>
                            <td> <?php echo $row['email']; ?> </td>

                            
                            <?php if ($_SESSION['Access'] == "admin") { ?>
                               
                                <td> <?php echo $row['access']; ?> </td>

                                <td>
                                    <a class="view btn btn-warning btn-sm font-weight-bold" name="update" href="/ccitforum/update.php?ID=<?php echo $row['userID'] ?>">Update</a>
                                </td>
                                <td>
                                    <form action="delete.php" onSubmit="return confirm('Do you really want to delete this user?')" method="post">
                                        <button type="submit" class="view btn btn-danger btn-sm font-weight-bold" name="deleteUser">Delete</button>
                                        <input type="hidden" class="<style>hidden" name="ID" value="<?php echo $row['userID'] ?>">
                                    </form>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } while ($row = $users->fetch_assoc()) ?>
            </tbody>


        </table>
        <div>

</body>
<html>