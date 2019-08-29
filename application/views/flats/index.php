<div id="breadcrumbs-wrapper" class="animated fadeIn">
<div class="row mb0">
<div class="col s12 m6">
<h5 class="breadcrumbs-title">Flats Management</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Flats Management</li>
</ol>
</div>
<div class="col s12 m6">
<a class="btn-small right white-text waves-effect waves-light blue btn" href="<?php echo base_url('flats/add'); ?>"><i class="fa fa-plus"></i> Add A Flat</a>
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12 m6">
<a href="<?php echo base_url('flats/all_flats'); ?>" class="red center accent-3 white-text bold-500 animated fadeIn card-panel block"><i class="fa fa-building"></i> See All Flats</a>
<?php
foreach ($wings as $key => $value) {
	echo '<input type="hidden" name="wing[]" value="'.$value->id.'"><div class="col s6"><a href="'.base_url('flats/list_by_block/'.$value->id).'" class="indigo center accent-3 white-text bold-500 animated fadeIn card-panel block"><i class="fa fa-building"></i> '.h($value->total_flats).' Flats in Block '.h($value->name).'</a></div>';
}
echo '<div class="clearfix"></div>';
unset($value);
?>
</div>
<div class="col s12 m6">
<div class="card"><div class="card-content">
<span class="card-title">Flats Summary</span>
<div class="sum-res"></div>
</div>
</div>
</div>
<?php
echo '<div class="col s12">';
foreach ($wings as $key => $value) {
	echo '<div class="col s12 m6 l4"><div class="card"><div class="card-content"><span class="card-title">Summary of Block '.h($value->name).'</span><div class="sum-res-'.$key.'"></div><canvas id="summary-'.$key.'" width="100%"></canvas></div></div></div>';
}
unset($value);
echo '</div>';
?>
</div>