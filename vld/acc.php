<?

session_start();

spl_autoload_register(function ($class){
    include __DIR__.'/../cls/' . $class . '.class.php';
});

$requestPath = $_SERVER['REQUEST_URI'];
$apifilename = "acc.php";    
$requestArgs = substr($requestPath, strpos($requestPath, $apifilename) + strlen($apifilename) + 1); 

$requestArr = explode("/", $requestArgs);

$requestTask = $requestArr[0];
$requestAuth = $requestArr[1];


if($requestTask == 'verify'){

	$db = new Database();
	$db->select("id, username", "users", "", "token='".$requestAuth."'", "");

	if (!empty($db->users)) {
		$thisAccId = end($db->users)->id;
		unset($db->users);
		$db->update("users", "verified=?,token=?", array("TRUE", "", $thisAccId), $thisAccId);
		array_push($_SESSION['noticeQueue'], array("update", "-1", "", "Your verified! Please login"));
		header("Location: ../../../");
	}else{
		unset($db->users);
		array_push($_SESSION['noticeQueue'], array("error", "-1", "cool story bro", "your link has expired!"));
		header("Location: ../../../");
	}

}

elseif($requestTask == 'reset-pswd'){

	$db = new Database();
	$db->select("id, username", "users", "", "token='".$requestAuth."'", "");

	if (!empty($db->users)) {
		$_SESSION['support']['pswdReset'] = end($db->users)->id;
		$_SESSION['support']['username'] = end($db->users)->username;
		header("Location: ../../../support/pswdReset/");
	}else{
		unset($db->users);
		array_push($_SESSION['noticeQueue'], array("error", "-1", "cool story bro", "your link has expired!"));
		header("Location: ../../../");
	}

}

?>
