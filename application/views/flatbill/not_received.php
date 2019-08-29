<div class="row height-600">
<div class="col s12">
<div class="card"><div class="card-content">
<table class="table data_list bordered"><thead><tr><th>Flat No</th></tr></thead>
<tbody>
<?php
foreach ($data as $key => $value) {
	echo '<tr><td>';
	if(!empty($value->name)){echo h($value->name).' - ';}
	echo h($value->flat_no);
	echo '</td></tr>';
}
?>
</tbody></table>
</div>
</div>
</div>
</div>