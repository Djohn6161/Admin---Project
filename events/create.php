<?php
require_once "../database/config.php";
require_once("../database/Query.php");

if(!isset($_SESSION['name'])){
    header("Location: ../index.php");
  }
  $event_date = $event_name = "";
    $org_id = $_SESSION['org_id'];
    if(isset($_GET['event_id'])){
        $event_id = $_GET['event_id'];
        $row = sqlSelect("*","events","id = '$event_id'"); 
        $event_name = $row['name'];
        $event_date = $row['date'];
        if($row['organizer_id']!=$org_id){
            header("Location: ../events/index.php");
        }
    }
  $flag = 0;
  $org_id = $_SESSION['org_id'];

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //$flag = 1;
    $name = $slug = $date = "";
    $name_err = $slug_err = $date_err = "";

    if(empty($_POST["name"])){
        $name_err = "Name is required.";
    }
    else{
        $name = $_POST["name"];
        $name_err = "";
    }
    if(empty($_POST["slug"])){
        $slug_err = "Slug must not be empty and only contain a-z, 0-9 and '-'";
    }
    else{
        $sql = sqlSelectReturnQuery("*","events", "1=1");
        while($row = mysqli_fetch_assoc($sql)){
            if($row['slug']!=$_POST["slug"]){
                $slug_err = "";
                $slug = $_POST["slug"];
            }
            else{
                $slug_err = "Slug is already used";
                break;
            }
        }
        
        
    }
    if(empty($_POST["date"])){
        $date_err = "Date is empty";
    }
    else{
        $date_err = "";
        $date = $_POST["date"];
    }
    if(empty($name_err) && empty($slug_err) && empty($date_err)){
        $flag = 1;
        sqlInsertInto("events", "'','$org_id','$name', '$slug', '$date'");
        $row = sqlSelect("*","events","name = '$name' and slug = '$slug'"); 
        $_SESSION['status'] = "Event successfully created";
        header("Location: ../events/detail.php?event_id=$row[id]");
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Event Backend</title>

    <base href="../">
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="assets/css/custom.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="events/index.php">Event Platform</a>
    <span class="navbar-organizer w-100">WorldSkills</span>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" id="logout" href="logout.php">Sign out</a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="events/index.php">Manage Events</a></li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Events</h1>
            </div>

            <div class="mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h2 class="h4">Create new event</h2>
                </div>
            </div>

            <form class="needs-validation" novalidate action='' method="post" >

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputName">Name</label>
                        <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                        <input type="text" class="form-control <?php if(!empty($name_err)){ echo 'is-invalid';} ?>" id="inputName" name="name" placeholder="" value="">
                        <div class="invalid-feedback">
                            <?php 
                                if(!empty($name_err)){
                                    echo '<div class="alert alert-danger">' . $name_err . '</div>';
                                }        
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputSlug">Slug</label>
                        <input type="text" class="form-control <?php if(!empty($slug_err)){ echo 'is-invalid';} ?>" id="inputSlug" name="slug" placeholder="" value="">
                        <div class="invalid-feedback">
                            <?php 
                                if(!empty($slug_err)){
                                    echo '<div class="alert alert-danger">' . $slug_err . '</div>';
                                }        
                            ?>
                        </div> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputDate">Date</label>
                        <input type="date"
                               class="form-control"
                               id="inputDate"
                               name="date"
                               placeholder="yyyy-mm-dd"
                               value="">
                            <?php 
                            if(!empty($date_err)){
                                echo '<div class="alert alert-danger">' . $date_err . '</div>';
                            }        
                        ?>
                    </div>
                </div>

                <hr class="mb-4">
                <button class="btn btn-primary" type="submit">Save event</button>
                <a href="events/index.php" class="btn btn-link">Cancel</a>
            </form>
        </main>
    </div>
</div>

</body>
</html>
