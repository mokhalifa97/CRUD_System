<?php
include("init.php");
$statement = $connect->prepare("SELECT * FROM comments");
$statement->execute();
$commentCount = $statement->rowcount();
$result = $statement->fetchAll();

$page = 'all';

if (isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 'all';
}

if ($page == 'all') {
?>



  <div class="card mt-5 bg-dark text-light">
    <div class="card-header">
      Comments <span class="badge badge-primary"> <?php echo $commentCount ?></span>
      <a href="?page=addNew" class="btn btn-success">Add New Comment</a>
    </div>
    <div class="card-body">


      <table class="table table-dark table-hover table-striped table-bordered">
        <thead>
          <tr>
            <th scope="col">comment_id</th>
            <th scope="col">comment</th>
            <th scope="col">status</th>
            <th scope="col">user_id</th>
            <th scope="col">post_id</th>
            <th scope="col">created_at</th>
            <th scope="col">operation</th>
          </tr>
        </thead>
        <tbody>

          <?php
          if ($commentCount > 0) {
            foreach ($result as $x) {
          ?>

              <tr>
                <th scope="row"><?php echo $x['comment_id'] ?></th>
                <th scope="row"><?php echo $x['comment'] ?></th>
                <th scope="row"><?php echo $x['status'] ?></th>
                <th scope="row"><?php echo $x['user_id'] ?></th>
                <th scope="row"><?php echo $x['post_id'] ?></th>
                <th scope="row"><?php echo $x['created_at'] ?></th>
                <th scope="row">

                  <a href="?page=show&userId=<?php echo $x['comment_id']; ?>" class="btn btn-success">
                    <i class="fa-regular fa-eye fa-fade"></i>
                  </a>

                  <a href="?page=delete&userId=<?php echo $x['comment_id']; ?>" class="btn btn-danger">
                    <i class="fa-solid fa-trash fa-bounce"></i>
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
} elseif ($page == 'delete') {
  $user = "";
  if (isset($_GET['userId'])) {
    $user = $_GET['userId'];
  } else {
    $user = "";
  }

  $statement = $connect->prepare("DELETE FROM comments where comment_id=?");
  $statement->execute(array($user));
  $result = $statement->fetchAll();

  echo "<h2 class='alert alert-danger text-center'>Comment Deleted Successfully</h2>";
  header('refresh:3;url=comments.php');
}elseif ($page == "show") {
  $user = "";
  if (isset($_GET['userId'])) {
    $user = $_GET['userId'];
  } else {
    $user = "";
  }

  $statement = $connect->prepare("SELECT * FROM comments where comment_id=?");
  $statement->execute(array($user));
  $result = $statement->fetchAll();
  foreach ($result as $x) {
  ?>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <form method="POST" action="?page=saveNew&userId=<?php echo $x['comment_id']; ?>">
            <h2 class="text-center p-3">Show And Edit</h2>

            <div class="form-group">
              <label for="exampleInputEmail1">comment_id</label>
              <input type="text" class="form-control" name="id" value="<?php echo $x['comment_id'] ?>">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">comment</label>
              <input type="text" class="form-control" name="comment" value="<?php echo $x['comment'] ?>">
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
              <label for="exampleInputEmail1">user_id</label>
              <input type="text" class="form-control" name="user_id" value="<?php echo $x['user_id'] ?>">
            </div>

          <div class="form-group">
              <label for="exampleInputEmail1">post_id</label>
              <input type="text" class="form-control" name="post_id" value="<?php echo $x['post_id'] ?>">
            </div>
    
            
            <button type="submit" name="changes" class="btn btn-primary btn-block">Save Changes</button>
          </form>
        </div>
      </div>
    </div>

<?php

}
}elseif($page=="saveNew"){
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST['changes'])){
      $user="";
      if(isset($_GET['userId'])){
        $user=$_GET['userId'];
      }else{
        $user="";
      }

      $id=$_POST['id'];
      $comment=$_POST['comment'];
      $status=$_POST['status'];
      $user_id=$_POST['user_id'];
      $post_id=$_POST['post_id'];

      $statement=$connect->prepare("UPDATE comments SET 
      comment_id=?,
      comment=?,
      `status`=?,
      user_id=?,
      post_id=? where comment_id=?;
      ");
      $statement->execute(array($id,$comment,$status,$user_id,$post_id,$user));
      $result=$statement->fetchAll();

      
      echo "<h1 class='alert alert-success text-center'> Comment Edited Successfully </h1>" ;
      header("refresh:3;url=comments.php");
    }
  }
}elseif($page=="addNew"){
  ?>

<div class="container">
      <div class="row">
        <div class="col-md-12">
          <form method="POST" action="?page=saveComment">
            <h2 class="text-center p-3">Add New Comment</h2>

            <div class="form-group">
              <label for="exampleInputEmail1">comment_id</label>
              <input type="text" class="form-control" name="id">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1">comment</label>
              <input type="text" class="form-control" name="comment" >
            </div>

            <div class="form-group">
            <label for="exampleInputPassword1">Status</label>
            <select name="status" class="form-control">
              <option value="1" selected>Active</option>
              <option value="0">Blocked</option>
            </select>
          </div>

          <div class="form-group">
              <label for="exampleInputEmail1">user_id</label>
              <input type="text" class="form-control" name="user_id" >
            </div>

          <div class="form-group">
              <label for="exampleInputEmail1">post_id</label>
              <input type="text" class="form-control" name="post_id">
            </div>
    
            
            <button type="submit" name="newComment" class="btn btn-primary btn-block">Add Comment</button>
          </form>
        </div>
      </div>
    </div>
<?php
}elseif($page=="saveComment"){
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST['newComment'])){
      $id=$_POST['id'];
      $comment=$_POST['comment'];
      $status=$_POST['status'];
      $user_id=$_POST['user_id'];
      $post_id=$_POST['post_id'];

      $statement=$connect->prepare("INSERT INTO comments(comment_id,comment,`status`,user_id,post_id,created_at)
      VALUES (?,?,?,?,?,now());
      ");
      $statement->execute(array($id,$comment,$status,$user_id,$post_id));
      $result=$statement->fetchAll();

      echo "<h1 class='alert alert-success text-center'>comment Added Successfully </h1>" ;
      header('refresh:3;url=comments.php');
    }
  }
}
?>

<?php
include("includes/templates/footer.php");
?>