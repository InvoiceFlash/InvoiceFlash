<?php echo $header; ?>
<?php include DIR_TEMPLATE . 'common/template-header.tpl'; ?>
<div class="panel panel-default">
  <div class="panel-heading clearfix">
    <div class="h2"><i class="fas fa-user"></i> <?php echo $heading_title; ?></div>
  </div>
  <div class="panel-body">
    <div class="well" id="filter">
      <div class="row">
        <div class="col-sm-3">
					<div class="input-group">
						<input type="text" class="form-control date" id="date-start" name="filter_date_start" value="<?php echo $filter_date_start ?>" placeholder="<?php echo $entry_date_start?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="input-group">
						<input type="text" class="form-control date" id="date-end" name="filter_date_end" value="<?php echo $filter_date_end ?>" placeholder="<?php echo $entry_date_end?>">
						<div class="input-group-append"><div class="input-group-text"><i class="fas fa-calendar"></i></div></div>
					</div>
				</div>
        <div class="col-sm-6 text-right">
					<button type="button" onclick="filter();" class="btn btn-info"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
				</div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th class="text-left"><?php echo $column_date_added; ?></th>
            <th class="text-left"><?php echo $column_customer; ?></th>
            <th class="text-left d-none d-sm-table-cell"><?php echo $column_city; ?></th>
            <th class="text-left d-none d-sm-table-cell"><?php echo $column_email; ?></th>
            <th class="text-left d-none d-sm-table-cell"><?php echo $column_phone; ?></th>
            <th class="text-right"><span class="hidden-xs"><?php echo $column_action; ?></span></th>
          </tr>
        </thead>
        <tbody>
          <?php if ($customers): ?>
            <?php foreach ($customers as $customer): ?>
              <tr>
                <td class="text-left"><?php echo $customer['date_added'] ?></td>
                <td class="text-left"><?php echo $customer['customer'] ?></td>
                <td class="text-left d-none d-sm-table-cell"><?php echo $customer['city'] ?></td>
                <td class="text-left d-none d-sm-table-cell"><?php echo $customer['email'] ?></td>
                <td class="text-left d-none d-sm-table-cell"><?php echo $customer['telephone'] ?></td>
                <td class="text-right"><a href="<?php echo $customer['href'] ?>" class="btn btn-info">
                  <i class="fas fa-eye"></i><span class="d-none d-sm-inline"> <?php echo $customer['text'] ?></span>
                </a></td>
              </tr>
            <?php endforeach ?>
          <?php else: ?>
            <tr>
              <td class="text-center" colspan="6"><?php echo $text_no_results ?></td>
            </tr>
          <?php endif ?>
        </tbody>
      </table>
    </div>
    <div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
  </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter(opcion,email1,subject1,text1) {
	url = 'index.php?route=report/customer_support&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	if (opcion=='print'){
       url += '&print=1';
    }else if (opcion=='pdf'){
       url += '&print=2';
    }else if (opcion=='email'){
       url += '&print=3';
    }else{
        url +='&print=0';
    }
    url +='&uiemail=' + email1 ;
    url +='&uisubject=' + subject1 ;
    url +='&uitext=' + encodeURIComponent(text1) ;
	
	location = url;
} 
//--></script> 
<script type="text/javascript"><!--
function checkLength( o, n, min, max ) {
	if ( o.val().length > max || o.val().length < min ) {
		alert ("Length of " + n + " must be between " +
			min + " and " + max + "." );
		return false;
	} else {
		return true;
	}
}
$('#print').click(function() {
    var $dialog2 = $('<div></div>')
    .load('view/template/report/print.php')
    .dialog({
			autoOpen: false,
            title: 'Send mail with report',
			width:'auto',
			buttons: {
				"Cancel": function() {
					$( this ).dialog( "close" );
				},
                "Send E-Mail": function() {
                    var lctexto = $('#uitext').val();
                    var bValid = true;
                    bValid = bValid && checkLength( $('#uiemail'), "email", 6, 30 );
					bValid = bValid && checkLength( $('#uisubject'), "subject", 5, 80 );
                    bValid = bValid && checkLength( $('#uitext'), "Text", 3, 250 );

                    if ( bValid ) {
                        filter('email',$('#uiemail').val(),$('#uisubject').val(),lctexto);
                    }
                }
			}
	});
    var $dialog = $('<div>Select Option</div>')
    .dialog({
        autoOpen: false,
        title: 'Select Print Output',
        buttons: {
            "Cancel": function() {
                $(this).dialog("close");
            },
            "Print": function() {
                filter('print');
            },
            "PDF": function() {
                filter('pdf');
            },
            "E-Mail": function() {
                $(this).dialog("close");
                $dialog2.dialog('open');
            }
        }
    });
    $dialog.dialog('open');
});
//--></script>
<?php echo $footer; ?>