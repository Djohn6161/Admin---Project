<?php
require_once "../database/config.php";
require_once("../database/Query.php");
if(!isset($_SESSION['name'])){
    header("Location: ../index.php");
  }
  $org_id = $_SESSION['org_id'];
  
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
                    <li class="nav-item"><a class="nav-link active" href="events/index.php?event_id=<?php echo $event_id; ?>">Manage Events</a></li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Events</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="events/create.php" class="btn btn-sm btn-outline-secondary">Create new event</a>
                    </div>
                </div>
            </div>
            <?php
                $sql = sqlSelectReturnQuery("*","events","organizer_id='$org_id'");
                $column_num = 3;
                $i = $column_num;
                $num_rows = mysqli_num_rows($sql) + $column_num;
                $flag = false;
                while($row = mysqli_fetch_assoc($sql)):
                    $x = $i % $column_num;
                    if($x==0){
                        echo "<div class='row events'>";
                        //print_r($num_registration);
                        $j = $i + $column_num;
                    }
                    $i++;
                    ?>
                                <div class="col-md-4">
                                    <div class="card mb-4 shadow-sm">
                                        <a href="events/detail.php?event_id=<?php echo $row['id']?>" class="btn text-left event">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                                <p class="card-subtitle"><?php echo $row['date']; ?></p>
                                                <hr>
                                                <p class="card-text">
                                                    <script>
                                                        console.log(<?php echo $org_id;?>);
                                                    </script>
                                                    <?php
                                                    
                                                    
                                                    $sql2 = sqlSelectReturnQuery("*","events, event_tickets, registrations","events.id = event_tickets.event_id AND event_tickets.id = registrations.ticket_id AND events.organizer_id='$org_id' AND events.id = '$row[id]'");
                                                    $num_registration = mysqli_num_rows($sql2);
                                                    ?>
                                                    <script>
                                                        console.log(<?php echo $num_registration;?>);
                                                    </script>
                                                    <?php
                                                    echo $num_registration . " Registrations";
                                                    ?></p>
                                            </div>
                                        </a>
                                    </div>
                                </div>  
                            <?php  
                            if($i==$j || $i == $num_rows){
                            echo "</div>";
                            $flag = false;
                        }  
                endwhile;
            ?>
        </main>
    </div>
</div>

</body>
</html>
