
$(document).ready(function() {
	
	// Define default Ajax params	
	$.ajaxSetup({
		type: 'post',
		success: cbDefaultSuccess,
		error: cbDefaultFail
	});
	
	// Load the content of the page
	$.ajax({
		url: 'core/xhr.admin.index.php',
		success: cbIndexLoadSuccess
	});
	
	// Bind events
	$(document).delegate('.btn-sign-in', 'click', cbSignIn);
	$(document).delegate('a.xhr-link', 'click', cbLoadPage);
	$(document).delegate('.btn.url-services', 'click', cbLoadServices);
	$(document).delegate('.btn.load-service', 'click', cbLoadService);
	$(document).delegate('.btn.request-service', 'click', cbRequestService);
	$(document).delegate('select[name="services"]', 'change', cbChooseService);
});