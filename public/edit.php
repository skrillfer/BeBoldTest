<?php
session_start();
require "header.php";

if (isset($_POST['submit'])) {
    try {
      $employee =[
        "employee_id"        => $_POST['employee_id'],
        "firstname" => $_POST['firstname'],
        "lastname"  => $_POST['lastname'],
        "address"     => $_POST['address'],
        "telephone"       => $_POST['telephone'],
        "job"  => $_POST['job']
      ];
  
      $sql = "UPDATE EMPLOYEE
              SET employee_id = :employee_id,
                firstname = :firstname,
                lastname = :lastname,
                address = :address,
                telephone = :telephone,
                job = :job
              WHERE employee_id = :employee_id";
  
    $statement = $connection->prepare($sql);
    $statement->execute($employee);
    $_SESSION['message']= "Employee edited successfully!!!!";
    header("Location: dashboard.php");
    exit;
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

if (isset($_GET['employee_id_edit'])) {
    try {
      $id = $_GET['employee_id_edit'];
      $sql = "SELECT * FROM EMPLOYEE WHERE employee_id = :id";
      $statement = $connection->prepare($sql);
      $statement->bindValue(':id', $id);
      $statement->execute();
  
      $user = $statement->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>
<link rel="stylesheet" href="./styles/styles_dashboard.css" >
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">


<nav class="navbar navbar-expand-lg navbar-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="#"><h1>Bbold</h1></a>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <?php 
        $nickname=$_SESSION['logged_in_user_id'];
        if($nickname){
            echo "<strong class='span-welcome'>Bienvenido <small>".$nickname."</small></strong>";
        }
      ?>
    </ul>
    <form class="form-inline my-2 my-lg-0" >
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <input class="btn btn-danger" id='btnSignOut' name='btnSignOut'  type='submit' value='Cerrar Session'>
      </li>
    </ul>
    </form>
  </div>
</nav>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item "><a href="dashboard.php">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
  </ol>
</nav>
<div class="container">
    <hr/>
    <a class="btn btn-dark" href="dashboard.php"><i class="fas fa-arrow-circle-left"></i> Back to dashboard</a>
    <hr/>
    <!-- form user info -->
    <div class="card card-outline-secondary">
        <div class="card-header">
            <h3 class="mb-0">Edit Employee</h3>
        </div>
        <div class="card-body">
            
            <form class="form" role="form" autocomplete="off" method="post">
                <?php foreach ($user as $key => $value) : ?>
                <div class="form-group row">
                            
                    <label class="col-lg-3 col-form-label form-control-label" for="<?php echo $key; ?>"><?php echo ucfirst($key); ?></label>
                    <div class="col-lg-9">
                        <input class="form-control" type="text" name="<?php echo $key; ?>" id="<?php echo $key; ?>" value="<?php echo $value ?>" <?php echo ($key === 'employee_id' ? 'readonly' : null); ?>>
                    </div>
                </div>
                <?php endforeach; ?>
                <input class="btn btn-success" type="submit" name="submit" value="Update Employee">
            </form>
        </div>
    </div>
</div>