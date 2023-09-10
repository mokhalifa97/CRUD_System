<?php

session_start();
if(isset($_SESSION['time'])){
    header('Location:dashboard.php');
}

include("includes/database/db.php");
include("includes/templates/header.php");

    if($_SERVER['REQUEST_METHOD'] =='POST'){
        if(isset($_POST['submit'])){
            $email= $_POST['email'];
            $pass= $_POST['password'];
            $hashPassword= sha1($pass);

            $statement=$connect->prepare("SELECT * FROM users where `email`=? and `password`=? limit 1");
            $statement->execute(array($email,$hashPassword));
            $userCount= $statement->rowCount();

            if($userCount>0){
                $result=$statement->fetch();
                if($result['role']=='admin'){
                    $_SESSION['time']=$result['username'];
                    header('location:dashboard.php');
                }else{
                echo "<div class='alert alert-danger text-center'>Your Not Admin </div>" ;
                }
            }else{
                echo "<div class='alert alert-danger text-center'> Your Not Rajestir,Please Create Account </div>";
            }
        }
    }

?>


<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center">Admin Login</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input name="email" type="email" class="form-control" >
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input name="password" type="password" class="form-control" >
                </div>
    
                <button name="submit" type="submit" class="btn btn-primary btn-block">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php
include("includes/templates/footer.php");
?>