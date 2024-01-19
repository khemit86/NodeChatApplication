<?php
require_once('../../master-config.php');


if(!empty($_REQUEST))
{
	$threadId = $_REQUEST['threadId'];
	$driverId = $_REQUEST['driverId'];
	$staffId = $_REQUEST['staffId'];
	
	
	/* $link = mysql_connect($commonDriverDatabases[0], $commonDriverDatabases[1], $commonDriverDatabases[2]);
	mysql_select_db($commonDriverDatabases[3], $link); */
	
	$conn_driver 		= 		new mysqli($commonDriverDatabases[0],$commonDriverDatabases[1],$commonDriverDatabases[2],$commonDriverDatabases[3]);
	
	
	

	//initiate the database connection 
	
	
	 $updateChatMessageSql = "UPDATE ".$commonDriverDatabases[3].".messages AS Message SET Message.NotificationStatus = '2' WHERE Message.ThreadId = '".$threadId."' AND Message.SenderId = '".$driverId."'  AND   Message.RecId = '".$staffId."' "; 
		
	$conn_driver->query($updateChatMessageSql);
	
	$deleteChatMessageSql = "DELETE  FROM ".$commonDriverDatabases[3].".messages  WHERE ThreadId = '".$threadId."' AND SenderId = '".$driverId."'  AND   NotificationStatus = '1' ";
	$conn_driver->query($deleteChatMessageSql);
	
	
	############ send notification to driver ###########
	
	
	/* $GetDriverSql	=	"SELECT Driver.DrvDeviceOs, Driver.DrvDeviceToken, Driver.DrvID FROM ".$commonDriverDatabases[3].".drivers_central AS Driver WHERE Driver.DrvID = '".$driverId."' AND Driver.DrvDeviceOs IS NOT NULL AND Driver.DrvDeviceToken IS NOT NULL  ";

	$resultDriver = $conn_driver->query($GetDriverSql);	
	$totalRecord = $resultDriver->num_rows;
	$DriverData = $resultDriver->fetch_assoc();
	
	
	if($totalRecord == 1)
	{
		if(!empty($DriverData['DrvDeviceOs']) && !empty($DriverData['DrvDeviceToken'])) 
		{
		
		$url = "https://www.wolero.com/secure/planning/chat/views/chat.php?threadId=".$threadId."&driverId=".$driverId;
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
	} */
	
	
	
	
	
	############ snd notification to driver ###########
	
	
	
	
	
		
}
	
?>
<script type="text/javascript">
	window.location.href = "chat.php?threadId="+'<?php echo $threadId; ?>'+"&staffId="+'<?php echo $staffId; ?>#';
	
</script>
