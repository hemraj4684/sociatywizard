<div id="breadcrumbs-wrapper">
<div class="row mb0">
<div class="col s12">
<h5 class="breadcrumbs-title">Vendors</h5>
<ol class="breadcrumb">
<li><a href="<?=base_url('me')?>">Home</a> / </li>
<li class="active">Vendors</li>
</ol>
</div>
</div>
</div>
<div class="row mt10 height-600">
<div class="col s12 table-cover">
<table class="striped bordered data_list"><thead><tr><th>#</th><th>Name</th><th>Contact No.</th><th>Category</th><th>Address</th></tr></thead><tbody>
<?php
foreach ($data as $key => $value) {
	echo '<tr><td>'.++$key.'</td><td>'.h($value->contact_name).'</td><td>'.h($value->contact_number_1);
	if(!empty($value->contact_number_2)){
		echo '<br>'.h($value->contact_number_2);
	}
	echo '</td><td>'.h($value->category).'</td><td data-addr="'.h($value->address).'">'.para($value->address).'</td></tr>';
}
unset($value);
?>
</tbody></table>
</div>
</div>