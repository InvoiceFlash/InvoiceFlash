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

// alert messages
var alertMessage=function(state,msg){
	var html='<div class="alert alert-'+state+' alert-dismissable" style="display:none;"><a class="close" data-dismiss="alert" href="#">&times;</a>'+msg+'</div>';
	
	$('#notification').html(html);
	$('#notification>.alert').fadeIn('slow').delay(15000).fadeTo(2000,0,function(){
		$(this).remove();
	});
};
$(document).ajaxError(function(event,xhr,ajaxSettings,thrownError){
	$('#notification').html($('<div>',{class:'alert alert-danger'}).html(thrownError+"\r\n"+xhr.statusText));
});

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

// Show content first tab
$(document).ready(function() {
	$('.nav-tabs,.nav-pills').each(function(){
		$(this).find('[data-toggle="tab"]:first').tab('show');
	});
});

/*--- invoice_form.tpl ---*/
$(function(){
	var mapped={};
	$('#sales-customer').typeahead({
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
			$('input[name="fax"]').val(mapped[item].fax);
			html='<option value="0">&mdash;</option>'; 
			for(i in mapped[item].address){
				html+='<option value="'+mapped[item].address[i]['address_id']+'">'+mapped[item].address[i]['firstname']+' '+mapped[item].address[i]['lastname']+','+mapped[item].address[i]['address_1']+','+mapped[item].address[i]['city']+','+mapped[item].address[i]['country']+'</option>';
			}
			$('select[name="shipping_address"]').html(html);
			$('select[name="payment_address"]').html(html);
			$('select[id="customer_group_id"]').button('reset').val(mapped[item]['customer_group_id']).change().button('loading'); 
			return item;
		}
	}).click(function(){
		this.select();
	});
	
	$('select[data-param]').on('change',function(e){
		var $this=$(this),param=$this.data('param');
		$.ajax({
			url:'index.php?route=sale/affiliate/country&token='+token+'&country_id='+$this.val(),
			dataType:'json',
			beforeSend:function(){
				$this.after($('<i>',{class:'icon-loading'}));
			},
			complete:function(){
				$('.icon-loading').remove();
			},
			success:function(json){
				if(json['postcode_required']==1){
					$('#postcode-required').show();
				}else{
					$('#postcode-required').hide();
				}
				
				if(json['zone']!=''){
					html='<option value="">'+param.select+'</option>';
					for(i=0;i<json['zone'].length;i++){
						html+='<option value="'+json['zone'][i]['zone_id']+'"';
						if(json['zone'][i]['zone_id']==param.zone_id){
							html+=' selected=""';
						}
						html+='>'+json['zone'][i]['name']+'</option>';
					}
				}else{
					html='<option value="0" selected="">'+param.none+'</option>';
				}
				$('select[name="zone_id"]').html(html);
			}
		});
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
		if (a.attr('id')=='button-voucher'){
			//data+='#tab-voucher input[type="text"],#tab-voucher input[type="hidden"],#tab-voucher input[type="radio"]:checked,#tab-voucher input[type="checkbox"]:checked,#tab-voucher select,#tab-voucher textarea,';
		}else{
			data+='#voucher input[type="text"],#voucher input[type="hidden"],#voucher input[type="radio"]:checked,#voucher input[type="checkbox"]:checked,#voucher select,#voucher textarea,';
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
					if(json['error']['vouchers']){
						$.each(json['error']['vouchers'],function(key,val){
							$('input[name="'+key+'"]').after('<div class="help-block error">'+val+'</div>');
						});
					}else{
						$('input[name="from_name"],input[name="from_email"],input[name="to_name"],input[name="to_email"],textarea[name="message"]').val('');
						$('input[name="amount"]').val('25.00');
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
					//if(json['error']['voucher']){
						//alertMessage('danger',json['error']['voucher']);
					//}
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
				if(json['order_voucher']!=''){
					var voucher_row=0;
					html='';
					
					$('#voucher').html(html);			
				}else{
					$('#voucher').html('<tr><td colspan="6" class="text-center">'+text_no_results+'</td></tr>');
				}
				if(json['order_product']!=''||json['order_voucher']!=''||json['order_total']!=''){
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
					if(json['order_voucher']!=''){
						
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