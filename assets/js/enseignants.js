function select(num) {
    //alert("coucou "+ligne);

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
