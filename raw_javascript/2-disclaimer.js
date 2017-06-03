//disclaimer
if (document.cookie.replace(/(?:(?:^|.*;\s*)termsAgreed\s*\=\s*([^;]*).*$)|^.*$/, "$1") !== "true") {
	$('#disclaimerBox').css({ display: 'block'});
}

$("#disclaimerBox #contents").on("click", "#disclaimerBtn", function(event){
	document.cookie = "termsAgreed=true; expires=Fri, 31 Dec 9999 23:59:59 GMT";
	$('#disclaimerBox').fadeOut('slow');
});