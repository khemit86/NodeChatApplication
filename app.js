// This is the main file of our chat app. It initializes a new 
// express.js instance, requires the config and routes files
// and listens on a port. Start the application by running
// 'node app.js' in your terminal


var express = require('express'),
	app = express();
var https = require('https');

var fs = require('fs');

var options = {
  // key: fs.readFileSync('/etc/ssl/private/www.wolero.com.key'),
  // cert: fs.readFileSync('/etc/ssl/certs/www.wolero.com.crt')
  key: fs.readFileSync('/home/woleroc/ssl/keys/d8c2f_57bed_d8168339c15bf60150ddb2d4ebc8d252.key'),
  cert: fs.readFileSync('/home/woleroc/ssl/certs/www_wolero_com_d8c2f_57bed_1460764799_e99cecc16e18c46627d6b85c98e4424a.crt')
};
	
var gravatar = require('gravatar');
var moment = require('moment-timezone');
var mysql = require('mysql');
var underscore = require('underscore');
var apns = require('apn'); // for iphone
var gcm = require('node-gcm'); // for android

var db_config = {
  host: '119.31.233.198',
    user: 'woleroc_wlrwobs',
    password: '2t7kWFIs7KQC',
    database: 'woleroc_wolero_limo'
};
var connection;
var last_record_added='';
function handleDisconnect() {
  mysqlConnection = mysql.createConnection(db_config); // Recreate the connection, since
                                                  // the old one cannot be reused.

  mysqlConnection.connect(function(err) {              // The server is either down
    if(err) {                                     // or restarting (takes a while sometimes).
      console.log('error when connecting to db:', err);
      setTimeout(handleDisconnect, 2000); // We introduce a delay before attempting to reconnect,
    }                                     // to avoid a hot loop, and to allow our node script to
  });                                     // process asynchronous requests in the meantime.
                                          // If you're also serving http, display a 503 error.
  mysqlConnection.on('error', function(err) {
    console.log('db error', err);
    if(err.code === 'PROTOCOL_CONNECTION_LOST') { // Connection to the MySQL server is usually
      handleDisconnect();                         // lost due to either server restart, or a
    } else {                                      // connnection idle timeout (the wait_timeout
      throw err;                                  // server variable configures this)
    }
  });
}

handleDisconnect();

// This is needed if the app is run on heroku:

var port = process.env.PORT || 4000;

// Initialize a new socket.io object. It is bound to 
// the express app, which allows them to coexist.

var io = require('socket.io').listen(https.createServer(options, app).listen(port));
//console.log(io);
// Require the configuration and the routes files, and pass
// the app and io as arguments to the returned functions.

//require('./config')(app, io);
//require('./routes')(app, io);
console.log('Your application is running on https://wolero.com:' + port);

io.sockets.on('connection', function (socket) {
	
	socket.on('join_room', function (data) {
	
		 //console.log(data);
		if (typeof socket !== 'undefined') {
			// data.id should be set if user is logged in
			if (typeof data.id !== 'undefined') {
				// Check if socket is already in room
				var roomlist = findClientsSocket(io, data.id, '');
				//console.log(roomlist.length);
				//if (roomlist.length <=1) {
				
					
					 //var roomlist = io.sockets.clients(data.id); // returns Socket instances of all clients in the room
					  
					var occupantSocket;
					if (typeof roomlist[0] !== 'undefined') {
					   // Should only be one socket in a private room
					   occupantSocket = roomlist[0];
					}
					//if (socket !== occupantSocket) {
					   // Socket hasn't joined before so join it now
					   socket.join(data.id);
					//}
					socket.username = data.username;
					socket.room = data.id;
					
					if (typeof data.image !== 'undefined') {
						if(data.image == "")
						{
						socket.avatar = gravatar.url(data.image, {s: '140', r: 'x', d: 'mm'});
						}
						else
						{
							socket.avatar = data.image;
						}
						socket.emit('img', socket.avatar);
					}
					else
					{
						socket.avatar = data.image;
						socket.emit('img', socket.avatar);
					}
				/* }
				else
				{
					console.log('many');
					socket.emit('tooMany', {boolean: false});
				} */
				
			} 
			else 
			{
				// User ID isn't set so disconnect the socket
				socket.disconnect();
			}
		}
	});
	
  socket.on("initiate_chat" , function(data){
		   // console.log(data);
		   
		if (data.senderType=='staff') {
			senderid= data.staffId;
			receiverId= data.driverId;
			} else {
			senderid= data.driverId;
			receiverId= data.staffId;
		}
						
						var input2 = {
									SenderId:senderid,
									RecId :receiverId,
									thread_id:data.threadId,
									senderType:data.senderType,
									name:data.staffname,
									created:moment.utc().format('YYYY-MM-DD HH:mm:ss')
									
								};
								

								if (typeof senderid === "undefined" || typeof receiverId === "undefined") {
									
								} else {
									 mysqlConnection.query("INSERT INTO active_chat_users set ? ",input2, function(err, results) {
										  
									}); 
								
								}
		
	});
	
	
	socket.on("check_driver_in_active_table",function(data){
			// console.log(data);
			
			mysqlConnection.query('SELECT * FROM `active_chat_users` AS `ActiveChatUser` WHERE  `ActiveChatUser`.`SenderId` =? OR `ActiveChatUser`.`RecId` =? LIMIT 1',[data.driverId,data.driverId], function(err, result) {
				if (result.length == 0){
							
					result = 0;
				} 
				//console.log(result);  
				socket.emit('active_driver_detail', {result:result,threadId:data.threadId,driverID:data.driverId});
				
				 
				
			
			});
			
	});  
	
	 
	socket.on("remove_joined_driver",function(data){
			  mysqlConnection.query("DELETE FROM `active_chat_users` WHERE `thread_id`=?",data.id, function(err, Res) {
				
		    });  
		
			socket.broadcast.to(data.id).emit('leave', {
				boolean: true,
				room: data.id,
				user: data.username,
				avatar: data.img
			
			});
		
	});
	socket.on('msg', function(data){
			 //console.log(data);
			mysqlConnection.query('SELECT * FROM `messages` AS `Message` WHERE  `Message`.`ThreadId` =?  ORDER BY `Message`.`MessId` desc  LIMIT 1',[data.threadId], function(err, sessRes) {
				  //console.log(sessRes.length);
					if (sessRes.length > 0) {
					//console.log(sessRes);
					
						var notificationStatus = sessRes[0].NotificationStatus;
							if(notificationStatus==2){
								 resSendId = sessRes[0].SenderId;
								resRecId = sessRes[0].RecId;
								if(data.SenderId == sessRes[0].SenderId)
								{
									 receiverId = sessRes[0].RecId;
								}
								else if(data.SenderId == sessRes[0].RecId)
								{
									 receiverId = sessRes[0].SenderId;
								}
								/* if(resSendId==data.SenderId){
									receiverId = resRecId;
								}
								else if(resSendId==data.RecId){
								
									receiverId = resSendId;
								} */
								
								if (data.type=='staff') {
									is_read = 0;
								} else {
									is_read = 1;
								}
								
								var input = {
									SenderId:data.SenderId,
									RecId :receiverId,
									ThreadId:data.threadId,
									message:data.msg,
									NotificationStatus:2,
									is_read_driver:is_read,
									senderType:data.type,
									CompanyId:sessRes[0].CompanyId,
									created:moment().tz("Asia/Singapore").format(),
									modified:moment().tz("Asia/Singapore").format()
								};
								
								mysqlConnection.query("INSERT INTO messages set ? ",input, function(err, results) {
									  //console.log(results);
									   last_record_added=results.insertId;
									 /* if drivr existed on the page then he should read the messages sent by stafff */
									if(data.type == 'staff')
									{ 
										 mysqlConnection.query('SELECT * from active_chat_users WHERE thread_id=? ',data.threadId, function(err, Res) {
											if (Res.length > 0){
												mysqlConnection.query('UPDATE messages SET is_read_driver=1 WHERE MessId=?', last_record_added, function(err,resultupdate) {
												}); 
											}
										 });
									 
									 }
		 
								/* if drivr existed on the page then he should read the messages sent by stafff */
									 
								});
								
							}
						}	else {
						// console.log('no record inset new one');
						// if we are adding data from staff first time then
						
						if (data.type=='staff') {
							senderid= data.SenderId;
							receiverId= data.driverId;
							is_read = 0;
						} else {
							senderid= data.driverId;
							receiverId= data.staffId;
							is_read = 1;
						}
						
						var input1 = {
									SenderId:senderid,
									RecId :receiverId,
									ThreadId:data.threadId,
									message:data.msg,
									NotificationStatus:2,
									is_read_driver:is_read,
									senderType:data.type,
									CompanyId:'WLR',
									created:moment().tz("Asia/Singapore").format(),
									modified:moment().tz("Asia/Singapore").format()
								};
								 
								 
								if (typeof senderid === "undefined" && typeof receiverId === "undefined") {
									
								} else {
									 mysqlConnection.query("INSERT INTO messages set ? ",input1, function(err, results) {
										  
									}); 
								
								}
								  
						
						}
							
							
								
							
			});
						
		
	   
		// When the server receives a message, it sends it to the other person in the room.
		//socket.broadcast.to(socket.room).emit('receive', {msg: data.msg, user: data.user, img: data.img});
		 
		if(data.type == 'driver')
		{	
			DrvId = data.SenderId;
		}
		else
		{
			DrvId = data.driverId;
		}
		
		 //console.log(socket.room);
		 socket.emit('receive_new', {msg: data.msg, user: data.user, img: data.img,senderid:data.SenderId,driverid:DrvId});
		 socket.broadcast.to(socket.room).emit('receive', {msg: data.msg, user: data.user, img: data.img,driverid:DrvId,staffid:data.staffid,threadID:data.threadId});
		 
		
	});
	 
	  
	 
	 
	 
	
	
	socket.on("sendNotification", function(data){
		// console.log('notification'+data);
		var room = findClientsSocket(io, data.threadId, '');
		var driver_id = data.driverId;
		if (room.length < 2) {
			var newArray=data.staffId.split(",");
			var staffArray = [];
			for(var m=0;m<newArray.length;m++) {
				
				staffArray=newArray[m].split("|");
				 //console.log(staffArray);
				// console.log(staffArray[0]);
				//console.log(staffArray[1]);
				var roomlist = findClientsSocket(io, staffArray[0], '');
				var occupantSocket;
				
				if (typeof roomlist[0] !== 'undefined') {
				   // Should only be one socket in a private room
				   occupantSocket = roomlist[0];
				}
				if (socket !== occupantSocket) {
					 // Socket hasn't joined before so join it now
				   socket.join(staffArray[0]);
				}
				
				
				var messageCount = 0;
				mysqlConnection.query('SELECT COUNT(*) AS `count` FROM `messages` AS `Message`   WHERE  `Message`.`ThreadId`=?',data.threadId, (function(m){
				return function(err, results) {
					
					var staffArray = [];
					staffArray=newArray[m].split("|");
					
					var messageCount = results[0].count;
					//console.log(messageCount);
						if(messageCount==0){ 	
							var input = {
								SenderId:data.driverId,
								RecId :staffArray[0],
								CompanyId :staffArray[1],
								ThreadId:data.threadId,
								senderType:'driver',
								message:data.message,
								NotificationStatus:1,
								is_read_driver:1,
								created:moment().tz("Asia/Singapore").format(),
								modified:moment().tz("Asia/Singapore").format()
							};
							
							mysqlConnection.query("INSERT INTO messages set ? ",input, function(err, results) {
								 
								
							});
							socket.broadcast.to(staffArray[0]).emit("broadcastRequest", data);
						}
						
				};
								})(m));
								
								
						
			}
			
			
						 
		}
	
	
	});
	
	 
	socket.on("fetch_chat_request_new",function(data){
	
	  // console.log(data);
		var staffId = data.staffId;
		var driverId = data.driverId;
		var threadId = data.threadId;
		 
		if (threadId!='') {
		  mysqlConnection.query("DELETE FROM `messages` WHERE `messages`.`NotificationStatus`=1  and  `messages`.`RecId`!=? AND `messages`.`SenderId`=? AND `messages`.`threadId`=?",[staffId,driverId,threadId], function(err, Res) {
			
			if (staffId!='' && driverId!='') {
				mysqlConnection.query('UPDATE messages SET NotificationStatus=2 WHERE RecId=? and SenderId =? and threadId=?', [staffId,driverId,threadId], function(err,updateres) {
				 //console.log(err);
				if (!err) 
				{ 
				  //console.log(updateres);
				 } 
				
				});
			}
			});
		} 		
	
		
			 mysqlConnection.query('SELECT `Message`.`ThreadId`,`Message`.`SenderId`,`Message`.`RecId`,`Message`.`message`,`Message`.`created`,`Driver`.`DrvName`,`Driver`.`DrvLastName`,`Driver`.`DrvUsername`,`Driver`.`DrvProfilePic` FROM `messages` AS Message LEFT JOIN `drivers_central` AS Driver ON (`Message`.`SenderId` = `Driver`.`DrvID`) WHERE  `Message`.`RecId`=? AND `Message`.`SenderId`=? OR `Message`.`RecId`=? AND `Message`.`SenderId`=? order by Message.created ASC',[staffId,driverId,driverId,staffId], function(err, Res) {
					if (Res.length > 0) {
					
					//console.log("popup data"+Res);
					
					mysqlConnection.query('SELECT Driver.DrvName, Driver.DrvLastName, Driver.DrvProfilePic, Driver.DrvID, Driver.DrvDeviceToken FROM drivers_central AS Driver WHERE Driver.DrvActive = "YES" AND Driver.DrvID=?',driverId, function(err, result) {
				 		socket.emit('make_popup_new', { chat_request:Res,driver_detail:result,threadId:threadId});	
						 
				 	});
						
						
					}
					else
					{
					mysqlConnection.query('SELECT Driver.DrvName, Driver.DrvLastName, Driver.DrvProfilePic, Driver.DrvID, Driver.DrvDeviceToken FROM drivers_central AS Driver WHERE Driver.DrvActive = "YES" AND Driver.DrvID=?',driverId, function(err, result) {
				 		socket.emit('make_popup_new', { chat_request:0,driver_detail:result,threadId:threadId});	
				 	});
					
				 }
				});	
				
				socket.broadcast.emit('refresh_drivers_list');
		 
	
	});

	
	socket.on('check_threadID', function (data){
			//console.log(data);
			var staffId = data.staffId;
			var driverId = data.driverId;
			 mysqlConnection.query('SELECT `Message`.`ThreadId`,`Message`.`SenderId`,`Message`.`RecId`,`Message`.`message`,`Message`.`created`,`Driver`.`DrvName`,`Driver`.`DrvLastName`,`Driver`.`DrvUsername`,`Driver`.`DrvProfilePic` FROM `messages` AS Message LEFT JOIN `drivers_central` AS Driver ON (`Message`.`SenderId` = `Driver`.`DrvID`) WHERE  `Message`.`RecId`=? AND `Message`.`SenderId`=? OR `Message`.`RecId`=? AND `Message`.`SenderId`=?  limit 1',[staffId,driverId,driverId,staffId], function(err, Res) {
				if (Res.length > 0) {
					var threadid = Res[0].ThreadId;
					socket.emit('send_threadID',{ThreadID:threadid,driverId:driverId,staffId:staffId});		
				}   else {
					//var threadid = '602860286028';
					socket.emit('send_threadID',{ThreadID:0,driverId:driverId,staffId:staffId});		
				}  
				
				
			});
	
	});
	
	
	
	
	socket.on('driver_searching', function (data) {
			
			if(data.popup_type=='1')
			{
				
			mysqlConnection.query('SELECT Driver.DrvName, Driver.DrvLastName, Driver.DrvProfilePic, Driver.DrvID, Driver.DrvDeviceToken FROM drivers_central AS Driver WHERE Driver.DrvActive = "YES" AND Driver.DrvName LIKE "%'+data.searchtxt+'%" order BY CASE WHEN Driver.DrvDeviceToken IS NULL THEN 0 ELSE 1 END DESC,Driver.DrvName ASC', function(err, Res) {
				//console.log(Res);
				 if (Res.length > 0) {
						//console.log(Res);
						
						socket.emit('driver_searching_result', { search_result:Res,refresh:0});	
					} 
						
						 
										 
				});	
			} else if(data.popup_type=='2') {
				
				mysqlConnection.query('SELECT Driver.DrvName, Driver.DrvLastName, Driver.DrvProfilePic, Driver.DrvID, Driver.DrvDeviceToken FROM drivers_central AS Driver WHERE Driver.DrvActive = "YES" order BY CASE WHEN Driver.DrvDeviceToken IS NULL THEN 0 ELSE 1 END DESC,Driver.DrvName ASC', function(err, Res) {
				//console.log(Res);
				 if (Res.length > 0) {
						 //console.log(Res);
						
						socket.emit('driver_searching_result', { search_result:Res,refresh:0});	
					} 
						
						 
										 
				});	
			

			} else if(data.popup_type=='3') {
			
				mysqlConnection.query('SELECT Driver.DrvName, Driver.DrvLastName, Driver.DrvProfilePic, Driver.DrvID, Driver.DrvDeviceToken FROM drivers_central AS Driver WHERE Driver.DrvActive = "YES" order BY CASE WHEN Driver.DrvDeviceToken IS NULL THEN 0 ELSE 1 END DESC,Driver.DrvName ASC', function(err, Res) {
				//console.log(Res);
				 if (Res.length > 0) {
						 //console.log(Res);
						
						socket.emit('driver_searching_result', { search_result:Res,refresh:1});	
					} 
						
						 
										 
				});

		   }			
	
	});
	
	 
	
	
	socket.on('disconnect', function() {
	
		io.sockets.emit('driver_disconnected',this.room);	
		socket.broadcast.to(this.room).emit('leave', {
				boolean: true,
				room: this.room,
				user: this.username,
				avatar: this.avatar
			});
			
			mysqlConnection.query("DELETE FROM `active_chat_users` WHERE `thread_id`=?",socket.room, function(err, Res) {
			
			});
			
			// leave the room
			socket.leave(socket.room);
	});
	
	function findClientsSocket(io,roomId, namespace) {
		var res = [],
			ns = io.of(namespace ||"/");    // the default namespace is "/"
		//console.log(ns);
		if (ns) {
			//console.log('if');
			for (var id in ns.connected) {
				if(roomId) {
					var index = ns.connected[id].rooms.indexOf(roomId) ;
					if(index !== -1) {
						res.push(ns.connected[id]);
					}
				}
				else {
					res.push(ns.connected[id]);
				}
			}
		}
		return res;
	}
	

});



