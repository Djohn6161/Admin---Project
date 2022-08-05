<?php
require_once "../../../database/config.php";
require_once("../../../database/Query.php");
// if(!isset($_SESSION['name'])){
//     header("Location: ../../../index.php");
//   }
//   $org_id = $_SESSION['org_id'];
//     if(isset($_GET['event_id'])){
//         $event_id = $_GET['event_id'];
//         $row = sqlSelect("*","events","id = '$event_id'"); 
//         $event_name = $row['name'];
//         $event_date = $row['date'];
//         if($row['organizer_id']!=$org_id){
//             header("Location: ../../../events/index.php");
//         }
//     }
//     else{
//         $row = sqlSelect("*","events","organizer_id = '$org_id'");
//         if(!isset($row['id'])){
//             $event_id = "";
//             $event_name = "";
//             $event_date = "Sorry you currently have no events";
//         }
//         else{
//             $event_id = $row['id'];
//             $event_name = $row['name'];
//             $event_date = $row['date'];
//         }

//     }
    $eresponse = array();
    // $orgresponse = array();
    // $reponse = array("Event"=>$eresponse, "Organizer"=>$orgresponse);
    $response = array();
    $i = 0;
    $sql = sqlSelectReturnQuery("e.id as eid, e.name as ename, e.slug as eslug, e.date as edate, org.id as orgid, org.name as orgname, org.slug as orgslug","events as e, organizers as org","org.id = e.organizer_id");
    while($row = mysqli_fetch_assoc($sql)):
        $eresponse[$i]['id'] = $row['eid'];
        $eresponse[$i]['name'] = $row['ename'];
        $eresponse[$i]['slug'] = $row['eslug'];
        $eresponse[$i]['date'] = $row['edate'];
        
        $orgresponse['id'] = $row['orgid'];
        $orgresponse['name'] = $row['orgname'];
        $orgresponse['slug'] = $row['orgslug'];

        $eresponse[$i]['organizer'] = $orgresponse;
        $i++;
    endwhile;
    $response = array("Events"=>$eresponse);
    $header = "Response code: 200";
    // $body = array("Body"=>$response);
    // print_r($response);
    $test = array(
        'http'=>"POST",
        'header'=>$header,
        'body'=>$response
    );
    echo json_encode($response, JSON_PRETTY_PRINT);
?>
