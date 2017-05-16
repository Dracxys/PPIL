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
			var heuresCM = tr.find('input#heuresCM').val();
			var heuresTD = tr.find('input#heuresTD').val();
			var heuresTP = tr.find('input#heuresTP').val();
			var heuresEI = tr.find('input#heuresEI').val();
			var groupeTD = tr.find('input#groupeTD').val();
			var groupeTP = tr.find('input#groupeTP').val();
			var groupeEI = tr.find('input#groupeEI').val();
			$.ajax({
				url : lien,
				type: 'post',
				data: { 'id': id, 'heuresCM': heuresCM, 'heuresTD': heuresTD, 'heuresTP': heuresTP, 'heuresEI': heuresEI, 'groupeTD': groupeTD, 'groupeTP': groupeTP, 'groupeEI' : groupeEI, 'supprime' : supprime},
				success: function(e){
					if(supprime == 'true'){
						tr.remove();
					}
				}
			});

		});
	});
}
