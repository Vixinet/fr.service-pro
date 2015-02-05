// Params for services
var paramslist = {},
    paramsamples = {};

function cbStart(data, log) {
	
	if (log) {
		console.log(data);
	}
	
	$(data).find('error').each(function() {
		alert($(this).text());
	});
	
}

function cbEnd(data) {
	if($(data).find('redirect').length == 1) {
		window.location = $(data).find('redirect').text();
	}
}


function xml2string(xml) { 
	return $('<div>').text($('<textarea>').append(xml).html()).html();
}  