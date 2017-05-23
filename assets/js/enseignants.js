function select(num) {
    var lignes = document.getElementsByName("ligne");
    var iter = 0;

    lignes.forEach(function(element) {
        if(iter == num) {
            element.classList.add("active");
        } else {
            element.classList.remove("active");
        }
        iter++;
    });

}

function calculHeures() {
    var volCourants = document.getElementsByName("volCourant");
    var volMins = document.getElementsByName("volMin");
    var volFSTs = document.getElementsByName("volFST");

    var tailleC = volCourants.length;
    var tailleM = volMins.length;
    var tailleF = volFSTs.length;

    if(tailleC == tailleF && tailleC == tailleM) {
        for(var iter=0; iter<tailleC; iter++) {
            var volC = "#volCourant"+iter;
            var volF = "#volFST"+iter;
            var volM = "#volMin"+iter;


            if(volCourants.item(iter) == volMins.item(iter)) {
                $(volC).css("color", "green");
            } else {
                $(volC).css("color", "red");
            }
            if (volFSTs.item(iter) == volMins.item(iter)) {
                $(volF).css("color", "green");
            } else {
                $(volF).css("color", "red");
            }
        }
    }
}



function exporter(lien_exporter){
	$('#exporter').click(function(){
		window.location = lien_exporter;

	});
}

