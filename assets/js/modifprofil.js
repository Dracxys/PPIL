var boutonInfo = document.getElementById("boutonInfo");
var boutonResp = document.getElementById("boutonResp");
var boutonPhoto = document.getElementById("boutonPhoto");
var boutonPassword = document.getElementById("boutonPassword");


$( "#boutonInfo" ).click(function() {
    boutonInfo.classList.add("active");
    boutonPassword.classList.remove("active");
    boutonPhoto.classList.remove("active");
    boutonResp.classList.remove("active");

    $( "#infoperso" ).show();
    $("#motdepasse").hide();
    $("#photo").hide();
    $("#responsabilite").hide();
});

$( "#boutonResp" ).click(function() {
    boutonResp.classList.add("active");
    boutonPassword.classList.remove("active");
    boutonPhoto.classList.remove("active");
    boutonInfo.classList.remove("active");

    $( "#responsabilite" ).show();
    $("#motdepasse").hide();
    $("#photo").hide();
    $("#infoperso").hide();
});

$( "#boutonPhoto" ).click(function() {
    boutonPhoto.classList.add("active");
    boutonPassword.classList.remove("active");
    boutonInfo.classList.remove("active");
    boutonResp.classList.remove("active");

    $( "#photo" ).show();
    $("#motdepasse").hide();
    $("#responsabilite").hide();
    $("#infoperso").hide();
});

$( "#boutonPassword" ).click(function() {
    boutonPassword.classList.add("active");
    boutonInfo.classList.remove("active");
    boutonPhoto.classList.remove("active");
    boutonResp.classList.remove("active");

    $( "#motdepasse" ).show();
    $("#responsabilite").hide();
    $("#photo").hide();
    $("#infoperso").hide();
});