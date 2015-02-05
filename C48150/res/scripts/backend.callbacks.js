
var cbDefaultSuccess = function(data, status, xh) {
	cbStart(data, false);
	cbEnd(data);
};

var cbDefaultFail = function() {
	alert('Ajax request fail');
};

var cbIndexLoadSuccess = function(data, status, xh) {
	$('body').html($(data).find('xml').html());
	$('a.xhr-link[page="admin.services"]').click();
};

var cbSignIn = function() {
	$.ajax({
		url : 'core/xhr.admin.login.php',
		data : {
			email : $(this).parent().find('input[name="email"]').val(),
			password : $(this).parent().find('input[name="password"]').val()
		}
	});
};

var cbLoadPage = function() {
	$.ajax({
		url : 'core/xhr.' + $(this).attr('page') + '.php',
		success: cbLoadPageSuccess
	});
};

var cbLoadPageSuccess = function(data, status, xh) {
	cbStart(data, false);
	$('.body').html($(data).find('xml').html());
	cbEnd(data);
};

var cbLoadServices = function() {
	$.ajax({
		url : $(this).parent().find('input[name="url-services"]').val(),
		context : $(this).closest('form'),
		success: cbLoadServicesSuccess
	});
};

var cbLoadServicesSuccess = function(data, status, xh) {
	cbStart(data, false);
	
	var form = $(this),
		  select = form.find('select[name="services"]')
	
	select.html('');
	
	$(data).find('services service').each(function() {
		var name = $(this).find('name').text(),
		    params = $(this).find('params'),
		    sample = $(this).find('sample'),
				option = $('<option>').val(name).text(name);
		
		paramslist[name] = params;
		paramsamples[name] = sample;
		
		select.append(option);
	});
	
	cbEnd(data);
};

var cbLoadService = function() {
	var form = $(this).closest('form'),
			select = form.find('select[name="services"]'),
			servicename = select.val(),
			params = paramslist[servicename],
			sample = paramsamples[servicename].find('rsp'),
			tbody = form.find('table.service-params tbody');
			
	form.find('pre.sample').html(xml2string(sample).replace(/\t/g,''));
	form.find('.btn.request-service').removeClass('disabled');
	tbody.html('');
	
	$(params).find('param').each(function() {
		var tr = $('<tr>'),
		    name = $('<td>').text($(this).attr('name')).attr('class', 'name'),
				field = $('<input>').val($(this).attr('sample')).attr({
					placeholder:'Data type: '+$(this).attr('type'),
					type:'text',
					name: $(this).attr('name')
				}).addClass('input-block-level input-xxlarge'),
				input = $('<td>').append(field).attr('class', 'field'),
				checkbox = $('<input>').attr({type:'checkbox', name: 'chbx-'+$(this).attr('name'), checked: 1});
				send = $('<td>').append(checkbox).attr('class', 'send');
		
		if ($(this).attr('required') == 'true') {
			name.append($('<span style="color:red">*</span>'));
			checkbox.attr('disabled', 1);
		}
		
		tr.append(name).append(input).append(send);
		tbody.append(tr);
		
	});
};

var cbChooseService = function() {
	var form = $(this).closest('form'),
			input = form.find('input[name="service"]');
	
	form.find('.btn.load-service').removeClass('disabled');
	
	input.val($(this).val());
};

var cbRequestService = function() {
	
	var form = $(this).closest('form'),
			params = {};
			
	form.find('table.params tbody tr').each(function() {
		if($(this).find('td.send input').is(':checked')) {
			var name = $(this).find('td.field input, td.field textarea, td.field select').attr('name');
					value = $(this).find('td.field input, td.field textarea, td.field select').val();
			params[name] = value;
		}
	});
	
	$.ajax({
		url: form.find('input[name="url-service"]').val(),
		context: $(this).closest('form'),
		type: form.find('table.params select[name="request_type"]').val(),
		data: params,
		success: cbRequestServiceSuccess
	});
};

var cbRequestServiceSuccess = function(data, status, xh) {
	cbStart($(data), false);
	// .replace(/&gt;&lt;/g, '&gt;\n&lt;')
	$(this).find('pre.response').html(xml2string($(data).find('data')));
	cbEnd(data);
};


