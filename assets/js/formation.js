var ppil;
function recupererUE(lien) {
    var value = $('#selectForm option:selected').val();
    ppil = lien;
    $.ajax({
        url: lien  ,
        type: 'post',
        data: { 'nom' : value} ,
        dataType: 'json',
        success: function (tab) {
            if(tab.length > 0){
                var html="<ul class='nav'>";
                for(var i = 0; i < tab.length ; i++){
                    html = html + "<li><a id='" + tab[i] + "' onclick='choixUE(this)'>" + tab[++i] + "</a></li>";
                }
                html = html + "</ul>";
                $('#tableUE').html(html);
                $('#nomUE').text(tab[1]);
                choixUE($("#"+tab[0]));
            }
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    });
}

function choixUE(element) {
    $('#nomUE').text($(element).value);
    var id = $(element).attr('id');
    console.log(id);
    $.ajax({
        url: ppil + 'infos',
        type: 'post',
        data: { 'id' : id },
        success: function (tab) {
            if(tab != undefined){
                    $('#heureAffecteTD').val(tab.heuresTD);
                    $('#heureAttenduTD').val(tab.prevision_heuresTD);
                    $('#nbGroupeAffecteTD').val(tab.groupeTD);
                    $('#nbGroupeAttenduTD').val(tab.prevision_groupeTD);
                    if(tab.heuresTD == tab.prevision_heuresTD){
                        $('#heureAffecteTD').css("color","green");
                    }else{
                        $('#heureAffecteTD').css("color","red");
                    }
                    if(tab.groupeTD == tab.prevision_groupeTD){
                        $('#nbGroupeAffecteTD').css("color","green");
                    }else{
                        $('#nbGroupeAffecteTD').css("color","red");
                    }

            }
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    })
}





