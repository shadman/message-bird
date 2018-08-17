<?php
require_once('config/config.php');
require_once('classes/messages.php');

	$message = new Message();
	if ($_POST) {
		$response = $message->send($_POST);
	}

	$response = $message->getMessage($_GET['status'])
?>
<!DOCTYPE html>
<html>
<head>
	<title>MessageBird Magic</title>
	<script src="assets/message.js?v=1.0" type="text/javascript"></script>
	<link rel="stylesheet" href="assets/message.css?v=1.0">
</head>
<body>

	<div class="logo">
		<img alt="Message Bird" src="assets/logo.png">
	</div>
	<div class="message-box-area">
		<div class="request-message" id="request-message"><?php echo $response;?></div>
		<form action="" method="post">
			<div class="row">
				To*: <br/> 
				<select name="country_code">
					<option value="001">+1</option>
					<option value="0031" selected="selected">+31</option>
					<option value="0032">+32</option>
					<option value="0041">+41</option>
					<option value="0043">+43</option>
					<option value="0044">+44</option>
					<option value="0049">+49</option>
					<option value="0092">+92</option>
					<option value="00352">+352</option>
				</select>
				<input name="to" type="text" maxlength="11" placeholder="654321001" autofocus> 
			</div>
			<div class="row">
				Message*: <br/> <textarea name="message" id="message" placeholder="Your message will be here" onkeyup="maxLimit()"></textarea>
				<br/>Text Limit (<span id="remaining-count">300</span>)
			</div>
			<div class="row">
				<input type="submit" value="Send Message">
			</div>
		</form>
	</div>

</body>
</html>
