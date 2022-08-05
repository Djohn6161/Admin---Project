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
            <div class="border-bottom mb-3 pt-3 pb-2 event-title">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h1 class="h2"><?php echo $event_name;?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <a href="events/edit.php?event_id=<?php echo $event_id;?>" class="btn btn-sm btn-outline-secondary">Edit event</a>
                        </div>
                    </div>
                </div>
                <span class="h6"><?php echo $event_date; ?></span>
                <?php if(isset($_SESSION['status'])){
                    ?>
                <div class="alert alert-success">
                    <strong>Success!</strong> <?php echo $_SESSION['status'];?>
                </div>
                <?php unset($_SESSION['status']); 
            }?>
            </div>

            <!-- Tickets -->
            <div id="tickets" class="mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h2 class="h4">Tickets</h2>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <a href="tickets/create.php?event_id=<?php echo $event_id;?>" class="btn btn-sm btn-outline-secondary">
                                Create new ticket
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                $sql = sqlSelectReturnQuery("*","event_tickets","event_id='$event_id'");
                $column_num = 3;
                $i = $column_num;
                $num_rows = mysqli_num_rows($sql) + $column_num;
                $flag = false;
                while($row = mysqli_fetch_assoc($sql)):
                    $x = $i % $column_num;
                    if($x==0){
                        echo "<div class='row tickets'>";
                        $j = $i + $column_num;
                    }
                    $i++;
                    ?>
                                <div class="col-md-4">
                                    <div class="card mb-4 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $row['name'];?></h5>
                                            <p class="card-text"><?php echo "COST: " . $row['cost'];?></p>
                                            <p class="card-text">
                                                <?php
                                                    $amount_check = strpos($row['special_validity'],"amount");
                                                    $date_check = strpos($row['special_validity'],"date");
                                                    if(!empty($amount_check)){
                                                        $tamount = $row['special_validity'];
                                                        $amlen = strlen($tamount);
                                                        $tamount = substr($row['special_validity'], 26);
                                                        $tamount = rtrim($tamount,"}");
                                                        echo $tamount . " tickets available";
                                                    }
                                                    else if(!empty($date_check)){
                                                        $input = substr($row['special_validity'], 23,10);
                                                        $input = strtotime($input);
                                                        $input = date('M d, Y',$input);
                                                        echo "Available until " . $input;
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div> 
                            <?php  
                        if($i==$j || $i == $num_rows){
                        echo "</div>";
                        $flag = false;
                    }  
                endwhile;
            ?>

            <!-- Sessions -->
            <div id="sessions" class="mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h2 class="h4">Sessions</h2>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <a href="sessions/create.php?event_id=<?php echo $event_id; ?>" class="btn btn-sm btn-outline-secondary">
                                Create new session
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive sessions">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Time</th>
                        <th>Type</th>
                        <th class="w-100">Title</th>
                        <th>Speaker</th>
                        <th>Room</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sql = sqlSelectReturnQuery("s.start, s.end, s.type, s.title, s.speaker, r.name, s.id ","sessions AS s, rooms as r, channels as c, events as e","s.room_id = r.id AND c.id = r.channel_id AND e.id = c.event_id AND e.id = $event_id");
                        $i=0;
                        while($row = mysqli_fetch_assoc($sql)):

                            $tstart = $row['start'];
                            $tstart = substr($tstart, 11);
                            $tend = $row['end'];
                            $tend = substr($row['end'], 11);

                            $i++;
                           echo" <tr>";
                           echo"    <td class='text-nowrap'>" . 
                                        $tstart . " - " . $tend . 
                                    "</td>";
                           echo"    <td>" . $row['type'] ."</td>";
                           echo"    <td><a href='sessions/edit.php?session_id=" . $row['id'] . "&event_id=".$event_id."'>" . $row['title'] . "</a></td>";
                           echo"    <td class='text-nowrap'>" . $row['speaker'] . "</td>";
                           echo"    <td class='text-nowrap'>".$row['name']."</td>";
                           echo" </tr>";
                        
                        endwhile;
                        
                           
                    ?>
                    </tbody>
                </table>
            </div>

            <!-- Channels -->
            <div id="channels" class="mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h2 class="h4">Channels</h2>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <a href="channels/create.php?event_id=<?php echo $event_id; ?>" class="btn btn-sm btn-outline-secondary">
                                Create new channel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                $sql = sqlSelectReturnQuery(" channels.id, channels.name","channels, events","events.id = channels.event_id AND events.id='$event_id'");
                $column_num = 3;
                $i = $column_num;
                $num_rows = mysqli_num_rows($sql) + $column_num;
                $flag = false;
                while($row = mysqli_fetch_assoc($sql)):
                    $x = $i % $column_num;
                    if($x==0){
                        echo "<div class='row channels'>";
                        $j = $i + $column_num;
                        //print_r("row");
                    }
                    $i++;
                    ?>
                        <div class="col-md-4">
                            <div class="card mb-4 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $row['name'];?></h5>
                                    <p class="card-text">
                                        <?php
                                        $channel_id = $row['id'];
                                        $sqlS = sqlSelectReturnQuery("sessions.id","sessions, events, rooms, channels","events.id = channels.event_id AND channels.id = rooms.channel_id AND rooms.id = sessions.room_id AND events.id='$event_id' AND channels.id = '$channel_id'");
                                        $num_Srows = mysqli_num_rows($sqlS);
                                        $sqlR = sqlSelectReturnQuery("rooms.id","events, rooms, channels","events.id = channels.event_id AND channels.id = rooms.channel_id AND events.id='$event_id' AND channels.id = '$channel_id'");
                                        $num_Rrows = mysqli_num_rows($sqlR);
                                        echo $num_Srows . ' sessions, ' . $num_Rrows .' room';
                                        ?>
                                   </p>
                                </div>
                            </div>
                        </div>
                    <?php  
                        if($i==$j || $i == $num_rows){
                        echo "</div>";
                        $flag = false;
                    }  
                endwhile;
            ?>
            <!-- Rooms -->
            <div id="rooms" class="mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h2 class="h4">Rooms</h2>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <a href="rooms/create.php?event_id=<?php echo $event_id; ?>" class="btn btn-sm btn-outline-secondary">
                                Create new room
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive rooms">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Capacity</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $sql = sqlSelectReturnQuery("r.name, r.capacity","rooms as r, channels as c, events as e","c.id = r.channel_id AND e.id = c.event_id AND e.id = $event_id");
                        $i=0;
                        while($row = mysqli_fetch_assoc($sql)):
                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $row['name'];?></td>
                                <td><?php echo $row['capacity'];?></td>
                            </tr>
                        <?php
                        endwhile;
                    ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

</body>
</html>
