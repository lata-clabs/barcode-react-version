<?php
	require ("db_connect.php");
	date_default_timezone_set('Asia/Kolkata');
	
	$Date = $time = $startP = $endP = '';
	$insertedArr = $updatedArr = $allArr = array();
	
	$receivedData	="";
	$data	=$resultData =$someArray ='';
	$mid	=$machineid	 =$startP	 =$endP='';
	foreach($_REQUEST as $key => $value){
		// $key = '({"mcid":"A5A5A","start":"00001","end":"00999"})'; //data format: ({"mcid":"00001","start":"00001","end":"00030"})
		
		$data		= str_replace("(","",$key);
		$data		= str_replace(")","",$data);
		$data 	    = json_decode($data, true);
		
		$mid	=(int)$data["mcid"];
		$mcode	=$data["mcid"];
		$startP	=(int)$data["start"];
		$startP	=$startP-1;
		$endP	=(int)$data["end"];
		$endP	=$endP-$startP;
		$time	= date("H:i");

		$receivedData	="$mcode $startP $endP";
		
		//echo $receivedData;
		
		$masterDataUpdated 	= mysqli_query($con,"SELECT 1 FROM `MASTER_DATA` WHERE `row_status`='U'");
		$masterDataInserted = mysqli_query($con,"SELECT 1 FROM `MASTER_DATA` WHERE `row_status`='I'");
		$activeEmployess 	= mysqli_query($con,"SELECT 1 FROM `MASTER_DATA` WHERE `Active`=0");
		$macQue 			= mysqli_query($con,"SELECT 1 FROM `machines` WHERE `id`='$mid' ");
		$insertedRowCount 	= mysqli_num_rows($masterDataInserted);
		$updatedRowCount 	= mysqli_num_rows($masterDataUpdated);
		$allEmployees 		= mysqli_num_rows($activeEmployess);
		
		//echo "<br> Updated = $updatedRowCount <br>Inserted = $insertedRowCount <br> ActiveEmps = $allEmployees<br> MachineFound = ".mysqli_num_rows($macQue)." <br>";

		if($mcode == "A5A5A"){
			$fetchAll = mysqli_query($con,"SELECT `Commercial Id` FROM `MASTER_DATA` WHERE `Active`=0 GROUP BY `Commercial Id` ORDER BY `Commercial Id` LIMIT $startP , $endP");
			$allArr[] = "valid";
			$allArr[] = sprintf("%04d",$allEmployees);
			while($rFetchAll = mysqli_fetch_array($fetchAll)){
				$allArr[] = sprintf("%07d",$rFetchAll['Commercial Id']);
			}
			$allArr[] = "valid";
			$fetchAllJSON = json_encode($allArr);
			$fetchAllJSON = str_replace("\"","",$fetchAllJSON);
			echo $fetchAllJSON;
		}else{
			if(mysqli_num_rows($macQue)>0){
				mysqli_query($con,"UPDATE `machines` SET `updated` = 'Y' WHERE `id`='$mid'");
				if(mysqli_affected_rows($con)>0){
					$macUpdated = mysqli_query($con,"SELECT 1 FROM `machines` WHERE `updated`='N'");
					if(mysqli_num_rows($macUpdated)<1){
						mysqli_query($con,"UPDATE `MASTER_DATA` SET `row_status` = 'N', `Updated On Machine`=1 WHERE `row_status` != 'N'");
					}
				}
				// echo "[RCVD,".$mcode.",UD]";
				echo "[valid,valid]";
			}
		} 
	}
?>