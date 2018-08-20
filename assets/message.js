
	const minMessageLimit = 10;
	const maxMessageLimit = 320;
	const minCellphoneLimit = 9;
	const minCountryCodeLimit = 2;

	function maxLimit() {
		var message = document.getElementById('message').value;
		var error_message = document.getElementById('request-message');

		if (message.length > maxMessageLimit) 
			error_message.innerHTML = 'Text message should be in between 10 and 320 !';
		else if (message.length >= minMessageLimit && message.length <= maxMessageLimit && 
			cellphone_number > minCellphoneLimit) 
			error_message.innerHTML = '';

		var remaining = maxMessageLimit - message.length;
		document.getElementById('remaining-count').innerHTML = remaining;
	}


	function validation() {
		var message = document.getElementById('message').value;
		var cellphone_number = document.getElementById('cellphone_number').value;
		var error_message = document.getElementById('request-message');

		if (message.length >= maxMessageLimit) {
			error_message.innerHTML = 'Max. text limit reached !';
			return false;
		} else if (message.length < minMessageLimit) {
			error_message.innerHTML = 'Text message should be in between 10 and 320 !';
			return false;
		} else if (isNaN(cellphone_number) || cellphone_number.length < minCellphoneLimit) {
			error_message.innerHTML = 'Cell Phone is invalid!';
			return false;
		}

		return true;	
	}