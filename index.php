<?php
require_once('config/config.php');
require_once('services/message.php');

	$message = new Message();
	$response = $message->getMessage($_GET['status']);
	if ($_POST) {
		$response = $message->send($_POST);
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>MessageBird Magic</title>
	<script src="assets/js/message.js?v=1.0" type="text/javascript"></script>
	<link rel="stylesheet" href="assets/css/message.css?v=1.0">
</head>
<body onload="maxLimit()">

	<div class="logo">
		<img alt="Message Bird" src="assets/logo.gif">
	</div>
	<div class="message-box-area">
		<div class="request-message" id="request-message"><?php echo $response;?></div>
		<form action="#" method="post" onsubmit="return validation()" name="message_form">
			<div class="row">
				<label>To<span class="required">*</span>:</label>
				<select name="country_code">
					<option value="1">+1</option>
					<option value="31" selected="selected">+31</option>
					<option value="32">+32</option>
					<option value="41">+41</option>
					<option value="43">+43</option>
					<option value="44">+44</option>
					<option value="49">+49</option>
					<option value="92">+92</option>
					<option value="352">+352</option>
				</select>
				<input name="cellphone_number" id="cellphone_number" type="text" maxlength="11" placeholder="654321001"
				 value="<?php echo $_POST['cellphone_number'];?>" autofocus required> 
			</div>
			<div class="row">
				<label> Message<span class="required">*</span>: </label>
				<textarea name="message" id="message" placeholder="Your message will be here" 
				 onkeyup="maxLimit()" required><?php echo $_REQUEST['message'];?></textarea>
				<br/>Text Limit (<span id="remaining-count">300</span>)
			</div>
			<div class="row">
				<input type="submit" value="Send Message">
			</div>
		</form>
	</div>

</body>
</html>
