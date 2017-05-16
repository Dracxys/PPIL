
function recupererUE(lien) {
    var value = $('#selectForm option:selected').val();
    $.ajax({
        url: lien  ,
        type: 'post',
        data: { 'nom' : value} ,
        dataType: 'json',
        success: function (tab) {
            if(tab.length > 0){
                var html="";
                for(var i = 0; i < tab.length ; i++){
                    html = html + "<span id='ue' class='container'>" + tab[i] + "</span>";
                }
                $('#tableUE').html(html);
            }
        },
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true
    });
}

