<?php
include("init.php");
$statement = $connect->prepare("SELECT * FROM users");
$statement->execute();
$resultuser = $statement->rowcount();
$result = $statement->fetchAll();

$page = "All";
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = "All";
}


if ($page == "All") {
?>
    <div class="card mt-5 bg-dark text-light ">
        <div class="card-header">
            Users <span class="badge badge-primary"><?php echo $resultuser; ?></span>
            <a href="?page=newuser" class="btn btn-success">Add User</a>
        </div>
        <div class="card-body">
            <table class="table table-dark table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">user_id</th>
                        <th scope="col">username</th>
                        <th scope="col">email</th>
                        <th scope="col">password</th>
                        <th scope="col">status</th>
                        <th scope="col">role</th>
                        <th scope="col">created_at</th>
                        <th scope="col">operation</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if ($resultuser > 0) {
                        foreach ($result as $x) {
                    ?>

                            <tr>
                                <th scope="row"><?php echo $x['user_id'] ?></th>
                                <th scope="row"><?php echo $x['username'] ?></th>
                                <th scope="row"><?php echo $x['email'] ?></th>
                                <th scope="row"><?php echo $x['password'] ?></th>
                                <th scope="row"><?php echo $x['status'] ?></th>
                                <th scope="row"><?php echo $x['role'] ?></th>
                                <th scope="row"><?php echo $x['created_at'] ?></th>
                                <th scope="row">
                                    <a href="?page=show&user=<?php echo $x['user_id']; ?>" class="btn btn-success">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>

                                    <a href="?page=delete&user=<?php echo $x['user_id']; ?>" class="btn btn-danger">
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
} elseif ($page == "delete") {
    $user = "";
    if (isset($_GET['user'])) {
        $user = $_GET['user'];
    } else {
        $user = "";
    }

    $statement = $connect->prepare("DELETE from users where user_id=?");
    $statement->execute(array($user));
    // $result = $statement->fetchAll();

    echo "<h2 class='alert alert-danger text-center'>user deleted successfully</h2>";
    header("refresh:3;url=users.php");
} elseif ($page == 'show') {

    $user = "";
    if (isset($_GET['page'])) {
        $user = $_GET['user'];
    } else {
        $user = "";
    }
    $statement = $connect->prepare("SELECT * FROM users where user_id=?");
    $statement->execute(array($user));
    $result = $statement->fetchAll();

    foreach ($result as $x) {

    ?>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center p-3">Show And Edit</h2>
                    <form method="post" action="?page=saveuser&theid=<?php echo $x['user_id']; ?>">
                        <div class="form-group">
                            <label for="exampleInputEmail1">User ID</label>
                            <input type="text" class="form-control" name="id" value="<?php echo $x['user_id']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">User Name</label>
                            <input type="text" class="form-control" name="username" value="<?php echo $x['username']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" name="email" value="<?php echo $x['email']; ?>">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Status</label>
                            <select name="status" class="form-control">
                                <?php
                                if ($x['status'] == 1) {
                                ?>
                                    <option value="1" selected>1</option>
                                    <option value="0">0</option>

                                <?php
                                } elseif ($x['status'] == 0) {
                                ?>
                                    <option value="1">1</option>
                                    <option value="0" selected>0</option>

                                <?php
                                }
                                ?>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Role</label>
                            <select name="role" class="form-control">
                                <?php
                                if ($x['role'] == "user") {
                                ?>
                                    <option value="user" selected>User</option>
                                    <option value="admin">Admin</option>

                                <?php
                                } elseif ($x['role'] == "admin") {
                                ?>
                                    <option value="user">User</option>
                                    <option value="admin" selected>Admin</option>

                                <?php
                                }
                                ?>

                            </select>
                        </div>

                        <button name="save-form" type="submit" class="btn btn-primary btn-block">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>

    <?php
    }
} elseif ($page == "saveuser") {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['save-form'])) {
            $theid = "";
            if (isset($_GET['theid'])) {
                $theid = $_GET['theid'];
            } else {
                $theid = "";
            }
            $id = $_POST['id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $Status = $_POST['status'];
            $role = $_POST['role'];

            $statement=$connect->prepare("UPDATE users set
            user_id=?,
            username=?,
            email=?,
            `status`=?,
            `role`=?
            where user_id=?;
            ");
            $statement->execute(array($id,$username,$email,$Status,$role,$theid));
            // $result=$statement->fetchAll();

            echo "<h1 class='alert alert-success text-center p-3'>User Updated successfully</h1>";
            header('refresh:3;url=users.php');
        }
    }
} elseif ($page == 'newuser') {
    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h2 class="text-center">Add New User</h2>

                <form method="post" action="?page=adduser">
                    <div class="form-group">
                        <label for="exampleInputEmail1">ID</label>
                        <input type="text" class="form-control" name="id">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">User Name</label>
                        <input type="text" class="form-control" name="username">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" class="form-control" name="email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" name="pass">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Status</label>
                        <select class="form-control" name="status">
                            <option value="1">active</option>
                            <option value="0">block</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Role</label>
                        <select name="role" class="form-control">
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <button type="submit" name="new-submit" class="btn btn-primary btn-block">Submit</button>
                </form>
            </div>
        </div>
    </div>

<?php
}elseif($page='adduser'){
    if($_SERVER["REQUEST_METHOD"] == 'POST'){
        if(isset($_POST['new-submit'])){
            $id=$_POST["id"];
            $user=$_POST["username"];
            $email=$_POST["email"];
            $pass=$_POST["pass"];
            $hashpass= sha1($pass); 
            $status=$_POST["status"];
            $role=$_POST["role"];

            $statement=$connect->prepare("INSERT INTO users (user_id ,username,email,`password`,`status`,`role`,created_at)
            values (?,?,?,?,?,?,now());
            ");
            $statement->execute(array($id,$user,$email,$hashpass,$status,$role));
            // $result=$statement->fetchAll();

            echo "<h2 class='alert alert-success text-center p-3'>New User Added Successfully</h2>";
            header('refresh:3;url=users.php');

        }
    }
}
?>






<?php
include("includes/templates/footer.php");
?>