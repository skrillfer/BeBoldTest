<?php
session_start();
require "header.php";
if(array_key_exists("logged_in_user_id",$_SESSION)){
  header("Location: dashboard.php");
  exit;    
}
if (isset($_POST['submit'])) {
  try {
    if($connection!=null){
      $nickname = $_POST['nickname'];
      $pass = $_POST['password'];
      $stmt = $connection->prepare("SELECT * FROM USER WHERE nickname = :nickname  AND password_ = :pass"); 
      $stmt->execute(['nickname' => $nickname, 'pass' => $pass]); 
      
      $row = $stmt->fetch();

      if($row!=null){
        // start a session
        session_start();
        
        // initialize session variables
        $_SESSION['logged_in_user_id'] = $row['nickname'];
        $_SESSION['logged_in_user_rol'] = $row['rol'];
        echo "<script>localStorage.setItem('rol','".$row['rol']."');</script>";
        echo "<script>localStorage.setItem('nickname','".$row['nickname']."');</script>";
        echo "<script>location.href='dashboard.php';</script>";
      }
    }
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}
?>
 <body>
 
 <div class="container">
    <div class="row signin">
      <div class="col-sm-9 col-md-9 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Sign In</h5>
                <form class="form" role="form" autocomplete="off" method="post">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Nickname</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" name='nickname'>
                        </div> 
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Password</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="password" name='password' >
                        </div>
                    </div>
                    <center>
                            <input name="submit" type="submit" class="btn btn-primary" value="SignIn">
                    </center>
                </form>
          </div>
        </div>
      </div>
    </div>
  </div>
 
 </body>
</html>