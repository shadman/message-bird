

function maxLimit() {
	//alert(document.getElementById("message").value);
	var message = document.getElementById('message').value;

	error_message = document.getElementById('request-message');
	if (message.length >= 300) error_message.innerHTML = 'Max. text limit reached !';
	else error_message.innerHTML = '';

	var remaining = 300 - message.length;
	document.getElementById('remaining-count').innerHTML = remaining;
}