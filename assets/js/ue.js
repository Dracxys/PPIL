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
var id_UE;

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
                $('#tab').remove();
                if (tab != undefined){
                    var html;
                    for (var i = 0; i < tab.length; i = i + 10){
                        html += "<tr id='tab'>"
                            +"<th class='text-center'>" + tab[0+i] + " " + tab[1 + i] + "</th>"
                            +"<th class='text-center'>" + "<input type='number' id='hcm' class='form-control' value=" + tab[2+i] + " min='0'/></th>"
                            +"<th class='text-center'>" + "<input type='number' id='nbtd' class='form-control' value=" + tab[3+i] + " min='0'/></th>"
                            +"<th class='text-center'>" + "<input type='number' id='htd' class='form-control' value=" + tab[4+i] + " min='0'/></th>"
                            +"<th class='text-center'>" + "<input type='number' id='nbtp' class='form-control' value=" + tab[5+i] + " min='0'/></th>"
                            +"<th class='text-center'>" + "<input type='number' id='htp' class='form-control' value=" + tab[6+i] + " min='0'/></th>"
                            +"<th class='text-center'>" + "<input type='number' id='nbei' class='form-control' value=" + tab[7+i] + " min='0'/></th>"
                            +"<th class='text-center'>" + "<input type='number' id='hei' class='form-control' value=" + tab[8+i] + " min='0'/></th>"
                            +"<th class='text-center'>"
                            +"<button type='button' class='btn btn-default pull-left' onclick='boutonValidationModifIntervenantUE(tab[9+i],tab[2+i],tab[3+i],tab[4+i],tab[5+i],tab[6+i],tab[7+i],tab[8+i])' id='validerHeuresIntervenantUE'>Valider</button>"
                            +"<button type='button' class='btn btn-default pull-right' onclick='boutonSuppressionEnseignant(tab[9 + i])' id='supprimerInternantUE'>Supprimer</button>"
                            +"</th>"
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

    function boutonValidationModif() {
        id_UE = $('#selectUE option:selected').val();
        $.ajax({
            url: ppil + '/boutonModif',
            type: 'post',
            data: {'id': id_UE},
            success: function (element) {
                if (res != undefined) {
                    if (element == true) {
                        $("#nbGroupeAttenduTD").prop('disabled', false);
                        $("#nbGroupeAffecteTD").prop('disabled', false);
                        $("#heureAttenduTD").prop('disabled', false);
                        $("#heureAffecteTD").prop('disabled', false);
                        $("#hcm").prop('disabled', false);
                        $("#nbtd").prop('disabled', false);
                        $("#htd").prop('disabled', false);
                        $("#nbtp").prop('disabled', false);
                        $("#htp").prop('disabled', false);
                        $("#nbei").prop('disabled', false);
                        $("#hei").prop('disabled', false);
                        $("#validerHeuresIntervenantUE").prop('disabled', false);
                        $("#supprimerInternantUE").prop('disabled', false);
                        var html = "<button type='button' class='btn btn-default center-block' onclick='modifUE()' id='valider'>Valider</button>";
                        $('#boutton_validation').html(html);
                    } else {
                        $("#nbGroupeAttenduTD").prop('disabled', true);
                        $("#nbGroupeAffecteTD").prop('disabled', true);
                        $("#heureAttenduTD").prop('disabled', true);
                        $("#heureAffecteTD").prop('disabled', true);
                        $("#hcm").prop('disabled', true);
                        $("#nbtd").prop('disabled', true);
                        $("#htd").prop('disabled', true);
                        $("#nbtp").prop('disabled', true);
                        $("#htp").prop('disabled', true);
                        $("#nbei").prop('disabled', true);
                        $("#hei").prop('disabled', true);
                        $("#validerHeuresIntervenantUE").prop('disabled', true);
                        $("#supprimerInternantUE").prop('disabled', true);
                    }
                }
            }, xhrFields: {
                withCredentials: true
            },
            crossDomain: true
        });
    }

function boutonValidationModifIntervenantUE(mail, hcm, nbtd, htd, nbtp,htp,nbei,hei) {
    id_UE = $('#selectUE option:selected').val();
    $.ajax({
        url: ppil + '/modifHeureEnseignant',
        type: 'post',
        data: {'id': id_UE, 'mail': mail, 'heureCM': hcm, 'nbGroupeTD':nbtd,'heureTD':htd,'nbGroupeTP':nbtp,
            'heureTP':htp,'nbGroupeEI':nbei,'heureEI':hei},
        success: function (element) {
            if (res != undefined) {
                if (res[0] == true) {
                    $('#messageTitre').text('Succès');
                    $('#message').text('Les modifications ont bien été pris en compte.');
                    $('#modalDemandeEffectuee').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    listIntervenant();
                } else {
                    $('#messageTitre').text('Erreur');
                    $('#message').text('Les modifications n\'ont pas pu être sauvegardé.');
                    $('#modalDemandeEffectuee').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            }
        }, xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    });
}

function boutonSuppressionEnseignant(mail) {
    id_UE = $('#selectUE option:selected').val();
    $.ajax({
        url: ppil + '/suppressionEnseignant',
        type: 'post',
        data: {'id': id_UE, 'mail': mail},
        success: function (element) {
            if (res != undefined) {
                if (res[0] == true) {
                    $('#messageTitre').text('Succès');
                    $('#message').text('Les modifications ont bien été pris en compte.');
                    $('#modalDemandeEffectuee').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    listIntervenant();
                } else {
                    $('#messageTitre').text('Erreur');
                    $('#message').text('Les modifications n\'ont pas pu être sauvegardé.');
                    $('#modalDemandeEffectuee').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
            }
        }, xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    });
}