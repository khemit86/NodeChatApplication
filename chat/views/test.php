<?php
require_once('../../master-config.php');



		$conn 		= 		new mysqli($commonDriverDatabases[0],$commonDriverDatabases[1],$commonDriverDatabases[2],$commonDriverDatabases[3]);
		

		//initiate the database connection 
		if($conn->connect_error) {
		}
		echo $DriverChatPopupSql = "SELECT Message.ThreadId,Message.CompanyId,Message.SenderId,Message.RecId,Driver.DrvName,Driver.DrvLastName,Driver.DrvUsername,Driver.DrvProfilePic FROM messages AS Message LEFT JOIN drivers_central AS Driver ON (Message.SenderId = Driver.DrvID) WHERE Message.CompanyId = 'WLC' AND Message.NotificationStatus = '1' AND  Message.RecId= '888000001' ";
		$resultDriver = $conn->query($DriverChatPopupSql);	
		echo $totalRecord = $resultDriver->num_rows;
		die();
		