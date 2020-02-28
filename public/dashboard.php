
<?php
session_start();
require "header.php";
$user =  [];
echo "<script>localStorage.removeItem('edit_employee',false);</script>";
if(!array_key_exists("logged_in_user_id",$_SESSION)){
    header("Location: index.php");
    exit;    
}

if(array_key_exists("message",$_SESSION)){
    if($_SESSION["message"]!=""){
        echo "<script>alert('".$_SESSION['message']."');</script>";
        $_SESSION['message']="";
    }
}

if (isset($_POST['submit'])) {
    try {
      if($connection!=null){
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $address = $_POST['address'];
        $telephone = $_POST['telephone'];
        $job = $_POST['job'];

        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO EMPLOYEE (employee_id,firstname, lastname, address,telephone,job) VALUES (0,'$firstname', '$lastname', '$address','$telephone','$job')";
        // use exec() because no results are returned
        $connection->exec($sql);
        $_SESSION['message']= "Employee added successfully!!!!";
        header("Location: dashboard.php");
        exit;
      }
    } catch(PDOException $error) {
        echo "<script>alert('Error ".$error->getMessage()."');</script>";
    }
}

if (isset($_GET["employee_id"])) {
    try {
        
      $id = $_GET["employee_id"];
      $sql = "DELETE FROM EMPLOYEE WHERE employee_id = :id";

      $statement = $connection->prepare($sql);
      $statement->bindValue(':id', $id);
      $statement->execute();
      $_SESSION['message']= "Employee deleted successfully!!!!";
      header("Location: dashboard.php");
      exit;

    } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
    }
}

if(isset($_POST['btnSignOut'])) { 
    session_destroy();
    echo "<script>localStorage.removeItem('rol','".$row['rol']."');</script>";
    echo "<script>localStorage.revemoItem('nickname','".$row['nickname']."');</script>";    
    header("Location: index.php");
    exit;
} 



?>

<link rel="stylesheet" href="./styles/styles_dashboard.css" >
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

   
<body>
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
    <form class="form-inline my-2 my-lg-0" method="post">
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
    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
  </ol>
</nav>

 <div class="container">
    <br/>
    <div class="card custom-card">
        <div class="card-header backcolor">
            <span class='_span_manage'>Manage </span>
            <span class='_span_manage_employees'> Employees</span>
            <?php
                $rol=$_SESSION['logged_in_user_rol'];
                if($rol=='admin'){
                    echo '<div class="navbar-nav-buttons">';
                    echo '<button type="button" class="btn btn-danger" id="delete_selected" >Delete </button>&nbsp;&nbsp;';
                    echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Add Employee</button>';
                    echo '</div>';
                }                
            ?>
            
            
        </div>
        <div class="card-body">
            <?php
                if($connection!=null){
                    $rol=$_SESSION['logged_in_user_rol'];
                    $stmt = $connection->prepare("SELECT * FROM EMPLOYEE"); 
                    $stmt->execute(); 
                    $data = $stmt->fetchAll();  
                    echo "<table id='my_table' class=\"stripe row-border \" cellspacing=\"0\" style=\"width:100%\">";
                        echo "<thead>";
                        $cols = ['',"employee_id",'Firstname','Lastname','Address','Telephone','Job','#Actions'];
                        if($rol!='admin'){
                            $cols = ['','Firstname','Lastname','Address','Telephone','Job'];
                        }
                        echo '<tr>';
                        foreach ($cols as $col) echo '<th>' . ($col === 'employee_id' ? 'id' : $col). '</th>';
                        echo '</tr>';
                        echo "</thead>";
                        echo "<tbody>";
                        
                        foreach ($data as $row) {
                            echo '<tr>';
                            foreach ($cols as $colname){
                                if($colname!=''){
                                    if($colname!='#Actions'){
                                        echo '<td>' . $row[strtolower($colname)] . '</td>';
                                    }
                                }else{
                                    echo '<td>'  . '</td>';
                                }
                                
                            }
                            if($rol=='admin'){
                                echo '<td><a type="button" href="edit.php?employee_id_edit='.$row["employee_id"].'"  class="btn btn-warning" ><i class="fas fa-pencil-alt"></i></a>'; 
                                echo '&nbsp;&nbsp;<a   class="btn btn-danger"  href="dashboard.php?employee_id='.$row["employee_id"].'"><i class="fas fa-trash-alt"></i></a></td>'; 
                            }                
                        
                            
                            echo '</tr>';
                        }
                        
                        echo "</tbody>";
                    echo "</table>";
                    
                }
            ?>    
        </div>
    </div>
    
  </div>
  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        
        <!-- Modal body -->
        <div class="modal-body">
          <!-- form user info -->
          <div class="card card-outline-secondary">
            <div class="card-header">
                <h3 class="mb-0">Create New Employee</h3>
            </div>
            <div class="card-body">
                <form class="form" role="form" autocomplete="off" method="post">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">First name</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" name='firstname'>
                        </div> 
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Last name</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" name='lastname' >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Address</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" name='address' >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Telephone</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" name='telephone' >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label">Job</label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" name='job' >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label form-control-label"></label>
                        <div class="col-lg-9">
                            <input name="submit" type="submit" class="btn btn-primary" value="Create Employee">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /form user info -->
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  <!-- End The Modal -->
  
  <script type='text/javascript'>
    $(document).ready( function () {
        // access session variables
        
        let optionsDatatable = {
            scrollY:        true,
            scrollX:        true,
            //scrollCollapse: true,
            fixedColumns:   {
                leftColumns: 2
            },
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   0
            } ],
            select: {
                style:    'multi'
            },
            order: [[ 1, 'asc' ]]
        };
        
        optionsDatatable= localStorage.getItem('rol')=='user'?{"columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": false
            }
        ]}:optionsDatatable;
       let data_table=$('#my_table').DataTable( { ...optionsDatatable,     scrollX: true } );
       
       $('#delete_selected').click(function() {
            var rows_selected = data_table.rows('.selected').data();
            let array_id=[]
            $.each(rows_selected, function( index, value ) {
                array_id.push(parseInt(value[1]));
            });
            /*$.post("dashboard.php",
            {
                array_employee_id: array_id
            },
            function(data, status){
                console.log("Data: " + data + "\nStatus: " + status);
            });*/
            //var jsonString = JSON.stringify(array_id);
            $.post( "./dashboard.php",{'remix' : 'vaco'},  function( data ) {
                alert( "Load was performed." );
            });/*
            $.ajax({
                type: "GET",
                url: "dashboard.php",
                dataType: "json",

                data: {'arr_employee_id' : jsonString}, 
                success: function(){
                    console.log("OK");
                }
            }).done(function(response){
                console.log('finished');
            });*/
       });
       if(localStorage.getItem('edit_employee')){
            $('#myModal').modal('toggle');
       }
       
    } );
  </script>
 </body>
 
</html>