var ppil;
function recupererUE(lien) {
    var value = $('#selectForm option:selected').val();
    $('#nomFormation').text('Volume Horaire ' + value);
    ppil = lien;
    $.ajax({
        url: lien,
        type: 'post',
        data: {'nom': value},
        dataType: 'json',
        success: function (tab) {
            if (tab.length > 0) {
                var html = "<ul class='nav'>";
                for (var i = 0; i < tab.length; i++) {
                    html = html + "<li><a id='" + tab[i] + "' onclick='choixUE(this)'>" + tab[++i] + "</a></li>";
                }
                html = html + "</ul>";
                $('#tableUE').html(html);
                $('#nomUE').text(tab[1]);
                choixUE($("#" + tab[0]));
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
    $('#nomUE').text($(element).text());
    var id = $(element).attr('id');
    $.ajax({
        url: ppil + 'infos',
        type: 'post',
        data: {'id': id},
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

function totalLicence(nom) {
    console.log(nom);
    $.ajax({
        url: ppil + 'total',
        type: 'post',
        data: {'nom': nom},
        success: function (tab) {
            if (tab != undefined) {
                $('#volumeAttenduCM').text(tab[0]);
                $('#volumeAffecteCM').text(tab[1]);
                $('#volumeAttenduTD').text(tab[2]);
                $('#volumeAffecteTD').text(tab[3]);
                $('#volumeAttenduTP').text(tab[4]);
                $('#volumeAffecteTP').text(tab[5]);
                $('#volumeAttenduEI').text(tab[6]);
                $('#volumeAffecteEI').text(tab[7]);

                if(tab[0] == tab[1]){
                    $('#volumeAffecteCM').css("color", "green");
                }else{
                    $('#volumeAffecteCM').css("color", "red");
                }

                if(tab[2] == tab[3]){
                    $('#volumeAffecteTD').css("color", "green");
                }else{
                    $('#volumeAffecteTD').css("color", "red");
                }

                if(tab[4] == tab[5]){
                    $('#volumeAffecteTP').css("color", "green");
                }else{
                    $('#volumeAffecteTP').css("color", "red");
                }

                if(tab[6] == tab[7]){
                    $('#volumeAffecteEI').css("color", "green");
                }else{
                    $('#volumeAffecteEI').css("color", "red");
                }



            }
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    })
}





