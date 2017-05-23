var value;
var ppil;

function rechercheEnseignants(lien) {
	ppil = lien;
    $('#boutonRecherche').click(function() {
    	$('#boutonAnnulerRecherche').removeClass('disabled');
		value = $('#rechercheEnseignant').val();
		recupererEnseignants('/recherche');
	});

    $('#boutonAnnulerRecherche').click(function() {
    	$('#boutonAnnulerRecherche').addClass('disabled');
		recupererEnseignants('/annulerRecherche');
	});
}

function recupererEnseignants(lien) {
	$.ajax({
		url: ppil + '' + lien,
		type: 'post',
		data: {'chaine': value},
		dataType: 'json',
		success: function (tab) {
			if (tab.length > 0) {
				var html = "<table class='table table-bordered'><thead>" +
                      	"<tr>" +
                        "<th class=\"text-center\">Enseignant</th>" +
                        "<th class=\"text-center\">Statut</th>" +
                        "<th class=\"text-center\">Adresse Mail</th>" +
                        "<th class=\"text-center\">Photo</th>" +
                        "</tr>" +
                        "</thead><tbody>"

				for (var i=0; i<tab.length; i++) {
					html += "<tr>" +
					        "<th class=\"text-center\">" + tab[i][0] + " " + tab[i][1] + "</th>" +
					        "<th class=\"text-center\">" + tab[i][2] + "</th>" +
					        "<th class=\"text-center\">" + tab[i][3] + "</th>";
					if(tab[i][4] == null){
					    var photo = "/PPIL/assets/images/profil_pictures/default.jpg";
					    html += '<td class="center" ><img src="' + photo  + '" class="img-thumbnail" alt="Photo de profil" width="35" height="35"></td>';
					}else{
					    html += '<td class="center" ><img src=' + "/PPIL/" + tab[i][4]  +' class="img-thumbnail" alt="Photo de profil" width="35" height="35"></td>';
					}

					html += "</tr>";
				}

				html += "</tbody></table>";

				$('#tableEnseignants').html(html);
			} else {
				var html = "<label>Aucun enseignant</label>";
				$('#tableEnseignants').html(html);
			}
		},
		xhrFields: {
		    withCredentials: true
		},
		crossDomain: true
	});
}