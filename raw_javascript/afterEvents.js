function slugger(page){
	var slug = page.replace(/ /g, "-");
	slug = slug.replace("'", "");
	return slug;
}

function overBoxSwap(boxTask, type){
	var apiUri = 'api/run.php/overbox/';

	if(type == 'recipe'){
		apiUri = 'api/run.php/recipe/';
		var historyData = {type:"recipe", task:boxTask};
		history.pushState(historyData, null, 'recipes/'+boxTask);
	}

	$.ajax({ 
    	method: 'POST',
		url: apiUri+boxTask,                                        
		dataType: 'html',
		success: function(data)        
		{
			$("#overBox #contents").html(data);
		},
		error: function(xhr, status, error) {
			returnedInfo = JSON.parse(xhr.responseText);
			console.log(returnedInfo);
			runNoticeQueue(returnedInfo.notices);
        }
    });
}


function afterEvents(task, dataIn){


	if(task == 'recipeSaved'){
		//overBoxSwap(slugger(dataIn[Object.keys(dataIn)[0]]['name']), 'recipe');
		overBoxSwap(dataIn[Object.keys(dataIn)[0]]['id']+'/'+slugger(dataIn[Object.keys(dataIn)[0]]['name']), 'recipe');
		refreshList();
	}

	if(task == 'usrInfoUpadted'){
		overBoxSwap('accInfo', 'standard');
	}

	if(task == 'updateFavUnits'){
		overBoxSwap('accInfo', 'standard');
	}

	if (task == 'favCocktailToggle') {

		$("#favCocktailToggle").removeClass().addClass("favStar"+dataIn);
		refreshList();
	}
	


}