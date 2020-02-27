<?php
require "header.php";

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
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Sign In</h5>
            <form class="form-signin" method="post" >
              <div class="form-label-group">
                <input type="text" id="nickname" name="nickname" class="form-control" placeholder="Email address" required autofocus>
                <label for="inputEmail">Nickname</label>
              </div>

              <div class="form-label-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                <label for="inputPassword">Password</label>
              </div>
              <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="submit"  value="Sign in"/>
              <hr class="my-4">
              
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
 
 </body>
</html>