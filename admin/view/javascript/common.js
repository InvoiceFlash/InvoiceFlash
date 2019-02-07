function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}
token=getURLVar('token');
route=getURLVar('route');
if(!route){
	$('#dashboard').addClass('active');
}else{
	part=route.split('/');
	if(part[1]){
		$('a[href*="'+part[0]+'/'+part[1]+'"]').parents('li[id]').addClass('active');
	}
}
function filter(){
	url='index.php?route='+getURLVar('route')+'&token='+token;
	$('#filter').find('select,:text').each(function(){
		var a=$(this).val();
		if(a&&a!='*'){
			url+='&'+$(this).attr('name')+'='+encodeURIComponent(a);
		}
	});
	
	location=url;
}
!function(d){
	var c=function(a,f){
		f=d.extend({},d.fn.rowlink.defaults,f);
		var e=a.nodeName.toLowerCase()=="tr"?d(a):d(a).find("tr:not(#filter):has(td)").not(".rowlink-skip");
		var z=route.replace('/','_')+'_offset';
		if(localStorage.getItem(z)){
			d('html,body').animate({
				scrollTop:localStorage.getItem(z)
			},100);
			localStorage.removeItem(z);
		}
		e.each(function(){
			var g=d(this).find(f.target).first();
			if(!g.length){
				return
			}
			var h=g.attr("href");
			d(this).find("td").not(".rowlink-skip").click(function(e){
				if(e.target.className!='rowlink-skip'){
					localStorage.setItem(z,$(window).scrollTop());
					window.location=h;
				}
			}),d(this).addClass("rowlink")
		})
	};
	d.fn.rowlink=function(a){
		return this.each(function(){
			var f=d(this),b=f.data("rowlink");
			b||f.data("rowlink",b=new c(this,a))
		})
	},d.fn.rowlink.defaults={
		target:"a:not([target]):not([onclick])"
	},d.fn.rowlink.Constructor=c,d(function(){
		d('[data-link="row"]').each(function(){
			d(this).rowlink(d(this).data())
		})
	})
}(window.jQuery);
// Habilitar Submenus
$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
	if (!$(this).next().hasClass('show')) {
		$(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
	}
	var $subMenu = $(this).next(".dropdown-menu");
	$subMenu.toggleClass('show');
	
	$(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
		$('.dropdown-submenu .show').removeClass("show");
	});

	return false;
});
var alertMessage=function(state,msg){
	var html='<div class="alert alert-'+state+' alert-dismissable" style="display:none;"><a class="close" data-dismiss="alert" href="#">&times;</a>'+msg+'</div>';
	
	$('#notification').html(html);
	$('#notification>.alert').fadeIn('slow').delay(15000).fadeTo(2000,0,function(){
		$(this).remove();
	});
};
// datepicker
$(document).on('focus','.date',function(e){
	e.stopPropagation();
	$(this).datetimepicker({
		format:'DD-MM-YYYY'
	});
});
$(document).on('focus','.time',function(e){
	e.stopPropagation();
	$(this).datetimepicker({
		format: "LT"
	});
});
$(document).on('focus','.datetime',function(e){
	e.stopPropagation();
	$(this).datetimepicker();
});
$(document).ajaxError(function(event,xhr,ajaxSettings,thrownError){
	$('#notification').html($('<div>',{class:'alert alert-danger'}).html(thrownError+"\r\n"+xhr.statusText));
});
$(document).ready(function() {
	// Mostrar contenido primera pestaña
	$('.nav-tabs,.nav-pills').each(function(){
		$(this).find('[data-toggle="tab"]:first').tab('show');
	});
	$('.help-block.error').closest('.form-group').addClass('has-error');
	$(document).on('click','.list-group .label-trash',function(){
		$(this).parent().remove();
	});
	// Auto active tabs
	$('.nav-tabs .nav-item:first-child a').addClass('active');
	$(".tab-pane:first-child").addClass('active');

	//Form Submit for IE Browser
	$('button[type=\'submit\']').on('click', function() {
		$('form[id*=\'form-\']').submit();
	});
	$('#btn-delete').on('click',function(e){
		e.preventDefault();
		if(confirm(text_confirm)){
			$('#form').attr('action',$(this).attr('formaction')).submit();
		}
	});
	$('a').click(function(){
		if($(this).attr('href')!=null&&$(this).attr('href').indexOf('uninstall',1)!=-1){
			if(!confirm(text_confirm)){
				return false;
			}
		}
	});
	// Highlight any found errors
	$('.invalid-tooltip').each(function() {
		var element = $(this).parent().find(':input');

		if (element.hasClass('form-control')) {
			element.addClass('is-invalid');
		}
	});
	$('[data-toggle="selected"]').click(function(){
		$('input[name^="selected"]').prop('checked',this.checked);
	});
	$('.invalid-tooltip').show();

	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});

	// tooltip remove
	$('[data-toggle=\'tooltip\']').on('remove', function() {
		$(this).tooltip('dispose');
	});

	// Tooltip remove fixed
	$(document).on('click', '[data-toggle=\'tooltip\']', function(e) {
		$('body > .tooltip').remove();
	});
	$('.help-block.error').closest('.form-group').addClass('has-error');
	$(document).on('click','.list-group .label-trash',function(){
		$(this).parent().remove();
	});
	
	$.event.special.remove = {
		remove: function(o) {
			if (o.handler) {
				o.handler.apply(this, arguments);
			}
		}
	}

	$('#button-menu').on('click', function(e) {
		e.preventDefault();

		$('#column-left').toggleClass('active');
	});

	// Set last page opened on the menu
	$('#menu a[href]').on('click', function() {
		sessionStorage.setItem('menu', $(this).attr('href'));
	});

	if (!sessionStorage.getItem('menu')) {
		$('#menu #dashboard').addClass('active');
	} else {
		// Sets active and open to selected page in the left column menu.
		$('#menu a[href=\'' + sessionStorage.getItem('menu') + '\']').parent().addClass('active');
	}

	$('#menu a[href=\'' + sessionStorage.getItem('menu') + '\']').parents('li > a').removeClass('collapsed');

	$('#menu a[href=\'' + sessionStorage.getItem('menu') + '\']').parents('ul').addClass('show');

	$('#menu a[href=\'' + sessionStorage.getItem('menu') + '\']').parents('li').addClass('active');

	$(document).on('click', '[data-toggle=\'clear\']', function() {
		var element = this;

		$('#' + $(this).attr('data-thumb')).attr('src', $('#' + $(this).attr('data-thumb')).attr('data-placeholder'));

		$('#' + $(this).attr('data-target')).val('');
	});
	// table dropdown responsive fix
	$('.table-responsive').on('shown.bs.dropdown', function(e) {
		var t = $(this),
			m = $(e.target).find('.dropdown-menu'),
			tb = t.offset().top + t.height(),
			mb = m.offset().top + m.outerHeight(true),
			d = 20;

		if (t[0].scrollWidth > t.innerWidth()) {
			if (mb + d > tb) {
				t.css('padding-bottom', ((mb + d) - tb));
			}
		} else {
			t.css('overflow', 'visible');
		}
	}).on('hidden.bs.dropdown', function() {
		$(this).css({
			'padding-bottom': '',
			'overflow': ''
		});
	});

	$('[name="filter_name"],[name="filter_product"],[name="filter_model"],[name="filter_customer"],[name="filter_company"]').each(function(){
		var a=$(this),b=a.data('target');
		a.typeahead({
			source:function(q,process){
				return $.getJSON('index.php?route='+a.data('url')+'/autocomplete&token='+token+'&filter_'+b+'='+encodeURIComponent(q),function(json){
					var data=[];
					$.each(json,function(){
						data.push(this[b]);
					});
					return process(data);
				});
			},
			updater:function(item){
				a.val(item);
				filter();
				return item;
			}
		}).attr('autocomplete','off').click(function(){
			a.select();
		});
	}).first().select();
	// Filter Payroll/Subaccounts
	var a=$('input[name="filter_nombre"]'), mapped={};
	$('input[name="filter_nombre"]').typeahead({
		source: function(q, process){
			return $.getJSON('index.php?route=payroll/accounting_subaccounts/autocomplete&token='+token+'&filter_nombre='+encodeURIComponent(q), function(json){
				var data=[];
				$.each(json, function(i, item){
					mapped[item.name] = item.subaccount_id;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater: function(item){
			$('input[name="filter_nombre"]').val(mapped[item]);
			return item;
		}
	}).click(function(){
		this.select();
	}).first().select();
	var a=$('input[name="path"]'),mapped={};
	$('input[name="path"]').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/category/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item.category_id;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('input[name="parent_id"]').val(mapped[item]);
			return item;
		}
	}).click(function(){
		this.select();
	});
	
	var mapped={};
	$('#return-customer').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=sale/customer/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('input[name="customer"]').val(item);
			$('input[name="customer_id"]').val(mapped[item].customer_id);
			$('input[name="firstname"]').val(mapped[item].firstname);
			$('input[name="lastname"]').val(mapped[item].lastname);
			$('input[name="email"]').val(mapped[item].email);
			$('input[name="telephone"]').val(mapped[item].telephone);
			return item;
		}
	}).click(function(){
		this.select();
	});
	
	var mapped={};
	$('#return-product').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/product/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('input[name="product_id"]').val(mapped[item].product_id);
			$('input[name="model"]').val(mapped[item].model);
			return item;
		}
	}).click(function(){
		this.select();
	});
	
	var mapped={};
	$('#review-product').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/product/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item.product_id;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('input[name="product_id"]').val(mapped[item]);
			return item;
		}
	}).click(function(){
		this.select();
	});
	
	var mapped={};
	$('input[name="manufacturer"]').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/manufacturer/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item.manufacturer_id;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('input[name="manufacturer_id"]').val(mapped[item]);
			return item;
		}
	});
	
	$('input[name="category"]').each(function(){
		var a=$(this),b=a.data('target'),mapped={};
		a.typeahead({
			source:function(q,process){
				return $.getJSON('index.php?route=catalog/category/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
					var data=[];
					$.each(json,function(i,item){
						mapped[item.name]=item.category_id;
						data.push(item.name);
					});
					process(data);
				});
			},
			updater:function(item){
				$('#'+b+'-category'+mapped[item]).remove();
				$('#'+b+'-category').append('<div class="list-group-item" id="'+b+'-category'+mapped[item]+'">'+item+'<a class="label label-danger label-trash"><i class="fa fa-trash-o fa-lg"></i></a><input type="hidden" name="'+b+'_category[]" value="'+mapped[item]+'"></div>');
				return null;
			}
		});
	});
	
	var a=$('input[name="filter"]'),b=a.data('target'),mapped={};
	a.typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/filter/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item.filter_id;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('#'+b+'-filter'+mapped[item]).remove();
			$('#'+b+'-filter').append('<div class="list-group-item" id="'+b+'-filter'+mapped[item]+'">'+item+'<a class="label label-danger label-trash"><i class="fa fa-trash-o fa-lg"></i></a><input type="hidden" name="'+b+'_filter[]" value="'+mapped[item]+'"></div>');
			return null;
		}
	});
	var mapped={};
	$('input[name="download"]').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/download/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item.download_id;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('#product-download'+mapped[item]).remove();
			$('#product-download').append('<div class="list-group-item" id="product-download'+mapped[item]+'">'+item+'<a class="label label-danger label-trash"><i class="fa fa-trash-o fa-lg"></i></a><input type="hidden" name="product_download[]" value="'+mapped[item]+'"></div>');
			return null;
		}
	});
	
	var mapped={};
	$('input[name="related"]').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/product/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item.product_id;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('#product-related'+mapped[item]).remove();
			$('#product-related').append('<div class="list-group-item" id="product-related'+mapped[item]+'">'+item+'<a class="label label-danger label-trash"><i class="fa fa-trash-o fa-lg"></i></a><input type="hidden" name="product_related[]" value="'+mapped[item]+'"></div>');
			return null;
		}
	});
	
	var mapped={};
	$('input[name="products"]').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/product/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item.product_id;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('#product'+mapped[item]).remove();
			$('#product').append('<div class="list-group-item" id="product'+mapped[item]+'">'+item+'<a class="label label-danger label-trash"><i class="fa fa-trash-o fa-lg"></i></a><input type="hidden" name="product[]" value="'+mapped[item]+'"></div>');
			return null;
		}
	});
	
	var mapped={};
	$('input[name="coupon_products"]').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/product/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item.product_id;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('#coupon-product'+mapped[item]).remove();
			$('#coupon-product').append('<div class="list-group-item" id="coupon-product'+mapped[item]+'">'+item+'<a class="label label-danger label-trash"><i class="fa fa-trash-o fa-lg"></i></a><input type="hidden" name="coupon_product[]" value="'+mapped[item]+'"></div>');
			return null;
		}
	});
	var mapped={};
	$('#blog').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/blog/autocomplete&token='+token+'&filter_article='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.article]=item.blog_id;
					data.push(item.article);
				});
				process(data);
			});
		},
		updater:function(item){
			$('#blog_id').val(mapped[item]);
			return item;
		}
	});
	
});
function attributeautocomplete(attribute_row){
	var mapped={};
	$('input[name="product_attribute['+attribute_row+'][name]"]').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/attribute/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item.attribute_id;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('input[name="product_attribute['+attribute_row+'][attribute_id]"]').val(mapped[item]);
			return item;
		}
	}).click(function(){
		this.select();
	});
}
// Generate Purchases 
$(function(){
	$('#generate_purchase').click(function(){
		var action=$(this).val();
		var urlaction;
		if (action=="reception") {
			urlaction='index.php?route=purchases/order/generateReception&token='+token+'&order_id='+getURLVar('order_id');
		}
		$.ajax({
			url:urlaction,
			dataType:'json',
			beforeSend:function(){
				$('#generate_purchase').button('loading').append($('<i>',{class:'icon-loading'}));
			},
			success:function(json){
				if(json['error']){
					$('#generate_purchase').button('reset');
					alertMessage('danger',json['error']);
				}
				if (json['reception_id']){
					var id=json['reception_id'];
					var urlhtml='';
					if (action=="reception"){
						urlhtml='<a href="index.php?route=purchases/receptions/info&token='+token+'&reception_id='+parseInt(id)+'">'+id+'</a>' ;
					}
					$('#blockgenerate').html(urlhtml);
				}
			}
		});
	});
});
/*--- quote_info.tpl ---*/
$(function(){
	$('#generate').click(function(){
		var action = $(this).val();
		var urlaction ;
		if (action=="order"){
			urlaction = 'index.php?route=sale/quote/createOrder&token='+token+'&quote_id='+getURLVar('quote_id') ;
		}else if(action=="delivery"){
			urlaction = 'index.php?route=sale/order/createdelivery&token='+token+'&order_id='+getURLVar('order_id');
		}else if(action=="invoice"){
			urlaction = 'index.php?route=sale/delivery/createInvoice&token='+token+'&delivery_id='+getURLVar('delivery_id');
		}
		$.ajax({
			url:urlaction,
			dataType:'json',
			beforeSend:function(){
				$('#generate').button('loading').append($('<i>',{class:'icon-loading'}));
			},
			success:function(json){
				if(json['error']){
					$('#generate').button('reset');
					alertMessage('danger',json['error']);
				}
				if (json['invoice_no']){
					// Vamos a sacar el id del order al que dirigirá el enlace.
					var prefix = json['invoice_no'];
					var pos = prefix.lastIndexOf('-');
					var nextid = prefix.substring(pos+1);
					var urlhtml ;
					if (action=="order"){
						urlhtml = '<a href="index.php?route=sale/order/info&token='+token+'&order_id='+parseInt(nextid)+'">'+json['invoice_no']+'</a>' ;
					}else if(action=="delivery"){
						urlhtml = '<a href="index.php?route=sale/delivery/info&token='+token+'&delivery_id='+parseInt(nextid)+'">'+json['invoice_no']+'</a>' ;
					}else if(action=="invoice"){
						urlhtml = '<a href="index.php?route=sale/invoice/info&token='+token+'&invoice_id='+parseInt(nextid)+'">'+json['invoice_no']+'</a>' ;
					}
					// Cambiamos el boton 'generate' por un enlace al pedido recien creado
					$('#blockgenerate').html(urlhtml);
				}
			}
		});
	});
});
$(function(){
	$('#attribute tr').each(function(i){
		attributeautocomplete(i);
	});
	$('.btn-action').click(function(){
    var url = $(this).data("url"); 
	alert( url) ;
    $.ajax({
        type: "GET",
        url: url,
        dataType: 'json',
        success: function(res) {
            
            // get the ajax response data
            var data = res.body;
            // update modal content
            $('.modal-body').text(data.someval);
            // show modal
            $('#myModal').modal('show');
            
        },
        error:function(request, status, error) {
            console.log("ajax call went wrong:" + request.responseText);
        }
    });
});
	$('#button-history').click(function(){
		var a=$(this),b=a.data('action'),c=a.data('id'),d=a.data('target');
		$.ajax({
			url:'index.php?route='+d+'/'+b+'/history&token='+token+'&'+b+'_id='+c,
			type:'post',
			dataType:'html',
			data:b+'_status_id='+encodeURIComponent($('select[name="'+b+'_status_id"]').val())+'&notify='+encodeURIComponent($('input[name="notify"]').attr('checked') ? 1 :0)+'&append='+encodeURIComponent($('input[name="append"]').attr('checked') ? 1 :0)+'&comment='+encodeURIComponent($('textarea[name="comment"]').val()),
			beforeSend:function(){
				a.button('loading').append($('<i>',{class:'icon-loading'}));
			},
			complete:function(){
				a.button('reset');
			},
			success:function(html){
				$('#history').html(html);
				$('textarea[name="comment"]').val(''); 
				$('#'+b+'-status').html($('select[name="'+b+'_status_id"] option:selected').text());
			}
		});
	});
	if ($("#history").length > 0) {
		var a=$('#history');
		$(document).on('click','#history .pagination a',function(e){
			e.preventDefault();
			$('#history').on('load',(this.href));
		});
		a.load(a.data('href'));	
	}
	
	var a=$('#transaction');
	$(document).on('click','#transaction .pagination a',function(e){
		e.preventDefault();
		$('#transaction').on('load',(this.href));
	});
	// a.load(a.data('href'));
	$('#transaction').on('load',(this.href));
	
	$('#tab-transaction input,#tab-reward input').on('keypress',function(e){
		if (e.keyCode==13){               
			e.preventDefault();
			$(this).closest('.tab-pane').find('button[type="button"]').click();
		}
	});
	$(document).on('click','#button-transaction',function(e){
		var a=$(this),b=a.data('target');
		$.ajax({
			url:'index.php?route=sale/'+b+'/transaction&token='+token+'&'+b+'_id='+a.data('id'),
			type:'post',
			dataType:'html',
			data:'description='+encodeURIComponent($('#tab-transaction input[name="description"]').val())+'&amount='+encodeURIComponent($('#tab-transaction input[name="amount"]').val()),
			beforeSend:function(){
				a.button('loading').append($('<i>',{class:'icon-loading'}));
			},
			complete:function(){
				a.button('reset');
			},
			success:function(html){
				$('#transaction').html(html);
				$('#tab-transaction input[name="amount"],#tab-transaction input[name="description"]').val('');
			}
		});
	});
	
	$(document).on('click', 'a[data-toggle=\'image\']', function(e) {
		var $element = $(this);
		var $popover = $element.data('bs.popover'); // element has bs popover?
		
		e.preventDefault();

		// destroy all image popovers
		$('a[data-toggle="image"]').popover('dispose');

		// remove flickering (do not re-add popover when clicking for removal)
		if ($popover) {
			return;
		}

		$element.popover({
			html: true,
			placement: 'right',
			trigger: 'manual',
			content: function() {
				return '<button type="button" id="button-image" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></button> <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash"></i></button>';
			}
		});

		$element.popover('show');

		$('#button-image').on('click', function() {
			var $button = $(this);
			var $icon   = $button.find('> i');
			
			$('#modal-image').remove();
			$.ajax({
				url: 'index.php?route=common/filemanager&token=' + getURLVar('token') + '&target=' + $element.parent().find('input').attr('id') + '&thumb=' + $element.attr('id'),
				dataType: 'html',
				beforeSend: function() {
					$button.prop('disabled', true);
					if ($icon.length) {
						$icon.attr('class', 'fa fa-circle-notch fa-spin');
					}
				},
				complete: function() {
					$button.prop('disabled', false);
					if ($icon.length) {
						$icon.attr('class', 'fas fa-pencil-alt');
					}
				},
				success: function(html) {
					$('body').append('<div id="modal-image" class="modal">' + html + '</div>');

					$('#modal-image').modal('show');
				}
			});

			$element.popover('dispose');
		});

		$('#button-clear').on('click', function() {
			$element.find('img').attr('src', $element.find('img').attr('data-placeholder'));

			$element.parent().find('input').val('');

			$element.popover('dispose');
		});
	});

});
function newFunction(idNom) {
	if (idNom == "receptions") {
		idNom = "reception";
	}
	return idNom;
}
/*--- order_form.tpl ---*/
$(function(){
	var mapped={};
	$('#order-customer').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=sale/customer/autocomplete&token='+token+'&filter_company='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.company]=item;
					data.push(item.company);
				});
				process(data);
			});
		},
		updater:function(item){
			$('input[name="customer"]').val(mapped[item].company);
			$('input[name="company"]').val(mapped[item].company);
			$('input[name="customer_id"]').val(mapped[item].customer_id);
			$('input[name="firstname"]').val(mapped[item].firstname);
			$('input[name="lastname"]').val(mapped[item].lastname);
			$('input[name="email"]').val(mapped[item].email);
			$('input[name="telephone"]').val(mapped[item].telephone);
			$('input[name="fax"]').val(mapped[item].fax);
			html='<option value="0">&mdash;</option>'; 
			for(i in mapped[item].address){
				html+='<option value="'+mapped[item].address[i]['address_id']+'">'+mapped[item].address[i]['firstname']+' '+mapped[item].address[i]['lastname']+','+mapped[item].address[i]['address_1']+','+mapped[item].address[i]['city']+','+mapped[item].address[i]['country']+'</option>';
			}

			$('select[name="shipping_address"]').html(html);
			$('select[name="payment_address"]').html(html);

			// Auto seleccionar el 2º elemento de las listas (primer address)
			$('select[name="shipping_address"] option:nth-child(2)').attr('selected', true).change();
			$('select[name="payment_address"] option:nth-child(2)').attr('selected', true).change();

			$('select[id="customer_group_id"]').button('reset').val(mapped[item]['customer_group_id']).change().button('loading'); 
			
			return item;
		}
	}).click(function(){
		this.select();
	});
	$('#order-supplier').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=purchases/supplier/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('input[name="supplier"]').val(item);
			$('input[name="supplier_id"]').val(mapped[item].supplier_id);
			$('input[name="email"]').val(mapped[item].email);
			$('input[name="telephone"]').val(mapped[item].telephone);
			$('input[name="fax"]').val(mapped[item].fax);
			html='<option value="0">&mdash;</option>'; 
			for(i in mapped[item].address){
				html+='<option value="'+mapped[item].address[i]['address_id']+'">'+mapped[item].address[i]['address_1']+','+mapped[item].address[i]['city']+','+mapped[item].address[i]['country']+'</option>';
			}
			$('select[name="supplier_payment_address"]').html(html);
			$('select[id="supplier_group_id"]').button('reset').val(mapped[item]['supplier_group_id']).change().button('loading'); 
			return item;
		}
	}).click(function(){
		this.select();
	});
	$('select[name="country_id"]').change();

	var text_select=$('#text_select').val();
	var text_none=$('#text_none').val();
	var text_no_results=$('#text_no_results').val();
	var button_remove=$('#button_remove').val();
	$('[data-provide="countries"]').on('change',function(){
		var a=$(this),b=a.data('target');
		$.ajax({
			url:'index.php?route=sale/order/country&token='+token+'&country_id='+this.value,
			dataType:'json',
			beforeSend:function(){
				a.after($('<i>',{class:'icon-loading'}));
			},
			complete:function(){
				$('.icon-loading').remove();
			},
			success:function(json){
				$('.icon-loading').remove();
				if(json['postcode_required']=='1'){
					$('#'+b+'-postcode-required').show();
				}else{
					$('#'+b+'-postcode-required').hide();
				}
				html='<option value="">'+text_select+'</option>';
				if(json!=''&&json['zone']!=''){
					for(i=0;i<json['zone'].length;i++){
						html+='<option value="'+json['zone'][i]['zone_id']+'"';
						if(json['zone'][i]['zone_id']==a.data('selected')){
							html+=' selected=""';
						}
						html+='>'+json['zone'][i]['name']+'</option>';
					}
				}else{
					html+='<option value="0" selected="">'+text_none+'</option>';
				}
				$('select[name="'+b+'_zone_id"]').html(html);
			}
		});
	});
	
	$('[data-provide="countries"]').change();

	$('select[name="config_country_id"]').on('change',function(e){
		var a=$(this);
		$.ajax({
			url:'index.php?route=setting/setting/country&token='+token+'&country_id='+this.value,
			dataType:'json',
			beforeSend:function(){
				a.after($('<i>',{class:'icon-loading'}));
			},
			complete:function(){
				$('.icon-loading').remove();
			},
			success:function(json){
				html='<option value="">&ndash;</option>';
				if((typeof(json['zone'])+'undefined')&&json['zone']+''){
					for(i=0;i<json['zone'].length;i++){
						html+='<option value="'+json['zone'][i]['zone_id']+'"';
						if(json['zone'][i]['zone_id']==a.data('id')){
							html+=' selected=""';
						}
						html+='>'+json['zone'][i]['name']+'</option>';
					}
				}else{
					html+='<option value="0" selected="">'+a.data('none')+'</option>';
				}
				$('select[name="config_zone_id"]').html(html);
			}
		});
	});

	$('select[name="config_country_id"]').change();
	$('select[name="config_template"]').change();

	$(document).on('change','select[name="payment_address"]',function(){
		$.ajax({
			url:'index.php?route=sale/customer/address&token='+token+'&address_id='+this.value,
			dataType:'json',
			success:function(json){
				if(json!=''){
					$('input[name="payment_firstname"]').val(json['firstname']);
					$('input[name="payment_lastname"]').val(json['lastname']);
					$('input[name="payment_company"]').val(json['company']);
					$('input[name="payment_company_id"]').val(json['company_id']);
					$('input[name="payment_tax_id"]').val(json['tax_id']);
					$('input[name="payment_address_1"]').val(json['address_1']);
					$('input[name="payment_address_2"]').val(json['address_2']);
					$('input[name="payment_city"]').val(json['city']);
					$('input[name="payment_postcode"]').val(json['postcode']);
					$('select[name="payment_country_id"]').val(json['country_id']);
					$('select[name="payment_country_id"]').data('selected',json['zone_id']).change();
				}
			}
		});
	});
	$(document).on('change','select[name="shipping_address"]',function(){
		$.ajax({
			url:'index.php?route=sale/customer/address&token='+token+'&address_id='+this.value,
			dataType:'json',
			success:function(json){
				if(json!=''){
					$('input[name="shipping_firstname"]').val(json['firstname']);
					$('input[name="shipping_lastname"]').val(json['lastname']);
					$('input[name="shipping_company"]').val(json['company']);
					$('input[name="shipping_address_1"]').val(json['address_1']);
					$('input[name="shipping_address_2"]').val(json['address_2']);
					$('input[name="shipping_city"]').val(json['city']);
					$('input[name="shipping_postcode"]').val(json['postcode']);
					$('select[name="shipping_country_id"]').val(json['country_id']);
					$('#shipping_zone_id').val(json['zone_id']);
					$('select[name="shipping_country_id"]').data('selected',json['zone_id']).change();
				}
			}
		});
	});
	// add supplier address
	$(document).on('change','select[name="supplier_payment_address"]',function(){

		$.ajax({
			url:'index.php?route=purchases/supplier/address&token='+token+'&address_id='+this.value,
			dataType:'json',
			success:function(json){
				if(json!=''){
					$('input[name="payment_firstname"]').val(json['firstname']);
					$('input[name="payment_lastname"]').val(json['lastname']);
					$('input[name="payment_company"]').val(json['company']);
					$('input[name="payment_company_id"]').val(json['company_id']);
					$('input[name="payment_tax_id"]').val(json['tax_id']);
					$('input[name="payment_address_1"]').val(json['address_1']);
					$('input[name="payment_address_2"]').val(json['address_2']);
					$('input[name="payment_city"]').val(json['city']);
					$('input[name="payment_postcode"]').val(json['postcode']);
					$('select[name="payment_country_id"]').val(json['country_id']);
					$('select[name="payment_country_id"]').data('selected',json['zone_id']).change();
				}
			}
		});
	});
	$(document).on('change','select[name="supplier_shipping_address"]',function(){
		$.ajax({
			url:'index.php?route=purchases/supplier/address&token='+token+'&address_id='+this.value,
			dataType:'json',
			success:function(json){
				if(json!=''){
					$('input[name="shipping_firstname"]').val(json['firstname']);
					$('input[name="shipping_lastname"]').val(json['lastname']);
					$('input[name="shipping_company"]').val(json['company']);
					$('input[name="shipping_address_1"]').val(json['address_1']);
					$('input[name="shipping_address_2"]').val(json['address_2']);
					$('input[name="shipping_city"]').val(json['city']);
					$('input[name="shipping_postcode"]').val(json['postcode']);
					$('select[name="shipping_country_id"]').val(json['country_id']);
					$('#shipping_zone_id').val(json['zone_id']);
					$('select[name="shipping_country_id"]').data('selected',json['zone_id']).change();
				}
			}
		});
	});
	// end supplier address
	
	$('select[name="payment"]').bind('change',function(){
		if(this.value){
			$('input[name="payment_method"]').val($('select[name="payment"] option:selected').text());
		}else{
			$('input[name="payment_method"]').val('');
		}
		$('input[name="payment_code"]').val(this.value);
	});
	$('select[name="shipping"]').bind('change',function(){
		if(this.value){
			$('input[name="shipping_method"]').val($('select[name="shipping"] option:selected').text());
		}else{
			$('input[name="shipping_method"]').val('');
		}
		$('input[name="shipping_code"]').val(this.value);
	});
	var mapped={};
	var a=$('#order-product'),mapped={};
	a.typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/product/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('input[name="product_id"]').val(mapped[item].product_id);
			if(mapped[item]['option']!=''){
				var html='',s=$('#text_select').val();
				for(i=0;i<mapped[item]['option'].length;i++){
					var o=mapped[item]['option'][i];
					html+='<div class="form-group" id="option-'+o['product_option_id']+'">';
					html+='<label class="control-label col-sm-2">';
					if(o['required']==1){
						html+='<b class="required">*</b> ';
					}
					html+=o['name']+':</label>';
					html+='<div class="control-field col-sm-4">';
					if(o['type']=='select'){
						html+='<select name="option['+o['product_option_id']+']" class="form-control">';
						html+='<option value="">'+s+'</option>';
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<option value="'+ov['product_option_value_id']+'">'+ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</option>';
						}
						html+='</select>';
					}else if(o['type']=='radio'){
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<div class="radio"><label for="option-value-'+ov['product_option_value_id']+'">';
							html+='<input type="radio" name="option['+o['product_option_id']+'][]" value="'+ov['product_option_value_id']+'" id="option-value-'+ov['product_option_value_id']+'">';
							html+=ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</label></div>';
						}
					}else if(o['type']=='checkbox'){
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<div class="checkbox"><label for="option-value-'+ov['product_option_value_id']+'">';
							html+='<input type="checkbox" name="option['+o['product_option_id']+'][]" value="'+ov['product_option_value_id']+'" id="option-value-'+ov['product_option_value_id']+'">';
							html+=ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</label></div>';
						}
					}else if(o['type']=='image'){
						html+='<select name="option['+o['product_option_id']+']" class="form-control">';
						html+='<option value="">'+s+'</option>';
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<option value="'+ov['product_option_value_id']+'">'+ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</option>';
						}
						html+='</select>';
					}else if(o['type']=='text'){
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control">';
					}else if(o['type']=='textarea'){
						html+='<textarea name="option['+o['product_option_id']+']" class="form-control" rows="4">'+o['option_value']+'</textarea>';
					}else if(o['type']=='file'){
						html+='<button type="button" id="button-option-'+o['product_option_id']+'" class="btn btn-default"><i class="fa fa-upload"></i> '+$('#button_upload').val()+'</button>';
						html+='<input type="hidden" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" id="input-option-'+o['product_option_id']+'">';
					}else if(o['type']=='date'){
						html+='<div class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control date">';
						html+='<div class="input-group-append"><span class="input-group-text">';
						html+='<i class="fa fa-calendar"></i></span></div>';
					}else if(o['type']=='datetime'){
						html+='<div class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control datetime">';
						html+='<div class="input-group-append"><span class="input-group-text">';
						html+='<i class="fa fa-calendar"></i></span></div>';
						html+='</label>';
					}else if(o['type']=='time'){
						html+='<div class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control time">';
						html+='<div class="input-group-append"><span class="input-group-text">';
						html+='<i class="fa fa-calendar"></i></span></div>';
					}else if(o['type']=='date'){
						html+='<label class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" data-provide="datetimepicker" class="form-control" autocomplete="off">';
						html+='<span class="input-group-addon"><i class="fa fa-calendar"></i></span>';
						html+='</label>';
					}else if(o['type']=='datetime'){
						html+='<label class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" data-provide="datetimepicker" class="form-control" data-show-meridian="1" data-date-today-btn="1" data-min-view="0" data-date-format="yyyy-mm-dd hh:mm" autocomplete="off">';
						html+='<span class="input-group-addon"><i class="fa fa-calendar"></i></span>';
						html+='</label>';
					}else if(o['type']=='time'){
						html+='<label class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" data-provide="datetimepicker" class="form-control" data-max-view="1" data-start-view="1" data-show-meridian="1" data-min-view="0" data-date-format="hh:ii" autocomplete="off">';
						html+='<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>';
						html+='</label>';
					}
					html+='</div>';		
					html+='</div>';				
				}
				$('#option').html(html);
				for(i=0;i<mapped[item].option.length;i++){
					o=mapped[item].option[i];
					if(o['type']=='file'){
						$('#option').delegate('button[id^="button-option-"]','click',function(){
							var a=$(this);
							$('#form-upload').remove();
							$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display:none;"><input type="file" name="file"></form>');
							$('#form-upload input[name="file"]').on('change',function(){
								$.ajax({
									url:'index.php?route=sale/order/upload&token='+token,
									type:'post',
									dataType:'json',
									data:new FormData($(this).parent()[0]),
									cache:false,
									contentType:false,
									processData:false,
									beforeSend:function(){
										a.button('loading').append($('<i>',{class:'icon-loading'}));
									},
									complete:function(){
										a.button('reset');
									},
									success:function(json){
										$('.alert,.help-block.error').remove();
										$('.has-error').removeClass('has-error');
										if (json['error']){
											a.after('<div class="help-block error">'+json['error']+'</div>').closest('.form-group').addClass('has-error');
										}
										if (json['success']){
											alert(json['success']);
											a.parent().find('input[name^="option"]').val(json['file']);
										}
									}
								});
							}).click();
						});
					}
				}			
			}else{
				$('#option .form-group').remove();
			}
			return item;
		}
	}).click(function(){
		this.select();
	});
	$('#button-product,#button-update').on('click',function(){
		var a=$(this);

		if ($('#tab-customer').length!=0) {
			data='#tab-customer input[type="text"],#tab-customer input[type="hidden"],#tab-customer input[type="radio"]:checked,#tab-customer input[type="checkbox"]:checked,#tab-customer select,#tab-customer textarea,';
		} else if ($('#tab-supplier').length!=0) {
			data='#tab-supplier input[type="text"],#tab-supplier input[type="hidden"],#tab-supplier input[type="radio"]:checked,#tab-supplier input[type="checkbox"]:checked,#tab-supplier select,#tab-supplier textarea,';
		}

		data+='#tab-payment input[type="text"],#tab-payment input[type="hidden"],#tab-payment input[type="radio"]:checked,#tab-payment input[type="checkbox"]:checked,#tab-payment select,#tab-payment textarea,';
		data+='#tab-shipping input[type="text"],#tab-shipping input[type="hidden"],#tab-shipping input[type="radio"]:checked,#tab-shipping input[type="checkbox"]:checked,#tab-shipping select,#tab-shipping textarea,';
		if (a.attr('id')=='button-product'){
			data+='#tab-product input[type="text"],#tab-product input[type="hidden"],#tab-product input[type="radio"]:checked,#tab-product input[type="checkbox"]:checked,#tab-product select,#tab-product textarea,';
		}else{
			data+='#product input[type="text"],#product input[type="hidden"],#product input[type="radio"]:checked,#product input[type="checkbox"]:checked,#product select,#product textarea,';
		}
		data+='#tab-total input[type="text"],#tab-total input[type="hidden"],#tab-total input[type="radio"]:checked,#tab-total input[type="checkbox"]:checked,#tab-total select,#tab-total textarea';
		$.ajax({
			url:$('#store_url').val()+'index.php?route=checkout/manual&token='+token,
			type:'post',
			data:$(data),
			dataType:'json',
			beforeSend:function(){
				$('.alert,.text-error').remove();
				a.button('loading').append($('<i>',{class:'icon-loading'}));
			},
			success:function(json){
				$('.alert,.help-block.error').remove();
				$('.has-error').removeClass('has-error');
				a.button('reset');
				if(json['error']){
					if(json['error']['warning']){
						console.log("Hello error");
						alertMessage('danger',json['error']['warning']);
					}
					if(json['error']['customer']){
						alertMessage('danger',json['error']['customer']);
					}
					if(json['error']['firstname']){
						$('input[name="firstname"]').after('<div class="help-block error">'+json['error']['firstname']+'</div>');
					}
					if(json['error']['lastname']){
						$('input[name="lastname"]').after('<div class="help-block error">'+json['error']['lastname']+'</div>');
					}
					if(json['error']['email']){
						$('input[name="email"]').after('<div class="help-block error">'+json['error']['email']+'</div>');
					}
					if(json['error']['telephone']){
						$('input[name="telephone"]').after('<div class="help-block error">'+json['error']['telephone']+'</div>');
					}
					if(json['error']['payment']){
						$.each(json['error']['payment'],function(key,val){
							$('[name^="payment_'+key+'"]').after('<div class="help-block error">'+val+'</div>');
						});		
					}
					if(json['error']['shipping']){
						$.each(json['error']['shipping'],function(key,val){
							$('[name^="shipping_'+key+'"]').after('<div class="help-block error">'+val+'</div>');
						});
					}
					if(json['error']['product']){
						if(json['error']['product']['option']){
							for(i in json['error']['product']['option']){
								$('#option-'+i+' .controls').append('<div class="help-block error">'+json['error']['product']['option'][i]+'</div>');
							}					
						}
						if(json['error']['product']['stock']){
							alertMessage('danger',json['error']['product']['stock']);
						}
						if(json['error']['product']['minimum']){
							for(i in json['error']['product']['minimum']){
								alertMessage('danger',json['error']['product']['minimum'][i]);
							}					
						}
					}else{
						$('input[name="product"],input[name="product_id"]').val('');
						$('#option .form-group').remove();		
						$('input[name="quantity"]').val('1');		
					}
					$('.help-block.error').closest('.form-group').addClass('has-error');
					if(json['error']['shipping_method']){
						alertMessage('danger',json['error']['shipping_method']);
					}
					if(json['error']['payment_method']){
						alertMessage('danger',json['error']['payment_method']);
					}
					if(json['error']['coupon']){
						alertMessage('danger',json['error']['coupon']);
					}
					if(json['error']['reward']){
						alertMessage('danger',json['error']['reward']);
					}
				}else{
					$('input[name="product"],input[name="product_id"],input[name="from_name"],input[name="from_email"],input[name="to_name"],input[name="to_email"],textarea[name="message"]').val('');
					$('#option .form-group').remove();
					$('input[name="quantity"]').val('1');
					$('input[name="amount"]').val('25.00');
				}
				if(json['success']){
					alertMessage('success',json['success']);
				}
				if(json['order_product']!=''){
					var product_row=0,option_row=0,download_row=0;
					html='';
					for(i=0;i<json['order_product'].length;i++){
						product=json['order_product'][i];
						html+='<tr id="product-row'+product_row+'">';
						html+='<td class="text-center"><a class="label label-danger" title="'+button_remove+'" onclick="$("#product-row'+product_row+'").remove();$("#button-update").trigger("click");"><i class="fa fa-trash-o fa-lg"></i></a></td>';
						html+='<td>'+product['name']+'<br><input type="hidden" name="order_product['+product_row+'][order_product_id]" value=""><input type="hidden" name="order_product['+product_row+'][product_id]" value="'+product['product_id']+'"><input type="hidden" name="order_product['+product_row+'][name]" value="'+product['name']+'">';
						if (product['option']){
							for(j=0;j<product['option'].length;j++){
								option = product['option'][j];
								
								html+='<div class="help">'+option['name']+':'+option['value']+'</div>';
								html+='<input type="hidden" name="order_product['+product_row+'][order_option]['+option_row+'][order_option_id]" value="'+option['order_option_id']+'">';
								html+='<input type="hidden" name="order_product['+product_row+'][order_option]['+option_row+'][product_option_id]" value="'+option['product_option_id']+'">';
								html+='<input type="hidden" name="order_product['+product_row+'][order_option]['+option_row+'][product_option_value_id]" value="'+option['product_option_value_id']+'">';
								html+='<input type="hidden" name="order_product['+product_row+'][order_option]['+option_row+'][name]" value="'+option['name']+'">';
								html+='<input type="hidden" name="order_product['+product_row+'][order_option]['+option_row+'][value]" value="'+option['value']+'">';
								html+='<input type="hidden" name="order_product['+product_row+'][order_option]['+option_row+'][type]" value="'+option['type']+'">';
								
								option_row++;
							}
						}
						if (product['download']){
							for(j=0;j<product['download'].length;j++){
								download = product['download'][j];
								
								html+='<input type="hidden" name="order_product['+product_row+'][order_download]['+download_row+'][order_download_id]" value="'+download['order_download_id']+'">';
								html+='<input type="hidden" name="order_product['+product_row+'][order_download]['+download_row+'][name]" value="'+download['name']+'">';
								html+='<input type="hidden" name="order_product['+product_row+'][order_download]['+download_row+'][filename]" value="'+download['filename']+'">';
								html+='<input type="hidden" name="order_product['+product_row+'][order_download]['+download_row+'][mask]" value="'+download['mask']+'">';
								html+='<input type="hidden" name="order_product['+product_row+'][order_download]['+download_row+'][remaining]" value="'+download['remaining']+'">';
								
								download_row++;
							}
						}
						html+='</td>';
						html+='<td>'+product['model']+'<input type="hidden" name="order_product['+product_row+'][model]" value="'+product['model']+'"></td>';
						html+='<td class="text-right">'+product['quantity']+'<input type="hidden" name="order_product['+product_row+'][quantity]" value="'+product['quantity']+'"></td>';
						html+='<td class="text-right">'+product['price']+'<input type="hidden" name="order_product['+product_row+'][price]" value="'+product['price']+'"></td>';
						html+='<td class="text-right">'+product['total']+'<input type="hidden" name="order_product['+product_row+'][total]" value="'+product['total']+'"><input type="hidden" name="order_product['+product_row+'][tax]" value="'+product['tax']+'"><input type="hidden" name="order_product['+product_row+'][reward]" value="'+product['reward']+'"></td>';
						html+='</tr>';
						product_row++;		
					}
					$('#product').html(html);
				}else{
					$('#product').html('<tr><td colspan="6" class="text-center">'+text_no_results+'</td></tr>');
				}
				if(json['order_product']!=''||json['order_total']!=''){
					html='';
					if(json['order_product']!=''){
						for(i=0;i<json['order_product'].length;i++){
							product=json['order_product'][i];
							html+='<tr>';
							html+='<td>'+product['name'];
							if (product['option']){
								for(j=0;j<product['option'].length;j++){
									option = product['option'][j];
									
									html+='<div class="help">'+option['name']+':'+option['value']+'</div>';
								}
							}
							html+='</td>';
							html+='<td>'+product['model']+'</td>';
							html+='<td class="text-right">'+product['quantity']+'</td>';
							html+='<td class="text-right">'+product['price']+'</td>';
							html+='<td class="text-right">'+product['total']+'</td>';
							html+='</tr>';
						}			
					}
					var total_row=0;
					for(i in json['order_total']){
						total=json['order_total'][i];
						html+='<tr id="total-row'+total_row+'">';
						html+='<td class="text-right" colspan="4"><input type="hidden" name="order_total['+total_row+'][order_total_id]" value=""><input type="hidden" name="order_total['+total_row+'][code]" value="'+total['code']+'"><input type="hidden" name="order_total['+total_row+'][title]" value="'+total['title']+'"><input type="hidden" name="order_total['+total_row+'][text]" value="'+total['text']+'"><input type="hidden" name="order_total['+total_row+'][value]" value="'+total['value']+'"><input type="hidden" name="order_total['+total_row+'][sort_order]" value="'+total['sort_order']+'">'+total['title']+':</td>';
						html+='<td class="text-right">'+total['value']+'</td>';
						html+='</tr>';
						total_row++;
					}
					$('#total').html(html);
				}else{
					$('#total').html('<tr><td colspan="6" class="text-center">'+text_no_results+'</td></tr>');				
				}
				if(json['shipping_method']){
					html='<option value="">'+text_select+'</option>';
					for(i in json['shipping_method']){
						html+='<optgroup label="'+json['shipping_method'][i]['title']+'">';
						if (!json['shipping_method'][i]['error']){
							for (j in json['shipping_method'][i]['quote']){
								if(json['shipping_method'][i]['quote'][j]['code']==$('input[name="shipping_code"]').val()){
									html+='<option value="'+json['shipping_method'][i]['quote'][j]['code']+'" selected="">'+json['shipping_method'][i]['quote'][j]['title']+'</option>';
								}else{
									html+='<option value="'+json['shipping_method'][i]['quote'][j]['code']+'">'+json['shipping_method'][i]['quote'][j]['title']+'</option>';
								}
							}	
						}else{
							html+='<option value="" class="text-error" disabled="">'+json['shipping_method'][i]['error']+'</option>';
						}
						html+='</optgroup>';
					}
					$('select[name="shipping"]').html(html);
					if ($('select[name="shipping"] option:selected').val()){
						$('input[name="shipping_method"]').val($('select[name="shipping"] option:selected').text());
					}else{
						$('input[name="shipping_method"]').val('');
					}
					$('input[name="shipping_code"]').val($('select[name="shipping"] option:selected').val());
				}
				if(json['payment_method']){
					html='<option value="">'+text_select+'</option>';
					for(i in json['payment_method']){
						if(json['payment_method'][i]['code']==$('input[name="payment_code"]').val()){
							html+='<option value="'+json['payment_method'][i]['code']+'" selected="">'+json['payment_method'][i]['title']+'</option>';
						}else{
							html+='<option value="'+json['payment_method'][i]['code']+'">'+json['payment_method'][i]['title']+'</option>';
						}	
					}
					$('select[name="payment"]').html(html);
					if ($('select[name="payment"] option:selected').val()){
						$('input[name="payment_method"]').val($('select[name="payment"] option:selected').text());
					}else{
						$('input[name="payment_method"]').val('');
					}
					$('input[name="payment_code"]').val($('select[name="payment"] option:selected').val());
				}
			}
		});
	});
	/* invoice_form.tpl */
	var a=$('#invoice-product'),mapped={};
	a.typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/product/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('input[name="product_id"]').val(mapped[item].product_id);
			if(mapped[item]['option']!=''){
				var html='',s=$('#text_select').val();
				for(i=0;i<mapped[item]['option'].length;i++){
					var o=mapped[item]['option'][i];
					html+='<div class="form-group" id="option-'+o['product_option_id']+'">';
					html+='<label class="control-label col-sm-4">';
					if(o['required']==1){
						html+='<b class="required">*</b> ';
					}
					html+=o['name']+':</label>';
					html+='<div class="control-field col-sm-8">';
					if(o['type']=='select'){
						html+='<select name="option['+o['product_option_id']+']" class="form-control">';
						html+='<option value="">'+s+'</option>';
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<option value="'+ov['product_option_value_id']+'">'+ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</option>';
						}
						html+='</select>';
					}else if(o['type']=='radio'){
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<div class="radio"><label for="option-value-'+ov['product_option_value_id']+'">';
							html+='<input type="radio" name="option['+o['product_option_id']+'][]" value="'+ov['product_option_value_id']+'" id="option-value-'+ov['product_option_value_id']+'">';
							html+=ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</label></div>';
						}
					}else if(o['type']=='checkbox'){
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<div class="checkbox"><label for="option-value-'+ov['product_option_value_id']+'">';
							html+='<input type="checkbox" name="option['+o['product_option_id']+'][]" value="'+ov['product_option_value_id']+'" id="option-value-'+ov['product_option_value_id']+'">';
							html+=ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</label></div>';
						}
					}else if(o['type']=='image'){
						html+='<select name="option['+o['product_option_id']+']" class="form-control">';
						html+='<option value="">'+s+'</option>';
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<option value="'+ov['product_option_value_id']+'">'+ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</option>';
						}
						html+='</select>';
					}else if(o['type']=='text'){
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control">';
					}else if(o['type']=='textarea'){
						html+='<textarea name="option['+o['product_option_id']+']" class="form-control" rows="4">'+o['option_value']+'</textarea>';
					}else if(o['type']=='file'){
						html+='<div class="input-group">';
						html+='<input type="text" name="mask" id="input-file" class="form-control">';
						html+='<input type="hidden" name="filename">';
						html+='<span class="input-group-btn"><button type="button" id="button-upload" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button></span>';
						html+='<input type="hidden" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" id="input-option-'+o['product_option_id']+'"></div>';
					}else if(o['type']=='date'){
						html+='<div class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control date">';
						html+='<div class="input-group-append"><span class="input-group-text">';
						html+='<i class="fa fa-calendar"></i></span></div></div>';
					}else if(o['type']=='datetime'){
						html+='<div class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control date">';
						html+='<div class="input-group-append"><span class="input-group-text">';
						html+='<i class="fa fa-calendar"></i></span></div></div>';
					}else if(o['type']=='time'){
						html+='<div class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control time">';
						html+='<div class="input-group-append"><span class="input-group-text">';
						html+='<i class="fa fa-clock"></i></span></div></div>';
					}
					html+='</div>';		
					html+='</div>';				
				}
				$('#option').html(html);			
			}else{
				$('#option .form-group').remove();
			}
			return item;
		}
	}).click(function(){
		this.select();
		this.setSelectionRange(0,0);
	});
	$('#button-invoice-product,#button-invoice-update').on('click',function(){
		var a=$(this);
        data='#tab-customer input[type="text"],#tab-customer input[type="hidden"],#tab-customer input[type="radio"]:checked,#tab-customer input[type="checkbox"]:checked,#tab-customer select,#tab-customer textarea,';
		data+='#tab-payment input[type="text"],#tab-payment input[type="hidden"],#tab-payment input[type="radio"]:checked,#tab-payment input[type="checkbox"]:checked,#tab-payment select,#tab-payment textarea,';
        data+='#tab-shipping input[type="text"],#tab-shipping input[type="hidden"],#tab-shipping input[type="radio"]:checked,#tab-shipping input[type="checkbox"]:checked,#tab-shipping select,#tab-shipping textarea,';
		
		// Datos del modal
		data+='#ProductModal input[type="text"],#ProductModal input[type="hidden"],#ProductModal input[type="radio"]:checked,#ProductModal input[type="checkbox"]:checked,#ProductModal select,#ProductModal textarea,';
		
		// datos de la tabla
		data+='#product input[type="text"],#product input[type="hidden"],#product input[type="radio"]:checked,#product input[type="checkbox"]:checked,#product select,#product textarea,';
		
		data+='#tab-total input[type="text"],#tab-total input[type="hidden"],#tab-total input[type="radio"]:checked,#tab-total input[type="checkbox"]:checked,#tab-total select,#tab-total textarea';
		// console.log('index.php?route=checkout/manual/checkInvoice&token='+token);
		$.ajax({
			url:'index.php?route=sale/invoice/checkInvoice&token='+token,
			type:'post',
			data:$(data),
			dataType:'json',
			beforeSend:function(){
				$('.alert,.text-error').remove();
				a.button('loading').append($('<i>',{class:'icon-loading'}));
			},
			success:function(json){
				if(json['error']){if(json['error']['product']){
					if(json['error']['product']['option']){
						for(i in json['error']['product']['option']){
							$('#option-'+i+' .controls').append('<div class="help-block error">'+json['error']['product']['option'][i]+'</div>');
						}					
					}
				}}
				if(json['invoice_product']!=''){
					var product_row=0;
					var option_row=0;
					html='';
					for(i=0;i<json['invoice_product'].length;i++){
						product=json['invoice_product'][i];
						html+='<tr id="product-row'+product_row+'">';
						html+='<td class="text-center"><a class="label label-danger" title="'+button_remove+'" onclick="$(\'#product-row'+product_row+'\').remove();$(\'#button-invoice-product\').click();"><i class="fa fa-trash"></i></a></td>';
						html+='<td>'+product['name']+'<br><input type="hidden" name="invoice_product['+product_row+'][invoice_product_id]" value=""><input type="hidden" name="invoice_product['+product_row+'][product_id]" value="'+product['product_id']+'"><input type="hidden" name="invoice_product['+product_row+'][name]" value="'+product['name']+'">';
						if (product['option']){
							for(j=0;j<product['option'].length;j++){
								option = product['option'][j];
								
								html+='<div class="help">'+option['name']+':'+option['value']+'</div>';
								html+='<input type="hidden" name="invoice_product['+product_row+'][invoice_option]['+option_row+'][invoice_option_id]" value="'+option['invoice_option_id']+'">';
								html+='<input type="hidden" name="invoice_product['+product_row+'][invoice_option]['+option_row+'][product_option_id]" value="'+option['product_option_id']+'">';
								html+='<input type="hidden" name="invoice_product['+product_row+'][invoice_option]['+option_row+'][product_option_value_id]" value="'+option['product_option_value_id']+'">';
								html+='<input type="hidden" name="invoice_product['+product_row+'][invoice_option]['+option_row+'][name]" value="'+option['name']+'">';
								html+='<input type="hidden" name="invoice_product['+product_row+'][invoice_option]['+option_row+'][value]" value="'+option['value']+'">';
								html+='<input type="hidden" name="invoice_product['+product_row+'][invoice_option]['+option_row+'][type]" value="'+option['type']+'">';
								
								option_row++;
							}
						}
						html+='</td>';
						html+='<td class="d-none d-sm-table-cell">'+product['model']+'<input type="hidden" name="invoice_product['+product_row+'][model]" value="'+product['model']+'"></td>';
						html+='<td class="text-right">'+product['quantity']+'<input type="hidden" name="invoice_product['+product_row+'][quantity]" value="'+product['quantity']+'"></td>';
						html+='<td class="text-right">'+product['price']+'<input type="hidden" name="invoice_product['+product_row+'][price]" value="'+product['price']+'"></td>';
						html+='<td class="text-right">'+product['total']+'<input type="hidden" name="invoice_product['+product_row+'][total]" value="'+product['total']+'"><input type="hidden" name="invoice_product['+product_row+'][tax]" value="'+product['tax']+'"></td>';
						html+='</tr>';
						product_row++;		
					}
					$('#product').html(html);
				}
				if(json['invoice_total']!=''){
					var total_row=0;
					html='';
					for(i in json['invoice_total']){
						total=json['invoice_total'][i];
						html+='<tr id="total-row'+total_row+'">';
						html+='<td class="d-none d-sm-table-cell"></td><td class="text-right" colspan="4"><input type="hidden" name="invoice_total['+total_row+'][invoice_total_id]" value=""><input type="hidden" name="invoice_total['+total_row+'][code]" value="'+total['code']+'"><input type="hidden" name="invoice_total['+total_row+'][title]" value="'+total['title']+'"><input type="hidden" name="invoice_total['+total_row+'][text]" value="'+total['text']+'"><input type="hidden" name="invoice_total['+total_row+'][value]" value="'+total['value']+'"><input type="hidden" name="invoice_total['+total_row+'][sort_order]" value="'+total['sort_order']+'">'+total['title']+':</td>';
						html+='<td class="text-right">'+total['text']+'</td>';
						html+='</tr>';
						total_row++;
					}
					$('#total').html(html);
				}
			}
		});
	});
	/* quote_form.tpl */
	var a=$('#quote-product'),mapped={};
	a.typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=catalog/product/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('input[name="product_id"]').val(mapped[item].product_id);
			if(mapped[item]['option']!=''){
				var html='',s=$('#text_select').val();
				for(i=0;i<mapped[item]['option'].length;i++){
					var o=mapped[item]['option'][i];
					html+='<div class="form-group" id="option-'+o['product_option_id']+'">';
					html+='<label class="control-label col-sm-4">';
					if(o['required']==1){
						html+='<b class="required">*</b> ';
					}
					html+=o['name']+':</label>';
					html+='<div class="control-field col-sm-8">';
					if(o['type']=='select'){
						html+='<select name="option['+o['product_option_id']+']" class="form-control">';
						html+='<option value="">'+s+'</option>';
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<option value="'+ov['product_option_value_id']+'">'+ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</option>';
						}
						html+='</select>';
					}else if(o['type']=='radio'){
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<div class="radio"><label for="option-value-'+ov['product_option_value_id']+'">';
							html+='<input type="radio" name="option['+o['product_option_id']+'][]" value="'+ov['product_option_value_id']+'" id="option-value-'+ov['product_option_value_id']+'">';
							html+=ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</label></div>';
						}
					}else if(o['type']=='checkbox'){
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<div class="checkbox"><label for="option-value-'+ov['product_option_value_id']+'">';
							html+='<input type="checkbox" name="option['+o['product_option_id']+'][]" value="'+ov['product_option_value_id']+'" id="option-value-'+ov['product_option_value_id']+'">';
							html+=ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</label></div>';
						}
					}else if(o['type']=='image'){
						html+='<select name="option['+o['product_option_id']+']" class="form-control">';
						html+='<option value="">'+s+'</option>';
						for(j=0;j<o['option_value'].length;j++){
							ov=o['option_value'][j];
							html+='<option value="'+ov['product_option_value_id']+'">'+ov['name'];
							if(ov['price']){
								html+=' ('+ov['price_prefix']+ov['price']+')';
							}
							html+='</option>';
						}
						html+='</select>';
					}else if(o['type']=='text'){
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control">';
					}else if(o['type']=='textarea'){
						html+='<textarea name="option['+o['product_option_id']+']" class="form-control" rows="4">'+o['option_value']+'</textarea>';
					}else if(o['type']=='file'){
						html+='<div class="input-group">';
						html+='<input type="text" name="mask" id="input-file" class="form-control">';
						html+='<input type="hidden" name="filename">';
						html+='<span class="input-group-btn"><button type="button" id="button-upload" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button></span>';
						html+='<input type="hidden" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" id="input-option-'+o['product_option_id']+'"></div>';
					}else if(o['type']=='date'){
						html+='<div class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control date">';
						html+='<div class="input-group-append"><span class="input-group-text">';
						html+='<i class="fa fa-calendar"></i></span></div></div>';
					}else if(o['type']=='datetime'){
						html+='<div class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control date">';
						html+='<div class="input-group-append"><span class="input-group-text">';
						html+='<i class="fa fa-calendar"></i></span></div></div>';
					}else if(o['type']=='time'){
						html+='<div class="input-group">';
						html+='<input type="text" name="option['+o['product_option_id']+']" value="'+o['option_value']+'" class="form-control time">';
						html+='<div class="input-group-append"><span class="input-group-text">';
						html+='<i class="fa fa-clock"></i></span></div></div>';
					}
					html+='</div>';		
					html+='</div>';				
				}
				$('#option').html(html);			
			}else{
				$('#option .form-group').remove();
			}
			return item;
		}
	}).click(function(){
		this.select();
		this.setSelectionRange(0,0);
	});
	$('#button-quote-product,#button-quote-update').on('click',function(){
		var a=$(this);
        data='#tab-customer input[type="text"],#tab-customer input[type="hidden"],#tab-customer input[type="radio"]:checked,#tab-customer input[type="checkbox"]:checked,#tab-customer select,#tab-customer textarea,';
		data+='#tab-payment input[type="text"],#tab-payment input[type="hidden"],#tab-payment input[type="radio"]:checked,#tab-payment input[type="checkbox"]:checked,#tab-payment select,#tab-payment textarea,';
        data+='#tab-shipping input[type="text"],#tab-shipping input[type="hidden"],#tab-shipping input[type="radio"]:checked,#tab-shipping input[type="checkbox"]:checked,#tab-shipping select,#tab-shipping textarea,';
		
		// Datos del modal
		data+='#ProductModal input[type="text"],#ProductModal input[type="hidden"],#ProductModal input[type="radio"]:checked,#ProductModal input[type="checkbox"]:checked,#ProductModal select,#ProductModal textarea,';
		// datos de la tabla
		data+='#product input[type="text"],#product input[type="hidden"],#product input[type="radio"]:checked,#product input[type="checkbox"]:checked,#product select,#product textarea,';

		data+='#tab-total input[type="text"],#tab-total input[type="hidden"],#tab-total input[type="radio"]:checked,#tab-total input[type="checkbox"]:checked,#tab-total select,#tab-total textarea';
		$.ajax({
			url:'index.php?route=sale/quote/checkQuote&token='+token,
			type:'post',
			data:$(data),
			dataType:'json',
			beforeSend:function(){
				$('.alert,.text-error').remove();
				a.button('loading').append($('<i>',{class:'icon-loading'}));
			},
			success:function(json){
				if(json['error']){if(json['error']['product']){
					if(json['error']['product']['option']){
						for(i in json['error']['product']['option']){
							$('#option-'+i+' .controls').append('<div class="help-block error">'+json['error']['product']['option'][i]+'</div>');
						}					
					}
				}}
				if(json['quote_product']!=''){
					var product_row=0;
					var option_row=0;
					html='';
					for(i=0;i<json['quote_product'].length;i++){
						product=json['quote_product'][i];
						html+='<tr id="product-row'+product_row+'">';
						html+='<td class="text-center"><a class="label label-danger" title="'+button_remove+'" onclick="$(\'#product-row'+product_row+'\').remove();$(\'#button-quote-product\').click();"><i class="fa fa-trash"></i></a></td>';
						html+='<td>'+product['name']+'<br><input type="hidden" name="quote_product['+product_row+'][quote_product_id]" value=""><input type="hidden" name="quote_product['+product_row+'][product_id]" value="'+product['product_id']+'"><input type="hidden" name="quote_product['+product_row+'][name]" value="'+product['name']+'">';
						if (product['option']){
							for(j=0;j<product['option'].length;j++){
								option = product['option'][j];
								
								html+='<div class="help">'+option['name']+':'+option['value']+'</div>';
								html+='<input type="hidden" name="quote_product['+product_row+'][quote_option]['+option_row+'][quote_option_id]" value="'+option['quote_option_id']+'">';
								html+='<input type="hidden" name="quote_product['+product_row+'][quote_option]['+option_row+'][product_option_id]" value="'+option['product_option_id']+'">';
								html+='<input type="hidden" name="quote_product['+product_row+'][quote_option]['+option_row+'][product_option_value_id]" value="'+option['product_option_value_id']+'">';
								html+='<input type="hidden" name="quote_product['+product_row+'][quote_option]['+option_row+'][name]" value="'+option['name']+'">';
								html+='<input type="hidden" name="quote_product['+product_row+'][quote_option]['+option_row+'][value]" value="'+option['value']+'">';
								html+='<input type="hidden" name="quote_product['+product_row+'][quote_option]['+option_row+'][type]" value="'+option['type']+'">';
								
								option_row++;
							}
						}
						html+='</td>';
						html+='<td class="d-none d-sm-table-cell">'+product['model']+'<input type="hidden" name="quote_product['+product_row+'][model]" value="'+product['model']+'"></td>';
						html+='<td class="text-right">'+product['quantity']+'<input type="hidden" name="quote_product['+product_row+'][quantity]" value="'+product['quantity']+'"></td>';
						html+='<td class="text-right">'+product['price']+'<input type="hidden" name="quote_product['+product_row+'][price]" value="'+product['price']+'"></td>';
						html+='<td class="text-right">'+product['total']+'<input type="hidden" name="quote_product['+product_row+'][total]" value="'+product['total']+'"><input type="hidden" name="quote_product['+product_row+'][tax]" value="'+product['tax']+'"></td>';
						html+='</tr>';
						product_row++;		
					}
					$('#product').html(html);
				}
				if(json['quote_total']!=''){
					var total_row=0;
					html='';
					for(i in json['quote_total']){
						total=json['quote_total'][i];
						html+='<tr id="total-row'+total_row+'">';
						html+='<td class="d-none d-sm-table-cell"></td><td class="text-right" colspan="4"><input type="hidden" name="quote_total['+total_row+'][quote_total_id]" value=""><input type="hidden" name="quote_total['+total_row+'][code]" value="'+total['code']+'"><input type="hidden" name="quote_total['+total_row+'][title]" value="'+total['title']+'"><input type="hidden" name="quote_total['+total_row+'][text]" value="'+total['text']+'"><input type="hidden" name="quote_total['+total_row+'][value]" value="'+total['value']+'"><input type="hidden" name="quote_total['+total_row+'][sort_order]" value="'+total['sort_order']+'">'+total['title']+':</td>';
						html+='<td class="text-right">'+total['text']+'</td>';
						html+='</tr>';
						total_row++;
					}
					$('#total').html(html);
				}
			}
		});
	});
});

// Download Form
$(document).on('click','#button-upload',function(){
	var a=$(this);
	$('#form-upload').remove();
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display:none;"><input type="file" name="file"></form>');
	$('#form-upload input[name="file"]').on('change',function(){
		$.ajax({
			url:'index.php?route=catalog/download/upload&token='+token,
			type:'post',
			dataType:'json',
			data:new FormData($(this).parent()[0]),
			cache:false,
			contentType:false,
			processData:false,
			beforeSend:function(){
				a.button('loading').append($('<i>',{class:'icon-loading'}));
			},	
			complete:function(){
				a.button('reset');
			},
			success:function(json){
				if (json['error']){
					alert(json['error']);
				}
				if (json['success']){
					alert(json['success']);
					$('input[name="filename"]').val(json['filename']);
					$('input[name="mask"]').val(json['mask']);
				}
			}
		});
	}).click();
});
// Contact
$(document).on('click','#button-send',function(){
	var a=$(this);
	for(instance in CKEDITOR.instances){
		CKEDITOR.instances[instance].updateElement();
	}
	$.ajax({
		url:a.data('url'),
		type:'post',
		data:$('select,input,textarea'),
		dataType:'json',
		beforeSend:function(){
			a.button('loading').append($('<i>',{class:'icon-loading'}));
		},
		complete:function(){
			a.button('reset');
		},
		success:function(json){
			$('.alert,.help-block.error').remove();
			$('.has-error').removeClass('has-error');
			if(json['error']){
				if(json['error']['warning']){
					alertMessage('danger',json['error']['warning']);
				}
				if(json['error']['subject']){
					$('input[name="subject"]').after('<span class="help-block error">'+json['error']['subject']+'</span>').closest('.form-group').addClass('has-error');
				}
				if(json['error']['message']){
					$('#message').parent().append('<span class="help-block error">'+json['error']['message']+'</span>').closest('.form-group').addClass('has-error');
				}				
			}
			if(json['success']){
				alertMessage('success',json['success']);
				if(json['next']){
					send(json['next']);
				}
			}
		}
	});
});
$(function(){
	$('select[name="to"]').bind('change',function(){
		$('#mail .to').hide();
		$('#mail #to-'+$(this).val().replace('_','-')).show().find('input[type="text"]').select();
	});
	$('select[name="to"]').change();
	var mapped={};
	$('input[name="customers"]').typeahead({
		source:function(q,process){
			return $.getJSON('index.php?route=sale/customer/autocomplete&token='+token+'&filter_name='+encodeURIComponent(q),function(json){
				var data=[];
				$.each(json,function(i,item){
					mapped[item.name]=item.customer_id;
					data.push(item.name);
				});
				process(data);
			});
		},
		updater:function(item){
			$('#customer'+mapped[item]).remove();
			$('#customer').append('<div class="list-group-item" id="customer'+mapped[item]+'"><a class="label label-danger label-trash"><i class="fa fa-trash-o fa-lg"></i></a>'+item+'<input type="hidden" name="customer[]" value="'+mapped[item]+'"></div>');
			return null;
		}
	});
});
// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			var $this = $(this);
			var $dropdown = $('<div class="dropdown-menu"/>');

			this.timer = null;
			this.items = [];

			$.extend(this, option);

			$(this).wrap('<div class="dropdown">');

			$this.attr('autocomplete', 'off');
			$this.active = false;

			// Focus
			$this.on('focus', function() {
				this.request();
			});

			// Blur
			$this.on('blur', function(e) {
				if (!$this.active) {
					this.hide();
				}
			});

			$this.parent().on('mouseover', function(e) {
				$this.active = true;
			});

			$this.parent().on('mouseout', function(e) {
				$this.active = false;
			});

			// Keydown
			$this.on('keydown', function(event) {
				switch (event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				var value = $(event.target).attr('href');

				if (value && this.items[value]) {
					this.select(this.items[value]);

					this.hide();
				}
			}

			// Show
			this.show = function() {
				$dropdown.addClass('show');
			}

			// Hide
			this.hide = function() {
				$dropdown.removeClass('show');
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 50, this);
			}

			// Response
			this.response = function(json) {
				var html = '';
				var category = {};
				var name;
				var i = 0, j = 0;

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						// update element items
						this.items[json[i]['value']] = json[i];

						if (!json[i]['category']) {
							// ungrouped items
							html += '<a href="' + json[i]['value'] + '" class="dropdown-item">' + json[i]['label'] + '</a>';
						} else {
							// grouped items
							name = json[i]['category'];

							if (!category[name]) {
								category[name] = [];
							}

							category[name].push(json[i]);
						}
					}

					for (name in category) {
						html += '<h6 class="dropdown-header">' + name + '</h6>';

						for (j = 0; j < category[name].length; j++) {
							html += '<a href="' + category[name][j]['value'] + '" class="dropdown-item">&nbsp;&nbsp;&nbsp;' + category[name][j]['label'] + '</a>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$dropdown.html(html);
			}

			$dropdown.on('click', '> a', $.proxy(this.click, this));

			$this.after($dropdown);
		});
	}
})(window.jQuery);

// +function($) {
// 	'use strict';

// 	// BUTTON PUBLIC CLASS DEFINITION
// 	// ==============================

// 	var Button = function(element, options) {
// 		this.$element = $(element)
// 		this.options = $.extend({}, Button.DEFAULTS, options)
// 		this.isLoading = false
// 	}

// 	Button.VERSION = '3.3.5'

// 	Button.DEFAULTS = {
// 		loadingText: 'loading...'
// 	}

// 	Button.prototype.setState = function(state) {
// 		var d = 'disabled'
// 		var $el = this.$element
// 		var val = $el.is('input') ? 'val' : 'html'
// 		var data = $el.data()

// 		state += 'Text'

// 		if (data.resetText == null) $el.data('resetText', $el[val]())

// 		// push to event loop to allow forms to submit
// 		setTimeout($.proxy(function() {
// 			$el[val](data[state] == null ? this.options[state] : data[state])

// 			if (state == 'loadingText') {
// 				this.isLoading = true
// 				$el.addClass(d).attr(d, d)
// 			} else if (this.isLoading) {
// 				this.isLoading = false
// 				$el.removeClass(d).removeAttr(d)
// 			}
// 		}, this), 0)
// 	}

// 	Button.prototype.toggle = function() {
// 		var changed = true
// 		var $parent = this.$element.closest('[data-toggle="buttons"]')

// 		if ($parent.length) {
// 			var $input = this.$element.find('input')
// 			if ($input.prop('type') == 'radio') {
// 				if ($input.prop('checked')) changed = false
// 				$parent.find('.active').removeClass('active')
// 				this.$element.addClass('active')
// 			} else if ($input.prop('type') == 'checkbox') {
// 				if (($input.prop('checked')) !== this.$element.hasClass('active')) changed = false
// 				this.$element.toggleClass('active')
// 			}
// 			$input.prop('checked', this.$element.hasClass('active'))
// 			if (changed) $input.trigger('change')
// 		} else {
// 			this.$element.attr('aria-pressed', !this.$element.hasClass('active'))
// 			this.$element.toggleClass('active')
// 		}
// 	}


	// BUTTON PLUGIN DEFINITION
	// ========================

// 	function Plugin(option) {
// 		return this.each(function() {
// 			var $this = $(this)
// 			var data = $this.data('bs.button')
// 			var options = typeof option == 'object' && option

// 			if (!data) $this.data('bs.button', (data = new Button(this, options)))

// 			if (option == 'toggle') data.toggle()
// 			else if (option) data.setState(option)
// 		})
// 	}

// 	var old = $.fn.button

// 	$.fn.button = Plugin
// 	$.fn.button.Constructor = Button


// 	// BUTTON NO CONFLICT
// 	// ==================

// 	$.fn.button.noConflict = function() {
// 		$.fn.button = old
// 		return this
// 	}


// 	// BUTTON DATA-API
// 	// ===============

// 	$(document)
// 		.on('click.bs.button.data-api', '[data-toggle^="button"]', function(e) {
// 			var $btn = $(e.target)
// 			if (!$btn.hasClass('btn')) $btn = $btn.closest('.btn')
// 			Plugin.call($btn, 'toggle')
// 			if (!($(e.target).is('input[type="radio"]') || $(e.target).is('input[type="checkbox"]'))) e.preventDefault()
// 		})
// 		.on('focus.bs.button.data-api blur.bs.button.data-api', '[data-toggle^="button"]', function(e) {
// 			$(e.target).closest('.btn').toggleClass('focus', /^focus(in)?$/.test(e.type))
// 		})

// }(jQuery);