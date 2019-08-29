<div class="row mt10 height-600">
<div class="col s12">


<div class="card animated bounceInDown">
	<div class="card-content">
	<?=form_open('societysettingsform/forwarding_to_payment_gateway','name="customerData" class="subscription_payment_form"')?>
	<?php
		if(strtotime($data->subscribed_until) < strtotime(date('Y-m-d'))){
			echo '<p class="red-text bold-700 font-16 center">Your subscription is not active.</p>';
		} else {
			echo '<p class="center bold-700">Your subscription is active until '.date('dS F Y',strtotime($data->subscribed_until)).'</p>';
		}
		echo '<p class="center">Your monthly subscription charge is Rs. '.$data->monthly_charges.'/-.</p>';
	?>
	<div class="row mb0 mt15">
		<div class="col offset-s1 s5">
			<select class="browser-default month_to_pay" name="merchant_param1" data-charges="<?=$data->monthly_charges?>">
				<option value="1">1 month</option>
				<option value="2">2 months</option>
				<option value="3">3 months</option>
				<option value="4">4 months</option>
				<option value="5">5 months</option>
				<option value="6">6 months</option>
				<option value="7">7 months</option>
				<option value="8">8 months</option>
				<option value="9">9 months</option>
				<option value="10">10 months</option>
				<option value="11">11 months</option>
				<option value="12" selected>12 months</option>
			</select>
		</div>
		<div class="col s6">
			<button type="submit" class="btn pay_now_admin_btn">Pay Now</button>
		</div>
		<div class="col s12 mt15">
			<p class="center">Pay Rs. <span class="total_money"><?=$data->monthly_charges*12?></span> /- for next <span class="total_months">12</span> months</p>
		</div>
	</div>
		<input type="hidden" name="amount" class="payment_amount" value="<?=$data->monthly_charges*12?>">
		<input type="hidden" name="merchant_id" value="107172">
		<input type="hidden" name="billing_country" value="India">
		<input type="hidden" name="currency" value="INR">
		<input type="hidden" name="language" value="EN">
		<input type="hidden" name="billing_zip" value="<?=$data->society_pincode?>">
		<input type="hidden" name="billing_name" value="<?=h($data->society_name)?>">
		<input type="hidden" name="order_id" value="<?=substr(md5(time()),0,30)?>">
		<input type="hidden" name="billing_address" value="<?=h($data->society_address)?>">
		<input type="hidden" name="redirect_url" value="http://www.societywizard.com/societysettingsform/payment_success">
		<input type="hidden" name="cancel_url" value="http://www.societywizard.com/societysettingsform/payment_error">
		<?=form_close()?>
	</div>
</div>

<img src="<?=base_url('assets/images/ccavenue-logo.png')?>" class="center-block z-depth-1" width="270px">

</div>
</div>