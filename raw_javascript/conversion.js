
// var unitArray = {
// 	1:1,
// 	2:0.1,
// 	3:29.573515625
// };



	//var currentUnit = $(this).val();

$("#overBox #contents").on('change', '.unitSelect', function(event) {

	currentUnit = $(this).siblings('.prevUnit').val();

	valPath = $(this).closest( "li" ).find('.qtyVal');
	
	newUnit = $(this).val();
	
	intIn = valPath.val();
	console.log("Value In: "+intIn);

	renderToMls = intIn / unitArray[currentUnit];
	convertToNew = renderToMls * unitArray[newUnit];

	valPath.val(convertToNew);
	valPath.siblings('.valOutput').text(Math.round(convertToNew * 100) / 100);
	$(this).siblings('.prevUnit').val(newUnit);

	console.log(intIn+' to '+convertToNew);

});


$('#overBox #contents').on('change', '#changeAll', function(event) {
	
	var allUnits = $(this).val();

	$('.unitSelect').each(function() {

		currentUnit = $(this).siblings('.prevUnit').val();
		valPath = $(this).closest( "li" ).find('.qtyVal');

		newUnit = allUnits;
		intIn = valPath.val();

		if(currentUnit != newUnit && newUnit != 'X'){

			renderToMls = intIn / unitArray[currentUnit];
			convertToNew = renderToMls * unitArray[newUnit];

			valPath.val(convertToNew);
			valPath.siblings('.valOutput').text(Math.round(convertToNew * 100) / 100);
			$(this).siblings('.prevUnit').val(newUnit);

	    	$(this).val(newUnit);
			console.log(intIn+' to '+convertToNew);
	    }
    });

    $(this).val('X');
	
});


$('#overBox #contents').on('click', '#addIng', function(event) {
	event.preventDefault();

	$('.ingTemplate').clone().appendTo( "#ingList" ).prop('class', 'ingRow').css({ display: 'none'}).fadeIn('slow');

	if ($("#ingList li").length == 2) {
		$('#addIng').animate({width: "48%"}, "slow", function(){
			$('#delIng').fadeIn('fast');
		});

	}

});

$('#overBox #contents').on('click', '#delIng', function(event) {
	event.preventDefault();

	$('#ingList li:last').slideUp('fast', function() {
		$(this).remove();
	});

	if ($("#ingList li").length < 3) {

		$('#delIng').fadeOut('fast', function(){
			$('#addIng').animate({width: "100%"}, "slow");
		});
	}
});


$('#overBox #contents').on('change', 'input[type=radio][name=bgColour]', function(event) {
	$("#overBox").removeClass().addClass("BG-"+this.value);
});








