<?php
require_once('../../master-config-app.php');
$conn_driver 		= 		new mysqli($commonDriverDatabases[0],$commonDriverDatabases[1],$commonDriverDatabases[2],$commonDriverDatabases[3]);

if(!empty($_POST))
{
	$SenderId = $_POST['SenderId'];
	$ReceiverId = $_POST['ReceiverId'];
	$ThreadId = $_POST['ThreadId'];
	//print_r($_POST);

	$GetDriverSql	=	"SELECT Driver.DrvDeviceOs, Driver.DrvDeviceToken, Driver.DrvID FROM ".$commonDriverDatabases[3].".drivers_central AS Driver WHERE Driver.DrvID = '".$ReceiverId."' AND Driver.DrvDeviceOs IS NOT NULL AND Driver.DrvDeviceToken IS NOT NULL  ";

	$resultDriver = $conn_driver->query($GetDriverSql);	
	$totalRecord = $resultDriver->num_rows;
	$DriverData = $resultDriver->fetch_assoc();
	
	
	if($totalRecord == 1)
	{
		if(!empty($DriverData['DrvDeviceOs']) && !empty($DriverData['DrvDeviceToken'])) 
		{
		
			$url = "https://wolero.com/secure/planning/chat/views/chat-initiate-agent.php?threadId=".$ThreadId."&staffId=".$SenderId."&driverId=".$ReceiverId."&senderType=driver#";
			$type = 'chat';
			
			
			$message = "You have received a chat request.";
			
			if($DriverData['DrvDeviceOs'] == 'Iphone' && !empty($DriverData['DrvDeviceToken']))
			{
					
				$DeviceToken = $DriverData['DrvDeviceToken'];
				$jobNo = '';
				send_notification_chat_iphone($DeviceToken,$type,$message,$url);
			
			}
			if($DriverData['DrvDeviceOs'] == 'Android' && !empty($DriverData['DrvDeviceToken']))
			{
				$jobNo = '';
				$android_device_ids = array();
				$android_device_ids[] = $DriverData['DrvDeviceToken'];
				send_notification_chat_android($android_device_ids,$message,$type,$url);
			}
		}
	}
}
//die("success");
die("success");
