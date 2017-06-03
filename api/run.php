<?

/////AJAX USE ONLY/////
if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') == 'POST'){

	session_start();
	
	spl_autoload_register(function ($class){
	    include __DIR__.'/../cls/' . $class . '.class.php';
	});

	/////SETUP/////
	$requestPath = $_SERVER['REQUEST_URI'];
	$apifilename = "run.php";    
	$requestArgs = substr($requestPath, strpos($requestPath, $apifilename) +strlen($apifilename));    

	$requestArr = explode("/", $requestArgs);

	$requestType = $requestArr[1];
	$requestTask = $requestArr[2];

	$setup = new Setup();
	$siteRoot = $setup->getSiteRoot();
	$customs = new Customs($setup->getRequiredClearance($requestTask), $siteRoot);
	$pageCleared = $customs->checkPageClearance($customs->validateUser());
	$noticeOnReload = false;

/*	                                                                                                            

88888888888 88        88 888b      88   ,ad8888ba, 888888888888 88   ,ad8888ba,   888b      88  ad88888ba   
88          88        88 8888b     88  d8"'    `"8b     88      88  d8"'    `"8b  8888b     88 d8"     "8b  
88          88        88 88 `8b    88 d8'               88      88 d8'        `8b 88 `8b    88 Y8,          
88aaaaa     88        88 88  `8b   88 88                88      88 88          88 88  `8b   88 `Y8aaaaa,    
88"""""     88        88 88   `8b  88 88                88      88 88          88 88   `8b  88   `"""""8b,  
88          88        88 88    `8b 88 Y8,               88      88 Y8,        ,8P 88    `8b 88         `8b  
88          Y8a.    .a8P 88     `8888  Y8a.    .a8P     88      88  Y8a.    .a8P  88     `8888 Y8a     a8P  
88           `"Y8888Y"'  88      `888   `"Y8888Y"'      88      88   `"Y8888Y"'   88      `888  "Y88888P"   

*/

	/////DEBUG CONSOLE/////
	function console($output, $dutration = 0){
		array_push($_SESSION['noticeQueue'], array("debug", $dutration, "Console", print_r($output, true)));
	}

	function humanTest(){
		if (isset($_POST['g-recaptcha-response'])) {
			$recaptchaURL = 'https://www.google.com/recaptcha/api/siteverify';
			$secretKey = '6LfE8x8TAAAAAMBgH6hue42-BsOrpb9djvLo_W7h';
			$response = file_get_contents($recaptchaURL.'?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response'].'&remoteip='.$_SERVER['REMOTE_ADDR']);
			$responseData =  json_decode($response);
			if (isset($responseData->success) && $responseData->success == true) {
				
				$human = true;
			}else{
				$human = false;
				array_push($_SESSION['noticeQueue'], array("error", "-1", "Human test failed", "Arkward"));
			}
		}else{
			$human = true;
		}
		return $human;
	}

	function sendVerificationEmail($email, $username, $token){
		$mailmsg = '<h1>Hello '.$username.',</h1>';
		$mailmsg .=  '<h2>Your myCocktailor account has been created!</h2>';
		$mailmsg .=  '<p>To activate your account and login, please use the link below.</p>';
		$mailmsg .=	 '<p><a href="'.rootPath.'vld/acc.php/verify/'.$token.'">'.rootPath.'vld/acc.php/verify/'.$token.'</a></p>';
		$mailmsg .=	 '<p>If this account was created in error, please take no further action.</p>';

		$mail = new Mail();
		$mail->sendMasterTemplate($email, 'Welcome to myCocktailor!', $mailmsg);
		unset($mail);

		array_push($_SESSION['noticeQueue'], array("update", "-1", "A vaidation email hasbeen sent to your email address", "please verify your email to login (don't forget to check your spam folder)"));
	}

	function sendReportEmail($cocktailId){
		$mailmsg = '<h1>'.$cocktailId.' has been reported as unsuitable</h1>';
		$mailmsg .=  '<h2>go check it out!</h2>';
		$mailmsg .=	 '<p><a href="'.rootPath.'recipes/'.$cocktailId.'">'.rootPath.'recipes/'.$cocktailId.'</a></p>';

		$mail = new Mail();
		$mail->sendMasterTemplate('ryan@myCocktailor', 'COCKTAIL REPORTED!', $mailmsg);
		unset($mail);

		array_push($_SESSION['noticeQueue'], array("error", "-1", "Cocktail Reported!", "This cocktail has been reported and shall be reviewed."));
	}





	if($pageCleared || $requestType == 'recipe'){

		header('Content-type: application/json');

		$returnArr = array();

		if ($requestType == "refreshList"){
			
			$usrId = isset($_SESSION['customs']['userId'])? $_SESSION['customs']['userId'] : '';
			$db = new Database();
			$db->select("id, name, author, bgColour", "cocktails", "", "id!=0 ORDER BY dateModified DESC", "");

			foreach ($db->cocktails as $key => $value) {
				
				//$slug = str_replace(" ", "-", $value->name);
				$slug = $value->id.'/'.urlencode(str_replace(" ", "-", str_replace("'", "", $value->name)));
				?>

				<a id="<?=$slug?>" href="recipes/<?=$slug?>" class="recipeLink BG-<?=$value->bgColour?>">
						
						<div class="recipeTitle">
							<?=strtoupper($value->name)?>
							<?
								if (!empty($usrId)) {
									if ($db->exists('favCocktails', "userId='".$usrId."' && cocktailId='".$value->id."'")) {
										echo '<div class="favStarTrue"></div>';
									}
								}
							?>
						</div>

						<div class="recipeAuthor">
							<?=strtoupper($value->author)?>
						</div>
				</a>
				<?

			}
		}


/*
                                                                                             
		  ,ad8888ba,  8b           d8 88888888888 88888888ba  88888888ba    ,ad8888ba, 8b        d8  
		 d8"'    `"8b `8b         d8' 88          88      "8b 88      "8b  d8"'    `"8b Y8,    ,8P   
		d8'        `8b `8b       d8'  88          88      ,8P 88      ,8P d8'        `8b `8b  d8'    
		88          88  `8b     d8'   88aaaaa     88aaaaaa8P' 88aaaaaa8P' 88          88   Y88P      
		88          88   `8b   d8'    88"""""     88""""88'   88""""""8b, 88          88   d88b      
		Y8,        ,8P    `8b d8'     88          88    `8b   88      `8b Y8,        ,8P ,8P  Y8,    
		 Y8a.    .a8P      `888'      88          88     `8b  88      a8P  Y8a.    .a8P d8'    `8b   
		  `"Y8888Y"'        `8'       88888888888 88      `8b 88888888P"    `"Y8888Y"' 8P        Y8  
	                                                                                       
		88888888ba  88888888888 ,ad8888ba,   88        88 88888888888 ad88888ba 888888888888 ad88888ba   
		88      "8b 88         d8"'    `"8b  88        88 88         d8"     "8b     88     d8"     "8b  
		88      ,8P 88        d8'        `8b 88        88 88         Y8,             88     Y8,          
		88aaaaaa8P' 88aaaaa   88          88 88        88 88aaaaa    `Y8aaaaa,       88     `Y8aaaaa,    
		88""""88'   88"""""   88          88 88        88 88"""""      `"""""8b,     88       `"""""8b,  
		88    `8b   88        Y8,    "88,,8P 88        88 88                 `8b     88             `8b  
		88     `8b  88         Y8a.    Y88P  Y8a.    .a8P 88         Y8a     a8P     88     Y8a     a8P  
		88      `8b 88888888888 `"Y8888Y"Y8a  `"Y8888Y"'  88888888888 "Y88888P"      88      "Y88888P"   
		                                                                                                 
		                                                                                                 
*/
		if ($requestType == "overbox") {

			if(isset($_POST)){
				$dataInArr = $_POST;
			}
			include __DIR__.'/../pgs/'.$requestTask.'.php';
			echo '<script>';
			$noticeQueue = $_SESSION['noticeQueue'];
			foreach ($noticeQueue as $key => $value) {
				echo 'displayNotice("', $noticeQueue[$key][0], '", ', $noticeQueue[$key][1], ', "', $noticeQueue[$key][2], '", "', $noticeQueue[$key][3], '");';
			}
			$_SESSION['noticeQueue'] = array();
			echo '</script>';
		}


		if ($requestType == "recipe") {
			$recipeSlug = $requestTask;
			include __DIR__.'/../pgs/recipeCard.php';
			echo '<script>';
			$noticeQueue = $_SESSION['noticeQueue'];
			foreach ($noticeQueue as $key => $value) {
				echo 'displayNotice("', $noticeQueue[$key][0], '", ', $noticeQueue[$key][1], ', "', $noticeQueue[$key][2], '", "', $noticeQueue[$key][3], '");';
			}
			$_SESSION['noticeQueue'] = array();
			echo '</script>';
		}


 /*                                                                                                  
		88888888888 88        88 888b      88   ,ad8888ba, 888888888888 88   ,ad8888ba,   888b      88     
		88          88        88 8888b     88  d8"'    `"8b     88      88  d8"'    `"8b  8888b     88     
		88          88        88 88 `8b    88 d8'               88      88 d8'        `8b 88 `8b    88     
		88aaaaa     88        88 88  `8b   88 88                88      88 88          88 88  `8b   88     
		88"""""     88        88 88   `8b  88 88                88      88 88          88 88   `8b  88     
		88          88        88 88    `8b 88 Y8,               88      88 Y8,        ,8P 88    `8b 88     
		88          Y8a.    .a8P 88     `8888  Y8a.    .a8P     88      88  Y8a.    .a8P  88     `8888     
		88           `"Y8888Y"'  88      `888   `"Y8888Y"'      88      88   `"Y8888Y"'   88      `888 

		88888888ba  88888888888 ,ad8888ba,   88        88 88888888888 ad88888ba 888888888888 ad88888ba   
		88      "8b 88         d8"'    `"8b  88        88 88         d8"     "8b     88     d8"     "8b  
		88      ,8P 88        d8'        `8b 88        88 88         Y8,             88     Y8,          
		88aaaaaa8P' 88aaaaa   88          88 88        88 88aaaaa    `Y8aaaaa,       88     `Y8aaaaa,    
		88""""88'   88"""""   88          88 88        88 88"""""      `"""""8b,     88       `"""""8b,  
		88    `8b   88        Y8,    "88,,8P 88        88 88                 `8b     88             `8b  
		88     `8b  88         Y8a.    Y88P  Y8a.    .a8P 88         Y8a     a8P     88     Y8a     a8P  
		88      `8b 88888888888 `"Y8888Y"Y8a  `"Y8888Y"'  88888888888 "Y88888P"      88      "Y88888P"   
*/		                                                                                                 

		if($requestType == "function"){

			if(!empty($_SESSION[$requestTask])){
				$tempArr = $_SESSION[$requestTask];
			}
			/////RESEND VERIFICATION EMAIL/////
			if ($requestTask == "resendVerification"){
				
				$token = sha1(uniqid(mt_rand(), true));
				$customs->update("users", "token=?", array($token, $tempArr['id']), $tempArr['id']);
				
				sendVerificationEmail($tempArr['email'], $tempArr['username'], $token);
				
				//addNoticesToReturn();

			}elseif ($requestTask == "favCocktailToggle"){
				
				//console($_SESSION, -1);

				if($_POST['class'] == 'favStarFalse'){

					$addFavArr = array('userId' => $_SESSION['customs']['userId'], 'cocktailId' => $_SESSION['current']['id']);

					$customs->formPush("insert", "favCocktails", $addFavArr);
					$newState = 'True';
				}else{
					$customs->removeCocktailfav($_SESSION['customs']['userId'], $_SESSION['current']['id']);
					$newState = 'False';
				}

				$returnArr['task'] = 'favCocktailToggle';
				$returnArr['data'] = $newState;

			}elseif($requestTask == "report-cocktail"){

				sendReportEmail($_SESSION['current']['id']);

				//console($_SESSION['current']['id'], -1);

			}


			unset($_SESSION[$requestTask]);

		}


/*                                                        
		88888888888 ,ad8888ba,   88888888ba  88b           d88  
		88         d8"'    `"8b  88      "8b 888b         d888  
		88        d8'        `8b 88      ,8P 88`8b       d8'88  
		88aaaaa   88          88 88aaaaaa8P' 88 `8b     d8' 88  
		88"""""   88          88 88""""88'   88  `8b   d8'  88  
		88        Y8,        ,8P 88    `8b   88   `8b d8'   88  
		88         Y8a.    .a8P  88     `8b  88    `888'    88  
		88          `"Y8888Y"'   88      `8b 88     `8'     88  
                                                                                          
		88888888ba  88888888888 ,ad8888ba,   88        88 88888888888 ad88888ba 888888888888 ad88888ba   
		88      "8b 88         d8"'    `"8b  88        88 88         d8"     "8b     88     d8"     "8b  
		88      ,8P 88        d8'        `8b 88        88 88         Y8,             88     Y8,          
		88aaaaaa8P' 88aaaaa   88          88 88        88 88aaaaa    `Y8aaaaa,       88     `Y8aaaaa,    
		88""""88'   88"""""   88          88 88        88 88"""""      `"""""8b,     88       `"""""8b,  
		88    `8b   88        Y8,    "88,,8P 88        88 88                 `8b     88             `8b  
		88     `8b  88         Y8a.    Y88P  Y8a.    .a8P 88         Y8a     a8P     88     Y8a     a8P  
		88      `8b 88888888888 `"Y8888Y"Y8a  `"Y8888Y"'  88888888888 "Y88888P"      88      "Y88888P"   
*/		                                                                                                 

		if ($requestType == "form") {

			$formValidation = new Validator();

			if($formValidation->validateformPush($_POST) && humanTest()){	

				//die('this far so good');

				/////LOGIN/////
				if ($requestTask == "login") {
					if($customs->login($_POST['email'], $_POST['password'])){
						$noticeOnReload = true;
						$returnArr['notices'] = "Reload";
					}else{
					}
				}

				/////SIGN UP/////
				if ($requestTask == "signUp") {
					
					$db = new Database();

					$addUser = true;

					$db->select("id", "users", "", "username='".$_POST['username']."'", "");
					if (!empty($db->users)) {
						array_push($_SESSION['noticeQueue'], array("error", "-1", "username exists", "please try another"));
						$addUser = false;
					}
					unset($db->users);

					$db->select("id", "users", "", "email='".$_POST['email']."'", "");
					if (!empty($db->users)) {
						array_push($_SESSION['noticeQueue'], array("error", "-1", "email already registered", "please try another"));
						$addUser = false;
					}
					unset($db->users);
					
					if ($addUser) {
						$_POST['password'] = $customs->hashup($_POST['newPassword'], $_POST['username']);
						unset($_POST['newPassword']);
						$_POST['token'] = sha1(uniqid(mt_rand(), true));
						$_POST['userType'] = 'user';
						$_POST['dateCreated'] = date("Y-m-d H:i:s");
						$newUser = $customs->formPush("insert", "users", $_POST);
						$customs->updateUserUnits(array(1,2,4,11,15,22,23), end($newUser)->id);
						sendVerificationEmail($_POST['email'], $_POST['username'], $_POST['token']);
					}
				}


				/////SEND PASSWORD RESET/////
				else if($requestTask == "requestPasswordReset") {

					$db = new Database();

					$sendEmail = true;

					$db->select("id", "users", "", "email='".$_POST['email']."'", "Exist");
					if (empty($db->usersExist)) {
						array_push($_SESSION['noticeQueue'], array("error", "-1", "email not found", "please enter a regestered email address"));
						$sendEmail = false;
						console('HIT');
					}else{
						$thisUserId = end($db->usersExist)->id;
					}
					unset($db->usersExist);

					if ($sendEmail) {

						$db->select("id, username, email", "users", "", "id='".$thisUserId."'", "");
						$token = sha1(uniqid(mt_rand(), true));
						$customs->update("users", "token=?", array($token, $thisUserId), $thisUserId);

						$mailmsg = '<h1>Hello '.$db->users[$thisUserId]->username.',</h1>';
						$mailmsg .=  '<h2>So, you want to reset your password?</h2>';
						$mailmsg .=  '<p>To reset your password, please use the link below.</p>';
						$mailmsg .=	 '<p><a href="'.rootPath.'vld/acc.php/reset-pswd/'.$token.'">'.rootPath.'vld/acc.php/reset-pswd/'.$token.'</a></p>';
						$mailmsg .=	 '<p>If you don\'t want to reset your password, please take no further action.</p>';

						$mail = new Mail();
						$mail->sendMasterTemplate($db->users[$thisUserId]->email, 'Password reset request', $mailmsg);
						unset($mail);

						array_push($_SESSION['noticeQueue'], array("update", "-1", "An email has been sent to your email address", "you can use it to reset you password"));

						unset($db->users);

					}
				}



				/////SHOW EMAIL REMINDER/////
				else if($requestTask == "showEmailReminder") {
					
					$db = new Database();

					$db->select("id", "users", "", "username='".$_POST['username']."'", "");
					if (empty($db->users)) {
						array_push($_SESSION['noticeQueue'], array("error", "-1", "username not found", "please enter a regestered username"));
					}else{
						if($customs->usernamePswd($_POST['username'], $_POST['password'])){
							unset($db->users);
							$db->select("id, email", "users", "", "username='".$_POST['username']."'", "");
							array_push($_SESSION['noticeQueue'], array("error", "-1", "Your email is", end($db->users)->email));
						}else{
							array_push($_SESSION['noticeQueue'], array("error", "-1", "request failed", "Your username and password do not match an account"));
						}
					}

					unset($db->users);

				}

				/////UPDATE USER INFO/////
				else if($requestTask == "updateUsrInfo") {

					

					$returnArr['task'] = 'usrInfoUpadted';
					$returnArr['data'] = $customs->formPush("update", "users", $_POST);
					array_push($_SESSION['noticeQueue'], array("update", "0", "", "info updated"));
					//console($_POST, -1);
				}

				/////UPDATE USER SPECIFIC UNITS/////
				else if($requestTask == "updateFavUnits") {
					
					$thisUser = $_SESSION['customs']['userId'];

					$customs->purgeUserUnits($thisUser);
					$customs->updateUserUnits($_POST, $thisUser);
					array_push($_SESSION['noticeQueue'], array("update", "0", "", "units saved"));
				}


				/////UPDATE USER PASSWORD/////
				else if($requestTask == "updatePswd") {

					if (isset($_SESSION['support']['pswdReset'] )) {
						$thisUser = $_SESSION['support']['pswdReset'];
						$thisUsername = $_SESSION['support']['username'];
					}else{
						$thisUser = $_SESSION['customs']['userId'];
						$thisUsername = $_SESSION['customs']['username'];
					}

					$newPswd = $customs->hashup($_POST['newPasswordConfirm'], $thisUsername);
					$customs->updatePswd($newPswd, $thisUser);
					unset($_POST);
				}

				


				/////ADD || UPDATE RECIPE/////
				else if($requestTask == "addNewRecipe") {
					
					$ingArr = array();
					$recArr = array();

					foreach ($_POST as $key => $value) {
						if(substr($key,0,4) == 'ing-') {
							$ingArr[str_replace('ing-', '', $key)] = $value;
						}else{
							$recArr[$key] = $value;
						}
					}

					$recArr['dateCreated'] = date('Y-m-d H:i:s');
					if (!isset($recArr['id'])) {
						//console('new', -1);
						$recipeIn = $customs->formPush("insert", "cocktails", $recArr);
					}else{
						//console('update', -1);
						$customs->purgeIngredients($recArr['id']);
						$recipeIn = $customs->formPush("update", "cocktails", $recArr);
					}
					
					reset($recipeIn);
					$newId = key($recipeIn);

					$preppedIngArr = array();

					foreach ($ingArr as $nameKey => $arr) {
						foreach ($arr as $valueKey => $value) {
							$preppedIngArr[$valueKey][$nameKey] = $value;
						}
					}

					foreach ($preppedIngArr as $key => $ingAttr) {
						if ($ingAttr['name'] != '') {
							$ingAttr['cocktailId'] = $newId;
							$ingAttr['dateCreated'] = date('Y-m-d H:i:s');
							$customs->formPush("insert", "ingredients", $ingAttr);
						}
						
					}
					$returnArr['data'] = $recipeIn;
					$returnArr['task'] = 'recipeSaved';
					array_push($_SESSION['noticeQueue'], array("update", "0", "", "cocktail saved"));

				}

			}

		}

	}else{
		//addNoticesToReturn();
		header('HTTP/1.1 500 Internal Server');
	}


	/////RETURN RESPONCE/////
	if ($noticeOnReload) {
		$returnArr['notices'] = "Reload";
	}else{
		$returnArr['notices'] = $_SESSION['noticeQueue'];
		$_SESSION['noticeQueue'] = array();	
	}
	if(empty($returnArr['notices'])){
		unset($returnArr['notices']);
	}

	if(!empty($returnArr)){
		echo json_encode($returnArr);
	}


}else{
	echo("I'm sorry. I'm afraid I can't do that.");
}
?>