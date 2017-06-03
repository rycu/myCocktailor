<?

/////SESSION START AND CLASS REGISTER////

session_start();

spl_autoload_register(function ($class){
    include __DIR__.'/../cls/' . $class . '.class.php';
});


function console($output, $dutration = 0){
	array_push($_SESSION['noticeQueue'], array("debug", $dutration, "Console", print_r($output, true)));
}


// array_push($_SESSION['noticeQueue'], array("debug", -1, "Debug", "message"));
// array_push($_SESSION['noticeQueue'], array("error", -1, "Error", "message"));
// array_push($_SESSION['noticeQueue'], array("update", -1, "update", "message"));

$setup = new Setup();
$formValidation = new Validator();


/////SET PAGE REFERENCE AS FILE NAME/////
$currentFilePath = $_SERVER["SCRIPT_NAME"];
$URIparts = Explode('/', $currentFilePath);	
$currentFile = str_replace(array(".php", ".html", ".htm"), "",($URIparts[count($URIparts) - 1]));
$pageRef = $currentFile;
/////////////////////////////////////////

//////CUSTOMS//////
$siteRoot = $setup->getSiteRoot();
$customs = new Customs($setup->getRequiredClearance($pageRef), $siteRoot);

if(isset($_POST['task'])){

	$task = $_POST['task'];

	if($task == 'logout') {
		$customs->logout();
	
	}
	
}

$loggedIn = $customs->validateUser();

$deployCss = '<link rel = "stylesheet" href="'.rootPath .'style.css">';
$deployJs = '<script src="'.rootPath .'scripts.js"></script>';

////INCLUDE HEAD TAG & CONTENTS////

include_once(__DIR__."/head.php");


////BUILD OPENING PAGE TAGS////

echo('<body id="'.fileName.'Body">');
include_once(__DIR__."/header.php");
//echo('<div id="navBox"><nav>'.$setup->getMainNav().'</nav></div>');
echo('<main>');

?>