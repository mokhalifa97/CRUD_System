<?php

    session_start();
    if(isset($_SESSION['time'])){


include("init.php");

$a1= $connect->prepare("SELECT * FROM users");
$a1->execute();
$userCount=$a1->rowcount();

$a2= $connect->prepare("SELECT * FROM categories");
$a2->execute();
$catCount=$a2->rowcount();

$a3= $connect->prepare("SELECT * FROM posts");
$a3->execute();
$postCount=$a3->rowcount();

$a4= $connect->prepare("SELECT * FROM 	comments");
$a4->execute();
$commentCount=$a4->rowcount();
?>

    <div class="statics mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="boxes">
                        <i class="fa-regular fa-user fa-beat"></i>
                        <h2>users</h2>
                        <span><?php echo $userCount; ?></span>
                        <br>
                        <a href="users.php" class="btn btn-danger">show</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="boxes">
                    <i class="fa-solid fa-shapes fa-beat"></i>
                        <h2>Categories</h2>
                        <span><?php echo $catCount; ?></span>
                        <br>
                        <a href="categories.php" class="btn btn-info">show</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="boxes">
                    <i class="fa-solid fa-address-card fa-beat"></i>
                        <h2>Posts</h2>
                        <span><?php echo $postCount; ?></span>
                        <br>
                        <a href="posts.php" class="btn btn-success">show</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="boxes">
                    <i class="fa-regular fa-comments fa-beat"></i>
                        <h2>Comments</h2>
                        <span><?php echo $commentCount; ?></span>
                        <br>
                        <a href="comments.php" class="btn btn-warning">show</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
include("includes/templates/footer.php");


}else{
    echo "<h1>Please login first</h1>";
    header('refresh:3;url=login.php');
}


?>