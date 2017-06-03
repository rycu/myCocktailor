<?
class Setup extends Config{
	
	function __construct(){
		
		date_default_timezone_set('Europe/London');
		
		$pathArray = explode(self::domainName, $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] );
		$dirArray = explode('/', end($pathArray));
		$protocal = 'https://';
		
		$serverPathArray = explode(self::domainName, __DIR__);
		
		define('rootPath', $protocal.$pathArray[0].self::domainName.'/');
		define('svrRootPath', $serverPathArray[0].self::domainName.'/');
		define('fileName', str_replace('.php','', end($dirArray)));	
	}
	
	public function getPageTitle(){
		return $this->pageConfig[fileName]['H2'];
	}
	
	public function getSiteRoot(){
		if(strpos($_SERVER['HTTP_HOST'], self::domainName) !== FALSE){
			$pathOut = '';
		}else{
			$URIparts = Explode('/', $_SERVER['PHP_SELF']);
			$i = 0;
			$rootpath = '';
			while (strpos($URIparts[$i], self::domainName) === FALSE && count($URIparts) > $i) {
				$rootpath .= ($URIparts[$i] != '' ? $URIparts[$i].'/' : '');
				$i++;
			}
			$pathOut =  '/'.$rootpath.$URIparts[$i].'/';

		}
		
		//echo $pathOut;
		
		return $pathOut;
	}	
	
	
	public function getPageMeta(){
		
		$thisPageConfig =  $this->pageConfig[fileName];
		
		$metaCode = '<meta charset="UTF-8">';
		$metaCode .= '<title>'.self::siteName.' - '.$thisPageConfig['H2'].'</title>';
		$metaCode .= '<meta name="keywords" content="cocktails, unit conversion, cocktail recipes"/>';
		$metaCode .= '<meta name="description" content="myCocktailor is a public cocktail database that supports conversion between international units of measurement."/>';
		$metaCode .= '<meta name="viewport" content="width=device-width, initial-scale=1">';

		return $metaCode;
	}
	
	public function getGoogleFontsCss(){
		
		$cssLink = (self::googleFonts != '' ? '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family='.self::googleFonts.'">' : '');
		return $cssLink;
	}
	
	public function getRequiredClearance($task) {
		
		if (isset($this->pageConfig[$task]['clearance'])) {
			$clearanceLevel = $this->pageConfig[$task]['clearance'];
		}else{
			$clearanceLevel = 'none';
		}

		return $clearanceLevel; 
	}
	
	public function getPageAttribute($attribute) {
		
		return $this->pageConfig[fileName][$attribute];
	}
	
	public function getFooterNav(){
		$footNav = '';
		foreach ($this->pageConfig as $key => $value) {	
			if(in_array('foot', $value["navbars"])){
				$footNav .= '<li><a id="'.$value["fileName"].'" href="'.rootPath.'support/'.$value["fileName"].'">'.$value["name"].'</a></li>';
			
			}
		}
		
		$footNav = '<div id="footNav" class="nav"><div class="navBar"><ul>'.$footNav.'</ul></div><div id="copy">'.self::clientName.' &copy;'.date("Y").'</div></div>';
		
		return $footNav;
	}
    
}
?>