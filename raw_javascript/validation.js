
var errorListArr = [];

var regexListObj = {

	"username" : ["\\S", "required field"],
	"password" : ["\\S", "required field"],
	"newPasswordConfirm" : ["\\S", "The new passwords entered do not match"],
	"newPassword" : ["(?!.* )(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,}", "passwords must be more than 8 characters and include a mixture of cases and numbers"],
	"email" : ["[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,6})", "not a valid address"],
	"tel" : ["[0-9 (\\)\\+]{10,30}", "please enter a valid telephone number"],

	"name" : ["\\S", "you forgot to name your cocktail!"],
	"method" : ["\\S", "explain how you make you cocktail in the method box"],
	"author" : ["\\S", "let everbody know who came up with this recipe"]


};

function validateValue(name, value) {

	var cleared = true; 

	if(regexListObj.hasOwnProperty(name)){

		var thisRegex = new RegExp(regexListObj[name][0]);
		
		if(!thisRegex.test(value)){ 
			errorListArr.push(name);
			cleared = false;
		}
		//alert('name: '+name+'\nvalue: '+value+'\nRegex: '+thisRegex+'\noutcome: '+cleared);
	}
	return cleared;
}


function validateForm(formIn){ 

	var cleared = true;	
	for (var i=0; i<formIn.length; i++) {
		if(formIn[i].name !== ''){
			
			if(!validateValue(formIn[i].name, formIn[i].value)){
				cleared = false;
			}

			if (formIn[i].name == 'newPasswordConfirm' && formIn[i].value !== $("#updatePswd .inputField[name='newPassword']").val()) {
				errorListArr.push(formIn[i].name);
				cleared = false;	
			}
		} 
	}
	if (!cleared) {
		displayErrors($(formIn).attr('id'));
	}

	return cleared;

}



function displayErrors(formId){

	$(".inputField").css("background-color", "");

	console.log("errorList: "+errorListArr);

	$.each(errorListArr, function(index, val) {
		 $("#"+formId+" .inputField[name="+val+"]").css("background-color", "orange");
		 displayNotice("error", -1,  val, regexListObj[val][1]);
	

	});

	errorListArr = [];

}


