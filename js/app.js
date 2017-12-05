$( document ).ready(function() {
	mdc.autoInit();
	let user_menu = new mdc.menu.MDCSimpleMenu(document.querySelector("#user-menu"));
	if($.cookie("uuid") == null) {
		console.log("you're unauthorized!");
		window.location.replace("index.html");
	} else {
		$.ajax({
		  url: "http://0.0.0.0:8000/user.php/"+$.cookie("uid"),
		  method: "GET",
		  dataType: "json"
		}).done(function(response) {
			$("#user-menu-btn").text("Hey "+response['fname']+"!");
		}).fail(function(error) {
			console.log(error); //log them out or something
		});

	}
	$("#user-menu-btn").click(function() {
		user_menu.open = !user_menu.open;
	})
	$("#logout-btn").click(function() {
		$("#overlay").css("display","block");
		$.ajax({
		  url: "http://0.0.0.0:8000/auth.php/logout?user="+$.cookie("uid"),
		  method: "GET",
		  dataType: "json"
		}).done(function(response) {
			$.removeCookie('uuid', {path:'/'}); //delete cookie
			window.location.replace("index.html");
			$("#overlay").css("display","none");
		}).fail(function(error) {

		});
	});
	
});