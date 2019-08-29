<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" href="<?php echo base_url('assets/css/materialize.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/animate.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
<?=style_css()?>
</head>
<body class="white">
<div class="row">
<div class="col s12">
<div class="row mb0 mt15">
<div class="mb15 center col s12">
<button class="btn btn-small no-print accent-3 red" onclick="window.close()"><i class="fa fa-close"></i> Cancel</button>
<button class="btn btn-small no-print green" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
<?php if($month->is_paid!=='1' && $this->session->type==='1'){ ?>
<a class="blue btn btn-small no-print" href="<?=base_url('flats/edit_bill/'.$month->id)?>"><i class="fa fa-edit"></i> Edit</a>
<?php } ?>
</div>
<div class="center col s12">
<h5 class="mt0">Invoice</h5>
<?=$soc_name?>
<?=$soc_addr?>
<?=$reg_no?>
</div>
<div class="col s6">
<p class="mb0"><b>For The Month Of :</b> <?php echo date('F Y',strtotime($month->invoice_month));if(!empty($month->advance_month)){echo ' to '.date('F Y',strtotime($month->advance_month));} ?></p>
</div>
<div class="right-align col s6">
<p class="mb0"><b>Flat No :</b> <?php echo $flat->flat_no;if(!empty($flat->flat_wing)){echo ' - '.$flat->wing_name;} ?></p>
</div>
<div class="clearfix col s6">
<p class="mb10"><b>Name :</b> <?php echo h($flat->owner_name); ?></p>
</div>
<div class="right-align col s6">
<p class="mb10"><b>Date :</b> <?php echo date('d/m/Y',strtotime($month->date_created)); ?></p>
</div>
</div>
<div class="row">
<div class="col s12 m12">
	<table class="mb10 bordered user_data table-sm-padding"><tbody>
	<tr><th>Sr no.</th><th>Particulars</th><th class="right-align">Amount</th></tr>
		<?php
			$bill_amount = $month->total_amount;
			foreach ($partic as $key => $value) {
				echo '<tr><td>'.++$key.'</td><td>'.$value->particulars.'</td><td class="right-align">'.$value->amount.'</td></tr>';
			}
			unset($value);
			echo '<tr><th colspan="2">Total Amount</th><td class="right-align"><i class="fa fa-rupee"></i> '.number_format($month->principal_amount,2).'/-</td></tr>';
			// if($month->credit_arrear>0 || $prev_bal>0){
			if($month->credit_arrear>0){
				// $bill_amount = $bill_amount;// + $prev_bal;
				echo '<tr><th colspan="2">Arrears</th><td class="right-align"><i class="fa fa-rupee"></i> '.number_format($month->credit_arrear).' DB</td></tr>';
			}
			if($month->debit_arrear>0){
				echo '<tr><th colspan="2">Arrears</th><td class="right-align"><i class="fa fa-rupee"></i> '.h($month->debit_arrear).' CR</td></tr>';
			}
			if($month->fine>0){
				echo '<tr><th colspan="2">Fine</th><td class="right-align"><i class="fa fa-rupee"></i> '.h($month->fine).'/-</td></tr>';
			}
			if($month->late_fee_amonut>0){
				echo '<tr><th colspan="2">Late Payment Interest</th><td class="right-align"><i class="fa fa-rupee"></i> '.number_format($month->late_fee_amonut,2).'/-</td></tr>';
			}
		?>
		<tr><th colspan="2">Total Payable</th><td class="right-align"><i class="fa fa-rupee"></i> <?php echo number_format($bill_amount,2); ?>/-</td></tr>
		<?php if($month->is_paid!=='2'){ ?>
			<tr><th colspan="2">Total Paid</th><td class="right-align"><i class="fa fa-rupee"></i> <?php echo number_format($month->amount_paid,2); ?>/-</td></tr>

		<?php } ?>
	</tbody></table>
	<?php if($month->is_paid!=='2'){ ?>
<div class="col s12"><img class="m-bill-reciept-icon" src="<?=base_url('assets/images/paid.png')?>"></div>
<?php } ?>
	<?=$notes?>
</div>
</div>
</div>
</div>
</body>
</html>