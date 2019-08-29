<div id="breadcrumbs-wrapper" class="animated fadeIn">
<div class="row mb0">
<div class="col s12 m4">
<h5 class="breadcrumbs-title">Maintenance Bill</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Maintenance Bill</li>
</ol>
</div>
<div class="col s12 m8">
<a href="<?=base_url('flatbill/pending')?>" class="waves-effect waves-light purple btn btn-small mb5"><i class="fa fa-file-text-o"></i> Pending Bills</a>
<a href="<?=base_url('flatbill/arrears')?>" class="waves-effect waves-light red accent-3 btn btn-small mb5"><i class="fa fa-file-text-o"></i> Arrears</a>
<a href="<?=base_url('flatbill/pending_cheques')?>" class="waves-effect waves-light blue btn btn-small mb5"><i class="fa fa-file-text-o"></i> Pending Cheques</a>
<a class="btn-small white-text waves-effect waves-light orange accent-3 btn mb5" href="<?php echo base_url('flatbill/generate'); ?>"><i class="mdi-content-send"></i> Create Bill</a>
</div>
</div>
</div>
<div class="row height-600">
<div class="col s12">
<div class="card"><div class="auto-scroll card-content">
<table class="table data_list mb15 bordered highlight"><thead><tr><th>#</th><th>Month</th><th>Total Amount Of Bill Generated</th><th>Total Amount Collected</th><th>Action</th></tr></thead><tbody>
<?php
$arr = array();
$year = date('Y');
if(!empty($months)){
foreach ($months as $key => $month) {
	$date = strtotime($month->invoice_month);
	if($year===date('Y',$date)){
		$arr[date('m',$date)] = array($month->collected,$month->generateds);
	}
	echo '<tr><td>'.++$key.'</td><td>'.date('F Y',$date).'</td><td><i class="fa fa-rupee"></i> '.number_format($month->generateds).'/-</td><td><i class="fa fa-rupee"></i> '.number_format($month->collected).'/-</td><td><a class="btn green btn-small" href="'.base_url('flatbill/month/'.date('m',$date).'/'.date('Y',$date)).'"><i class="fa fa-folder"></i> view</a></td></tr>';
}
unset($month);
} else{
echo '<tr><th class="center" colspan="5">No Data Available</th></tr>';
}
?>
</tbody></table>
<?php
$array = array('01','02','03','04','05','06','07','08','09','10','11','12');
$res = array();
$col = array();
foreach ($array as $key => $month) {
	if(isset($arr[$month])){
		array_push($res, $arr[$month][0]);
		array_push($col, $arr[$month][1]);
	} else {
		array_push($res, 0);
		array_push($col, 0);
	}
}
unset($month);
$json1 = json_encode($res);
$json2 = json_encode($col);
?>
<div class="row mt15"><div class="col s12"><canvas id="collecton_chart"></canvas></div></div>
</div>
</div>
</div>
</div>
<script>var co = <?=$json1?></script>
<script>var ds = <?=$json2?></script>