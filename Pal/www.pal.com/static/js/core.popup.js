$.waring = function ($msg = null) {
	if (!$msg) {
		return false;
	}

	var div = document.createElement('div');

	div.class = "none";
	div.id = "waring";
	div.innerHTML = $msg;
	document.body.appendChild(div);

	var clickHidden = function () {
		var waring = document.getElementById('waring');
		if (waring !== null) {
			document.body.removeChild(waring);
		}
	};

	document.addEventListener('click', clickHidden, false);

	setTimeout(function () {
		var waring = document.getElementById('waring');
		if (waring !== null) {
			document.body.removeChild(waring);
		}
	}, 5000);
};