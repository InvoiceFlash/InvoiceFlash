<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
  <?php $fa = 'clock'; include(DIR_TEMPLATE . 'common/template-title-list.tpl'); ?>
	<div class="panel-body">
    <div clasS="panel panel-default" style="max-height:250px;">
      <div class="panel-heading"><i class="fa fa-question-circle"></i> <?php echo $text_instruction; ?></div>
      <div class="panel-body">
        <p><?php echo $text_cron_1; ?></p>
        <p><?php echo $text_cron_2; ?></p>
        <div class="input-group">
          <div class="input-group-prepend"><span class="input-group-text"><?php echo $entry_cron; ?></span></div>
          <input type="text" value="wget &quot;<?php echo $cron; ?>&quot; --read-timeout=5400" id="input-cron" class="form-control">
        </div>
      </div>
    </div>
    <div class="panel panel-default mt-2" style="max-height:500px;">
      <div class="panel-heading"><i class="fa fa-list"></i> <?php echo $text_list; ?></div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
         
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th class="text-center" style="width:40px;"><input type="checkbox" onclick="$('input[name*=\'selected\']').trigger('click');"/></th>
                  <th class="text-left"><?php if($sort == 'code'){ ?><a href="<?php echo $sort_code; ?>" class="<?php strtolower($order); ?>"><?php echo $column_code; ?></a><?php } else { ?><a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a><?php } ?></th>
                  <th class="text-left"><?php if($sort == 'action'){ ?><a href="<?php echo $sort_action; ?>" class="<?php strtolower($order); ?>"><?php echo $column_action; ?></a><?php } else { ?><a href="<?php echo $sort_action; ?>"><?php echo $column_action; ?></a><?php } ?></th>
                  <th class="text-left"><?php if($sort == 'status'){ ?><a href="<?php echo $sort_status; ?>" class="<?php strtolower($order); ?>"><?php echo $column_status; ?></a><?php } else { ?><a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a><?php } ?></th>
                  <th class="text-left"><?php if($sort == 'date_added'){ ?><a href="<?php echo $sort_date_added; ?>" class="<?php strtolower($order); ?>"><?php echo $column_date_added; ?></a><?php } else { ?><a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a><?php } ?></th>
                  <th class="text-left"><?php if($sort == 'date_modified'){ ?><a href="<?php echo $sort_date_modified; ?>" class="<?php strtolower($order); ?>"><?php echo $column_date_modified; ?></a><?php } else { ?><a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a><?php } ?></th>
                  <th class="text-right"><?php echo $column_action; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php if($crons) { ?>
                  <?php foreach($crons as $cron) { ?>
                    <tr>
                      <td class="text-center"><input type="checkbox" name="selected[]" value="<?php echo $cron['cron_id']; ?>"<?php echo (in_array($cron['cron_id'], $selected)) ? ' checked="checked"' : ''; ?> ></td>
                        <td class="text-left"><?php echo $cron['code']; ?></td>
                        <td class="text-left"><?php echo $cron['action']; ?><input type="hidden"></td>
                        <td class="text-left"><?php echo $cron['status']; ?></td>
                        <td class="text-left"><?php echo $cron['date_added']; ?></td>
                        <td class="text-left"><?php echo $cron['date_modified']; ?></td>
                        <td class="text-right">
                          <?php echo $cron['edit']; ?>
                          <a data-toggle="tooltip" id="btn-run" data-title="<?php echo $button_run; ?>" data-id="<?php echo $cron['cron_id']; ?>" class="btn btn-warning"><i class="fa fa-play"></i></a>
                        </td>
                    </tr>
                  <?php } ?>
                <?php } else { ?>
                  <tr>
                    <td colspan="9" class="text-center"><?php echo $text_no_results; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="pagination"><?php echo str_replace('....','',$pagination); ?></div>
      </div>
    </div>
  </div>
</div>
<script>
$('#btn-run').click(function(){
		$.ajax({
			url:'index.php?route=setting/cron/run&token='+token,
			type:'post',
			dataType:'json',
			data:'id='+$(this).attr('data-id'),
			complete:function(){
				$(this).button('reset');
			},
			success:function(json){
        if(json['error']){
          alertMessage('danger', json['error']);
        }else{
          alertMessage('success',json['success']);
        }
			}
		});
	});
</script>
<?php echo $footer; ?>