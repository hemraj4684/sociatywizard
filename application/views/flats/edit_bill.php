<?php
$year = date('Y');
$inv_y = date('Y',strtotime($month->invoice_month));
$inv_m = date('m',strtotime($month->invoice_month));
$diff=1;
if(!empty($month->advance_month)){
$str_to = strtotime($month->invoice_month);
$str_from = strtotime($month->advance_month);
$year1 = date('Y', $str_to);
$year2 = date('Y', $str_from);
$month1 = date('m', $str_to);
$month2 = date('m', $str_from);
$diff = (($year2 - $year1) * 12) + ($month2 - $month1) + 1;
}
?>
<?php $this->load->view('layout/head'); ?>
<div class="row mb0">
<div class="col s12">
<?=form_open('','id="edit-bill" class="card"')?>
<input type="hidden" value="<?=$id?>" name="bill_id" id="bill_id">
<div class="card-content">
<div class="row">
<div class="col s12">
<div class="card-title">Bill Of Flat No : <?php if(!empty($flat->wing_name)){echo $flat->wing_name.' - ';} echo $flat->flat_no?></div>
<p class="mb10">Date Sent : <?=date('dS M Y',strtotime($month->date_created))?></p>
<p class="mb10">Bill For The Month Of : <?=date('F Y',strtotime($month->invoice_month))?>
<?php
if(!empty($month->advance_month)){
echo ' to '.date('F Y',strtotime($month->advance_month));
}
?>
</p>
<p class="mb10">Total Due : <i class="fa fa-rupee"></i> <?=$month->total_amount?>/-</p>
<button type="submit" class="indigo submit-btn btn-small accent-3 btn"><i class="fa fa-edit"></i> Save Changes</button> <button type="button" onclick="window.close()" class="red btn-small accent-3 btn"><i class="fa fa-close"></i> Cancel</button> <a class="blue btn btn-small right no-print" href="<?=base_url('flats/view_bill/'.$id)?>"><i class="fa fa-folder"></i> View Bill</a>
<div class="form-errors mt15">
<p class="send_to"></p>
<p class="due_date"></p>
<p class="fine"></p>
</div>
<hr>
</div>
<div class="input-field col s12 m6">
<label for="due_date">Due Date</label>
<input type="text" name="due_date" id="due_date" value="<?=date('d-m-Y',strtotime($month->due_date))?>" class="datepicker">
</div>
<div class="input-field col s12 m6">
<label for="fine">Fine</label>
<input type="text" name="fine" id="fine" value="<?=$month->fine?>">
<input type="hidden" name="fine_old" value="<?=$month->fine?>">
</div>
</div>
</div>
<?=form_close()?>
</div>

<?php if($month->late_fee_amonut>0){ ?>
<div class="col s12">
<div class="card">
<div class="card-content">
<p class="center font-16 bold-500 mb5">Late payment interest ( <i class="fa fa-rupee"></i> <?=$month->late_fee_amonut?>/- ) has been added to this bill.</p>
<button class="btn btn-small center-block block red accent-4 clean-interest-btn">Remove Interest Amount ?</button>
</div>
</div>
</div>
<?php } ?>

</div>
<script>var URL = "<?php echo base_url(); ?>"</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/materialize.min.js'); ?>"></script>
<?=script_js()?>
<script type="text/javascript" src="<?php echo base_url('assets/js/edit_bill.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bill_calci.js'); ?>"></script>
</body>
</html>