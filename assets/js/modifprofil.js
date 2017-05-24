function setup(modifprofil){
	$("#cercle").circliful({
		animation: 1,
		animationStep: 5,
		foregroundBorderWidth: 15,
		backgroundBorderWidth: 15,
		textSize: 28,
		textStyle: 'font-size: 12px;',
		textColor: '#666',
		multiPercentage: 1,
		percentages: [10, 20, 30],
	});

	var boutonInfo = document.getElementById("boutonInfo");
	var boutonResp = document.getElementById("boutonResp");
	var boutonPhoto = document.getElementById("boutonPhoto");
	var boutonPassword = document.getElementById("boutonPassword");
        var boutonReinitialiser = document.getElementById("boutonReinitialiser");

	$('#navbar_panel').on('hidden.bs.collapse', function () {
		$('#liste_groupe').toggleClass("list-group-horizontal");
	});

	$('#navbar_panel').on('show.bs.collapse', function () {
		$('#liste_groupe').toggleClass("list-group-horizontal");
	});

	$( "#boutonInfo" ).click(function() {
		boutonInfo.classList.add("active");
		boutonPassword.classList.remove("active");
		boutonPhoto.classList.remove("active");
		boutonResp.classList.remove("active");
                boutonReinitialiser.classList.remove("active");

		$( "#infoperso" ).show();
		$("#motdepasse").hide();
		$("#photo").hide();
		$("#responsabilite").hide();
                $("#reinitialiser").hide();
	});

	$( "#boutonResp" ).click(function() {
		boutonResp.classList.add("active");
		boutonPassword.classList.remove("active");
		boutonPhoto.classList.remove("active");
		boutonInfo.classList.remove("active");
                boutonReinitialiser.classList.remove("active");

		$( "#responsabilite" ).show();
		$("#motdepasse").hide();
		$("#photo").hide();
		$("#infoperso").hide();
                $("#reinitialiser").hide();
	});

	$( "#boutonPhoto" ).click(function() {
		boutonPhoto.classList.add("active");
		boutonPassword.classList.remove("active");
		boutonInfo.classList.remove("active");
		boutonResp.classList.remove("active");
                boutonReinitialiser.classList.remove("active");

		$( "#photo" ).show();
		$("#motdepasse").hide();
		$("#responsabilite").hide();
		$("#infoperso").hide();
                $("#reinitialiser").hide();
	});

	$( "#boutonPassword" ).click(function() {
		boutonPassword.classList.add("active");
		boutonInfo.classList.remove("active");
		boutonPhoto.classList.remove("active");
		boutonResp.classList.remove("active");
                boutonReinitialiser.classList.remove("active");

		$( "#motdepasse" ).show();
		$("#responsabilite").hide();
		$("#photo").hide();
		$("#infoperso").hide();
                $("#reinitialiser").hide();
	});
        
        $( "#boutonReinitialiser" ).click(function() {
                boutonReinitialiser.classList.add("active");
		boutonInfo.classList.remove("active");
		boutonPassword.classList.remove("active");
		boutonPhoto.classList.remove("active");
		boutonResp.classList.remove("active");
                
                console.log("coucou");

		$( "#infoperso" ).hide();
		$("#motdepasse").hide();
		$("#photo").hide();
		$("#responsabilite").hide();
                $("#reinitialiser").show();
	});

	$("#respUE").click(function(){
		document.getElementById("formation").classList.remove("active");
		document.getElementById("UE").classList.add("active");
		$("#UE").show();
		$("#formation").hide();
	});

	$("#respForm").click(function(){
		document.getElementById("formation").classList.add("active");
		document.getElementById("UE").classList.remove("active");
		$("#UE").hide();
		$("#formation").show();
	});

	/*
	$("form#form_resp").submit(function(e){
		e.preventDefault();
		$.ajax({
			url : modifprofil,
			type: 'post',
			data: $("form#form_resp").serializeArray(),
			dataType: 'json',
			success: function(json){
				console.log(json);
				//location.reload();
			}
		});
	});*/


}
