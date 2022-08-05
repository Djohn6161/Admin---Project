<?php
require_once "../database/config.php";
require_once("../database/Query.php");
if(!isset($_SESSION['name'])){
    header("Location: ../index.php");
  }
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
    else{
        $row = sqlSelect("*","events","organizer_id = '$org_id'");
        if(!isset($row['id'])){
            $event_id = "";
            $event_name = "";
            $event_date = "Sorry you currently have no events";
        }
        else{
            $event_id = $row['id'];
            $event_name = $row['name'];
            $event_date = $row['date'];
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
    <script src="chart.js"></script>
    <script src="chart.bundle.js"></script>
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
                    <span><?php echo $event_name; ?></span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="events/detail.php?event_id=<?php echo $event_id; ?>">Overview</a></li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Reports</span>
                </h6>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item"><a class="nav-link active" href="reports/index.php?event_id=<?php echo $event_id; ?>">Room capacity</a></li>
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="border-bottom mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h1 class="h2"><?php echo $event_name; ?></h1>
                </div>
                <span class="h6"><?php echo $event_date; ?></span>
            </div>

            <div class="mb-3 pt-3 pb-2">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h2 class="h4">Room Capacity</h2>
                </div>
            </div>

            <div class="container">
                <canvas id="myChart"></canvas>
            </div>

        </main>
    </div>
</div>
<script>
    let myChart = document.getElementById('myChart').getContext('2d');

    Chart.defaults.global.defaultFontFamily = 'Lato';
    Chart.defaults.global.defaultFontColor = '#777';
    Chart.defaults.global.defaultFontSize = 18;

    let massPopChart = new Chart(myChart, {
        type: 'bar', 
        data:{
            labels:[
                <?php
                    $sql = sqlSelectReturnQuery("sessions.title, count(session_registrations.id) as Counter","session_registrations join sessions on session_registrations.session_id = sessions.id join rooms on sessions.room_id = rooms.id join channels on rooms.channel_id = channels.id","channels.event_id = '$event_id' group by session_registrations.session_id");
                    while($row = mysqli_fetch_assoc($sql)):
                ?>
                '<?php echo $row['title'];?>',
                
                <?php
                    endwhile;
                ?>
                ],
            datasets:[{
                label:['Atendees'],
                data:[
                    <?php
                        $sql = sqlSelectReturnQuery("sessions.title, count(session_registrations.id) as Counter, capacity","session_registrations join sessions on session_registrations.session_id = sessions.id join rooms on sessions.room_id = rooms.id join channels on rooms.channel_id = channels.id","channels.event_id = '$event_id' group by session_registrations.session_id");
                        while($row = mysqli_fetch_assoc($sql)):
                    
                        echo $row['Counter'] . ", ";
                        //if($row['Counter']>$row)
                        endwhile;
                    ?>
                ],
                backgroundColor:[ 
                    <?php
                        $sql = sqlSelectReturnQuery("sessions.title, count(session_registrations.id) as Counter, capacity","session_registrations join sessions on session_registrations.session_id = sessions.id join rooms on sessions.room_id = rooms.id join channels on rooms.channel_id = channels.id","channels.event_id = '$event_id' group by session_registrations.session_id");
                        while($row = mysqli_fetch_assoc($sql)):

                        if($row['Counter']<$row['capacity'])    
                            echo "'green', ";
                        else
                            echo "'red', ";
                        //
                        endwhile;
                    ?>
                    // '#8fdb96', 'red'
                ],
                borderWidth:1,
                borderColor:'black',
                hoverBorderWidth:3,
                hoverBorderColor:'gray'

            },
            {
                label:['capacity'],
                data:[
                     <?php
                        $sql = sqlSelectReturnQuery("sessions.title, count(session_registrations.id) as Counter, capacity","session_registrations join sessions on session_registrations.session_id = sessions.id join rooms on sessions.room_id = rooms.id join channels on rooms.channel_id = channels.id","channels.event_id = '$event_id' group by session_registrations.session_id");
                        while($row = mysqli_fetch_assoc($sql)):
                    
                       echo $row['capacity'] . ", ";
                    
                        endwhile;
                    ?>
                ],
                 backgroundColor: '#add8e6',
                borderWidth:1,
                borderColor:'black',
                hoverBorderWidth:3,
                hoverBorderColor:'gray'

            },
        ]},
        options:{
            title:{
                display:true,
                fontSize:25
            },
            legend:{
                position:'left',
                labels:{
                    fontColor:'#000'
                }

            },
            layout:{
                padding:{
                    left:0,
                    top:0,
                    right:0,
                    left:0
                }
            },
            tooltips:{
                enabled:true,
            }
        
        }
    });
</script>
</body>
</html>
