var ppil;
var id_UE;
var value;
function recupererUE(lien) {
    value = $('#selectForm option:selected').val();
    $('#nomFormation').text('Volume Horaire ' + value);
    ppil = lien ;
    $.ajax({
        url: ppil + '/ue',
        type: 'post',
        data: {'nom': value},
        dataType: 'json',
        success: function (tab) {
            if (tab.length > 0) {
                var html = "<table class='table-bordered'><tbody>";
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
            url: ppil + '/ue/infos',
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
        $('#heureAffecteCM').css("color","black");
        $('#heureAffecteTD').css("color","black");
        $('#nbGroupeAffecteTD').css("color","black");
        $('#heureAffecteTP').css("color","black");
        $('#nbGroupeAffecteTP').css("color","black");
        $('#heureAffecteEI').css("color","black");
        $('#nbGroupeAffecteEI').css("color","black");
    }

}

function totalLicence(nom) {
    $.ajax({
        url: ppil + '/ue/total',
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
                    $('#volumeAffecteCM').css("color","black");
                    $('#volumeAffecteTD').css("color","black");
                    $('#volumeAffecteTP').css("color","black");
                    $('#volumeAffecteEI').css("color","black");
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
        $('#valider').addClass( 'disabled' );
        $.ajax({
            url: ppil + '/ue/modif',
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
                $('#valider').removeClass( 'disabled' );

            },
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true
        })
    }
}

function creerForm() {
    enseignant();
    $('#modalAjouterForm').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function ajouterForm() {
    var nom = $('#nomForm').val();
    if(nom == "" || nom == "Obligatoire"){
        $('#nomForm').val("Obligatoire");
        $('#nomForm').css("color","red");
    }else{

        var respon1 = $('#respForm1 option:selected').val();
        var respon2 = $('#respForm2 option:selected').val();
        var respon3 = $('#respForm3 option:selected').val();
        var respon4 = $('#respForm4 option:selected').val();
        if(respon1 != '0' && respon2 != '0' && respon1 == respon2){
            $('#respForm2').css('color','red');
        }else if(respon1 != '0' && respon3 != '0' && respon1 == respon3) {
            $('#respForm3').css('color', 'red');
        }else if(respon1 != '0' && respon4 != '0' && respon1 == respon4){
            $('#respForm4').css('color', 'red');
        }else if(respon2 != '0' && respon3 != '0' && respon2 == respon3) {
            $('#respForm3').css('color', 'red');
        }else if(respon2 != '0' && respon4 != '0' && respon2 == respon4){
            $('#respForm4').css('color', 'red');
        }else if(respon3 != '0' && respon4 != '0' && respon3 == respon4) {
            $('#respForm4').css('color', 'red');
        }else{
            $('#modalValide').addClass('disabled');
            var fst = 1;
            $.ajax({
                url: ppil + '/ue/creer/form',
                type: 'post',
                data: {'nom': nom, 'fst': fst, 'resp1': respon1, 'resp2': respon2, 'resp3': respon3, 'resp4': respon4},
                success: function (res) {
                    if (res != undefined && res[0] == 'true') {
                        $('#modalAjouterForm').modal('toggle');
                        $('#messageTitre').text('Succès');
                        $('#message').text('La formation a bien été créée.');
                        $('#modalDemandeEffectuee').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $('#modalValide').removeClass( 'disabled' );
                        $.ajax({
                            url: ppil + '/ue/actu',
                            type: 'post',
                            success: function (res) {
                                if (res != undefined) {
                                    var html = "";
                                    var i = 0;
                                    res.forEach(function (element) {
                                        if(i == 0){
                                            html += "<option selected value='" + element +"'>" + element + "</option>";
                                        }else{
                                            html += "<option>" + element + "</option>";
                                        }

                                    });
                                    $('#selectForm').empty();
                                    $('#selectForm').html(html);
                                    $('#nomForm').val("");
                                    $('#nomForm').css("color","black");
                                    $('#respForm2').css('color','black');
                                    $('#respForm3').css('color','black');
                                    $('#respForm4').css('color','black');
                                    recupererUE(ppil);
                                }

                            },
                            xhrFields: {
                                withCredentials: true
                            },
                            crossDomain: true
                        });

                    } else {
                        $('#modalAjouterForm').modal('toggle');
                        $('#messageTitre').text('Erreur');
                        $('#message').text('Problème lors de la création de la formation.');
                        $('#modalDemandeEffectuee').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $('#nomForm').val("");
                        $('#nomForm').css("color","black");
                        $('#nomForm').css("color","black");
                        $('#respForm2').css('color','black');
                        $('#respForm3').css('color','black');
                        $('#respForm4').css('color','black');
                        $('#modalValide').removeClass( 'disabled' );
                    }
                },
                xhrFields: {
                    withCredentials: true
                },
                crossDomain: true
            })
        }

    }



}

function supprimer(ue) {
    $.ajax({
        url: ppil + '/ue/supprimer',
        type: 'post',
        data: {'id': ue},
        success: function (res) {
            if (res != undefined && res[0] == 'true') {
                $.ajax({
                    url: ppil + '/ue/actu',
                    type: 'post',
                    success: function (res) {
                        if (res != undefined) {
                            var html = "";
                            var i = 0;
                            res.forEach(function (element) {
                                if (i == 0) {
                                    html += "<option selected value='" + element + "'>" + element + "</option>";
                                    i++;
                                } else {
                                    html += "<option>" + element + "</option>";
                                }

                            });
                            $('#selectForm').html(html);
                            $('#messageTitre').text('Succès');
                            $('#message').text('Cette UE a bien été supprimé.');
                            $('#modalDemandeEffectuee').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            recupererUE(ppil);
                        }

                    },
                    xhrFields: {
                        withCredentials: true
                    },
                    crossDomain: true
                });

            } else {
                recupererUE(ppil);
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

function enseignant() {
    $.ajax({
        url: ppil + '/ue/enseignant',
        type: 'get',
        success: function (res) {
            var html = "<option selected value='0'>aucun</option>";
            if (res != undefined) {
                res.forEach(function (element) {
                    html += "<option value='"+element.mail+"'>" + element.nom + " " + element.prenom + "</option>";
                })
            }
            $('#resp').html(html);
            $('#respForm1').html(html);
            $('#respForm2').html(html);
            $('#respForm3').html(html);
            $('#respForm4').html(html);
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    })

}

function ajouterUE() {
    var nomUE = $('#nomUEForm').val();
    if(nomUE == "" || nomUE == "Obligatoire"){
        $('#nomUEForm').val("Obligatoire");
        $('#nomUEForm').css("color","red");
    }else{
        $('#modalValideUE').addClass('disabled');
        var heureCM = $('#heureCMForm').val();
        var nbGroupeTD = $('#nbGroupeTDForm').val();
        var heureTD = $('#heureTDForm').val();
        var nbGroupeTP = $('#nbGroupeTPForm').val();
        var heureTP = $('#heureTPForm').val();
        var nbGroupeEI = $('#nbGroupeEIForm').val();
        var heureEI = $('#heureEIForm').val();
        var respon = $('#resp option:selected').val();
        $.ajax({
            url: ppil + '/ue/ajout/ue',
            type: 'post',
            data: {'form' : value,'nom' : nomUE, 'heureCM' : heureCM, 'heureTD' : heureTD, 'heureTP' : heureTP, 'heureEI' : heureEI,
            'nbGroupeTD' : nbGroupeTD, 'nbGroupeTP' : nbGroupeTP , 'nbGroupeEI' : nbGroupeEI, 'resp' : respon},
            success: function (res) {
                $('#modalAjouterUE').modal('toggle');
                if (res != undefined && res[0] == 'true') {
                    $('#messageTitre').text('Succès');
                    $('#message').text('UE ajouté.');
                    $('#modalDemandeEffectuee').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }else{
                    $('#messageTitre').text('Erreur');
                    $('#message').text('Problème lors de l\'ajout.');
                    $('#modalDemandeEffectuee').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                }
                recupererUE(ppil);
                $('#nomUEForm').css("color","black");
                $('#nomUEForm').val("");
                $('#heureCMForm').val(0);
                $('#nbGroupeTDForm').val(0);
                $('#heureTDForm').val(0);
                $('#nbGroupeTPForm').val(0);
                $('#heureTPForm').val(0);
                $('#nbGroupeEIForm').val(0);
                $('#heureEIForm').val(0);
                $('#modalValideUE').removeClass('disabled');

            },
            xhrFields: {
                withCredentials: true
            },
            crossDomain: true
        });
    }
}

function supprimerForm() {
    $('#deleteForm').addClass('disabled');
    $.ajax({
        url: ppil + '/supprimer',
        type: 'post',
        data: {'nom': value},
        success: function (res) {
            $('#delete').modal('toggle');
            if (res != undefined && res[0] == 'true') {
                $('#messageTitre').text('Succès');
                $('#message').text('Cette formation a bien été supprimé.');
                $('#modalDemandeEffectuee').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $.ajax({
                    url: ppil + '/ue/actu',
                    type: 'post',
                    success: function (res) {
                        if (res != undefined) {
                            var html = "";
                            var i = 0;
                            res.forEach(function (element) {
                                if(i == 0){
                                    html += "<option selected value='" + element +"'>" + element + "</option>";
                                    value = element;
                                    i++;
                                }else{
                                    html += "<option>" + element + "</option>";
                                }

                            });
                            $('#selectForm').empty();
                            $('#selectForm').html(html);
                            recupererUE(ppil);
                        }
                    },
                    xhrFields: {
                        withCredentials: true
                    },
                    crossDomain: true
                });

                $('#deleteForm').removeClass('disabled');
            } else {
                $('#delete').modal('toggle');
                $('#messageTitre').text('Erreur');
                $('#message').text('Problème lors de la suppression de cette formation.');
                $('#modalDemandeEffectuee').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#deleteForm').removeClass('disabled');
            }
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    });
}

function modifForm() {
    enseignant();
    $('#modalValide').addClass('hidden');
    $('#modalValideModif').removeClass('hidden');
    $.ajax({
        url: ppil + '/info',
        type: 'post',
        data: { 'nom' : value},
        success: function (res) {
            $('#nomForm').val(value);
            var i = 0;
            res.forEach(function (element) {
                var mail = element.enseignant;
                switch (i){
                    case 0:
                        //console.log(mail);
                        //var tmp = $('#respForm1 option:selected').val();
                        //console.log(tmp);
                        $('#respForm1').val(mail);
                        i++;
                        break;
                    case 1:
                        $('#respForm2').val(mail);
                        i++;
                        break;
                    case 2:
                        $('#respForm3').val(mail);
                        i++;
                        break;
                    case 3:
                        $('#respForm4').val(mail);
                        i++;
                        break;

                }

            });
            $('#modalAjouterForm').modal({
                backdrop: 'static',
                keyboard: false
            });

        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    });
}

function modifFormBase() {
    var nom = $('#nomForm').val();
    if(nom == "" || nom == "Obligatoire"){
        $('#nomForm').val("Obligatoire");
        $('#nomForm').css("color","red");
    }else {
        var respon1 = $('#respForm1 option:selected').val();
        var respon2 = $('#respForm2 option:selected').val();
        var respon3 = $('#respForm3 option:selected').val();
        var respon4 = $('#respForm4 option:selected').val();
        if (respon1 != '0' && respon2 != '0' && respon1 == respon2) {
            $('#respForm2').css('color', 'red');
        } else if (respon1 != '0' && respon3 != '0' && respon1 == respon3) {
            $('#respForm3').css('color', 'red');
        } else if (respon1 != '0' && respon4 != '0' && respon1 == respon4) {
            $('#respForm4').css('color', 'red');
        } else if (respon2 != '0' && respon3 != '0' && respon2 == respon3) {
            $('#respForm3').css('color', 'red');
        } else if (respon2 != '0' && respon4 != '0' && respon2 == respon4) {
            $('#respForm4').css('color', 'red');
        } else if (respon3 != '0' && respon4 != '0' && respon3 == respon4) {
            $('#respForm4').css('color', 'red');
        } else {
            $('#modalValideModif').addClass('disabled');
            var fst = 1;
            $.ajax({
                url: ppil + '/ue/modif/form',
                type: 'post',
                data: {'nom': nom, 'ancienNom' : value, 'resp1': respon1, 'resp2': respon2, 'resp3': respon3, 'resp4': respon4},
                success: function (res) {
                    if (res != undefined && res[0] == 'true') {
                        $('#modalValideModif').addClass('hidden');
                        $('#modalValide').removeClass('hidden');
                        $('#modalAjouterForm').modal('toggle');
                        $('#messageTitre').text('Succès');
                        $('#message').text('La formation a bien été modifiée.');
                        $('#modalDemandeEffectuee').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $('#modalValideModif').removeClass('disabled');
                        $.ajax({
                            url: ppil + '/ue/actu',
                            type: 'post',
                            success: function (res) {
                                if (res != undefined) {
                                    var html = "";
                                    var i = 0;
                                    res.forEach(function (element) {
                                        if (i == 0) {
                                            html += "<option selected value='" + element + "'>" + element + "</option>";
                                            i++;
                                        } else {
                                            html += "<option>" + element + "</option>";
                                        }

                                    });
                                    $('#selectForm').html(html);
                                    recupererUE(ppil);
                                    $('#nomForm').val("");
                                    $('#nomForm').css("color", "black");
                                    $('#respForm2').css('color', 'black');
                                    $('#respForm3').css('color', 'black');
                                    $('#respForm4').css('color', 'black');

                                }

                            },
                            xhrFields: {
                                withCredentials: true
                            },
                            crossDomain: true
                        });

                    } else {
                        $('#modalValideModif').addClass('hidden');
                        $('#modalValide').removeClass('hidden');
                        $('#modalAjouterForm').modal('toggle');
                        $('#messageTitre').text('Erreur');
                        $('#message').text('Problème lors de la modification de la formation.');
                        $('#modalDemandeEffectuee').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $('#nomForm').val("");
                        $('#nomForm').css("color", "black");
                        $('#nomForm').css("color", "black");
                        $('#respForm2').css('color', 'black');
                        $('#respForm3').css('color', 'black');
                        $('#respForm4').css('color', 'black');
                        $('#modalValideModif').removeClass('disabled');
                    }
                },
                xhrFields: {
                    withCredentials: true
                },
                crossDomain: true
            })
        }
    }

}





