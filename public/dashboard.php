<?php
require "header.php";


?>

<link rel="stylesheet" href="./styles/styles_dashboard.css" >
  
<body>
<nav class="navbar navbar-expand-lg navbar-light">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="#"><h1>Bbold</h1></a>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      
    </ul>
    <form class="form-inline my-2 my-lg-0">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="#">Add <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Delete</a>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>
    </ul>
    </form>
  </div>
</nav>

 <div class="container">
    <br/>
    <div class="card custom-card">
        <div class="card-header backcolor">
            <div>
            <span class='_span_manage'>Manage </span>
            <span class='_span_manage_employees'> Employees</span>
            <div class="navbar-nav-buttons">
                <button>Delete</button>
                <button>Add New Employee</button>
            </div>
            
            </div>
            
        </div>
        <div class="card-body">
            <?php
                if($connection!=null){
                    $stmt = $connection->prepare("SELECT * FROM EMPLOYEE"); 
                    $stmt->execute(); 
                    $data = $stmt->fetchAll();
                    echo "<table id='my_table' class=\"stripe row-border \" cellspacing=\"0\" style=\"width:100%\">";
                        echo "<thead>";
                        $cols = ['','Firstname','Lastname','Address','Telephone','Job'];
                        echo '<tr>';
                        foreach ($cols as $col) echo '<th>' . $col . '</th>';
                        echo '</tr>';
                        echo "</thead>";
                        echo "<tbody>";
                        
                        foreach ($data as $row) {
                            echo '<tr>';
                            foreach ($cols as $colname){
                                if($colname!=''){
                                    echo '<td>' . $row[strtolower($colname)] . '</td>';
                                }else{
                                    echo '<td>'  . '</td>';
                                }
                                
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
  
  <script type='text/javascript'>
    $(document).ready( function () {
        // access session variables
        
        let optionsDatatable = {
            scrollY:        300,
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
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
        
        optionsDatatable= localStorage.getItem('rol')=='user'?{}:optionsDatatable;
       $('#my_table').DataTable( { ...optionsDatatable,     scrollX: true } );
       
    } );
  </script>
 </body>
 
</html>