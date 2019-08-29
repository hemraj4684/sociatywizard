<?php if($data->is_paid != 1): ?>
<?php if(strtolower($this->router->fetch_class())==='paymentgateway'): ?>
<?=form_open('paymentgateway/forwarding_to_payment_gateway','name="customerData"')?>
<?php else: ?>
<?=form_open('me/forwarding_to_payment_gateway','name="customerData"')?>
<?php endif; ?>
<input type="hidden" name="merchant_id" value="107172">
<input type="hidden" name="order_id" value="<?=$data->id?>">
<input type="hidden" name="amount" value="<?=$data->total_amount?>">
<input type="hidden" name="currency" value="INR">
<input type="hidden" name="redirect_url" value="http://www.societywizard.com/me/payment_completed">
<input type="hidden" name="cancel_url" value="http://www.societywizard.com/me/payment_completed">
<input type="hidden" name="language" value="EN">
<?php if(strtolower($this->router->fetch_class())==='paymentgateway'): ?>
<input type="hidden" name="billing_name" value="<?=$u_info->fn.' '.$u_info->ln?>">
<?php else: ?>
<input type="hidden" name="billing_name" value="<?=$this->fn.' '.$this->ln?>">
<?php endif; ?>
<input type="hidden" name="billing_country" value="India">
<input type="hidden" name="billing_tel" value="<?=$u_info->mobile_no?>">
<input type="hidden" name="billing_email" value="<?=$u_info->email?>">
<input type="hidden" name="billing_zip" value="<?=$u_info->society_pincode?>">
<input type="hidden" name="billing_address" value="<?=$u_info->society_name.','.$u_info->society_address?>">
<input type="hidden" name="merchant_param1" value="<?=$u_info->id?>">
<?=form_close()?>
<script language='javascript'>document.customerData.submit();</script>
<?php endif; ?>