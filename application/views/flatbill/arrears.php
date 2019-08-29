<div id="breadcrumbs-wrapper" class="animated fadeIn">
<div class="row mb0">
<div class="col s12 m12 l12">
<h5 class="breadcrumbs-title">Arrears</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li><a href="<?=base_url('flatbill')?>">Maintenance Bill</a> / </li>
<li class="active">Arrears</li>
</ol>
</div>
</div>
</div>
<div class="row height-600">
<div class="col s12">
<div class="card"><div class="auto-scroll card-content">
<table class="table data_list bordered highlight">
<thead><tr><th>#</th><th>Flat No</th><th>Arrears</th><th width="150px">Action</th></thead><tbody>
<?php
$inc=0;
foreach ($data as $key => $value) {
	if($value->total_received>$value->total_paid){
		$total = (($value->total_received-$value->total_paid)-$value->d_arrear)-$value->c_arrear;
		$total_s = abs($total).' CR';
	} else {
		$total = $value->c_arrear+($value->total_paid-$value->total_received-$value->d_arrear);
		$total_s = abs($total).' DB';
	}
	if($total!=0){
		echo '<tr><td>'.++$inc.'</td><td>';
		if(!empty($value->name)){
			echo h($value->name).' - ';
		}
		echo h($value->flat_no).'</td><td><i class="fa fa-rupee"></i> ';
		echo $total_s;
		echo '</td><td><a class="btn green btn-small" href="'.base_url('flats/old_invoices/'.$value->flat_id.'?ref=arrears').'"><i class="fa fa-history"></i> view history</a></td></tr>';
	}
}
unset($value);
?>
</tbody>
</table>
</div>
</div>
</div>
</div>