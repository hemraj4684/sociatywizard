<div class="row"><div class="col s12 m10 l8 offset-l2 offset-m1"><div class="card">
	<div class="card-content">
		<div class="row mb0">
			<?=form_open('','id="'.$id.'"')?><?php
			if($id=='custom_ie_category'){echo '<input type="hidden" name="category" value="'.h($cat).'">';}
			if($type=='6'){echo '<input type="hidden" name="trans" value="1">';}else{echo '<input type="hidden" name="trans" value="2">';}
			?>
			<div class="input-field col s12 m4">
				<input type="text" class="datepicker" id="report_date_from" name="date_from"><label for="report_date_from">Date From</label>
			</div>
			<div class="input-field col s12 m4">
				<input type="text" class="datepicker" id="report_date_to" name="date_to"><label for="report_date_to">Date To</label>
			</div>
			<div class="input-field col s12 m4">
				<button class="sr-btn btn col s12"><i class="fa fa-search left"></i> Search</button>
			</div>
			<?=form_close()?>
		</div>
	</div>
</div></div></div>