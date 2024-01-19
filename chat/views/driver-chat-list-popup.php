<?php
require_once('../../master-config.php');

$threadId = $_POST['threadId'];
$staffId = $_POST['staffId'];

if(!empty($commonDriverDatabases))
{
	
	
	if(!empty($_POST))
	{ 
		$link = mysql_connect($commonDriverDatabases[0], $commonDriverDatabases[1], $commonDriverDatabases[2]);
		mysql_select_db($commonDriverDatabases[3], $link);
		

		
		$DriverChatPopupSql = "SELECT Message.ThreadId,Message.SenderId,Message.RecId,Driver.DrvName,Driver.DrvLastName,Driver.DrvUsername,Driver.DrvProfilePic FROM messages AS Message LEFT JOIN drivers_central AS Driver ON (Message.SenderId = Driver.DrvID) WHERE  Message.NotificationStatus = '1' AND  Message.RecId= '".$staffId."' ";
		$resultDriver = mysql_query($DriverChatPopupSql);	
		$totalRecord = mysql_num_rows($resultDriver);
		
		if($totalRecord>0)
		{
			
			while ($rowDriver = mysql_fetch_array($resultDriver,MYSQL_ASSOC))
			{
				
				$chat_url = SITE_URL ."planning/chat/views/chat_initialize.php?threadId=".$rowDriver['ThreadId']."&staffId=".$rowDriver['RecId']."&driverId=".$rowDriver['SenderId'];
		?>
		
				<li>
				<div class="Chatlistimg">
				<a alt="<?php echo $rowDriver['DrvName']; ?>"  title="<?php echo $rowDriver['DrvName']; ?>" href="javascript:;" onclick="windowOpen('<?php echo $chat_url;?>')" >
				<?php
				if(!empty($rowDriver['DrvProfilePic']) && file_exists('../../driver-profile-picture/original-pic/'.$rowDriver['DrvProfilePic']))
				{
					$image_src = SITE_URL ."planning/driver-profile-picture/thumb50x50/".$rowDriver['DrvProfilePic'];
				?>
					<img alt="<?php echo $rowDriver['DrvName']; ?>"  title="<?php echo $rowDriver['DrvName']; ?>" src = "<?php echo $image_src ?>"/>
				
				<?php
				}
				else
				{
					$image_src = SITE_URL."planning/driver-profile-picture/no_image.jpg";
				?>
					<img alt="<?php echo $rowDriver['DrvName']; ?>"  title="<?php $rowDriver['DrvName']; ?>" src = "<?php echo $image_src ;?>"/>
				<?php	
				}
				?>
				</a></div>
				<div class="Chatlisttxt">
				
				
				
				<h3><a href="javascript:;" onclick="windowOpen('<?php echo $chat_url;?>')" ><?php echo $rowDriver['DrvName'];?></a></h3>
				<!--<p>Wolero Pte Ltd 2014 </p>
				<div class="Cmtcntbox">15</div>-->
				</div>
				<div class="Avlicongreen Avlchatic" id="<?php echo "chat_list_".(int)$rowDriver['DrvID']; ?>"></div>
				</li>
				
		
		<?php
			}
			
		?>
		<?php	
		}
		?>
		<input type="hidden" id="c_counter" value="<?php echo $totalRecord ?>" /> 
		<?php
	}
}	

?>