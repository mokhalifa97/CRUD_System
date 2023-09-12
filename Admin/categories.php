<?php
include("init.php");
$statement = $connect->prepare("SELECT * FROM categories");
$statement->execute();
$catCount = $statement->rowcount();
$result = $statement->fetchAll();

$page = "all";
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = "all";
}

if ($page == 'all') {
?>

    <div class="card mt-5 bg-dark text-light ">
        <div class="card-header d-flex justify-content-between align-item-center">
            <h5>Categories <span class="badge badge-primary"><?php echo $catCount; ?></span></h5>
            <a href="?page=addUser" class="btn btn-success">Add New Categories</a>
        </div>
        <div class="card-body">
            <table class="table table-dark table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">category_id</th>
                        <th scope="col">title</th>
                        <th scope="col">description</th>
                        <th scope="col">status</th>
                        <th scope="col">created_at</th>
                        <th scope="col">operation</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if ($catCount > 0) {
                        foreach ($result as $x) {
                    ?>

                            <tr>
                                <th scope="row"><?php echo $x['category_id'] ?></th>
                                <th scope="row"><?php echo $x['title'] ?></th>
                                <th scope="row"><?php echo $x['description'] ?></th>
                                <th scope="row"><?php echo $x['status'] ?></th>
                                <th scope="row"><?php echo $x['created_at'] ?></th>
                                <th scope="row">
                                    <a href="?page=show&userid=<?php echo $x['category_id']; ?>" class="btn btn-success">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>

                                    <a href="?page=delete&userid=<?php echo $x['category_id']; ?>" class="btn btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </th>
                            </tr>

                    <?php
                        }
                    }
                    ?>


                </tbody>
            </table>
        </div>
    </div>


    <?php
} elseif ($page == "show") {
    $userId = "";
    if (isset($_GET['userid'])) {
        $userId = $_GET['userid'];
    } else {
        $userId = "";
    }

    $statement = $connect->prepare("SELECT * FROM categories where category_id=?");
    $statement->execute(array($userId));
    $result = $statement->fetchAll();
    foreach ($result as $x) {
    ?>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="?page=change&userid=<?php echo $x['category_id']; ?>">
                        <h2 class="text-center p-3">Show And Edit</h2>

                        <div class="form-group">
                            <label for="exampleInputEmail1">ID</label>
                            <input type="text" class="form-control" name="id" value="<?php echo $x['category_id']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Title</label>
                            <input type="text" class="form-control" name="title" value="<?php echo $x['title']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Description</label>
                            <input type="text" class="form-control" name="desc" value="<?php echo $x['description']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Status</label>
                            <select name="status" class="form-control">
                            <?php
                            if($x['status']==1){
                            ?>
                                <option value="1" selected>Active</option>
                                <option value="0">Blocked</option>
                            <?php
                            }elseif($x['status']==0){
                            ?>
                                <option value="1" >Active</option>
                                <option value="0" selected>Blocked</option>
                            <?php
                            }
                            ?>
                            </select>
                        </div>
                        <button type="submit" name="saveChange" class="btn btn-primary btn-block">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>

<?php
    }
}elseif($page == "change"){
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['saveChange'])){
        $userId="";
        if(isset($_GET['userid'])){
            $userId=$_GET['userid'];
        }else{
            $userId="";
        }
        $id=$_POST['id'];
        $title=$_POST['title'];
        $desc=$_POST['desc'];
        $status=$_POST['status'];

        $statement=$connect->prepare("UPDATE categories set 
        category_id =?,
        title =?,
        `description` =?,
        `status` =? where category_id =? 
        ");
        $statement->execute(array($id,$title,$desc,$status,$userId));
        // $result=$statement->fetchAll();

        echo "<h1 class='alert alert-success text-center'> categories Edited Successfully </h1>" ;
        header("refresh:3;url=categories.php");

        }
    }
}elseif($page== "delete"){
    $userId="";
    if(isset($_GET['userid'])){
        $userId=$_GET['userid'];
    }else{
        $userId="";
    }

    $statement=$connect->prepare("DELETE FROM categories where category_id =?");
    $statement->execute(array($userId));
    // $result=$statement->fetchAll();

    echo "<h1 class='alert alert-danger text-center'>categories Deleted Successfully </h1>" ;
    header('refresh:3;url=categories.php');


}elseif($page == "addUser"){
?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                <form method="post" action="?page=newCat">
                        <h2 class="text-center p-3">Add New Categories</h2>

                        <div class="form-group">
                            <label for="exampleInputEmail1">ID</label>
                            <input type="text" class="form-control" name="id">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Description</label>
                            <input type="text" class="form-control" name="desc">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Status</label>
                            <select name="status" class="form-control">

                                <option value="1" >Active</option>
                                <option value="0">Blocked</option>
                        
                            </select>
                        </div>
                        <button type="submit" name="newSubmit" class="btn btn-primary btn-block">Add New Categories</button>
                    </form>

                </div>
            </div>
        </div>
<?php    
}elseif($page=="newCat"){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['newSubmit'])){
            $id = $_POST['id'];
            $title = $_POST['title'];
            $desc = $_POST['desc'];
            $status = $_POST['status'];

            $statement=$connect->prepare("INSERT INTO categories(category_id,title,`description`,`status`,created_at)
            VALUES (?,?,?,?,now());
            ");
            $statement->execute(array($id,$title,$desc,$status));
            // $result=$statement->fetchAll();

            echo "<h1 class='alert alert-success text-center'>categories Added Successfully </h1>" ;
            header('refresh:3;url=categories.php');
        }
    }
}
?>

<?php
include("includes/templates/footer.php");
?>