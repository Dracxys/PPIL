var boutonCompo = document.getElementById("boutonCompo");
var boutonInterv = document.getElementById("boutonInterv");


$( "#boutonCompo" ).click(function() {
    boutonCompo.classList.add("active");
    boutonInterv.classList.remove("active");

    $( "#compoUE" ).show();
    $("#intervenantsUE").hide();
});

$( "#boutonInterv" ).click(function() {
    boutonInterv.classList.add("active");
    boutonCompo.classList.remove("active");

    $( "#intervenantsUE" ).show();
    $("#compoUE").hide();
});

var ppil;

function setLien(lien){
    ppil = lien;
}

function choixUE() {
    id_UE = $('#selectUE option:selected').val();
    $.ajax({
        url: ppil,
        type: 'post',
        data: {'id': id_UE},
        success: function (tab) {
            if (tab != undefined) {
                $('#heureAffecteCM').val(tab.heuresCM);
                $('#heureAttenduCM').val(tab.prevision_heuresCM);
                if (tab.heuresCM == tab.prevision_heuresCM) {
                    $('#heureAffecteCM').css("color", "green");
                } else {
                    $('#heureAffecteCM').css("color", "red");
                }

                $('#heureAffecteTD').val(tab.heuresTD);
                $('#heureAttenduTD').val(tab.prevision_heuresTD);
                $('#nbGroupeAffecteTD').val(tab.groupeTD);
                $('#nbGroupeAttenduTD').val(tab.prevision_groupeTD);
                if (tab.heuresTD == tab.prevision_heuresTD) {
                    $('#heureAffecteTD').css("color", "green");
                } else {
                    $('#heureAffecteTD').css("color", "red");
                }
                if (tab.groupeTD == tab.prevision_groupeTD) {
                    $('#nbGroupeAffecteTD').css("color", "green");
                } else {
                    $('#nbGroupeAffecteTD').css("color", "red");
                }

                $('#heureAffecteTP').val(tab.heuresTP);
                $('#heureAttenduTP').val(tab.prevision_heuresTP);
                $('#nbGroupeAffecteTP').val(tab.groupeTP);
                $('#nbGroupeAttenduTP').val(tab.prevision_groupeTP);
                if (tab.heuresTP == tab.prevision_heuresTP) {
                    $('#heureAffecteTP').css("color", "green");
                } else {
                    $('#heureAffecteTP').css("color", "red");
                }
                if (tab.groupeTP == tab.prevision_groupeTP) {
                    $('#nbGroupeAffecteTP').css("color", "green");
                } else {
                    $('#nbGroupeAffecteTP').css("color", "red");
                }

                $('#heureAffecteEI').val(tab.heuresEI);
                $('#heureAttenduEI').val(tab.prevision_heuresEI);
                $('#nbGroupeAffecteEI').val(tab.groupeEI);
                $('#nbGroupeAttenduEI').val(tab.prevision_groupeEI);
                if (tab.heuresEI == tab.prevision_heuresEI) {
                    $('#heureAffecteEI').css("color", "green");
                } else {
                    $('#heureAffecteEI').css("color", "red");
                }
                if (tab.groupeEI == tab.prevision_groupeEI) {
                    $('#nbGroupeAffecteEI').css("color", "green");
                } else {
                    $('#nbGroupeAffecteEI').css("color", "red");
                }

            }
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    })
}

    function modifUE() {
        var heureCM = $('#heureAttenduCM').val();
        var nbGroupeTD = $('#nbGroupeAttenduTD').val();
        var heureTD = $('#heureAttenduTD').val();
        var nbGroupeTP = $('#nbGroupeAttenduTP').val();
        var heureTP = $('#heureAttenduTP').val();
        var nbGroupeEI = $('#nbGroupeAttenduEI').val();
        var heureEI = $('#heureAttenduEI').val();

        if (heureCM < 0 || nbGroupeTD < 0 || heureTD < 0 || nbGroupeTP < 0 || heureTP < 0 || nbGroupeEI < 0 || heureEI < 0) {
            $('#erreur').show();
        } else {
            $('#erreur').hide();
            $.ajax({
                url: ppil + '/modif',
                type: 'post',
                data: {
                    'id': id_UE, 'heureCM': heureCM, 'nbGroupeTD': nbGroupeTD,
                    'heureTD': heureTD, 'nbGroupeTP': nbGroupeTP, 'heureTP': heureTP,
                    'nbGroupeEI': nbGroupeEI, 'heureEI': heureEI
                },
                success: function (res) {
                    if (res != undefined) {
                        if (res[0] == 'true') {
                            $('#messageTitre').text('Succès');
                            $('#message').text('Les modifications ont bien été pris en compte.');
                            $('#modalDemandeEffectuee').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            choixUE();
                        } else {
                            $('#messageTitre').text('Erreur');
                            $('#message').text('Les modifications n\'ont pas pu être sauvegardé.');
                            $('#modalDemandeEffectuee').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                        }
                    }

                },
                xhrFields: {
                    withCredentials: true
                },
                crossDomain: true
            })
        }

    }

    function listIntervenant() {
        id_UE = $('#selectUE option:selected').val();
        $.ajax({
            url: ppil + '/listIntervenant',
            type: 'post',
            data: {'id': id_UE},
            success: function (tab) {
                console.log("succès ajax");
                if (tab != undefined){
                    var html;
                    for (var i = 0; i < tab.length; i = i + 9){
                        html += "<tr>"
                            +"<th class='text-center'>" + tab[0+i] + " " + tab[1 + i] + "</th>"
                            +"<th class='text-center'>" + tab[2+i] + "</th>"
                            +"<th class='text-center'>" + tab[3+i] + "</th>"
                            +"<th class='text-center'>" + tab[4+i] + "</th>"
                            +"<th class='text-center'>" + tab[5+i] + "</th>"
                            +"<th class='text-center'>" + tab[6+i] + "</th>"
                            +"<th class='text-center'>" + tab[7+i] + "</th>"
                            +"<th class='text-center'>" + tab[8+i] + "</th>"
                            +"</tr>";
                    }
                    $('#tableau').html(html);
                }

            }, xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    });
}