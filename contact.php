<?php
function clean_name($tag) {  
	$code_ent_match = array(' ','-','--','---','&quot;','!','@','#','$','%','^','&','*','(',')','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','/','*','+','~','`','='); 
	$code_ent_replace = array(' ','','','','','','','','','','','','','','','','','','','','','','','','','','',''); 
	$tag = str_replace($code_ent_match, $code_ent_replace, $tag); 
	return $tag; 
} 

function check_email_address($email) {

	if (preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $email))
	{ 
    	return true;
	}

	return false;
}

// Output JSON
$LanguageMsgs = array(
	'english'	=> array(
		'name'		=> 'You must provide a name.',
		'photo'		=> 'Please enter a phone number.',
		'email'		=> 'Please enter an email.',
		'email_invalid' => 'That email address is not valid.',
		'success'	=> 'Your message was successfully sent!',
		'error'		=> 'Oops we could not send your message.'
	),
	'icelandic' => array(
		'name'		=> 'Þú verður að gefa upp nafn.',
		'photo'		=> 'Vinsamlegast sláðu inn símanúmer.',
		'email'		=> 'Þú verður að gefa upp netfang.',
		'email_invalid' => 'Þetta netfang er ekki gilt.',
		'success'	=> 'Skeytið var send!',
		'error'		=> 'Við gátum ekki sent skilaboð.'
	)
);

$Language = stripslashes($_POST['language']);
$ResultMessage = '';

// Name
if (isset($_POST['name'])) {
	$Name 			= stripslashes(clean_name($_POST['name']));
	$NameState 		= true;
}
else {
	$NameState 		= false;
	$ResultMessage .= $LanguageMsgs[$Language]['name'];
}	

// Phone
if (isset($_POST['phone'])) {
	$Phone			= stripslashes($_POST['phone']);
	$PhoneState 	= true;
}
else {
	$PhoneState 	= false;
	$ResultMessage .= $LanguageMsgs[$Language]['phone'];
}

// Email
if (check_email_address($_POST['email'])) {
	$Email 			= stripslashes($_POST['email']);
	$EmailState 	= true;
}
else {
	$EmailState 	=  false;
	$ResultMessage .= $LanguageMsgs[$Language]['email_invalid'];
}



//CHECKS TO SEE IF ALL THREE REQUIRED FIELDS ARE FILLED IN	
if (($NameState == true) && ($EmailState == true) && ($PhoneState == true)) {

	//SEND MEMBER EMAIL
	$EmailTo   		= "Vísar <visar@visar.is>";
	$EmailFrom     	= $Name." <".$Email.">";
	$Subject     	= "Contact message from Visar.is";
	$Headers 	    = 'From: '.$EmailFrom."\r\n";
	$Message		= $Name.'<br>'.$Email.'<br>'.$Phone.'<br>'.$_POST['comment'];
	$SentMessage 	= mail($EmailTo, $Subject, $Message, $Headers);

	echo json_encode(array('status' => 'success', 'message' => $LanguageMsgs[$Language]['success']));
}
else
{
	$ErrorMessage = $LanguageMsgs[$Language]['error'].' '.$ResultMessage;

	echo json_encode(array('status' => 'error', 'message' => $ErrorMessage));
}
