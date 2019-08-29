<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<link rel="stylesheet" href="<?php echo base_url('assets/css/materialize.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/animate.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
<?=style_css()?>
<style type="text/css">
table.bordered{
	border-right:1px solid #d0d0d0;
	border-left:1px solid #d0d0d0;
	border-top:1px solid #d0d0d0;
}
table.notb{
	border:0;
}
</style>
</head>
<body class="white">



<div class="row">
<div class="col s12 center">

<div class="mt15 mb15">
<a href="<?=base_url('incomeexpense/edit_ie/'.$data->id)?>" class="btn btn-small no-print accent-3 orange" o><i class="fa fa-edit"></i> Edit</a>
<button class="btn btn-small no-print accent-3 red" onclick="window.close()"><i class="fa fa-close"></i> Cancel</button>
<button class="btn btn-small no-print green" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<h5 class="mb5"><?=$society->society_name?></h5>
<p class="m0"><?=$society->society_address?></p><hr class="mt10 mb10">
<h6 class="mt0 bold-700">Payment Voucher</h6>
</div>
<div class="col s12">

<table>
<tr>
<td><b>Voucher No :</b> <?=$data->id?></td>
<td class="center"><b>Date :</b> <?=$data->date_of_payment?></td>
<td class="right-align"><b>Cheque No :</b> <?=($data->cheque_no) ? h($data->cheque_no) : 'N/A'?></td>
</tr>
</table>

<table class="bordered">
<tr class="green"><th>Description</th><th class="right-align">Amount</th></tr>
<tr>
<td><?=h($data->giver_taker)?></td>
<td class="right-align"><i class="fa fa-rupee"></i> <?=$data->amount?>/-</td>
</tr><tr class="bold-700"><td colspan="2" class="right-align">Total Amount -  <i class="fa fa-rupee"></i> <?=$data->amount?>/-</td></tr></table>

<table class="bordered notb mt15">
<tr><th>Prepared By</th><th>Authorised By</th></tr>
<tr><td><?=h($data->fn_init.' '.$data->ln_init)?></td><td><?=h($data->fn_auth.' '.$data->ln_auth)?></td></tr>
</table>
</div>
</div>
</body>
</html>