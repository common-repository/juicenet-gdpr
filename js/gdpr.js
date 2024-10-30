
function tryFunc(fName,args){    
	if(typeof window[fName]==='function'){
		window[fName](args);
	}else{
		if(args){
			if(args.href){  
				window.open(args.href)}
			}    
	}
	return false;
}


jQuery(document).ready(function($){
	$(".expand-content").hide();
	$(".expand").addClass("collapsed");
	$(".expand .expand-click").click(function () {
		$(this).parents(".expand").toggleClass("collapsed");
		$(this).parents(".expand").toggleClass("expanded");
		$(this).parents(".expand-item").toggleClass("hover");
		$(this).children('.icon-17').toggleClass("icon-expand");
		$(this).children('.icon-17').toggleClass("icon-collapse");
		$(this).parents('.expand').children('.expand-content').slideToggle("fast");
	});
});