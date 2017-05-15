function valider(lien){
	$('form#valider').submit(function(e){
		$('#modalDemandeEffectuee').modal();

		e.preventDefault();
		$.ajax({
			url: lien,
			type: 'post',
			data: $('form#valider').serialize(),
			success:function(){
				$('#modalDemandeEffectuee').modal();
			}
		});
	});
}
