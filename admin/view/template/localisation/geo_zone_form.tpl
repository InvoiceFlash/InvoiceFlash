<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<?php $fa = 'globe'; include(DIR_TEMPLATE . 'common/template-title-form.tpl'); ?>
	<div class="panel-body">
		<form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_name; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="name" value="<?php echo $name; ?>" class="form-control" autofocus="">
					<?php if ($error_name) { ?>
						<div class="help-block error"><?php echo $error_name; ?></div>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><b class="required">*</b> <?php echo $entry_description; ?></label>
				<div class="control-field col-sm-4">
					<input type="text" name="description" value="<?php echo $description; ?>" class="form-control">
					<?php if ($error_description) { ?>
						<div class="help-block error"><?php echo $error_description; ?></div>
					<?php } ?>
				</div>
			</div>
			<table id="zone-to-geo-zone" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th><?php echo $entry_country; ?></th>
						<th><?php echo $entry_zone; ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php $zone_to_geo_zone_row = 0; ?>
				<?php foreach ($zone_to_geo_zones as $zone_to_geo_zone) { ?>
					<tr id="zone-to-geo-zone-row<?php echo $zone_to_geo_zone_row; ?>">
						<td><select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][country_id]" id="country<?php echo $zone_to_geo_zone_row; ?>" onchange="$('#zone<?php echo $zone_to_geo_zone_row; ?>').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id='+this.value+'&zone_id=0');" class="form-control">
							<?php foreach ($countries as $country) { ?>
							<?php	if ($country['country_id'] == $zone_to_geo_zone['country_id']) { ?>
							<option value="<?php echo $country['country_id']; ?>" selected=""><?php echo $country['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select></td>
						<td><select name="zone_to_geo_zone[<?php echo $zone_to_geo_zone_row; ?>][zone_id]" id="zone<?php echo $zone_to_geo_zone_row; ?>" class="form-control"></select></td>
						<td><a onclick="$('#zone-to-geo-zone-row<?php echo $zone_to_geo_zone_row; ?>').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>
					</tr>
				<?php $zone_to_geo_zone_row++; ?>
				<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="2"></td>
						<td><a onclick="addGeoZone();" class="btn btn-info"><i class="fa fa-plus-circle"></i><span class="hidden-xs">	<?php echo $button_add_geo_zone; ?></span></a></td>
					</tr>
				</tfoot>
			</table>
		</form>
	</div>
</div>
<?php $zone_to_geo_zone_row = 0; ?>
<script>
$('#zone-id').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id='+$('#country-id').val()+'&zone_id=0');
<?php foreach ($zone_to_geo_zones as $zone_to_geo_zone) { ?>
$('#zone<?php echo $zone_to_geo_zone_row; ?>').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=<?php echo $zone_to_geo_zone['country_id']; ?>&zone_id=<?php echo $zone_to_geo_zone['zone_id']; ?>');
<?php $zone_to_geo_zone_row++; ?>
<?php } ?>
</script>
<script>
var zone_to_geo_zone_row=<?php echo $zone_to_geo_zone_row; ?>;

function addGeoZone(){
	html ='<tr id="zone-to-geo-zone-row'+zone_to_geo_zone_row+'">';
	html+='<td><select name="zone_to_geo_zone['+zone_to_geo_zone_row+'][country_id]" id="country'+zone_to_geo_zone_row+'" onchange="$(\'#zone'+zone_to_geo_zone_row+'\').load(\'index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=\'+this.value+\'&zone_id=0\');" class="form-control">';
	<?php foreach ($countries as $country) { ?>
	html+='<option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
	<?php } ?>	
	html+='</select></td>';
	html+='<td><select name="zone_to_geo_zone['+zone_to_geo_zone_row+'][zone_id]" id="zone'+zone_to_geo_zone_row+'" class="form-control"></select></td>';
	html+='<td><a onclick="$(\'#zone-to-geo-zone-row'+zone_to_geo_zone_row+'\').remove();" class="btn btn-danger"><i class="fa fa-trash "></i><span class="hidden-xs"> <?php echo $button_remove; ?></span></a></td>';
	html+='</tr>';
	
	$('#zone-to-geo-zone > tbody').append(html);
		
	$('#zone'+zone_to_geo_zone_row).load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id='+$('#country'+zone_to_geo_zone_row).val()+'&zone_id=0');
	
	zone_to_geo_zone_row++;
}
</script>
<?php echo $footer; ?>