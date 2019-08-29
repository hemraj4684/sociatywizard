<table class="striped bordered data_list"><thead><tr><th>#</th><th>Name</th><th>Contact No.</th><th>Flat No</th><th>Purpose</th><th>Date/Time Of Visit</th></thead><tbody>
<?php
foreach ($data as $key => $value) {
	echo '<tr><td><input type="checkbox" class="filled-in v-item" value="'.$value->log_id.'" id="v-c-'.$value->log_id.'" name="visitor[]"><label for="v-c-'.$value->log_id.'" class="valign-top"></label> <img width="100" src="';
	echo ($value->image) ? base_url('assets/visitors/'.$this->society.'/'.h($value->image)) : base_url('assets/images/user_image.png');
	echo '" class="responsive-img"></td><td>'.h($value->name).'</td><td>'.h($value->number).'</td><td>';
	echo ($value->wing) ? h($value->wing).' - ' : '';
	echo h($value->flat_no).'</td><td>'.h($value->purpose).'</td><td>'.date('dS M Y, h:ia',strtotime($value->entry_date)).'</td></tr>';
}
unset($value);
?>

</tbody>
<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
</table>