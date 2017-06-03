<?
class Customs extends Database{


	private $regExMail = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,10})$/';
	private $regExPswd = '/^(?!.* )(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,}$/';

	function __construct($thisClearanceLevel, $siteRoot) {
		parent::__construct();
		$this->clearanceLevel = $thisClearanceLevel;
		$this->siteRoot = $siteRoot;
	}

	
	private function purgeSignIn() {
		
		if(isset($_SESSION['customs']['userId'])){
			$this->update("users", "lastLogout=?", array( date("Y-m-d H:i:s"), $_SESSION['customs']['userId']), $_SESSION['customs']['userId']);
		}
		unset($_SESSION['customs']);
		unset($_SESSION['state']);
	}


  public function hashup($dataIn, $usr) {

    $hash = password_hash($dataIn, PASSWORD_BCRYPT);

    //$salt = '$2a$07$'.substr(sha1($usr), 5, 22).'$';
    //$hash = substr(crypt($dataIn, '$6$rounds=150000$'.$salt.'$'), 17);

    return $hash;
  }

	
	public function checkPageClearance($loggedIn) {

		$userRank = 'null';

		if($this->clearanceLevel == 'none'){
			$clearedForPage = true;

		}elseif($loggedIn){
			
			$userRank = $_SESSION['customs']['userType'];
			
			if($userRank == 'admin'){
				$clearedForPage = true;
			}else if($userRank == 'user' && $this->clearanceLevel != 'admin'){
				$clearedForPage = true;
			}else{
				$clearedForPage = false;
				array_push($_SESSION['noticeQueue'], array("error", "0", "clearance failed",  "You do not have the required securtity clearance to use this page"));
			}
			
		}else{
			$clearedForPage = false;
			array_push($_SESSION['noticeQueue'], array("error", "0", "You are not signed in",  "Please sign in to use this page"));
		}

		//debug Clearance
		// $outcome = $clearedForPage ? 'true' : 'false';
		// console('PageClearance... User:'.$userRank.' + Page:'.$this->clearanceLevel.' = '.$outcome, -1);

		return $clearedForPage;
		
	}

    public function validateUser(){
    	
    	$allowedIdleTime = 43200;
    	
    	if(isset($_SESSION['customs']['userId']) && isset($_SESSION['customs']['token']) && isset($_SESSION['customs']['timeStamp']) && $_SESSION['customs']['timeStamp']+$allowedIdleTime > time()){
    		
    		$this->select("id, token", "users", "", "id=".$_SESSION['customs']['userId']."", "");
    		
    		if ($_SESSION['customs']['token'] == end($this->users)->token) {
    			$_SESSION['customs']['timeStamp'] = time();
    			$userValid = TRUE;
    			$state = 'PASS'; 
    		}else{
    			$userValid = FALSE;
    			$state = 'BAD TOKEN';
    		}
    	}else {
    		$userValid = FALSE;
    		$state = 'BAD SESSION';
    	}
    	
    	
    	if(!$userValid){
    		$this->purgeSignIn();
    	}
    	
    	//echo $state;
    	
    	// //PASSWORD GEN
    	// echo $this->hashup("Mc12asifasif!!", "Ryan");
    	// die();
    	
    	return $userValid;
    	
    	
    	
    }
    
    
    public function login($email, $pswd){

	    if (preg_match ($this->regExMail, stripslashes(trim($email))) 
	    	&& preg_match ($this->regExPswd, stripslashes(trim($pswd)))) {
	    	$currentUsr = $email;
	    }else{
		    $currentUsr = FALSE;
		    $userValid = FALSE;
		    $state = 'REGEX fail';
      }
		
  		if($currentUsr){
  			$this->select("id", "users", "", "email='$currentUsr'", "Usr");
  			
  			if($this->usersUsr){
  				
  				$this->select("id, password, username", "users", "", "email='$currentUsr'", "HASH");

  				$userhash = end($this->usersHASH)->password;
          $username = end($this->usersHASH)->username;
  				
  				if (password_verify($pswd, $userhash)) {
          //if($userhash == $this->hashup($pswd, $username)){

            $this->select("id, username, userType, password, email, verified", "users", "", "email='$currentUsr'", "");

            $userDeails = end($this->users);
            $token = sha1(uniqid(mt_rand(), true));

            if($userDeails->verified == 'TRUE'){

    					$state = 'LOGGED IN';
    					$userValid = TRUE;
    					$this->update("users", "token=?, lastLogin=?", array($token, date("Y-m-d H:i:s"), $userDeails->id), $userDeails->id);
    					$_SESSION['customs']['token'] = $token;
    	   			$_SESSION['customs']['userType'] = $userDeails->userType;
    					$_SESSION['customs']['userId'] = $userDeails->id;
    					$_SESSION['customs']['username'] = $userDeails->username;
    					$_SESSION['customs']['timeStamp'] = time();
    					session_regenerate_id();

            }else{
              $state = 'EMAIL NOT VERIFIED';
              $userValid = FALSE;
            }

  				}else{
  					$state = 'PASSWORD INCORRECT';
  					$userValid = FALSE;
  				}
  			}else{
  				$state = 'USER NOT FOUND';
  				$userValid = FALSE;
  			}
  			unset($this->usersUsr);			
  		}
  		
  		if(!$userValid){
  			if($state == 'EMAIL NOT VERIFIED'){
          array_push($_SESSION['noticeQueue'], array("error", "-1", "Your email has not been verified", "<button id='resendVerification'>resend verification email</button>"));
           $_SESSION['resendVerification']['id'] = $userDeails->id;
          $_SESSION['resendVerification']['email'] = $userDeails->email;
          $_SESSION['resendVerification']['username'] = $userDeails->username;
  			}else{
          array_push($_SESSION['noticeQueue'], array("error", "0", "Sign in failed", "please enter a correct email & password."));
        }
        $this->purgeSignIn();
  		}else{
  			array_push($_SESSION['noticeQueue'], array("update", "0", $_SESSION['customs']['username'], "Signed In"));
  		}
  		
  		return $userValid;

  		//echo $state;
			

    }
    
   	public function logout(){
   		
   		$this->purgeSignIn();
   		array_push($_SESSION['noticeQueue'], array("update", "0", "you have been",  "Signed out"));
   		header("Location: ".$this->siteRoot);
   	}
   	
   	
    public function usernamePswd($username, $pswd){

      $this->select("id, password", "users", "", "username='$username'", "HASH");
      $userhash = end($this->usersHASH)->password;
      $result = (password_verify($pswd, $userhash) ? true : false);
      return $result;
    
    }
   	
   	
   	public function updatePswd($pswd, $userId) {
   		$this->update("users", "password=?", array($pswd, $userId), $userId);
   		array_push($_SESSION['noticeQueue'], array("update", "-1", "", "Password Changed Successfully"));
   	}


    public function purgeIngredients($cocktailId){
      $this->delete('ingredients', 'cocktailId='.$cocktailId);
    }

    public function removeCocktailfav($userId, $cocktailId){
      $this->delete('favCocktails', 'userId='.$userId.' && cocktailId='.$cocktailId);
    }

    public function purgeUserUnits($userId){
      $this->delete('favUnits', 'userId='.$userId);
    }

    public function updateUserUnits($unitArr, $userId){

      $i = 0;
      $len = count($unitArr);

      foreach ($unitArr as $key => $value) {
        $names = '(userId, unitId) VALUES (?, ?)';
        $values = $arrayName = array($userId, $value);
          if ($len == 1) {
              $multi = NULL;
          }else if ($i == 0) {
              $multi = 'START';
          }else if ($i == $len - 1) {
              $multi = 'END';
          }else{
            $multi = 'MID';
          }
        $this->insert('favUnits', $names, $values, $multi);
        $i++;
      }

    }

   	

       
}

//$cs = new Customs();
//$cs->validateUser();

?>