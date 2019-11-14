<?php 

// Connecting to mysql (phpmyadmin) database
	require ("db_connect.php");
	date_default_timezone_set('Asia/Kolkata');

	$msg = $mid = $macName = "";
	$system_id = $Name = $IDCodeNO = $CommercialID = $Date = $CoffeeCupAllowance = '';
	$CoffeeCups = $TeaCups = $Drink3	= $Drink4 = $TeaCupAllowance	= $time = '';
	foreach($_REQUEST as $key => $value){
		//$key='({"mcid":"001","id":"1002085","D1":"001","D2":"000","D3":"000","D4":"001","check":"110011"})';
		$data = str_replace("(","",$key);
		$data = str_replace(")","",$data);
		$data = json_decode($data, true);

		$mid			= (int)$data["mcid"];
		$CommercialID	= $data["id"];
		$Date			= date('Y-m-d');
		$time			= date("H:i");
		$CoffeeCups 	= $data["D1"];
		$TeaCups 		= $data["D2"];
		$Drink3 		= $data["D3"];
		$Drink4			= $data["D4"];
		$check			= $data["check"];			
		$total			= $CoffeeCups+$TeaCups+$Drink3+$Drink4;

		$total 			= sprintf("%05d",$total);
		$receivedData 	= "$mid $CommercialID $CoffeeCups $TeaCups $Drink3 $Drink4 $check";

		//get machine name from machines table
		$macQue = mysqli_query($con,"SELECT `name` FROM `machines` WHERE `id`='$mid' ");
		$r		  = mysqli_fetch_array($macQue);
		$macName= $r['name'];

		/* 
			Check if machine with machine code provided by the user exists
			If No  -> Print Message: [RCVD,".$time.",ME]
				(ME=Machine Error)
			If Yes -> Go to next check
		*/
		if(mysqli_num_rows($macQue)>0){
			
			if($CommercialID =="ABCD555" ){
				
				$masterDataUpdated = mysqli_query($con,"SELECT 1 FROM `MASTER_DATA` WHERE `row_status` IN ('U','I')");
				$machineQ 			 = mysqli_query($con,"SELECT `name` FROM `machines` WHERE `updated`='N' && `ID`='$mid' ");
				
				if(mysqli_num_rows($masterDataUpdated)>0 && mysqli_num_rows($machineQ)>0){
					echo "[RCVD,".$time.",UP]";
				}else{
					echo "[RCVD,".$time.",OK]";
				}
			}else{
				$empExist = mysqli_query($con,"SELECT `emp_name`,`id_code`,`commercial_id` FROM `MASTER_DATA` WHERE `commercial_id`='$CommercialID' LIMIT 1");
				
				if(mysqli_num_rows($empExist)>0){
					/* 
						Check if Employee with Bar Code Id provided by the user exists
						If No  -> Print Message: [RCVD,".$time.",IE]
							(IE= Invalid Employee)
						If Yes -> Go to next check
					*/
					if(($CoffeeCups+$TeaCups)>0){
						/* 
							Check if there is any drinks to update
							Only proceed further if no. of CoffeeCups + TeaCups are greater than zero 
							else exit the program
						*/
						$empExistData	= mysqli_fetch_array($empExist);
						$Name	 			= $empExistData['emp_name'];
						$IDCodeNO		= $empExistData['id_code'];

						$coffeeAllowncesQ = mysqli_query($con,"SELECT `allowance` FROM `cost` WHERE `drink`='Coffee'");	
						$cal				= mysqli_fetch_array($coffeeAllowncesQ);
						$CoffeeCupAllowance = $cal['allowance'];
						
						$teaAllowncesQ	= mysqli_query($con,"SELECT `allowance` FROM `cost` WHERE `drink`='Tea'");	
						$tal				= mysqli_fetch_array($teaAllowncesQ);
						$TeaCupAllowance = $tal['allowance'];
						 
						$dataQ = mysqli_query($con,"SELECT `data_update` FROM data WHERE `data_update`='$receivedData' ");
						if (mysqli_num_rows($dataQ)<1){
							/* 
								Check if data string provided by the user has not been recieved earlier(Data Duplicacy Check)
								If No  -> Insert a new row in the EMPLOYEE table with all the data been provided
											and Delete all the data inserted 2 months before the current date 
								If Yes -> Print Message: [RCVD,".$time.",DP]
									(DP= Duplicate)
							*/
							$fromDate = date('Y-m', strtotime("-1 months")).'-01';
							$selectForDelete = mysqli_query($con,"SELECT 1 FROM `EMPLOYEE` WHERE `date`<'$fromDate'");
							if(mysqli_num_rows($selectForDelete)>0){
								$deleteQ = mysqli_query($con,"DELETE FROM `EMPLOYEE` WHERE `date`<'$fromDate'");
							}
							$insertQ = mysqli_query($con,"
								INSERT INTO `EMPLOYEE`(
									`name`, `id_code`, `commercial_id`, `date`, `coffee_cup _allowance`, `tea_cup_allowance`, `coffee_cups`, `tea_cups`, `drink3`, `drink4`, `machine_id`, `machine_name`, `time`)
								VALUES('$Name','$IDCodeNO','$CommercialID','$Date','$CoffeeCupAllowance','$TeaCupAllowance',
									'$CoffeeCups','$TeaCups','$Drink3','$Drink4','$mid','$macName','$time')
							");
					
							if(mysqli_affected_rows($con)>0){
								/* 
									Check if data insertion is successful
									If Yes -> Print Message: [RCVD,".$time.",OK]
											and update the DataDuplicacyCheck variable in UPDATE table
									If No  -> Print Message: [RCVD,".$time.",FL]
											(FL= Failed)
								*/
								$dataQ=mysqli_query($con,"UPDATE `data` SET `data_update`='$receivedData'");
								
								echo "[RCVD,".$time.",OK]";//new record/s inserted
							}else{
								echo "[RCVD,".$time.",FL]";//No new record/s inserted
							}
						}else{
							echo "[RCVD,".$time.",DP]";//duplicate data
						}
					}else{
						echo "[RCVD,".$time.",OK]";
						//echo "[RCVD:$total,NU]";//nothing to update
					}
				}else{
					echo "[RCVD,".$time.",IE]";//Not a registered employee
				}
			}
		}else{
			echo "[RCVD,".$time.",ME]";//Machine Error
		}
	}
?>