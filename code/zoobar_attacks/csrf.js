var frame = document.getElementById('form_target'); 
var form = document.getElementById('post_form');
form.target = frame.name;
frame.addEventListener('load', function() {
	window.location = "http://pdos.csail.mit.edu/6.893/2009/";
}, false);
form.submit();
