<?php
require_once "../database/config.php";
require_once("../database/Query.php");

if(!isset($_SESSION['name'])){
    header("Location: ../index.php");
  }
    $event_date = $event_name = $event_slug = "";
    $org_id = $_SESSION['org_id'];

    if(isset($_GET['event_id'])){
        $event_id = $_GET['event_id'];
        $row = sqlSelect("*","events","id = '$event_id'"); 
        $event_name = $row['name'];
        $event_date = $row['date'];
        $event_slug = $row['slug'];
        if($row['organizer_id']!=$org_id){
            header("Location: ../events/index.php");
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $name = $_POST["name"];
        $slug = $_POST["slug"];
        $date = $_POST["date"];

        sqlUpdate("events", "name = '$name', slug = '$slug', date = '$date'", "id=$event_id");

        $row = sqlSelect("*","events","name = '$name' and slug = '$slug'"); 
        $_SESSION['status'] = "Event successfully updated";
        header("Location: ../events/detail.php?event_id=$row[id]");
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
                    <li class="nav-item"><a class="nav-link" href="events/index.php">Manage Events</a></li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span><?php echo $event_name;?></span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="events/detail.php?event_id=<?php echo $event_id; ?>">Overview</a></li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Reports</span>
                </h6>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item"><a class="nav-link" href="reports/index.php?event_id=<?php echo $event_id; ?>">Room capacity</a></li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="border-bottom mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h1 class="h2"><?php echo $event_name;?></h1>
                </div>
            </div>

            <form class="needs-validation" novalidate action="" method="post">

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputName">Name</label>
                        <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                        <input type="text" class="form-control <?php if(!empty($name_err)){ echo 'is-invalid';} ?>" name="name" id="inputName" placeholder="" value="<?php echo $event_name;?>">
                        <div class="invalid-feedback">
                            Name is required.
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputSlug">Slug</label>
                        <input type="text" class="form-control <?php if(!empty($name_err)){ echo 'is-invalid';} ?>" name="slug" id="inputSlug" placeholder="" value="<?php echo $event_slug;?>">
                        <div class="invalid-feedback">
                            Slug must not be empty and only contain a-z, 0-9 and '-'
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
                               placeholder=""
                               value="<?php echo $event_date;?>">
                    </div>
                </div>

                <hr class="mb-4">
                <button class="btn btn-primary" type="submit">Save</button>
                <a href="events/detail.php?event_id=<?php echo $event_id;?>" class="btn btn-link">Cancel</a>
            </form>

        </main>
    </div>
</div>

</body>
</html>
