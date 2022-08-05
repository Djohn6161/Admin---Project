<?php
    require_once "../database/config.php";
    require_once("../database/Query.php");

    if(!isset($_SESSION['name'])){
        header("Location: ../index.php");
    }
    $flag = false;
    $org_id = $_SESSION['org_id'];
    $event_id = $_GET['event_id'];
    $title = $type = $speaker = $room = $cost = $start = $end = $description = "";
    $title_err = $type_err = $speaker_err = $room_err = $cost_err = $start = $end = $description = "";
    
    if(isset($_GET['event_id'])){
        $event_id = $_GET['event_id'];
        $row = sqlSelect("*","events","id = '$event_id'"); 
        $event_name = $row['name'];
        $event_date = $row['date'];
        if($row['organizer_id']!=$org_id){
            header("Location: ../events/index.php");
        }
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //$flag = 1;
        
        if(empty($_POST["type"])){
            $type_err = "type is required.";
            $type = $_POST["type"];
        }
        else{
            $type = $_POST["type"];
            $type_err = "";
        }

        if(empty($_POST["title"])){
            $title_err = "title is required";
            $title = $_POST["title"];

        }
        else{
            $title_err = "";
            $title = $_POST["title"];
        }

        if(empty($_POST["speaker"])){
            $speaker_err = "speaker is required";
            $spearker_err = "";
        }
        else{
            $spearker_err = "";
            $speaker = $_POST["speaker"];
        }

        if(empty($_POST["room"])){
            $room_err = "room is required";
            $room = $_POST["room"];
        }
        else{
            $room_err = "";
            $room = $_POST["room"];
        }

        if(empty($_POST["cost"])){
            $cost_err = "";
        }
        else{
            $cost_err = "";
            $cost = $_POST["cost"];
        }

        if(empty($_POST["start"])){
            $start_err = "start of time is required";
            $start = $_POST["start"];

        }
        else{
            $start_err = "";
            $start = $_POST["start"];
        }

        if(empty($_POST["end"])){
            $end_err = "end of time is required";
            $end = $_POST["end"];
        }
        else{
            $end_err = "";
            $end = $_POST["end"];
        }

        if(empty($_POST["description"])){
            $description_err = "description is required";
            $description = $_POST["description"];
        }
        else{
            $description_err = "";
            $description = $_POST["description"];
        }
        if($start >= $end){
            $end_err = "Wrong Input";
            $start_err = "Wrong input";
        }
        if((empty($type_err) && empty($title_err) && empty($speaker_err)) && (empty($room_err)&&empty($cost_err)&&empty($start_err))&&(empty($end_err)&&empty($description_err))){
            // $start = date_create_from_format("Y-m-d H:i:s","2019-09-23 09:00:00");
            // $start = date_format($date,"m/d/Y H:i:s");
            // $_SESSION['status'] = "Room already Booked this time " . $start;
            // $start = strtotime($start);
            // $end = strtotime($end);
            $sql = sqlSelectReturnQuery("sessions.start, sessions.end","rooms, sessions","rooms.id=sessions.room_id AND rooms.id = '$room' OR '$start' >= sessions.start AND '$end' <= sessions.end");
            $i=0;
            $num_rows = mysqli_num_rows($sql);
           
            if($num_rows>0){
                $_SESSION['status'] = "Room already Booked this time ";
                $start_err = "Room already Booked this time ";
                $end_err = "Room already Booked this time ";
            }
            else{
            $_SESSION['status'] = "Session successfully created ";
            sqlInsertInto("sessions", "'','$room','$title', '$description', '$speaker', '$start', '$end', '$type', '$cost'");
            header("Location: ../events/detail.php?event_id=$event_id");    
            }
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
                    <span><?php echo $event_name;?></span>
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
                    <h1 class="h2"><?php echo $event_name;?></h1>
                </div>
                <span class="h6"><?php echo $event_date;?></span>
                <?php if(isset($_SESSION['status'])){
                    ?>
                    <div class="alert alert-danger">
                        <strong>Failed!</strong> <?php echo $_SESSION['status'];?>
                    </div>
                    <?php unset($_SESSION['status']); 
                }?>
            </div>

            <div class="mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h2 class="h4">Create new session</h2>
                </div>
            </div>

            <form class="needs-validation" novalidate action="" method="post">
                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="selectType">Type</label>
                        <select class="form-control" id="selectType" name="type" value="">
                            <option value="talk" <?php if($type=="talk"){ echo "selected";} ?>>Talk</option>
                            <option value="workshop" <?php if($type=="workshop"){ echo "selected";} ?>>Workshop</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo $type_err; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputTitle">Title</label>
                        <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                        <input type="text" class="form-control <?php if(!empty($title_err)){ echo 'is-invalid';} ?>" id="inputTitle" name="title" placeholder="" value="<?php if(!empty($title)){ echo $title;} ?>">
                        <div class="invalid-feedback">
                        <?php echo $title_err; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputSpeaker">Speaker</label>
                        <input type="text" class="form-control <?php if(!empty($speaker_err)){ echo 'is-invalid';} ?>" id="inputSpeaker" name="speaker" placeholder="" value="<?php if(!empty($speaker)){ echo $speaker;} ?>">
                        <div class="invalid-feedback">
                        <?php echo $speaker_err; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="selectRoom">Room</label>
                        <select class="form-control" id="selectRoom" name="room">
                        <?php 
                        $sql = sqlSelectReturnQuery("rooms.id, rooms.name","rooms,channels","rooms.channel_id = channels.id AND channels.event_id=$event_id");
                        $i=1;
                        while($row = mysqli_fetch_assoc($sql)):
                            ?>
                            <option value="<?php echo $row['id']; ?>" <?php if($room==$i){ echo "selected";} ?>><?php echo $row['name'];?></option>
                            <?php
                            $i++;
                        endwhile;
                            ?>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo $room_err; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputCost">Cost</label>
                        <input type="number" class="form-control <?php if(!empty($cost_err)){ echo 'is-invalid';} ?>" id="inputCost" name="cost" placeholder="" value="<?php if(!empty($cost)){ echo $cost;} ?>">
                        <div class="invalid-feedback">
                            <?php echo $cost_err; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <label for="inputStart">Start</label>
                        <input type="text"
                               class="form-control <?php if(!empty($start_err)){ echo 'is-invalid';} ?>"
                               id="inputStart"
                               name="start"
                               placeholder="yyyy-mm-dd HH:MM:SS"
                               value="<?php if(!empty($start)){ echo $start;} ?>">
                               <div class="invalid-feedback">
                               <?php echo $start_err; ?>
                                </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-3">
                        <label for="inputEnd">End</label>
                        <input type="text"
                               class="form-control <?php if(!empty($end_err)){ echo 'is-invalid';} ?>"
                               id="inputEnd"
                               name="end"
                               placeholder="yyyy-mm-dd HH:MM:SS"
                               value="<?php if(!empty($end)){ echo $end;} ?>">
                        <div class="invalid-feedback">
                            <?php echo $end_err; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="textareaDescription">Description</label>
                        <textarea class="form-control <?php if(!empty($description_err)){ echo 'is-invalid';} ?>" id="textareaDescription" name="description" placeholder="" rows="5"><?php if(!empty($description)){ echo $description;} ?></textarea>
                        <div class="invalid-feedback">
                            <?php echo $description_err; ?>
                        </div>
                    </div>
                </div>

                <hr class="mb-4">
                <button class="btn btn-primary" type="submit">Save session</button>
                <a href="events/detail.php" class="btn btn-link">Cancel</a>
            </form>

        </main>
    </div>
</div>

</body>
</html>
