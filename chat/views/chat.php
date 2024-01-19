<?php 
if (!empty($_REQUEST['driverId']) && $_REQUEST['driverId'] != '210') {
die("Under Work.");
} 
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

$GetChatMessageSql = "SELECT Message.threadId,Message.SenderId,Message.RecId,Message.message,Message.NotificationStatus,Message.senderType,Message.created  FROM " . $commonDriverDatabases[3] . ".messages AS Message WHERE Message.ThreadId = '" . $threadId . "' AND Message.NotificationStatus = '2'  ";

$resultChat = mysql_query($GetChatMessageSql);
$totalRecordChat = mysql_num_rows($resultChat);

#########End######################

if (!empty($_REQUEST['driverId'])) {
    $driverId = $_REQUEST['driverId'];
    $userId = $driverId;
	
	$update="UPDATE messages SET is_read_driver=1 WHERE senderType='staff' and RecId='".$driverId."' "; 	
	mysql_query($update);
	
	
	
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
} else if (!empty($_REQUEST['staffId'])) {


   $staffId = $_REQUEST['staffId'];
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
    $image = SITE_URL . "secure/planning/driver-profile-picture/no_image.jpg";
    ############## end ###################
} else {
    $userName = "";
    $image = SITE_URL . "secure/planning/driver-profile-picture/no_image.jpg";
    $userId = "";
    $type = 'driver';
}
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

    </head>

    <body>

        <header class="banner" style="height:200px;">

            <h1 class="bannertext">
                <a href="javascript:;" id="logo"><img src="../public/img/wolero-logo.png"  /><br/>LIVE CHAT</a>
            </h1>

        </header>


        <section class="section">
            <div class="left">

                <img src="../img/unnamed.jpg" id="leftImage" />

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
			/* 	setInterval(function() {

                    messageTimeSent.each(function() {
                        var each = moment($(this).data('time'));
						//console.log(each); each.fromNow()
						console.log(each);
                        $(this).text(each.fromNow());
                    });
					console.log('run time interval');
              }, 1000); */
			
			
				var driverID = '<?php echo $_REQUEST['driverId'];?>';
				 var name = '<?php echo $userName; ?>',
                        email = "",
                        img = "<?php echo $image; ?>",
                        friend = "";
                var socket = io.connect("https://wolero.com:4000");
                 socket.emit('join_room', {id: '<?php echo $threadId; ?>', image: '<?php echo $image; ?>',
											username: name});
                var id = '<?php echo $threadId; ?>';
<?php if (!empty($_REQUEST['staffId'])) { ?>
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
                   // console.log(data);
                    if (data.boolean && id == data.room) {

                        showMessage("somebodyLeft", data);
                        chats.empty();
                    }

                });

                socket.on('receive', function(data) {
				
					 
					  //console.log(data);
					//console.log($('ul.chats>li.check_message_repeat').length);
					showMessage('chatStarted');
				     //alert('Lenght'+$('ul.chats>li.check_message_repeat').length);
				      if ($('ul.chats>li.check_message_repeat').length=='0'){
						 
						li = '';
					
						var driver_id = '<?php echo $_REQUEST['driverId'];?>';
						 
						 if (data.staffid !='') {
							
							$.ajax({
							url:"<?php echo SITE_URL;?>/planning/ajax_message_data.php",
							type:"POST",  
							async:true,
							dataType:"json", 
							data:{driverId:driver_id,staffId:data.staffid},
							success: function(response){ 
							
									 $.each(response, function(idx, obj) { 
											var who = '';
											if (driver_id === obj.SenderId) {
												who = "me check_message_repeat";
											}
											else {
												who = "you check_message_repeat";
											} 
										li+=
											'<li class= "' + who + '">' +
											'<div class="image chatimgshow">' +
											'<img src=' + obj.profile_image + ' />' +
											'<b>'+obj.senderName+'</b>' +
											'<i class="timesent" data-time="' + obj.time + '" >'+obj.created+'</i> ' +
											'</div>' +
											'<div class="Chattxtshow"><p>'+ obj.message +'</p></div>' +
											'</li>';
											
									});
								   
								   //chats.append(li);
								   chats.html(li);
								   scrollToBottom();
									
								},
								error: function(XMLHttpRequest, textStatus, errorThrown) { 
									alert("Status: " + textStatus); alert("Error: " + errorThrown + XMLHttpRequest.responseText); 
								} 
							}); 
					
						 }  
					
					 }
					   else {     
					
					if (data.msg.trim().length) {
							createChatMessage(data.msg, data.user, data.img, moment());
							scrollToBottom();
						}   
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
                            socket.emit("sendNotification", {threadId: '<?php echo $threadId; ?>', driverId: '<?php echo $userId; ?>',
                                staffId: '<?php echo $staffIds; ?>', message: textarea.val()});
    <?php }
?>
                        // Send the message to the other person in the chat
                        socket.emit('msg', {msg: textarea.val(), SenderId: '<?php echo $userId; ?>', user: '<?php echo $userName; ?>', img: img, type: '<?php echo $type; ?>', threadId: '<?php echo $threadId; ?>'});


                    }
                    // Empty the textarea
                    textarea.val("");
                });
                setInterval(function() {

                    messageTimeSent.each(function() {
                        var each = moment($(this).data('time'));
                        $(this).text(each.fromNow());
                    });

                }, 1000);   

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


    </body>

</html>