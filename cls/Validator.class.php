<?
class Validator{

	private $commonTypes = array (
	
		'username' => array('message' => 	'required field',
							'regex' => 		'\\S',
							'process' =>	NULL
		),
		
		'password' => array('message' => 	'required field',
							'regex' => 		'\\S',
							'process' =>	NULL
		),
		'newPassword' => array('message' => 'passwords must be more than 8 characters and include a mixture of cases and numbers',
							'regex' => 		'(?!.* )(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,}',
							'process' =>	NULL
		),
		'email' => array(	'message' => 	'not a valid address',
							'regex' => 		'[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,6})',
							'process' => 	NULL
		),

		'name' => array(	'message' => 	'you forgot to name your cocktail!',
							'regex' => 		'\\S',
							'process' => 	NULL
		),

		'method' => array(	'message' => 	'explain how you make you cocktail in the method box',
							'regex' => 		'\\S',
							'process' => 	NULL
		),

		'author' => array(	'message' => 	'let everbody know who came up with this recipe',
							'regex' => 		'\\S',
							'process' => 	NULL
		)



	);

	function __construct(){

	}

	private function validateValue($name, $value) {

		$cleared = true;

		if(array_key_exists($name, $this->commonTypes)){

			if (!preg_match ('/'.$this->commonTypes[$name]['regex'].'/', $value)){
 				$cleared = false;
 				array_push($_SESSION['noticeQueue'], array("error", "-1", '###'.$name, $this->commonTypes[$name]['message']));
			}
		}

		return $cleared;
	}	
	
	
	public function validateformPush(){
		
		$cleared = true;
		foreach ($_POST as $key => $value) {
			if(isset($key)){
				
				//CHECK THAT AT LEAST 1 INGREDIENT IS PRESENT//
				if($key == 'ing-name' && ($value == null || $value == '' || implode("", $value) == '')){
						array_push($_SESSION['noticeQueue'], array("error", "", "Wait!", "You haven't added any ingredients."));
						$cleared = false;

				//STANDARD TEST//
				}else if (!$this->validateValue($key, $value)) {
					$cleared = false;
				}

				if($key == 'newPasswordConfirm' && $value != $_POST['newPassword']){
					array_push($_SESSION['noticeQueue'], array("error", "", "", "The new passwords entered do not match"));
				}	


			}
		}
		return $cleared;
	}

}

?>