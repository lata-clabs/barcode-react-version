<?php
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/dbclass.php';
include_once '../entities/emp.php';

$dbclass = new DBClass();
$connection = $dbclass->getConnection();
$employee = new Employee($connection);

$mid = $_REQUEST['mid'];
$from = $_REQUEST['from'];
$to = $_REQUEST['to'];

if(!$mid){
    $mid='';
}
if(!$from){
    $from= date("Y-m").'-01';
}
if(!$to){
    $to=date('Y-m-d');
}

$stmt = $employee->getMenuData($mid, $from, $to);
$count = $stmt->rowCount();

if($count > 0){
    			
    $employees = array();
    $employees["param"] = (object)[
        "mid"=> $uid,
        "from" => $from,
        "to" => $to
    ];
    $employees["result"] = array();
    $employees["count"] = $count;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);	
        
        $p  = array(
              "name" => $name,
              "uid" => $uid,
              "cid" => $cid,
              "cCups" => $cCups,
              "tCups" => $tCups,
              "d3" => $d3,
              "d4" => $d4,
              "cCost" => $cCost,
              "tCost" => $tCost,
              "total" => $total
        );

        array_push($employees["result"], $p);
    }

    echo json_encode($employees);
}

else {

    echo json_encode(
        array("param" => (object) [],"result" => array(), "count" => 0)
    );
}
?>