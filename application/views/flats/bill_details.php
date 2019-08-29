<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" href="<?php echo base_url('assets/css/materialize.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/animate.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
<?=style_css()?>
</head>
<body>
<div class="row mb0">
<div class="col s12">

<div class="card indigo accent-3 white-text">
<div class="card-content">
<div class="row mb0">
<div class="col s12">
<div class="card-title">Bill Details Of Flat No : <?php $dop_date = date('d-m-Y'); if(!empty($details->date_of_payment)){$dop_date=date('d-m-Y',strtotime($details->date_of_payment)); } if(!empty($details->name)){echo $details->name.' - ';} echo $details->flat_no?></div></div>
<p class="col s12 mb10">For Month Of : <?php echo date('F Y',strtotime($details->invoice_month)); if(!empty($details->advance_month)){ echo ' to '.date('F Y',strtotime($details->advance_month)); }?></p>
<div class="col s12 mb10">
Total Amount : <i class="fa fa-rupee"></i> <?=h($details->total_amount)?>
</div>
</div>
</div>
</div>
<div class="form-errors res">
<div class="paid"></div>
<div class="p_method"></div>
<div class="dop_err"></div>
<div class="amt_paid"></div>
</div>
<div style="display:none" class="success-area"><div class="card-panel green"><i class="fa fa-thumbs-o-up"></i> Details Have Been Successfully Saved</div></div>
<?=form_open('','id="edit-bill-details" class="card"')?>
<input type="hidden" name="id" value=<?=h($id)?>>
<div class="card-content">
<div class="row mb0">
<table class="table bordered">
<tbody>
<tr><td colspan="2"><button class="btn green submit-btn btn-small" type="submit"><i class="fa fa-save"></i> Save Changes</button> <button class="btn red accent-3 btn-small" onclick="window.close()" type="button"><i class="fa fa-close"></i> Cancel</button></td></tr>
<tr><td width="35%" class="grey-text text-darken-2">Payment Status</td><td>
<input name="paid" type="radio" value="1" id="pt1"<?php if($details->is_paid==='1' || $details->is_paid==='3'){echo ' checked';} ?>><label for="pt1">Paid</label>
<input name="paid" type="radio" value="2" id="pt2"<?php if($details->is_paid==='2'){echo ' checked';} ?>><label for="pt2">Not Paid</label>
</td></tr></tbody></table>
<table <?php if($details->is_paid==='2'){echo 'style="display:none"';}?> class="table bordered p_details">
<tbody>
<tr><td width="35%"><label class="font-14 grey-text text-darken-2" for="amt_paid">Total Amount Paid</label></td><td><input type="text" name="amt_paid" id="amt_paid" value="<?=($details->is_paid==='1') ? h($details->amount_paid) : h($details->cheque_amount)?>"></td></tr>
<tr><td width="35%"><label class="font-14 grey-text text-darken-2" for="dop">Date Of Payment</label></td><td><input type="text" name="dop" class="datepicker" id="dop" value="<?=$dop_date?>"></td></tr>
<tr><td class="grey-text text-darken-2">Payment Method</td><td>
<input name="p_method" type="radio" value="1" id="pm1"<?php $is_cheque = 0; if($details->payment_method==='1'){echo ' checked';} ?>><label for="pm1">Cash</label>
<input name="p_method" type="radio" value="2" id="pm2"<?php if($details->payment_method==='2'){$is_cheque=1;echo ' checked';} ?>><label for="pm2">Cheque</label>
<input name="p_method" type="radio" value="3" id="pm3"<?php if($details->payment_method==='3'){echo ' checked';} ?>><label for="pm3">Other</label>
</td></tr>
<tr <?php if($is_cheque===0){echo 'style="display:none"';} ?> class="on_pm"><td><label class="font-14 grey-text text-darken-2" for="cq_no">Cheque No</label></td><td><input type="text" name="cq_no" id="cq_no" value="<?=h($details->cheque_no)?>" placeholder="Enter Cheque Number"></td></tr>
<tr <?php if($is_cheque===0){echo 'style="display:none"';} $cq_date = ''; if(!empty($details->cheque_date)){$cq_date=date('d-m-Y',strtotime($details->cheque_date)); } ?> class="on_pm"><td><label class="font-14 grey-text text-darken-2" for="cq_date">Cheque Date</label></td><td><input type="text" class="datepicker" name="cq_date" id="cq_date" value="<?=$cq_date?>" placeholder="Enter Cheque Date"></td></tr>
<tr <?php if($is_cheque===0){echo 'style="display:none"';} ?> class="on_pm"><td></td><td><input type="checkbox" name="cheque_success" id="cheque_success" class="filled-in" value="1"<?php if($details->cheque_status==='1'){echo ' checked';} ?>><label class="font-14 grey-text text-darken-2" for="cheque_success">Cheque Cleared</label></td></tr>
<tr><td><label class="font-14 grey-text text-darken-2" for="note_area">Note</label></td><td><textarea id="note_area" class="materialize-textarea" name="note" placeholder="Write a note"><?=h($details->note)?></textarea></td></tr></tbody></table>


<!-- <div class="input-field col s12"> -->
<?php
// var_dump($details);
?>
<!-- </div> -->
</div>
</div>
<?=form_close()?>
</div>
</div>
<script>var URL = "<?php echo base_url(); ?>"</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/materialize.min.js'); ?>"></script>
<?=script_js()?>
<script type="text/javascript" src="<?php echo base_url('assets/js/bill_details.js'); ?>"></script>
</body>
</html>