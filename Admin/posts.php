<?php
include("init.php");
$statement = $connect->prepare("SELECT * FROM posts");
$statement->execute();
$postCount = $statement->rowcount();
$result = $statement->fetchAll();

$page = "all";
if (isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = "all";
}


if ($page == 'all') {
?>



  <div class="card mt-5 bg-dark text-light">
    <div class="card-header d-flex justify-content-between align-item-center">
      <h5>Posts <span class="badge badge-primary"> <?php echo $postCount ?> </span></h5>
      <a href="?page=addNew" class="btn btn-success">Add New Post</a>
    </div>
    <div class="card-body">


      <table class="table table-dark table-hover table-striped table-bordered">
        <thead>
          <tr>
            <th scope="col">post_id</th>
            <th scope="col">title</th>
            <th scope="col">description</th>
            <th scope="col">image</th>
            <th scope="col">status</th>
            <th scope="col">category_id</th>
            <th scope="col">user_id</th>
            <th scope="col">created_at</th>
            <th scope="col">operation</th>
          </tr>
        </thead>
        <tbody>

          <?php
          if ($postCount > 0) {
            foreach ($result as $x) {
          ?>

              <tr>
                <th scope="row"><?php echo $x['post_id'] ?></th>
                <th scope="row"><?php echo $x['title'] ?></th>
                <th scope="row"><?php echo $x['description'] ?></th>
                <th scope="row"><?php echo $x['image'] ?></th>
                <th scope="row"><?php echo $x['status'] ?></th>
                <th scope="row"><?php echo $x['category_id'] ?></th>
                <th scope="row"><?php echo $x['user_id'] ?></th>
                <th scope="row"><?php echo $x['created_at'] ?></th>
                <th scope="row">
                  <a href="?page=show&userId= <?php echo $x['post_id']; ?>" class="btn btn-success">
                    <i class="fa-regular fa-eye"></i>
                  </a>
                  <a href="?page=delete&userId= <?php echo $x['post_id']; ?>" class="btn btn-danger">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                </th>
              </tr>
              <tr>

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
  if (isset($_GET['userId'])) {
    $user = $_GET['userId'];
  } else {
    $user = "";
  }
  $statement = $connect->prepare("DELETE FROM posts where post_id=?");
  $statement->execute(array($user));
  $result = $statement->fetchAll();
  echo "<h2 class='alert alert-success text-center'>Post Deleted Successfully</h2>";
  header("refresh:3;url=posts.php");
} elseif ($page == "show") {
  $user = "";
  if (isset($_GET['userId'])) {
    $user = $_GET['userId'];
  } else {
    $user = "";
  }

  $statement=$connect->prepare("SELECT * FROM posts where post_id=?");
  $statement->execute(array($user));
  $result=$statement->fetchAll();

  foreach($result as $x){
    
?>

  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <form method="post" action="?page=edit&userId=<?php echo $x['post_id'] ?> ">
          <h2 class="text-center p-3">Show And Edit</h2>

          <div class="form-group">
            <label for="exampleInputEmail1">Post ID</label>
            <input type="text" class="form-control" name="id" value="<?php echo $x['post_id']; ?>">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Title</label>
            <input type="text" class="form-control" name="title" value="<?php echo $x['title']; ?>" >
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Description</label>
            <input type="text" class="form-control" name="desc" value="<?php echo $x['description']; ?>">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Image</label>
            <input type="text" class="form-control" name="image" value="<?php echo $x['image']; ?>">
          </div>

          <div class="form-group">
            <label for="exampleInputPassword1">Status</label>
            <select name="status" class="form-control">
              <?php
              if($x['status']==1){
              ?>
              <option value="1" selected>Active</option>
              <option value="0">Blocked</option>
              <?php
              }else{
                ?>

              <option value="1" >Active</option>
              <option value="0" selected>Blocked</option>

              <?php
              }
              ?>

            </select>
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">category_id</label>
            <input type="text" class="form-control" name="cat" value="<?php echo $x['category_id']; ?>">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">user_id</label>
            <input type="text" class="form-control" name="user" value="<?php echo $x['user_id']; ?>">
          </div>
  
          <button type="submit" name="saveChange" class="btn btn-primary btn-block">Save Changes</button>
        </form>
      </div>
    </div>
  </div>

<?php
  }
}elseif($page == "edit"){
  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['saveChange'])){
      $user="";
      if(isset($_GET['userId'])){
        $user=$_GET['userId'];
      }else{
        $user="";
      }

      $id=$_POST['id'];
      $title=$_POST['title'];
      $desc=$_POST['desc'];
      $image=$_POST['image'];
      $status=$_POST['status'];

      
      $statement=$connect->prepare("UPDATE posts set
        post_id=?,
        title=?,
        `description`=?,
        `image`=?,
        `status`=? where post_id=?
      ");
      $statement->execute(array($id,$title,$desc,$image,$status,$user));
      $result=$statement->fetchAll();

      echo "<h2 class='alert alert-success text-center'>Post Saved Successfully</h2>";
      header("refresh:3;url=posts.php");

    }
  }
}elseif($page=="addNew"){
  ?>

<div class="container">
      <div class="row">
        <div class="col-md-12">
        <form method="post" action="?page=saveComment">
          <h2 class="text-center p-3">Add New Post</h2>

          <div class="form-group">
            <label for="exampleInputEmail1">Post ID</label>
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
            <label for="exampleInputEmail1">Image</label>
            <input type="text" class="form-control" name="image">
          </div>

          <div class="form-group">
            <label for="exampleInputPassword1">Status</label>
            <select name="status" class="form-control">
              <option value="1" selected>Active</option>
              <option value="0">Blocked</option>
            </select>
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">category_id</label>
            <input type="text" class="form-control" name="cat">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">user_id</label>
            <input type="text" class="form-control" name="user">
          </div>
  
          <button type="submit" name="saveChange" class="btn btn-primary btn-block">Add Post</button>
        </form>
        </div>
      </div>
    </div>
<?php
}elseif($page=="saveComment"){
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST['saveChange'])){
      $id=$_POST['id'];
      $title=$_POST['title'];
      $desc=$_POST['desc'];
      $image=$_POST['image'];
      $status=$_POST['status'];
      $category_id=$_POST['cat'];
      $user_id=$_POST['user'];

      $statement=$connect->prepare("INSERT INTO posts (post_id,title,`description`,`image`,`status`,category_id,user_id,created_at)
      VALUES (?,?,?,?,?,?,?,now());
      ");
      $statement->execute(array($id,$title,$desc,$image,$status,$category_id,$user_id));
      $result=$statement->fetchAll();

      echo "<h1 class='alert alert-success text-center'>Post Added Successfully </h1>" ;
      header('refresh:3;url=posts.php');
    }
  }
}
?>



<?php
include("includes/templates/footer.php");
?>