<? 

class Config{

	const domainName = 	'myCocktailor';
	const siteName = 	'myCocktailor';
	const clientName = 	'ryan cutter';
	const googleFonts = '';


	protected $pageConfig = array(
				
			"index" => array(
				"fileName" =>"index",
				"name" => "Home",
				"H2" => "Made to Measure",
				"discription" => "",
				"keywords" => "",
				"clearance" => "none",
				"navbars" => array("open")
			),

			"emailForgot" => array(
				"fileName" =>"emailForgot",
				"name" => "Forgot email",
				"H2" => "Forgot email",
				"discription" => "",
				"keywords" => "",
				"clearance" => "none",
				"navbars" => array() 
			),

			"pswdForgot" => array(
				"fileName" =>"pswdForgot",
				"name" => "Forgot email",
				"H2" => "Forgot email",
				"discription" => "",
				"keywords" => "",
				"clearance" => "none",
				"navbars" => array() 
			),

			"accInfo" => array(
				"fileName" =>"accInfo",
				"name" => "Account Settings",
				"H2" => "Account Settings",
				"discription" => "",
				"keywords" => "",
				"clearance" => "user",
				"navbars" => array() 
			),

			"newRecipe" => array(
				"fileName" =>"newRecipe",
				"name" => "",
				"H2" => "",
				"discription" => "",
				"keywords" => "",
				"clearance" => "user",
				"navbars" => array() 
			),

			"pswdReset" => array(
				"fileName" =>"pswdReset",
				"name" => "",
				"H2" => "",
				"discription" => "",
				"keywords" => "",
				"clearance" => "none",
				"navbars" => array() 
			),

			"terms" => array(
				"fileName" =>"terms",
				"name" => "terms",
				"H2" => "Terms of use",
				"discription" => "",
				"keywords" => "",
				"clearance" => "none",
				"navbars" => array('foot') 
			),

			"privacy" => array(
				"fileName" =>"privacy",
				"name" => "privacy",
				"H2" => "Privacy policy",
				"discription" => "",
				"keywords" => "",
				"clearance" => "none",
				"navbars" => array('foot') 
			),

			"cookies" => array(
				"fileName" =>"cookies",
				"name" => "cookies",
				"H2" => "Cookie Policy",
				"discription" => "",
				"keywords" => "",
				"clearance" => "none",
				"navbars" => array('foot') 
			),

			"help" => array(
				"fileName" =>"help",
				"name" => "help",
				"H2" => "help",
				"discription" => "",
				"keywords" => "",
				"clearance" => "none",
				"navbars" => array('foot') 
			),

			"disclaimer" => array(
				"fileName" =>"disclaimer",
				"name" => "disclaimer",
				"H2" => "disclaimer",
				"discription" => "",
				"keywords" => "",
				"clearance" => "none",
				"navbars" => array() 
			),



			"run" => array(
				"fileName" =>"run",
				"clearance" => "none",
				"navbars" => array() 
			),

			"login" => array(
				"clearance" => "none",
				"navbars" => array() 
			),

			"signUp" => array(
				"clearance" => "none",
				"navbars" => array() 
			),

			"updateUsrInfo" => array(
				"fileName" =>"updateUsrInfo",
				"clearance" => "user",
				"navbars" => array() 
			),

			"updateFavUnits" => array(
				"fileName" =>"updateFavUnits",
				"clearance" => "user",
				"navbars" => array() 
			),

			"updatePswd" => array(
				"fileName" =>"updatePswd",
				"clearance" => "none",
				"navbars" => array() 
			),


			"resendVerification" => array(
				"fileName" =>"resendVerification",
				"clearance" => "none",
				"navbars" => array() 
			),

			"requestPasswordReset" => array(
				"fileName" =>"requestPasswordReset",
				"clearance" => "none",
				"navbars" => array()
			),

			"showEmailReminder" => array(
				"fileName" =>"showEmailReminder",
				"clearance" => "none",
				"navbars" => array()
			),

			"addNewRecipe" => array(
				"fileName" =>"addNewRecipe",
				"clearance" => "user",
				"navbars" => array()
			),

			"favCocktailToggle" => array(
				"fileName" =>"favCocktailToggle",
				"clearance" => "user",
				"navbars" => array() 
			)

		);
	
}

?>