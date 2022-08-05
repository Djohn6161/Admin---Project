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

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //$flag = 1;
        $name = $cost = $special_validity = $valid_until = $amount = "";
        $name_err = $cost_err = $special_validity_err = $valid_until_err = $amount_err = "";
    
        if(empty($_POST["name"])){
            $name_err = "Name is required.";
        }
        else{
            $name = $_POST["name"];
            $name_err = "";
        }
        if(empty($_POST["cost"])){
            $cost_err = "Cost is required";
        }
        else{
            $cost_err = "";
            $cost = $_POST["cost"];
            
            
        }
        if(empty($_POST["special_validity"])){
            $special_validity_err = "special is empty";
        }
        else{
            $special_validity_err = "";
            $special_validity = $_POST["special_validity"];
            
            if($_POST["special_validity"]=="amount"){
                if(empty($_POST["amount"])){
                    $amount_err = "amount is empty";
                }
                else{
                    $amount_err = "";
                    $amount = $_POST["amount"];
                }
            }
            else if($_POST["special_validity"]=="date"){
                if(empty($_POST["valid_until"])){
                    $valid_until_err = "Date is empty";
                }
                else{
                    $valid_until_err = "";
                    $valid_until = $_POST["valid_until"];    
                }
            }
            
        }
        if((empty($name_err) && empty($cost_err) && empty($valid_until_err)) && (empty($special_validity_err)&&empty($amount_err))){
            $flag = 1;
            if($special_validity=="date"){
                $special_validity = '{"type":"'.$special_validity.'","date":"'.$valid_until.'"}';
            }
            else if($special_validity=="amount"){
                $special_validity = '{"type":"'.$special_validity.'","amount":'.$amount.'}';
            }
            
            sqlInsertInto("event_tickets", "'','$event_id','$name', '$cost', '$special_validity'");
            $_SESSION['status'] = "Ticket successfully created";
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
                    <span><?php echo $event_name; ?></span>
                </h6>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="events/detail.php?event_id=<?php echo $event_id; ?>">Overview</a></li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    <span>Reports</span>
                </h6>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item"><a class="nav-link" href="reports/index.php">Room capacity</a></li>
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
                    <h2 class="h4">Create new ticket</h2>
                </div>
            </div>

            <form class="needs-validation" novalidate action="" method="post">

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputName">Name</label>
                        <!-- adding the class is-invalid to the input, shows the invalid feedback below -->
                        <input type="text" class="form-control <?php if(!empty($name_err)){ echo 'is-invalid';} ?>" id="inputName" name="name" placeholder="" value="">
                        <div class="invalid-feedback">
                            <?php echo $name_err ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputCost">Cost</label>
                        <input type="number" class="form-control <?php if(!empty($cost_err)){ echo 'is-invalid';} ?>" id="inputCost" name="cost" placeholder="" value="0">
                        <div class="invalid-feedback">
                            <?php echo $cost_err ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="selectSpecialValidity">Special Validity</label>
                        <select class="form-control <?php if(!empty($special_validity_err)){ echo 'is-invalid';} ?>" id="selectSpecialValidity" name="special_validity">
                            <option value="" selected>None</option>
                            <option value="amount">Limited amount</option>
                            <option value="date">Purchaseable till date</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo $special_validity_err ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputAmount">Maximum amount of tickets to be sold</label>
                        <input type="number" class="form-control <?php if(!empty($amount_err)){ echo 'is-invalid';} ?>" id="inputAmount" name="amount" placeholder="" value="0">
                        <div class="invalid-feedback">
                            <?php echo $amount_err; ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label for="inputValidTill">Tickets can be sold until</label>
                        <input type="date"
                               class="form-control <?php if(!empty($valid_until_err)){ echo 'is-invalid';} ?>"
                               id="inputValidTill"
                               name="valid_until"
                               placeholder="yyyy-mm-dd"
                               value="">
                        <div class="invalid-feedback">
                            <?php echo $valid_until_err; ?>
                        </div>
                    </div>
                </div>

                <hr class="mb-4">
                <button class="btn btn-primary" type="submit">Save ticket</button>
                <a href="events/detail.php?event_id=<?php echo $event_id; ?>" class="btn btn-link">Cancel</a>
            </form>

        </main>
    </div>
</div>

</body>
</html>
