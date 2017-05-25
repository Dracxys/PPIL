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

function exporter(lien_exporter){
	$('#exporter').click(function(){
		window.location = lien_exporter;
	});
}

function importer(lien_importer){
	$("#input_csv").change(function() {
		$("#form_input_csv").submit();
	});

	$("#form_input_csv").submit(function(e){
		e.preventDefault();
		$.ajax({
            url: lien_importer,
            type: 'post',
            data: new FormData(this),
			processData: false,
			contentType: false,
            success: function (json) {
				if(json.error == true){
					$("#import_succes").addClass('hidden');
					if(json.messages.error){
						$("#import_erreur_autre").removeClass('hidden');
					}
					if(json.messages.size){
						$("#import_erreur_taille").removeClass('hidden');
					}
					if(json.messages.extension){
						$("#import_erreur_extension").removeClass('hidden');
					}
					if(json.messages.parse){
						$("#import_erreur_parse").removeClass('hidden');
					}
				} else {
					$("#import_succes").removeClass('hidden');
					$("#import_erreur_autre").addClass('hidden');
					$("#import_erreur_taille").addClass('hidden');
					$("#import_erreur_extension").addClass('hidden');
					$("#import_erreur_parse").addClass('hidden');
				}
            }
		});

	});


	$('#importer').click(function(){
		$('#input_csv').click();
		//window.location = lien_importer;
	});
}

function setup(){
	choixUE();
    listIntervenant();
    boutonValidationModif();
    $('#selectUE').change(function() {
        choixUE();
        listIntervenant();
        boutonValidationModif();
    });
    $('#erreur').hide();
}


function choixUE() {
    id_UE = $('#selectUE option:selected').val();
	if(id_UE != undefined){
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
		});
	}
}

function modifUE() {
	if(id_UE != undefined){

        $('#valider').addClass("disabled");

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
                            $('#message').text('Les modifications ont bien été prises en compte.');
                            $('#modalDemandeEffectuee').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            choixUE();
                            $('#valider').removeClass("disabled");
                        } else {
                            $('#messageTitre').text('Erreur');
                            $('#message').text('Les modifications n\'ont pas pu être sauvegardées.');
                            $('#modalDemandeEffectuee').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            $('#valider').removeClass("disabled");
                        }
                    }

                },
                xhrFields: {
                    withCredentials: true
                },
                crossDomain: true
            });
        }
    }
}

function listIntervenant() {
    id_UE = $('#selectUE option:selected').val();
	if(id_UE != undefined){
		$.ajax({
			url: ppil + '/listIntervenant',
			type: 'post',
			data: {'id': id_UE},
			success: function (tab) {
				$('#tab').empty();
				if (tab != undefined){
					var html;
					var line = 0;
					for (var i = 0; i < tab.length; i = i + 10){
						html += "<tr id='tab' class=''>"
							+"<th class='col-md-3 text-center'>" + tab[0+i] + " " + tab[1 + i] + "</th>"
							+"<th class='col-md-1'>" + "<input type='number' id='hcm"+ line +"' class='form-control' value=" + tab[2+i] + " min='0'/></th>"
							+"<th class='col-md-1'>" + "<input type='number' id='nbtd"+ line +"' class='form-control' value=" + tab[3+i] + " min='0'/></th>"
							+"<th class='col-md-1'>" + "<input type='number' id='htd"+ line +"' class='form-control' value=" + tab[4+i] + " min='0'/></th>"
							+"<th class='col-md-1'>" + "<input type='number' id='nbtp"+ line +"' class='form-control' value=" + tab[5+i] + " min='0'/></th>"
							+"<th class='col-md-1'>" + "<input type='number' id='htp"+ line +"' class='form-control' value=" + tab[6+i] + " min='0'/></th>"
							+"<th class='col-md-1'>" + "<input type='number' id='nbei"+ line +"' class='form-control' value=" + tab[7+i] + " min='0'/></th>"
							+"<th class='col-md-1'>" + "<input type='number' id='hei"+ line +"' class='form-control' value=" + tab[8+i] + " min='0'/></th>"
							+"<th class='col-md-3 text-center'>"
							+"<button type='button' class='btn btn-primary' onclick='modifIntervenantUE(\"" + tab[9+i] + '\",' + line +")' id='validerHeuresIntervenantUE'>Valider</button>"
							+"<button type='button' class='btn btn-danger' onclick='boutonSuppressionEnseignant(\"" + tab[9+i] +"\")' id='supprimerIntervenantUE'>Supprimer</button>"
							+"</th>"
							+"</tr>";
						line++;
					}
					$('#tableau').html(html);
				}

			}, xhrFields: {
				withCredentials: true
			},
			crossDomain: true
		});
	}
}

function boutonValidationModif() {
    id_UE = $('#selectUE option:selected').val();
	if(id_UE != undefined){
		$.ajax({
			url: ppil + '/boutonModif',
			type: 'post',
			dataType: 'json',
			data: {'id': id_UE},
			success: function (element) {
				if (element != undefined) {
					if (element == true) {
						listeAjoutEnseignant();
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
                        $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", false);
                        $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", false);
						var html =  "<button type='button' class='btn btn-primary center-block' onclick='modifUE()' id='valider'>Valider</button>";
						$('#ajoutEnseignant').removeClass("hidden");
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
                        $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", true);
                        $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", true);
						$('#ajoutEnseignant').addClass("hidden");
					}
				}
			}, xhrFields: {
				withCredentials: true
			},
			crossDomain: true
		});
	}
}

function modifIntervenantUE(mail, line) {
    id_UE = $('#selectUE option:selected').val();
    $(function () {
        $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", true);
        $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", true);
    });
    if (id_UE != undefined) {

        var hcm = $('#hcm' + line).val();
        var nbtd = $('#nbtd' + line).val();
        var htd = $('#htd' + line).val();
        var nbtp = $('#nbtp' + line).val();
        var htp = $('#htp' + line).val();
        var nbei = $('#nbei' + line).val();
        var hei = $('#hei' + line).val();
        $.ajax({
            url: ppil + '/modifHeureEnseignant',
            type: 'post',
            data: {
                'id': id_UE, 'mail': mail, 'heureCM': hcm, 'nbGroupeTD': nbtd, 'heureTD': htd, 'nbGroupeTP': nbtp,
                'heureTP': htp, 'nbGroupeEI': nbei, 'heureEI': hei
            },
            success: function (element) {
                if (element != undefined) {
                    if (element[0] == 'true') {
                        $('#messageTitre').text('Succès');
                        $('#message').text('Les modifications ont bien été prises en compte.');
                        $('#modalDemandeEffectuee').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        choixUE();
                        listIntervenant();
                        $(function () {
                            $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", false);
                            $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", false);
                        });
                    } else if (element[0] == 'Depassement') {
                        $('#messageTitre').text('Erreur');
                        $('#message').text('Les modifications n\'ont pas pu être sauvegardées, vos modifications feraient dépasser les prévisions en heures et en groupe pour cet UE.');
                        $('#modalDemandeEffectuee').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $(function () {
                            $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", false);
                            $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", false);
                        });
                    } else {
                        $('#messageTitre').text('Erreur');
                        $('#message').text('Les modifications n\'ont pas pu être sauvegardées.');
                        $('#modalDemandeEffectuee').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $(function () {
                            $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", false);
                            $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", false);
                        });
                    }
                }
            }, xhrFields: {
                withCredentials: true
            },
            crossDomain: true
        });
    }
}

	function boutonSuppressionEnseignant(mail) {
		id_UE = $('#selectUE option:selected').val();
		$(function () {
            $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", true);
            $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", true);
        });
		$.ajax({
			url: ppil + '/suppressionEnseignant',
			type: 'post',
			data: {'id': id_UE, 'mail': mail},
			success: function (element) {
				if (element != undefined) {
					if (element == true) {
						$('#messageTitre').text('Succès');
						$('#message').text('Les modifications ont bien été prises en compte.');
						$('#modalDemandeEffectuee').modal({
							backdrop: 'static',
							keyboard: false
						});
						choixUE();
						listIntervenant();
						listeAjoutEnseignant();
                        $(function () {
                            $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", false);
                            $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", false);
                        });
					} else {
                        $("#validerHeuresIntervenantUE").removeClass("disabled");
                        $("#supprimerIntervenantUE").removeClass("disabled");
						$('#messageTitre').text('Erreur');
						$('#message').text('Les modifications n\'ont pas pu être sauvegardées.');
						$('#modalDemandeEffectuee').modal({
							backdrop: 'static',
							keyboard: false
						});
                        $(function () {
                            $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", false);
                            $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", false);
                        });
					}
				}
			}, xhrFields: {
				withCredentials: true
			},
			crossDomain: true
		});
}

function listeAjoutEnseignant() {
    id_UE = $('#selectUE option:selected').val();
	if(id_UE != undefined){
		$.ajax({
			url: ppil + '/ajoutEnseignant',
			type: 'post',
			data: {'id': id_UE, },
			success: function (element) {
				if(element != undefined){
					$('#tableUEAjoutEnseignant').empty();
					var html;
					for (var i = 0; i < element.length; i = i + 3){
						html += "<tr >"
                            +"<th id='nom'  class='text-center'>" + element[i] +"</th>"
                            +"<th id='prenom' class='text-center'>" + element[i + 1] +"</th>"
                            +"<th id='mail' class='text-center'>" + element[i + 2] +"</th>"
                            +"<th>"
                            +"<form class='form-inline text-center ' method='post' action='' id='form_ajout_enseignant'>"
                            +"<div class='form-group '>"
                            +"<button  name='selectionner' class='btn btn-primary ' id='selectionner' value='false' type='submit'>Sélectionner</button>"
                            +"<button  name='annuler' class='btn btn-primary hidden' id='annuler' value='false' type='submit'>Annuler</button>"
                            +"<input type='hidden' id='EnseignantSelected' value='false' />"
                            +"</div>"
                            +"</form>"
                            +"</th>"
                            +"</tr>";
					}
					$('#tableUEAjoutEnseignant').html(html);

					$("form#form_ajout_enseignant").each(function() {
						$(this).submit(function(e){
							e.preventDefault();
						});

						$(this).find('button#selectionner').click(function(){
							$(this).parent().find('input#EnseignantSelected').val(true);
							$(this).toggleClass('hidden');
							$(this).parent().find('button#annuler').toggleClass('hidden');
							$('button#modal_demande').prop('disabled', false);
							$('button#modal_demande').addClass('btn-primary')
						});

						$(this).find('button#annuler').click(function(){
							$(this).parent().find('input#EnseignantSelected').val(false);
							$(this).toggleClass('hidden');
							$(this).parent().find('button#selectionner').toggleClass('hidden');
						});
					});
				}
			}, xhrFields: {
				withCredentials: true
			},
			crossDomain: true
		});
	}
}

function addEnseignants() {
    id_UE = $('#selectUE option:selected').val();
	if(id_UE != undefined){
		var tab = [];
		$('#tableUEAjoutEnseignant tr').each(function() {
			if($(this).find('input#EnseignantSelected').val() == "true"){
				tab.push($(this).find('th#mail').text());
			}
		});
		$("#ajoutEnseignant").addClass("disabled");
        $("#valider").addClass("disabled");
        $("#importer").addClass("disabled");
        $("#exporter").addClass("disabled");
        $("#modifierUE").addClass("disabled");
        $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", false);
        $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", false);
        $.ajax({
			url: ppil + '/addInterventions',
			type: 'post',
			data: {'id': id_UE, 'mail': tab},
			success: function (res) {
				if (res != undefined) {
					if (res == true) {
						$('#messageTitre').text('Succès');
						$('#message').text('Les modifications ont bien été prises en compte.');
						$('#modalDemandeEffectuee').modal({
							backdrop: 'static',
							keyboard: false
						});
						listIntervenant();
						listeAjoutEnseignant();
                        $("#ajoutEnseignant").removeClass("disabled");
                        $("#valider").removeClass("disabled");
                        $("#importer").removeClass("disabled");
                        $("#exporter").removeClass("disabled");
                        $("#modifierUE").removeClass("disabled");
                        $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", true);
                        $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", true);
					} else {
						$('#messageTitre').text('Erreur');
						$('#message').text('Les modifications n\'ont pas pu être sauvegardées.');
						$('#modalDemandeEffectuee').modal({
							backdrop: 'static',
							keyboard: false
						});
                        $("#ajoutEnseignant").removeClass("disabled");
                        $("#valider").removeClass("disabled");
                        $("#importer").removeClass("disabled");
                        $("#exporter").removeClass("disabled");
                        $("#modifierUE").removeClass("disabled");
                        $('[id=supprimerIntervenantUE]').slice(0).prop("disabled", true);
                        $('[id=validerHeuresIntervenantUE]').slice(0).prop("disabled", true);
					}
				}
			}, xhrFields: {
				withCredentials: true
			},
			crossDomain: true
		});
	}
}

function modifierUE(){
    $.ajax({
        url: ppil + '/infoRespUE',
        type: 'post',
        data: {'id': id_UE},
        success: function (tab) {
            if (tab != undefined){
            	var i = 0;
            	var html = "";
                var j = 0
            	for(j; j < tab.length ; j++){
					if(i == 0){
						if(tab[j] == '0'){
							html += "<option selected value='0'>aucun</option>";
							j++;
						}else{
                            html += "<option value='0'>aucun</option>";
							html += "<option selected value='" + tab[j] + "'>" + tab[++j] + "</option>";
						}
						i++;
					}else{
                        html += "<option value='" + tab[j] + "'>" + tab[++j] + "</option>";
					}
                }
            	$('#respForm1').html(html);
				$('#nomUE').val($('#selectUE option:selected').text());
                $('#modalModifierUE').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }

        }, xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    });
}

function validerModifierUE() {
    $('#modalValideMoifUE').addClass('disabled');
    var nom = $('#nomUE').val();
	var respon = $('#respForm1 option:selected').val();
    $.ajax({
        url: ppil + '/modifUE',
        type: 'post',
        data: {'id': id_UE, 'nom' : nom, 'resp' : respon},
        success: function (tab) {
            if (tab != undefined){
				if(tab[0] == 'true'){
                    $('#messageTitre').text('Succès');
                    $('#message').text('Les modifications ont bien été prises en compte.');
                    $('#modalDemandeEffectuee').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#modalValideMoifUE').removeClass('disabled');
                    $('#modalModifierUE').modal('toggle');
                    listIntervenant();
                    listeAjoutEnseignant();
				}else{
                    $('#modalValideMoifUE').removeClass('disabled');
                    $('#messageTitre').text('Erreur');
                    $('#message').text('Les modifications n\'ont pas pu être sauvegardées.');
                    $('#modalDemandeEffectuee').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#modalModifierUE').modal('toggle');
                    listIntervenant();
                    listeAjoutEnseignant();
				}
            }

        }, xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    });

}
