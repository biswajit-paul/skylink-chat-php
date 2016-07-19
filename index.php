<?php
include_once 'functions.php';

if( ! is_user_logged_in() ) {
	header("Location: " . site_url() . '/login.php');
	exit();
}

include_once 'header.php';

$room_id = isset( $_GET['rid'] ) ? sanitize_int( $_GET['rid'] ) : 1;
$room_details = $db->where('id', $room_id)->getOne('chat_rooms');
//echo '<pre>'; print_r($_SESSION); print_r($current_user); echo '</pre>';
?>

	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Brand</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
					<li><a href="<?php echo site_url() . '/chatroom.php'; ?>">Chatrooms</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#">One more separated link</a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="#">Link</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="<?php echo site_url() . '/logout.php' ?>">Logout</a></li>
						</ul>
					</li>
				</ul>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>

	<div class="row">
		<div class="col-md-4 bg-white ">
			<div class="border-bottom padding-sm" style="height:40px;">
				Member
			</div>

			<!-- member list -->
			<ul class="friend-list" id="UserList"></ul>
		</div>

		<!-- selected chat -->
		<div class="col-md-8 bg-white ">
			<div class="chat-message">
				<?php $chat_messages = $db->where('room_id', $room_id)->orderBy ("message_time","asc")->get('chat_messages', 20); ?>
				<ul class="chat" id="MessageList">
					<?php if( $db->count > 0 ) :	?>
						<?php foreach( $chat_messages as $message ) : ?>
							<?php $chat_user = get_user( $message['user_id'] ); ?>
							<li class="<?php echo ( $message['user_id'] == $current_user->user_id ) ? 'left' : 'right'; ?> clearfix">
								<span class="chat-img <?php echo ( $message['user_id'] == $current_user->user_id ) ? 'pull-left' : 'pull-right'; ?>">
									<img src="<?php echo $chat_user->user_pic; ?>" alt="<?php echo $chat_user->username; ?>">
								</span>
								<div class="chat-body clearfix">
									<div class="header">
										<strong class="primary-font"><?php echo $chat_user->username; ?></strong>
										<small class="pull-right text-muted timebox"><i class="fa fa-clock-o"></i> <time class="timeago" data-livestamp="<?php echo $message['message_time']; ?>"></time></small>
									</div>
									<p><?php echo $message['message']; ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
			<div class="chat-box bg-white">
				<div class="input-group">
					<input class="form-control border no-shadow no-rounded" id="MessageInput" placeholder="Type your message here">
					<span class="input-group-btn">
						<button class="btn btn-success no-rounded" id="MessageInputButton" type="button">Send</button>
					</span>
				</div><!-- /input-group -->
			</div>
		</div>
	</div>

<?php
include_once 'footer.php';
?>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.6.8-fix/jquery.nicescroll.min.js"></script>
<!--<script type="text/javascript" src="js/jquery.timeago.js"></script>-->
<script type="text/javascript" src="js/moment.min.js"></script>
<script type="text/javascript" src="js/livestamp.min.js"></script>
<script type="text/javascript" src="js/skylink.complete.js"></script>
<script type="text/javascript">
	var config = {
		apiKey:"8e9f94fb-4ef6-450c-868f-7202eafa76c6",
		//defaultRoom:"CHAT_ROOM"
		defaultRoom: '<?php echo $room_details['name']; ?>'
	};

	// Create our Skylink object
	var SkylinkDemo = new Skylink();
	var userList;
	var messageList;

	$(document).ready(function(){
		$(".chat-message").niceScroll();

		//$("time.timeago").timeago();

		//Get Object by ID
		userList = document.getElementById("UserList");
		messageList = document.getElementById("MessageList");
		var userInputMessage = document.getElementById("MessageInput");
		var userInputMessageButton = document.getElementById("MessageInputButton");

		//setName('');
		//setRoom('CHAT_ROOM');

		function getTextAndSend() {
			sendMessage(userInputMessage.value);
			userInputMessage.value = '';
		}
		userInputMessage.addEventListener("keypress", function(event) {
			if (event.keyCode == 13)
				getTextAndSend();
		});
		userInputMessageButton.addEventListener("click", function() {
			getTextAndSend();
		});
	});

	SkylinkDemo.init(config, function (error, success) {
		if (success) {
			var userObj = {
											"name": "<?php echo $current_user->username; ?>",
											"user_pic": "<?php echo $current_user->user_pic; ?>"
										};
			var userStr = JSON.stringify(userObj);

			//var displayName = '<?php echo $current_user->username; ?>';

			SkylinkDemo.joinRoom({
				userData: userStr,
				audio: false,
				video: false
			});

			var div = document.createElement('li');
			div.className = "alert alert-info msg-date";
			div.innerHTML = '<strong>Join Room "' + success.selectedRoom + '"</strong>';

			messageList.appendChild(div);
			//messageList.insertBefore(div, messageList.firstChild);

		} else {
			for (var errorCode in SkylinkDemo.READY_STATE_CHANGE_ERROR) {
				if (SkylinkDemo.READY_STATE_CHANGE_ERROR[errorCode] === error.errorCode) {
					var div = document.createElement('div');
					div.className = "alert alert-danger msg-date";
					div.innerHTML = '<strong>Impossible to connect to Skylink: ' + errorCode + '</strong>';
					messageList.appendChild(div);
					break;
				}
			}
		}
	});

	//New User in the room, we add it to the user list
	SkylinkDemo.on('peerJoined', function(peerId, peerInfo, isSelf) {
		console.log("Peer Joined");
		var timestamp = new Date();
		var userParsed = JSON.parse(peerInfo.userData);
		var div = document.createElement('li');
		div.className = "media conversation";
		div.id = "User_" + peerId;
		div.innerHTML = '<a href="#" class="clearfix">' +
						'<img src="'+ userParsed.user_pic +'" alt="'+ peerId +'" class="img-circle">' +
						'<div class="friend-name">' +
							'<strong>' +  userParsed.name + ((isSelf) ? " (You)" : "") + '</strong>' +
						'</div>' +
						'<small class="time text-muted" data-livestamp="'+ timestamp.toISOString() +'"></small>' +
						'</a>';
		userList.appendChild(div);
		console.log( peerInfo );
	});


	//User in the room changed his name
	SkylinkDemo.on('peerUpdated', function(peerId, peerInfo, isSelf) {
		var userParsed = JSON.parse(peerInfo.userData);
		document.getElementById("UserTitle_" + peerId).innerHTML = userParsed.name + ((isSelf) ? " (You)" : "");
	});

	//User in the room left
	SkylinkDemo.on('peerLeft', function(peerId, peerInfo, isSelf) {
		var elm = document.getElementById("User_" + peerId);
		if (elm) {
			elm.remove();
		} else {
			console.error('Peer "' + peerId + '" DOM element does not exists');
		}
	});

	//User in the room (including us) sent a message
	SkylinkDemo.on('incomingMessage', function(message, peerId, peerInfo, isSelf) {
		var userParsed = JSON.parse(peerInfo.userData);
		var Name = userParsed.name + ((isSelf) ? " (You)" : "");
		var SelfMe = isSelf;
		var UserPic = userParsed.user_pic;
		var peerIF = SkylinkDemo.getPeerInfo(peerId);
		console.log( peerIF );

		addMessage(Name, message.content, SelfMe, UserPic);

		// Save message to database
		if( isSelf ) {
			$.ajax({
				url: ajaxurl,
				method: 'post',
				data: {
					action: 'save_message',
					rid: '<?php echo $room_id; ?>',
					uid: '<?php echo $current_user->user_id; ?>',
					msg: message.content
				}
			});
		}

		$.timeago($("time.timeago"));
	});

	function sendMessage(message) {
		if (message != undefined) {
			message = message.trim(); //Protection for empty message
			if (message != '') {
				SkylinkDemo.sendP2PMessage(message);
			}
		}
	}

	function addMessage(user, message, isMe, MyPic) {
		var timestamp = new Date();
		var div = document.createElement('li');
		//var time_ago = $.timeago( timestamp.toISOString() );
		div.className = ( isMe ) ? 'left clearfix' : 'right clearfix';
		picClass = ( isMe ) ? 'pull-left' : 'pull-right';
		div.innerHTML = '<span class="chat-img '+ picClass +'">' +
											'<img src="'+ MyPic +'" alt="User Avatar">' +
										'</span>' +
										'<div class="chat-body clearfix">' +
											'<div class="header">' +
												'<strong class="primary-font">'+ user +'</strong>' +
												'<small class="pull-right text-muted timebox"><i class="fa fa-clock-o"></i> <time class="timeago" data-livestamp="'+ timestamp.toISOString() +'"></time></small>' +
											'</div>' +
											'<p>' + message + '</p>' +
										'</div>';

		messageList.appendChild(div);
		messageList.scrollTop = messageList.scrollHeight;
	}
</script>