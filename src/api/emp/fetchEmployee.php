<?php
header("Content-Type: application/json; charset=UTF-8");

// header("Access-Control-Allow-Origin: *");

include_once '../config/dbclass.php';
include_once '../entities/emp.php';

$dbclass = new DBClass();
$connection = $dbclass->getConnection();

$employee = new Employee($connection);


$uid=$_REQUEST['uid'];
$from=$_REQUEST['from'];
$to=$_REQUEST['to'];
if(!$uid){
    $uid='1117';
}
if(!$from){
    $from= date("Y-m").'-01';
}
if(!$to){
    $to=date('Y-m-d');
}

$stmt = $employee->getEmpData($uid, $from, $to);

$count = $stmt->rowCount();

if($count > 0){
    			
    $employees = array();
    $employees["param"] = (object)[
        "uid"=> $uid,
        "from" => $from,
        "to" => $to
    ];
    $employees["result"] = array();
    $employees["count"] = $count;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);	
        
        $p  = array(
            "date" => $date,
            "cCups" => $cCups,
            "tCups" => $tCups,
            "cCost" => $cCost,
            "tCost" => $tCost,
            "total" => $total,
            "machine" => $machine,
            "time" => $time
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