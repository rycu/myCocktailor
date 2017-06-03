
function killOverBox() {
	$("#overBox").fadeOut('slow', function(){
		$("#overBox").removeClass();
	});
	$("body").css('overflow', 'auto');
	var historyData = {type:"root"};
	history.pushState(historyData, null, '');
}

$("#overBox .closeBtn").click(function(){killOverBox();});

function showOverBox(contents){
	$("#overBox #contents").html(contents);
	$("#overBox").fadeIn();
	$("body").css('overflow', 'hidden');
}

function overBoxCall(boxTask, dataIn, type){
	var apiUri = 'api/run.php/overbox/';

	if(type == 'recipe'){
		apiUri = 'api/run.php/recipe/';
		var historyData = {type:"recipe", task:boxTask};
		history.pushState(historyData, null, 'recipes/'+boxTask);
	}

	$.ajax({ 
    	method: 'POST',
		url: apiUri+boxTask,   
		data: dataIn,                                     
		dataType: 'html',
		success: function(data)        
		{
			showOverBox(data);
		},
		error: function(xhr, status, error) {
			returnedInfo = JSON.parse(xhr.responseText);
			console.log(returnedInfo);
			runNoticeQueue(returnedInfo.notices);
        }
    });
}

//Overbox Listeners

$("#emailForgot, #pswdForgot, #accInfo, #newRecipe, #about, #terms, #privacy, #cookies, #help").click(function(event){
	event.preventDefault();
	overBoxCall(this.id, null);
});

$("#overBox #contents").on("click", "#newRecipe", function(event){
	event.preventDefault();
	alert('HITHIT');
	overBoxCall(this.id, {id:this.value}, 'standard');
});

$("#recipeList").on("click", ".recipeLink", function(event){
	event.preventDefault();
	overBoxCall(this.id, null, 'recipe');
});



//overBoxCall('pswdForgot', null);


