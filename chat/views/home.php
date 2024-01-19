<!DOCTYPE html>

<!-- 	
	This file is rendered by express.js, when the home url of the chat is opened in a browser.
	It doesn't do much, except showing the create button for the chat.
 -->

<html>

<head>

	<title>Create a new chat room! | Tutorialzine Demo</title>

	<link type="text/css" rel="stylesheet" href="http://localhost/wolero/planning/chat/public/css/stylesheet.css" />
	<link href="http://fonts.googleapis.com/css?family=Open+Sans Condensed:300italic,300,700" rel="stylesheet" type="text/css">


</head>

<body>

	<header class="homebanner">

			<h1 class="homebannertext">
				<a href="http://tutorialzine.com/2014/03/nodejs-private-webchat/" id="logo">Tutorial<span>zine</span></a>
			</h1>

	</header>

	<section>

		<div class="homesection">

			<div class="node">
				<img src="../public/img/nodejslogo.png" alt="nodelogo" id="nodelogo"/>
				<h2 id="chat">powered web chat</h2>
			</div>

			<a title="Create" href="chat.php" id="create">
				<div id="createbutton">
					<div id="little">Create a Private</div>
					<div id="big">CHAT ROOM!</div>
				</div>
			</a>

			<div class="tutorial">
				<a title="Download the source code." href="http://tutorialzine.com/2014/03/nodejs-private-webchat/" id="tutorial">
					or download a copy of the source code here
				</a>
			</div>
		</div>

	</section>

</body>
</html>
