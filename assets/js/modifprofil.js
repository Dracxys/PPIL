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

	var boutonInfo = $("#boutonInfo");
	var boutonResp = $("#boutonResp");
	var boutonPhoto = $("#boutonPhoto");
	var boutonPassword = $("#boutonPassword");
    var boutonReinitialiser = $("#boutonReinitialiser");

	var pageInfo =  $("#infoperso");;
	var pageResp = $("#responsabilite");
	var pagePhoto = $("#photo");
	var pagePassword = $("#motdepasse");
    var pageReinitialiser = $("#reinitialiser");

	$('#navbar_panel').on('hidden.bs.collapse', function () {
		$('#liste_groupe').toggleClass("list-group-horizontal");
	});

	$('#navbar_panel').on('show.bs.collapse', function () {
		$('#liste_groupe').toggleClass("list-group-horizontal");
	});

	boutonInfo.click(function() {
		boutonInfo.addClass("active");
		boutonPassword.removeClass("active");
		boutonPhoto.removeClass("active");
		boutonResp.removeClass("active");
		boutonReinitialiser.removeClass("active");

		pageInfo.show();
		pagePassword.hide();
		pagePhoto.hide();
		pageResp.hide();
        pageReinitialiser.hide();
	});

	boutonResp.click(function() {
		boutonResp.addClass("active");
		boutonPassword.removeClass("active");
		boutonPhoto.removeClass("active");
		boutonInfo.removeClass("active");
		boutonReinitialiser.removeClass("active");

		pageResp.show();
		pagePassword.hide();
		pagePhoto.hide();
		pageInfo.hide();
        pageReinitialiser.hide();
	});

	boutonPhoto.click(function() {
		boutonPhoto.addClass("active");
		boutonPassword.removeClass("active");
		boutonInfo.removeClass("active");
		boutonResp.removeClass("active");
        boutonReinitialiser.removeClass("active");

		pagePhoto.show();
		pagePassword.hide();
		pageResp.hide();
		pageInfo.hide();
        pageReinitialiser.hide();
	});

	boutonPassword.click(function() {
		boutonPassword.addClass("active");
		boutonInfo.removeClass("active");
		boutonPhoto.removeClass("active");
		boutonResp.removeClass("active");
        boutonReinitialiser.removeClass("active");

		pagePassword.show();
		pageResp.hide();
		pagePhoto.hide();
		pageInfo.hide();
        pageReinitialiser.hide();
	});

    $( "#boutonReinitialiser" ).click(function() {
        boutonReinitialiser.addClass("active");
		boutonInfo.removeClass("active");
		boutonPassword.removeClass("active");
		boutonPhoto.removeClass("active");
		boutonResp.removeClass("active");

		pageInfo.hide();
		pagePassword.hide();
		pagePhoto.hide();
		pageResp.hide();
        pageReinitialiser.show();
	});

	$("#respUE").click(function(){
		$("#formation").removeClass("active");
		$("#UE").addClass("active");
		$("#UE").show();
		$("#formation").hide();
	});

	$("#respForm").click(function(){
		$("#formation").addClass("active");
		$("#UE").removeClass("active");
		$("#UE").hide();
		$("#formation").show();
	});
}
