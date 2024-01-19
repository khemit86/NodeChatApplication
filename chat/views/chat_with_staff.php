<?php
require_once('../../master-config-app.php');
require_once('chat-header.php');

$link = mysql_connect($commonDriverDatabases[0], $commonDriverDatabases[1], $commonDriverDatabases[2]);

mysql_select_db($commonDriverDatabases[3], $link);
//initiate the database connection 


$CompanyId = 'WLR';
$dbrow = $alldatabases[$CompanyId];
// get the array of database regarding compnay
$conn_web = mysql_connect($dbrow[0], $dbrow[1], $dbrow[2]);
mysql_select_db($dbrow[3], $conn_web);

if (!empty($_REQUEST['threadId'])) {
    $threadId = $_REQUEST['threadId'];
}




######### get the chat of driver and staff for show the chat start##

 

//$GetChatMessageSql = "SELECT Message.threadId,Message.SenderId,Message.RecId,Message.message,Message.NotificationStatus,Message.senderType,Message.created  FROM " . $commonDriverDatabases[3] . ".messages AS Message WHERE Message.ThreadId = '" . $threadId . "' AND Message.NotificationStatus = '2' order by Message.created ASC ";
$GetChatMessageSql = "SELECT Message.threadId,Message.SenderId,Message.RecId,Message.message,Message.NotificationStatus,Message.senderType,Message.created  FROM " . $commonDriverDatabases[3] . ".messages AS Message WHERE `Message`.`RecId`='".$_REQUEST['driverId']."' AND `Message`.`SenderId`='".$_REQUEST['staffid']."' OR `Message`.`RecId`='".$_REQUEST['staffid']."' AND `Message`.`SenderId`='".$_REQUEST['driverId']."' AND Message.NotificationStatus = '2' order by Message.created ASC ";

//echo $GetChatMessageSql;
$resultChat = mysql_query($GetChatMessageSql);
$totalRecordChat = mysql_num_rows($resultChat);

#########End######################

if (!empty($_REQUEST['driverId'])) {
    $driverId = $_REQUEST['driverId'];
    $userId = $driverId;
    $type = 'driver';

    $GetDriverSql = "SELECT Driver.DrvName,Driver.DrvLastName,Driver.DrvProfilePic, Driver.DrvID FROM " . $commonDriverDatabases[3] . ".drivers_central AS Driver WHERE Driver.DrvID = '" . $driverId . "'  ";

    $resultDriver = mysql_query($GetDriverSql);
    $totalRecord = mysql_num_rows($resultDriver);
    //$rowDriverData = $resultDriver->fetch_assoc();
    $rowDriverData = mysql_fetch_array($resultDriver,MYSQL_ASSOC);
    $userName = $rowDriverData['DrvName'] . " " . $rowDriverData['DrvLastName'];

    if (!empty($rowDriverData['DrvProfilePic']) && file_exists('../../driver-profile-picture/thumb50x50/' . $rowDriverData['DrvProfilePic'])) {
        $image = SITE_URL . "planning/" . DRIVER_THUMB_IMAGE_DIR_50x50 . $rowDriverData['DrvProfilePic'];
    } else {
        $image = "";
    }
} else if (!empty($_REQUEST['staffid'])) {


    $staffId = $_REQUEST['staffid'];
    $userId = $staffId;
    $type = 'staff';
    ################ connection for staff #########

    $GetStaffDetailSql = "SELECT Customer.CustName  ";
    $GetStaffDetailSql.= "FROM " . $dbrow[3] . ".customers As Customer WHERE Customer.CustTypeID = '1' AND Customer.CustID = '" . $staffId . "' ";

    $resultStaff = mysql_query($GetStaffDetailSql);
    $totalStaffRecord = mysql_num_rows($resultStaff);
    //$rowStaffData = $resultStaff->fetch_assoc();
    $rowStaffData = mysql_fetch_array($resultStaff,MYSQL_ASSOC);
    $staffName = explode(" ", $rowStaffData['CustName']);
    //pr($rowStaffData);
    $userName = $staffName[0];
    $image = SITE_URL . "planning/driver-profile-picture/no_image.jpg";
    ############## end ###################
} else {
    $userName = "";
    $image = SITE_URL . "planning/driver-profile-picture/no_image.jpg";
    $userId = "";
    $type = 'driver';
	
}

 // new code has been added to insert into active chat table code start here

if (!empty($_REQUEST['threadId']) && !empty($_REQUEST['driverId']) &&  !empty($_REQUEST['staffid'])) {
	$active_driver_check_sql = "SELECT * FROM `active_chat_users` AS `ActiveChatUser` WHERE  `ActiveChatUser`.`SenderId` =".$_REQUEST['driverId']." OR `ActiveChatUser`.`RecId` =".$_REQUEST['driverId']." LIMIT 1";
	
		$result_active_driver_check = mysql_query($active_driver_check_sql);
		$totalRecord_active_driver_check = mysql_num_rows($result_active_driver_check);

		if ($totalRecord_active_driver_check ==0){
			
			if ($_REQUEST['senderType']=='staff') {
			$sender = $_REQUEST['staffid'];
			$receiver =  $_REQUEST['driverId'];
			} else {
			$sender = $_REQUEST['driverId'];
			$receiver = $_REQUEST['staffid'];
			}
			
		
			$insert= "INSERT INTO active_chat_users (thread_id , SenderId, RecId,senderType,name,created)
VALUES ('".$_REQUEST['threadId']."', '".$sender."', '".$receiver."','".$_REQUEST['senderType']."','".$userName."',now())"; 

			 
			  $resultactive_driver_insert = mysql_query($insert);

		}		
		$update="UPDATE messages SET is_read_driver=1 WHERE senderType='staff' and RecId='".$_REQUEST['driverId']."' "; 	
		mysql_query($update);
}

 // new code has been added to insert into active chat table code end here


?>
<!DOCTYPE html>

<!--
        This file is rendered by express.js, when the rurl /chat/123456 is visited in a browser.
        It includes jQuery, socket.io.js (it is automatically served by the socket.io library), 
        and a few more JavaScript files that you should check out.
-->

<html>

    <head>

        <title>Wolero Live Support</title>
<meta name="viewport" content="width=device-width, user-scalable=no">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link type="text/css" rel="stylesheet" href="https://wolero.com/secure/planning/chat/public/css/stylesheet.css" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans Condensed:300italic,300,700" rel="stylesheet" type="text/css">
       
		 <script src="https://wolero.com/secure/planning/chat/public/js/jquery.min.js"></script>
		<script type="text/javascript">
			  function moveWin()
			  {  
				window.scroll(0,10000);
			  }
			  </script>
    </head>
    <body onLoad="moveWin();">

        <header class="banner" style="height:200px;">

            <h1 class="bannertext">
                <a href="javascript:;" id="logo"><img src="../public/img/wolero-logo.png"  /><br/>LIVE CHAT</a>
            </h1>

        </header>


        <section class="section">
            <div class="left">

                <img src="../public/img/unnamed.jpg" id="leftImage" />

                <div class="info">
                    <h2><span class="nickname-left"></span> has left this chat.</h2>
                    <h5>&nbsp;</h5>
                </div>

            </div>
            <div class="toomanypeople">

                <h2>Oops, you can not join this chat!</h2>
                <h5>There are already two people in it</h5>

            </div>

            <!-- These elements are displayed as white info cards in the middle of the screen -->
            <div class="chatscreen" style="display:block;">

                <ul class="chats" >
                    <!-- The chat messages will go here -->
<?php
$notificationType = 1;
 
if (!empty($totalRecordChat)) {
    while ($row = mysql_fetch_array($resultChat,MYSQL_ASSOC)) {
        $notificationType = $row['NotificationStatus'];
        if ($userId == $row['SenderId']) {
            $class = "me";
            $senderID = $row['SenderId'];
        } elseif ($userId == $row['RecId']) {
            $class = "you";
            $senderID = $row['SenderId'];
        }


        if ($row['senderType'] == "staff") {

            $StaffSql = "SELECT Customer.CustName  ";
            $StaffSql.= "FROM " . $dbrow[3] . ".customers As Customer WHERE Customer.CustTypeID = '1' AND Customer.CustID = '" . $senderID . "' ";

            $resultsStaff = mysql_query($StaffSql);

            $rowStaff = mysql_fetch_array($resultsStaff,MYSQL_ASSOC);
            $nameStaff = explode(" ", $rowStaff['CustName']);
            //pr($rowStaffData);
            $name = $nameStaff[0];
            $img = SITE_URL."planning/driver-profile-picture/no_image.jpg";
        } elseif ($row['senderType'] == "driver") {

            $DriverSql = "SELECT Driver.DrvName,Driver.DrvLastName,Driver.DrvProfilePic, Driver.DrvID FROM " . $commonDriverDatabases[3] . ".drivers_central AS Driver WHERE Driver.DrvID = '" . $senderID . "'  ";

            $resultsDriver = mysql_query($DriverSql);

            $rowDriver = mysql_fetch_array($resultsDriver,MYSQL_ASSOC);
            $name = $rowDriver['DrvName'] . " " . $rowDriver['DrvLastName'];

            if (!empty($rowDriver['DrvProfilePic']) && file_exists('../../driver-profile-picture/thumb50x50/' . $rowDriver['DrvProfilePic'])) {
                $img = SITE_URL . "planning/" . DRIVER_THUMB_IMAGE_DIR_50x50 . $rowDriver['DrvProfilePic'];
            } else {
                $img = SITE_URL."planning/driver-profile-picture/no_image.jpg";
            }
        }
        ?>
                            <li class="<?php echo $class ?>"><div class="image"><img src="<?php echo $img; ?>"><b><?php echo $name; ?></b><i data-time="<?php echo $row['created']; ?>" class="timesent"><?php echo getRelativeTime($row['created']); ?></i> </div><p><?php echo $row['message']; ?></p></li>
                            <!--
                                    <li class="you"><div class="image"><img src="http://www.gravatar.com/avatar/d41d8cd98f00b204e9800998ecf8427e?s=140&amp;r=x&amp;d=mm"><b>AGNES</b><i data-time="1427089701875" class="timesent">a few seconds ago</i> </div><p>yukyku</p></li>
                                    
                                    <li class="me"><div class="image"><img src="https://192.168.1.67/wolero/planning/driver-profile-picture/thumb50x50/profile_image_142536699011.jpg"><b>khem verm</b><i data-time="1427089705180" class="timesent">a few seconds ago</i> </div><p>kyuk</p></li>
                            -->
                            <?php
                        }
                    }
                    ?>

                </ul>

            </div>

        </section>

        <footer>

            <form id="chatform">

                <textarea id="message" placeholder="Write something.."></textarea>
                <input type="submit" id="submit" value="SEND"/>

            </form>

        </footer>
         <script src="https://wolero.com/secure/planning/chat/public/js/moment.min.js"></script>
        <script src="https://wolero.com:4000/socket.io/socket.io.js"></script>
        <script type="text/javascript">

            $(function() {
				 var name = '<?php echo $userName; ?>',
                        email = "",
                        img = "<?php echo $image; ?>",
                        friend = "";
				var driverid ='<?php echo $_REQUEST['driverId'];	?>'	
				var staffid ="";		
                var socket = io.connect("https://wolero.com:4000");
                socket.emit('join_room', {id: '<?php echo $threadId; ?>', image: '<?php echo $image; ?>',
											username: name});
                var id = '<?php echo $threadId; ?>';
<?php if (!empty($_REQUEST['staffid'])) { 

?>			staffid= '<?php echo $_REQUEST['staffid'];?>';
                    //accept_interval();
					accept_interval();
                    //setTimeout('accept_interval()', 3000);
    <?php }
?>

               
                var section = $(".section"),
                        footer = $("footer"),
                        chatScreen = $(".chatscreen");
                tooManyPeople = $(".toomanypeople"),
                        left = $(".left");


                // some more jquery objects
                var chatForm = $("#chatform"),
                        hisName = $("#hisName"),
                        textarea = $("#message"),
                        messageTimeSent = $(".timesent"),
                        chats = $(".chats");

                var ownerImage = $("#ownerImage"),
                        leftImage = $("#leftImage"),
                        noMessagesImage = $("#noMessagesImage"),
                        leftNickname = $(".nickname-left");

                socket.on('img', function(data) {
                    img = data;


                });

                socket.on('tooMany', function(data) {
					  showMessage('tooManyPeople');
                });

                socket.on('leave', function(data) {
                    console.log(data);
                    if (data.boolean && id == data.room) {

                        showMessage("somebodyLeft", data);
                        chats.empty();
                    }

                });

                socket.on('receive', function(data) {
					//console.log(data);
                    showMessage('chatStarted');
					  if (data.msg.trim().length) {
                        createChatMessage(data.msg, data.user, data.img, moment());
                        scrollToBottom();
                    }  
                });
                textarea.keypress(function(e) {

                    // Submit the form on enter

                    if (e.which == 13) {
                        e.preventDefault();
                        chatForm.trigger('submit');
                    }

                });

                chatForm.on('submit', function(e) {
				    e.preventDefault();

                    // Create a new chat message and display it directly

                     showMessage("chatStarted");

                    if (textarea.val().trim().length) {
                        createChatMessage(textarea.val(), name, img, moment());
                        scrollToBottom();
<?php if (!empty($_REQUEST['driverId']) && $totalRecordChat == 0) { ?>
                            //socket.emit("sendNotification", {threadId: '<?php echo $threadId; ?>', driverId: '<?php echo $userId; ?>',staffId: '<?php echo $staffIds; ?>', message: textarea.val()});
    <?php }
?>
                        // Send the message to the other person in the chat
                        socket.emit('msg', {msg: textarea.val(), SenderId: '<?php echo $userId; ?>', user: '<?php echo $userName; ?>', img: img, type: '<?php echo $type; ?>', threadId: '<?php echo $threadId; ?>',staffId:staffid,driverId:driverid});
					}
                    // Empty the textarea
                    textarea.val("");
                });
                setInterval(function() {

                    messageTimeSent.each(function() {
                        var each = moment($(this).data('time'));
                        $(this).text(each.fromNow());
                    });

                }, 60000);

                function createChatMessage(msg, user, imgg, now) {
					
                    var who = '';

                    if (user === name) {
                        who = 'me';
                    }
                    else {
                        who = 'you';
                    }

                    var li = $(
                            '<li class=' + who + '>' +
                            '<div class="image chatimgshow">' +
                            '<img src=' + imgg + ' />' +
                            '<b></b>' +
                            '<i class="timesent" data-time=' + now + '></i> ' +
                            '</div>' +
                            '<div class="Chattxtshow"><p></p></div>' +
                            '</li>');

                    // use the 'text' method to escape malicious user input
                    li.find('p').text(msg);
                    li.find('b').text(user);

                    chats.append(li);

                    messageTimeSent = $(".timesent");
                    messageTimeSent.last().text(now.fromNow());
                }

                function scrollToBottom() {
                    $("html, body").animate({scrollTop: $(document).height() - $(window).height()}, 1000);
                }

                function showMessage(status, data) {
				

                    if (status === "connected") {

                        section.children().css('display', 'none');
                        onConnect.fadeIn(1200);
                    }

                    else if (status === "inviteSomebody") {

                        // Set the invite link content
                        $("#link").text(window.location.href);

                        onConnect.fadeOut(1200, function() {
                            inviteSomebody.fadeIn(1200);
                        });
                    }

                    else if (status === "personinchat") {

                        onConnect.css("display", "none");
                        personInside.fadeIn(1200);

                        chatNickname.text(data.user);
                        ownerImage.attr("src", data.avatar);
                    }

                    else if (status === "youStartedChatWithNoMessages") {

                        left.fadeOut(1200, function() {
                            inviteSomebody.fadeOut(1200, function() {
                                noMessages.fadeIn(1200);
                                footer.fadeIn(1200);
                            });
                        });

                        friend = data.users[1];
                        noMessagesImage.attr("src", data.avatars[1]);
                    }

                    else if (status === "heStartedChatWithNoMessages") {

                        personInside.fadeOut(1200, function() {
                            noMessages.fadeIn(1200);
                            footer.fadeIn(1200);
                        });

                        friend = data.users[0];
                        noMessagesImage.attr("src", data.avatars[0]);
                    }

                    else if (status === "chatStarted") {

                        section.children().css('display', 'none');
                        chatScreen.css('display', 'block');
                    }

                    else if (status === "somebodyLeft") {
                        //console.log('somebodyLeft');
                        leftImage.attr("src", data.avatar);
                        leftNickname.text(data.user);

                        section.children().css('display', 'none');
                        footer.css('display', 'none');
                        left.fadeIn(1200);
                    }

                    else if (status === "tooManyPeople") {

                        section.children().css('display', 'none');
                        tooManyPeople.fadeIn(1200);
                        footer.css('display', 'none');
                    }
                }

                function accept_interval()
                {
                    socket.emit("acceptNotification", {threadId: '<?php echo $threadId; ?>', staffId: '<?php echo $staffIds; ?>'});
                   
                }

				$(document).keydown(function(e) {
                   //alert(e.keyCode);
                   if(e.keyCode==116 || e.keyCode==17 ){
                       e.preventDefault();
                       return false;
                   }
                });
				
            });
			
        </script>


<!--<script src="https://192.168.1.67/wolero/planning/chat/public/js/chat.js"></script>-->




    </body>

</html>