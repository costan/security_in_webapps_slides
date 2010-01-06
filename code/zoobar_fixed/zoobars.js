var div = document.getElementById('myZoobars');

if (div != null) {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', 'zoobars.txt.php', true);
	xhr.onreadystatechange = function(event) {
		if (xhr.readyState == 4) {
			if (xhr.status == 200) {
				div.innerHTML = xhr.responseText;
			}
			else {
				div.innerHTML = ":(";
				console.log(req.responseText);
			}
		}
	}
	xhr.send(null);
}
