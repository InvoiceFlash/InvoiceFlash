<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
  <div class="panel-heading clearfix">
    <div class="pull-left h2"><i class="hidden-xs fa fa-user"> <?php echo $heading_title ?></i></div>
    <div class="pull-right">
      <a href="<?php echo $insert; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i><span class="hidden-xs"> <?php echo $button_insert; ?></span></a>
      <button type="submit" form="form" form action="<?php echo $delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_delete; ?></span></button>
    </div>
  </div>
  <div class="panel-body">
    <form action="<?php echo $delete ?>" class="table" method="post" enctype="multipart/form-data" id="form">
      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr>
            <th width="40" class="text-center"><input type="checkbox" data-toggle="selected"></th>
            <th class="text-left"><?php if ($sort == 'name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></th>
            <th class="text-right"><?php echo $column_action; ?></th>
          </tr>
        </thead>
        <tbody>
         <?php if ($potentials_groups): ?>
           <?php foreach ($potentials_groups as $potentials_group): ?>
              <tr>
                <td class="text-center"><?php if ($potentials_group['selected']) { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $potentials_group['potentials_group_id']; ?>" checked="checked" />
                <?php } else { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $potentials_group['potentials_group_id']; ?>" />
                <?php } ?></td>
                <td><?php echo $potentials_group['name']; ?></td>
                <td class="text-right"><?php foreach ($potentials_group['action'] as $action) { ?>
                  <a href="<?php echo $action['href']; ?>" class="btn btn-default"><i class="fas fa-edit"></i><?php echo $action['text']; ?></a>
                <?php } ?></td>
              </tr>
           <?php endforeach ?>
           <?php else: ?>
            <tr>
              <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
            </tr>
         <?php endif ?>
        </tbody>
      </table>
    </form>
  </div>
</div>

<?php echo $footer; ?>