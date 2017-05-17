function valider(lien){
	$("form#form_interventions").each(function() {
		$(this).submit(function(e){
			e.preventDefault();
		});

		$(this).find('button#supprimer').click(function(){
			$(this).toggleClass('hidden');
			$(this).parent().find('button#refuse').val(false);
			$(this).parent().find('button#annuler').toggleClass('hidden');
			$(this).val(true);
		});

		$(this).find('button#annuler').click(function(){
			$(this).toggleClass('hidden');
			$(this).parent().find('button#supprimer').val(false);
			$(this).parent().find('button#supprimer').toggleClass('hidden');
		});
	});

	$('#appliquer').click(function(){
		$("form#form_interventions").each(function(){
			var supprime = $(this).find('button#supprimer').val();
			var id = $(this).find('input#id').val();
			var tr = $("tr#"+id);
			var infos = [
				tr.find('input#heuresCM').val(),
				tr.find('input#heuresTD').val(),
				tr.find('input#heuresTP').val(),
				tr.find('input#heuresEI').val(),
				tr.find('input#groupeTD').val(),
				tr.find('input#groupeTP').val(),
				tr.find('input#groupeEI').val()
			];
			$.ajax({
				url : lien,
				type: 'post',
				data: { 'id': id, 'heuresCM': infos[0], 'heuresTD': infos[1], 'heuresTP': infos[2], 'heuresEI': infos[3], 'groupeTD': infos[4], 'groupeTP': infos[5], 'groupeEI' : infos[6], 'supprime' : supprime},
				success: function(error){
					if(error){
						//$("div#erreur").removeClass('hidden');
						tr.addClass("danger");
					} else {
						tr.removeClass("danger");
					}
					if(supprime == 'true'){
						tr.remove();
					}
				}
			});

		});
	});
}
