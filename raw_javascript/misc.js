
////BROWSER NAV////
window.addEventListener('popstate', function(e) {
  var historyArr = e.state;
  //console.log(historyArr);
  if (historyArr['type'] === 'recipe') {
  	overBoxCall(historyArr['task'], null, 'recipe');
  }
  if (historyArr['type'] === null || historyArr['type'] === 'root') {
  	$("#overBox").fadeOut();
	$("body").css('overflow', 'auto');
  }
});


function toggleLabels(element){

	if($(element).val() === ""){
		$("#loginPane label[for='"+$(element).attr('name')+"']").css("visibility", "visible");
	}else{
		$("#loginPane label[for='"+$(element).attr('name')+"']").css("visibility", "hidden");
	}
}

$('#loginPane, input').bind("keyup change focus", function() {toggleLabels(this)});
$('#loginPane input').each(function() {toggleLabels(this);});



$(document).mouseup(function (e)
{
	if(gatewayBoxIn){

	    var container = $("#gatewayBox");
	    var target = $( e.target );

	    //console.log(target);

	    if (!container.is(target) && container.has(target).length === 0 && !target.is( "#overBox, #contents, #closeX, .noticeBox, .closeBox, i" ))
	    {
	        $( "#loginPane, #signUpPane" ).slideUp("slow" );
			$( "#loginBtn, #signUpBtn" ).animate({width: "47.5%"},"slow");
			$( "#loginBtn span, #signUpBtn span").fadeIn( "300" );
			gatewayBoxIn = false;
	    }
    }
});




//NOTICES//

var displayCount = 0;

function displayNotice(type, duration, title, info) {

	//duration -1 = sticky
	//duration 0 = defalt

	var defaltDelay = 1500;
	
	var thisUUID = $.now()+Math.random().toString(36).substr(2);
	
	var closeBut = (duration < 0) ? '<div id="'+thisUUID+'Close" class="closeBox"><i></i></div>' : '';
	
	var stickyClass = (duration < 0) ? ' sticky' : '';

	var msg =  (title != '') ? '<span class="noticeTitle">'+title+'</span>: '+info : info;

	var builtNotice ='<div id="'+thisUUID+'" class="'+type+'Box noticeBox'+stickyClass+'"><div class="noticeBoxMsg"><p>'+msg+'</p></div>'+closeBut+'</div>';

	$("#notices").prepend( builtNotice );
	
	if (duration > -1) {
		var baseDelay = (duration > 0 ) ? duration : defaltDelay;
		var thisDelay = baseDelay+(500*displayCount);
		displayCount++;
		$("#notices").animate({top:0}, 500);
		$("#"+thisUUID).delay(thisDelay).fadeOut(500, function(){displayCount--});
	}else{
		$("#notices").animate({top:0}, 500);
		$("#"+thisUUID).click(function () {$("#"+thisUUID).fadeOut(300);});
	}

	//example display call
	//displayNotice("error", 0, "Message", "Hello Ryan");

}




function functionCall(task, dataIn){

    $.ajax({
    	method: 'POST',
		url: 'api/run.php/function/'+task,         
		data: dataIn,                                
		dataType: 'html',                  
		success: function(returnArr)        
		{
			returnedInfo = JSON.parse(returnArr);
			console.log(returnedInfo);
			runNoticeQueue(returnedInfo.notices);
			if(returnedInfo.data != null){
				afterEvents(returnedInfo.task, returnedInfo.data);
			}
		},
		error: function(xhr, status, error) {
			returnedInfo = JSON.parse(xhr.responseText);
			console.log(returnedInfo);
			runNoticeQueue(returnedInfo.notices);
        }
    });
}

//Function Listeners

$("#notices").on("click", "#resendVerification", function(event){
 		event.preventDefault();
 		functionCall($(this).attr('id'), null);
});

$("#overBox #contents").on("click", "#favCocktailToggle", function(event){
 		event.preventDefault();
 		functionCall($(this).attr('id'), {class:$(this).attr('class')});
});

$("#overBox #contents").on("click", "#report-cocktail", function(event){
	 	event.preventDefault();
	 	var confirmReport = confirm("You are about to report this cocktail recipe as unsuitable...");
		if (confirmReport === true) {
			functionCall($(this).attr('id'), null);
		} 		
});




///Check boxes

$("#overBox #contents").on("change", ".formRow input[type='checkbox']", function(event){


    if(this.checked) {
        $( this ).parents(".formRow").removeClass().addClass("formRow selectedBox");
    }else{
    	$( this ).parents(".formRow").removeClass().addClass("formRow");
    }

});



//recipeSearch

$( "#recipeSearch" ).on('input', function() {
  $(".recipeLink").each(function() {
  	if ($(this).find('.recipeTitle').text().toLowerCase().indexOf($("#recipeSearch").val().toLowerCase()) == -1) {
  		$(this).hide();
  	}else{
  		$(this).show();
  	}
  });
});


var favState = false;
$( "#favs" ).click(function(event) {

	if(favState == false){
		$(".recipeLink").filter(function() {
		  	return $(this).find( "div" ).hasClass("favStarTrue") == 0;
	  	}).hide();
	  	$('#favs i').css({
	  		color: '#35a8e0'
	  	});
	  	$('#favs').css({
	  		'background-color': 'rgba(255, 255, 255, 1)'
	  	});
		favState = true;
	}else{
		$(".recipeLink").show();
		$('#favs i').css({
	  		color: '#fff'
	  	});
	  	$('#favs').css({
	  		'background-color': ''
	  	});
		favState = false;
	}



});






///LIST REFRESH///


function refreshList(){

    $.ajax({
    	method: 'POST',
		url: 'api/run.php/refreshList/go',                                       
		dataType: 'html',                  
		success: function(data)        
		{
			$("#recipeList").html(data);
		},
		error: function(xhr, status, error) {
			returnedInfo = JSON.parse(xhr.responseText);
			console.log(returnedInfo);
			runNoticeQueue(returnedInfo.notices);
        }
    });
}








