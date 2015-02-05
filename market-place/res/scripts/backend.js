
$(document).ready(function() {
	
	// Define default Ajax params	
	$.ajaxSetup({
		type: 'post',
		success: cbDefaultSuccess,
		error: cbDefaultFail,
		dataType: 'html'
	});
	
	// Load the content of the page
	$.ajax({
		url: 'core/xhr.admin.index.php',
		success: cbIndexLoadSuccess
	});
	
	
	// Bind events
	$(document).delegate('.btn-sign-in', 'click', cbSignIn);
	$(document).delegate('a.xhr-link', 'click', cbLoadPage);
	$(document).delegate('ul.product-entity li', 'click', cbProductContentChangePage);
	
	$(document).delegate('.btn-offers-manage-upd', 'click', cbOffersUpdate);
	
	$(document).delegate('.btn-offers-import', 'click', cbOfferImport);
	
	$(document).delegate('.btn-pm-upd-pricing', 'click', cbPricingUpdate);
	$(document).delegate('.btn-pm-set-pricing', 'click', cbPricingChange);
	$(document).delegate('.btn-pm-set-stock', 'click', cbStockChange);
	$(document).delegate('.btn-watch-reset', 'click', cbWatchReset);
	
	$(document).delegate('.btn-product-edit', 'click', cbProductEdit);
	$(document).delegate('.btn-product-open', 'click', cbProductOpen);
	$(document).delegate('.btn-product-add', 'click', cbProductAdd);
	$(document).delegate('.btn-product-delete', 'click', cbProductDelete);
	
	$(document).delegate('a.sku-report', 'click', cbSkuReport);
	$(document).delegate('.btn.chk_on', 'click', cbChkOn);
	$(document).delegate('.btn.chk_off', 'click', cbChkOff);
	
});

// ok
var cbSkuReport = function() {
	var val = $(this).parent().find('select').val();
	
	if ($(this).hasClass('all')) {
		$(this).closest('table').find('select.sku').val(val);
	} else if($(this).hasClass('bot')) {
		$(this).closest('tr').nextAll().find('td select.sku').val(val);
	} else {
		$(this).closest('tr').prevAll().find('td select.sku').val(val);
	}
};

// ok
var cbChkOn = function() {
	var table = $(this).closest('form');
	table.find('input[type="checkbox"]').each(function() {
		$(this).prop('checked', true);
	});
}

// ok
var cbChkOff = function() {
	var table = $(this).closest('form');
	table.find('input[type="checkbox"]').each(function() {
		$(this).prop('checked', false);
	});
}

var cbWatchReset = function() {
	tableMessageLoader();
	$.ajax({
		url: 'core/xhr.pricing.watch.reset.php'
	});
};

// ok
var cbStockChange = function() {
	tableMessageLoader();
	var form = $(this).closest('form');
	
	$.ajax({
		url: 'core/xhr.product.entity.pm.update.stock.php',
		data: {sku : $(form).find('input[name="sku"]').val()}
	});
};

// ok
var cbPricingChange = function() {
	tableMessageLoader();
	var form = $(this).closest('form'),
			d = {};
	
	$('input', form).each(function() {
		d[$(this).attr('name')] = $(this).attr('type') == 'checkbox' ? $(this).is(':checked'): $(this).val();
	});
	
	$.ajax({
		url: 'core/xhr.pricing.update.php',
		data: d,
		success: cbLoadPageSuccess
	});
};

// ok
var cbPricingUpdate = function() {
	tableMessageLoader();
	$.ajax({
		url: 'core/xhr.srv.pm.upd.pricing.php'
	});
};

// ok
var cbOffersUpdate = function() {
	var form = $(this).closest('form'),
			d = {};
	
	$('input, select', form).each(function() {
		d[$(this).attr('name')] = $(this).val();
	});
	
	$.ajax({
		url: 'core/xhr.offers.upd.php',
		data: d,
		success: cbLoadPageSuccess
	});
};

// ok
var cbOfferImport = function() {
	var form = $(this).closest('form');
	tableMessageLoader();
	$.ajax({
		url: 'core/xhr.srv.pm.upd.offers.php'
	});
};

// ok
var cbProductAdd = function() {
	var form = $(this).closest('form');
	$.ajax({
		url: 'core/xhr.product.entity.add.php',
		data: {
			sku: form.find('input[name="sku"]').val(),
		},
		success : cbLoadPageSuccess
	});
};

// ok
var cbProductDelete = function() {
	var form = $(this).closest('tr');
	$.ajax({
		url: 'core/xhr.product.entity.delete.php',
		data: {
			sku: form.find('input[name="sku_ref"]').val(),
		},
		success : cbLoadPageSuccess
	});
};

// ok
var cbProductEdit = function() {
	var form = $(this).closest('tr');
	$.ajax({
		url: 'core/xhr.product.entity.edit.php',
		data: {
			sku: form.find('input[name="sku"]').val(),
			sku_ref: form.find('input[name="sku_ref"]').val(),
			stock: form.find('input[name="stock"]').val(),
			price_base: form.find('input[name="price_base"]').val(),
			price_buy: form.find('input[name="price_buy"]').val()
		},
		success : cbLoadPageSuccess
	});
};

// OK
var cbProductOpen = function() {
	$.ajax({
		url: 'core/xhr.product.entity.info.php',
		data: {
			sku:$(this).closest('tr').find('input[name="sku_ref"]').val()
		},
		success : cbLoadPageSuccess
	});
}

// ok
var cbDefaultSuccess = function(data, status, xh) {
	cbStart(data, false);
	cbEnd(data);
};

// ok
var cbDefaultFail = function() {
	alert('Ajax request fail');
};

// ok
var cbIndexLoadSuccess = function(data, status, xh) {
	$('body').html($(data).find('response').html());
	$('a.xhr-link').first().click();
};

// ok
var cbLoadPage = function() {
	$('.menu li').removeClass('active');
	$(this).closest('li').addClass('active');
	tableMessageEmpty();
	$.ajax({
		url: 'core/xhr.' + $(this).attr('page') + '.php',
		success: cbLoadPageSuccess,
		context: this
	});
};

// ok
var cbLoadPageSuccess = function(data, status, xh) {
	cbStart(data, true);
	$('.body').html($(data).find('response').html());
	cbEnd(data);
};

// ok
var cbSignIn = function() {
	$.ajax({
		url : 'core/xhr.admin.login.php',
		data : {
			email : $(this).parent().find('input[name="email"]').val(),
			password : $(this).parent().find('input[name="password"]').val()
		}
	});
};

var cbProductContentLoadTable = function(data, status, xh) {
	cbStart(data, true);
	$('.products-table').html($(data).find('response').html());
	cbEnd(data);
};

var cbProductContentChangePage = function() {
	var page = $(this).prop('tagName') == 'LI' ? $(this).text().trim() : 1;
	$.ajax({
		url: 'core/xhr.product.entity.listing.php?page='+page,
		success : cbProductContentLoadTable
	});
}

// ok
function cbStart(data, log) {
	
	if (log) {
		console.log(data);
	}
	
	if ($(data).find('errors message, information message, warnings message').length > 0) {
		
		$('table.messages tbody').html('');
		$('table.messages').show();
		
		$(data).find('errors message').each(function() {
			var c = $(this).attr('code');
			var m = $(this).text();
			$('table.messages tbody').append('<tr class="error"><td>'+c+'</td><td>'+m+'</td></tr>');
		});
		
		$(data).find('warnings message').each(function() {
			var c = $(this).attr('code');
			var m = $(this).text();
			$('table.messages tbody').append('<tr class="warning"><td>'+c+'</td><td>'+m+'</td></tr>');
		});
		
		$(data).find('information message').each(function() {
			var c = $(this).attr('code');
			var m = $(this).text();
			$('table.messages tbody').append('<tr class="success"><td>'+c+'</td><td>'+m+'</td></tr>');
		});
	}
	
}

// ok
function cbEnd(data) {
	if($(data).find('redirect').length == 1) {
		window.location = $(data).find('redirect').text();
	}
}

// ok
function tableMessageLoader() {
	$('table.messages').show();
	$('table.messages tbody').html('<tr><td colspan="2"><p style="margin:0px;" class="text-center"><img src="res/images/loader.gif"/></p></td></tr>');
}

// ok
function tableMessageEmpty() {
	$('table.messages').hide();
}