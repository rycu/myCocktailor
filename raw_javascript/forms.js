
function runNoticeQueue(jsonQueue){

	if (jsonQueue === "Reload"){
		document.location.reload(true);
	}else{
		$.each(jsonQueue, function (item, value) {
			displayNotice(value[0], value[1], value[2], value[3]);
		});
	}
}


function formDataObj(form){
    var obj = {};
    var formData = $('#'+form).serializeArray();
    $.each(formData, function() {
        if (obj[this.name] !== undefined) {
            if (!obj[this.name].push) {
                obj[this.name] = [obj[this.name]];
            }
            obj[this.name].push(this.value || '');
        } else {
            obj[this.name] = this.value || '';
        }
    });
    return obj;
};

function formSubmitCall(task){

	//console.log(formDataObj(task));

    $.ajax({
    	method: 'POST',
		url: 'api/run.php/form/'+task,         
		data: formDataObj(task),                                
		dataType: 'html',                  
		success: function(returnArr)        
		{
			returnedInfo = JSON.parse(returnArr);
			console.log(returnedInfo.data);
			runNoticeQueue(returnedInfo.notices);
			if(returnedInfo.data != null){
				afterEvents(returnedInfo.task, returnedInfo.data);
			}
		},
		error: function(xhr, status, error) {
			returnedInfo = JSON.parse(xhr.responseText);
			//console.log(returnedInfo);
			runNoticeQueue(returnedInfo.notices);
        }
    });
}


$("#overBox #contents").on("submit", "#updateUsrInfo, #updateFavUnits, #updatePswd, #requestPasswordReset, #showEmailReminder, #addNewRecipe", function(event){
    event.preventDefault();
	if(validateForm(this)){
		formSubmitCall($(this).attr('id'));
	}
});



function gateway(btn, form){

	gatewayBoxIn = true;

	event.preventDefault();

	var outcome = false;

	if(btn === "login"){
		if ($('#loginPane').css('display') != 'none' ){
			outcome = validateForm(form);
		}else{
			$( "#loginPane" ).slideDown("slow" );
			$( "#signUpPane" ).slideUp( "slow" );

			$( "#loginBtn" ).animate({
				width: "76%"},
				"slow");
			$( "#signUpBtn" ).animate({
				width: "20%"},
				"slow");
			$('#signUpBtn span').fadeOut( "300" );
			$('#loginBtn span').fadeIn( "300" );


		}
	}else if(btn === "signUp"){
		if ($('#signUpPane').css('display') != 'none' ){
			outcome = validateForm(form);
		}else{
			$( "#signUpPane" ).slideDown( "slow" );
			$( "#loginPane" ).slideUp( "slow" );

			$( "#signUpBtn" ).animate({
				width: "76%"},
				"slow");
			$( "#loginBtn" ).animate({
				width: "20%"},
				"slow");
			$('#loginBtn span').fadeOut( "300" );
			$('#signUpBtn span').fadeIn( "300" );

		}
	}else{
		alert('error: unknown gateway!');
	}

	if (outcome) {
		formSubmitCall(btn);
	}

	return outcome;
}