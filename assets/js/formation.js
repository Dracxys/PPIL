var ppil;
var id_UE;
var value;
function recupererUE(lien) {
    value = $('#selectForm option:selected').val();
    $('#nomFormation').text('Volume Horaire ' + value);
    ppil = lien;
    $.ajax({
        url: lien,
        type: 'post',
        data: {'nom': value},
        dataType: 'json',
        success: function (tab) {
            if (tab.length > 0) {
                var html = "<table class='table'><tbody>";
                var j = 0;
                for (var i = 0; i < tab.length; i++) {
                    j = i + 1;
                    html = html + "<tr><td><a id='" + tab[i] + "' onclick='choixUE(this)'>" + tab[j] + "</a></td>"+
                        "<td><button type='button' class='btn btn-default pull-right' onclick='supprimer("+tab[i]+")' " +
                        ">Supprimer</button></td></tr>";
                    i = j;
                }
                html = html + "</tbody></table>";
                $('#tableUE').html(html);
                $('#nomUE').text(tab[1]);
                choixUE($("#" + tab[0]));
                totalLicence(value);
            } else {
                var html = "<ul class='nav'>";
                html = html + "</ul>";
                $('#tableUE').html(html);
                choixUE(undefined);
                $('#nomUE').text("Aucune UE");
                totalLicence(value);
            }
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    });
}

function choixUE(element) {
    if(element != undefined){
        $('#nomUE').text($(element).text());
        id_UE = $(element).attr('id');
        $.ajax({
            url: ppil + 'infos',
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
    }else{
        $('#heureAffecteCM').val(0);
        $('#heureAttenduCM').val(0);
        $('#heureAffecteTD').val(0);
        $('#heureAttenduTD').val(0);
        $('#nbGroupeAffecteTD').val(0);
        $('#nbGroupeAttenduTD').val(0);
        $('#heureAffecteTP').val(0);
        $('#heureAttenduTP').val(0);
        $('#nbGroupeAffecteTP').val(0);
        $('#nbGroupeAttenduTP').val(0);
        $('#heureAffecteEI').val(0);
        $('#heureAttenduEI').val(0);
        $('#nbGroupeAffecteEI').val(0);
        $('#nbGroupeAttenduEI').val(0);
    }

}

function totalLicence(nom) {
    $.ajax({
        url: ppil + 'total',
        type: 'post',
        data: {'nom': nom},
        success: function (tab) {
            if (tab != undefined) {
                if (tab.length > 0) {
                    $('#volumeAttenduCM').text(tab[0]);
                    $('#volumeAffecteCM').text(tab[1]);
                    $('#volumeAttenduTD').text(tab[2]);
                    $('#volumeAffecteTD').text(tab[3]);
                    $('#volumeAttenduTP').text(tab[4]);
                    $('#volumeAffecteTP').text(tab[5]);
                    $('#volumeAttenduEI').text(tab[6]);
                    $('#volumeAffecteEI').text(tab[7]);

                    if (tab[0] == tab[1]) {
                        $('#volumeAffecteCM').css("color", "green");
                    } else {
                        $('#volumeAffecteCM').css("color", "red");
                    }

                    if (tab[2] == tab[3]) {
                        $('#volumeAffecteTD').css("color", "green");
                    } else {
                        $('#volumeAffecteTD').css("color", "red");
                    }

                    if (tab[4] == tab[5]) {
                        $('#volumeAffecteTP').css("color", "green");
                    } else {
                        $('#volumeAffecteTP').css("color", "red");
                    }

                    if (tab[6] == tab[7]) {
                        $('#volumeAffecteEI').css("color", "green");
                    } else {
                        $('#volumeAffecteEI').css("color", "red");
                    }
                } else {
                    $('#volumeAttenduCM').text(0);
                    $('#volumeAffecteCM').text(0);
                    $('#volumeAttenduTD').text(0);
                    $('#volumeAffecteTD').text(0);
                    $('#volumeAttenduTP').text(0);
                    $('#volumeAffecteTP').text(0);
                    $('#volumeAttenduEI').text(0);
                    $('#volumeAffecteEI').text(0);
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
            url: ppil + 'modif',
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
                        choixUE($('#' + id_UE));
                        totalLicence(value);
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

function creerForm() {
    $('#modalAjouter').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function ajouterForm() {
    var nom = $('#nomForm').val();
    var fst = 1;
    $.ajax({
        url: ppil + 'creer/form',
        type: 'post',
        data: {'nom': nom, 'fst': fst},
        success: function (res) {
            if (res != undefined) {
                $('#modalAjouter').modal('toggle');
                $('#messageTitre').text('Succès');
                $('#message').text('La formation a bien été créée.');
                $('#modalDemandeEffectuee').modal({
                    backdrop: 'static',
                    keyboard: false
                });

            } else {
                $('#modalAjouter').modal('toggle');
                $('#messageTitre').text('Erreur');
                $('#message').text('Problème lors de la création de la formation.');
                $('#modalDemandeEffectuee').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    })
}

function supprimer(ue) {
    $.ajax({
        url: ppil + 'supprimer',
        type: 'post',
        data: {'id': ue},
        success: function (res) {
            if (res != undefined && res[0] == 'true') {
                $('#messageTitre').text('Succès');
                $('#message').text('Cette UE a bien été supprimé.');
                $('#modalDemandeEffectuee').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                recupererUE(ppil);
            } else {
                $('#messageTitre').text('Erreur');
                $('#message').text('Problème lors de la suppression de cette UE.');
                $('#modalDemandeEffectuee').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    })
}

function ajouterUE() {
    $.ajax({
        url: ppil + 'ue',
        type: 'get',
        success: function (res) {
            var html = "";
            res.forEach(function (element) {
                html += "<tr><th class='text-center hidden'>"+element.id_UE+"</th>"
                + "<th class='text-center'>"+element.nom_UE+"</th>"
                + "<th class='text-center'>Test</th></tr>"
            })
            $('#dispoUE').html(html);
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    });


    $('#modalAjouterUE').modal({
        backdrop: 'static',
        keyboard: false
    });
}



