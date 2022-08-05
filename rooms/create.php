<?php
    require_once "../database/config.php";
    require_once("../database/Query.php");

    if(!isset($_SESSION['name'])){
        header("Location: ../index.php");
    }
    $flag = 0;
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
    $name = $channel = $capacity = "";
    $name_err = $channel_err = $capacity_err = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //$flag = 1;
        
        if(empty($_POST["name"])){
            $name_err = "name is required.";
            $name = $_POST["name"];
        }
        else{
            $name = $_POST["name"];
            $name_err = "";
        }
        if(empty($_POST["capacity"])){
            $capacity_err = "name is required.";
            $capacity = $_POST["capacity"];
        }
        else{
            $capacity = $_POST["capacity"];
            $capacity_err = "";
        }
        if(empty($_POST["capacity"])){
            $capacity_err = "name is required.";
            $capacity = $_POST["capacity"];
        }
        else{
            $capacity = $_POST["capacity"];
            $capacity_err = "";
        }
        $channel = $_POST["channel"];
        if(empty($name_err) && empty($capacity_err)){
            $flag = 1;
            sqlInsertInto("rooms", "'','$channel','$name','$capacity'");
            $_SESSION['status'] = "room successfully created";
            header("Location: ../events/detail.php?event_id=$event_id");
            //$event_name = $special_validity;

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
                    <li class="nav-item"><a class="nav-link" href="events/index.php">Manage Events</a></li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span><?php echo $event_name?></span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="events/detail.php?event_id=<?php echo $event_id; ?>">Overview</a></li>
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
                    <h1 class="h2"><?php echo $event_name?></h1>
                </div>
                <span class="h6"><?php echo $event_date?></span>
            </div>

            <div class="mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h2 class="h4">Create new room</h2>
                </div>
            </div>

            <form class="needs-validation" novalidate action="" method="post">

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputName">Name</label>
                        <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                        <input type="text" class="form-control <?php if(!empty($name_err)){ echo 'is-invalid';} ?>" id="inputName" name="name" placeholder="" value="">
                        <div class="invalid-feedback">
                            <?php  echo $name_err;?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="selectChannel">Channel</label>
                        <select class="form-control" id="selectChannel" name="channel">
                            <?php 
                                $sql = sqlSelectReturnQuery("channels.id, channels.name","channels","channels.event_id=$event_id");
                                $i=1;
                                while($row = mysqli_fetch_assoc($sql)):
                                    ?>
                                    <option value="<?php echo $row['id']; ?>" <?php if($channel==$i){ echo "selected";} ?>><?php echo $row['name'];?></option>
                                    <?php
                                    $i++;
                                endwhile;
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputCapacity">Capacity</label>
                        <input type="number" class="form-control <?php if(!empty($capacity_err)){ echo 'is-invalid';} ?>" id="inputCapacity" name="capacity" placeholder="" value="">
                        <div class="invalid-feedback">
                            <?php  echo $capacity_err;?>
                        </div>
                    </div>
                </div>

                <hr class="mb-4">
                <button class="btn btn-primary" type="submit">Save room</button>
                <a href="events/detail.php?event_id=<?php echo $event_id?>" class="btn btn-link">Cancel</a>
            </form>

        </main>
    </div>
</div>

</body>
</html>
