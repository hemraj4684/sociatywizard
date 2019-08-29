<?php
$no=0;
?>
<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12">
<h5 class="breadcrumbs-title"><i class="mdi-action-assignment"></i> Reports</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('dashboard')?>">Dashboard</a> / </li>
<li class="active">Reports</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12">
<div class="card">
<div class="card-content">
<div class="report-list collection with-header">
	<div class="collection-header"><h5 class="font-20 bold-500">Below are the Expenses Report</h5></div>
	<a target="_blank" class="collection-item" href="<?=base_url('reports/view/5')?>"><?=++$no?>. Expenses Datewise</a>
	<a target="_blank" class="collection-item" href="<?=base_url('reports/view/1')?>"><?=++$no?>. Current Month Expense</a>
	<a target="_blank" class="collection-item" href="<?=base_url('reports/view/2')?>"><?=++$no?>. Last Month Expense</a>
	<a target="_blank" class="collection-item" href="<?=base_url('reports/view/3')?>"><?=++$no?>. Last Three Month Expense</a>
	<a target="_blank" class="collection-item" href="<?=base_url('reports/view/4')?>"><?=++$no?>. Expense In Last 30 Days</a>
	<?php
		foreach($data as $key => $value) {
			echo '<a target="_blank" class="collection-item" href="'.base_url('reports/expense_category/'.$value->id).'">'.++$no.'. Expense - '.h($value->name).'</a>';
		}
		unset($value);
		$no=0;
	?>
</div>
</div>
</div>
</div>
<div class="col s12">
<div class="card">
<div class="card-content">
<div class="report-list collection with-header">
	<div class="collection-header"><h5 class="font-20 bold-500">Below are the Income Reports</h5></div>
	<a target="_blank" class="collection-item" href="<?=base_url('reports/view/6')?>"><?=++$no?>. Income Datewise</a>
	<a target="_blank" class="collection-item" href="<?=base_url('reports/view/7')?>"><?=++$no?>. Current Month Income</a>
	<a target="_blank" class="collection-item" href="<?=base_url('reports/view/8')?>"><?=++$no?>. Last Month Income</a>
	<a target="_blank" class="collection-item" href="<?=base_url('reports/view/9')?>"><?=++$no?>. Last Three Month Income</a>
	<a target="_blank" class="collection-item" href="<?=base_url('reports/view/10')?>"><?=++$no?>. Income In Last 30 Days</a>
	<?php
		foreach($data1 as $key => $value) {
			echo '<a target="_blank" class="collection-item" href="'.base_url('reports/income_category/'.$value->id).'">'.++$no.'. Income - '.h($value->name).'</a>';
		}
		unset($value);
	?>
</div>
</div>
</div>
</div>
</div>