<?=form_open('','class="flat_bill_form"')?>
<table class="highlight bordered">
	<thead>
		<tr>
			<th>Flat No</th>
			<th>Bill Groups</th>
		</tr>
		<tr>
			<th>
				<button type="submit" class="hide btn-flat right">Save Changes</button>
			</th>
			<th>
				<?php
					foreach ($bills as $key => $bill) {
						echo '<input class="with-gap" name="all_gs" data-target="bill_'.$bill->id.'" value="'.$bill->id.'" class="bg_check_all" type="radio" id="all_g'.$key.'"> <label for="all_g'.$key.'">'.h($bill->name).'</label> &nbsp;&nbsp;&nbsp;';
					}
					unset($bill)
				?>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach ($flats as $key => $value) {
				echo '<tr';
				if(empty($value->bill_id)){
					echo ' class="purple lighten-5"';
				}
				echo '><td>';
				if($value->wing_name){
					echo h($value->wing_name).' - ';
				}
				echo h($value->flat_no);
				echo '</td><td>';
				foreach ($bills as $kb => $bill) {
					echo '<input value="'.$bill->id.'" class="with-gap bill_radio bill_'.$bill->id.'" name="group['.h($value->id).']" type="radio" id="bg_'.$key.'_'.$kb.'"';
					if($bill->id===$value->bill_id){
						echo ' checked';
					}
					echo '> <label for="bg_'.$key.'_'.$kb.'">'. h($bill->name).'</label> &nbsp;&nbsp;&nbsp;';
				}
				unset($bill);
				echo '</td></tr>';
			}
			unset($value);
		?>
	</tbody>
</table>
<?=form_close()?>
