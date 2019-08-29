<html>
<head>
<title> Non-Seamless-kit</title>
</head>
<body>
<center>
<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
	$merchant_data='';
	$working_key='927C0235026505239CD6FB48E1AD37C1';//Shared by CCAVENUES
	$access_code='AVXQ66DH60BN53QXNB';//Shared by CCAVENUES
	
	foreach ($_POST as $key => $value){
		$merchant_data.=$key.'='.$value.'&';
	}

	$encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.

?>
<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
<?php
echo "<input type=hidden name=encRequest value=$encrypted_data>";
echo "<input type=hidden name=access_code value=$access_code>";
?>
</form>
</center>
<script language='javascript'>document.redirect.submit();</script>
</body>
</html>

