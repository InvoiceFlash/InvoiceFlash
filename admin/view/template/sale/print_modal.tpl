<!-- Modal -->
<div id="PrintModal" class="modal fade" role="dialog" tabindex='-1'>
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Print Select</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form action="<?php echo $print ?>" id="formPrint" method="post" target="_blank">
          <?php foreach ($reports as $report): ?>
            <div class="radio">
              <label><input type="radio" name="report" value="<?php echo $report['report'] ?>"><?php echo $report['name'] ?></label>
            </div>
          <?php endforeach ?>
        </form>
  	  </div>
      <div class="modal-footer">
        <button class="btn btn-success" type="submit" form="formPrint" id="send"><i class="fa fa-print"></i> Print</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('input[name="report"]:first').each(function(){
    $(this).prop('checked', true);
});
</script>