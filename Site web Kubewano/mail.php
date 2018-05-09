<?php  

/* -------------------------------------+
 * code 10 -> empty name                |
 * code 11 -> empty email               |
 * code 12 -> empty subject             |
 * code 13 -> empty message             |
 * code 14 -> empty cgu check           |
 * -------------------------------------+
 * code 20 -> invalid email             |
 * code 21 -> invalid subject           |
 * code 22 -> invalid message (too long)|
 * -------------------------------------+
 * code 30 -> server error              |
 * -------------------------------------+
 * code 40 -> success                   |
 * -------------------------------------+
*/

$request = json_decode(file_get_contents('php://input'));
$variables = (array)$request;

$code = [];

///////////////////////////////////////////////////////////
if (empty($variables['name'])) {
	array_push($code, "10");
}

if (empty($variables['email'])) {
	array_push($code, "11");
}

if ($variables['subject'] == "Sélectionnez") {
	array_push($code, "12");
}

if (empty($variables['message'])) {
	array_push($code, "13");
}

if ($variables['cgu'] != true) {
	array_push($code, "14");
}
///////////////////////////////////////////////////////////
if (!empty($variables['email']) && !filter_var($variables['email'], FILTER_VALIDATE_EMAIL)) {
	array_push($code, "20");
}
if (($variables['subject'] !== "Sélectionnez") && !in_array($variables['subject'], ["Question", "Commande", "Partenariat", "Autre"])) {
	array_push($code, "21");
}
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
if (!empty($code)) {
	$code["errors"] = true;
	echo json_encode($code);
	exit();
}else{
	$name = htmlspecialchars($variables['name']); $email = htmlspecialchars($variables['email']); $subject = htmlspecialchars($variables['subject']); $message = htmlspecialchars($variables['message']);
	
	try{
		mail("romain.ordi@gmzafdebliaFail.com", $subject, "Nouveau message de : $name ($email) \n\n\n $message");
		array_push($code, "40");
		$code["success"] = true;
		echo json_encode($code);
		exit();
		
	} catch (Exception $e) {
		array_push($code, "30");
		$code["errors"] = true;
		echo json_encode($code);
		exit();
	}
}

exit();