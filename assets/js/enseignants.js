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

function exporter(lien_exporter){
	$('#exporter').click(function(){
		window.location = lien_exporter;

	});
}

