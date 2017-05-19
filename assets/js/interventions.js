function valider(lien, notification_exist){
	if(notification_exist){
		$("div#notification_exist").removeClass('hidden');
	}

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
			var id_UE = $(this).find('input#id_UE').val();
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
				data: { 'id': id, 'id_UE' : id_UE, 'heuresCM': infos[0], 'heuresTD': infos[1], 'heuresTP': infos[2], 'heuresEI': infos[3], 'groupeTD': infos[4], 'groupeTP': infos[5], 'groupeEI' : infos[6], 'supprime' : supprime},
				dataType: 'json',
				success: function(json){
					if(json.error && !json.notification_exist){
						//$("div#erreur").removeClass('hidden');
						tr.addClass("danger");
					} else {
						tr.removeClass("danger");
					}
					if(supprime == 'true'){
//						tr.remove();
					}
				}
			});

		});
		$('#modalDemandeEffectuee').modal({
			backdrop: 'static',
			keyboard: false
		});
	});
}

function ajouter(lien, lien_autre){
	$('#ajouter').click(function(){
		$('#modalAjouter').modal({
		});
	});

	$("div#erreur_ajout_autre").addClass('hidden');

	$('button#modal_demande').prop('disabled', true);

	$('#modal_ajout_autre').click(function(){
		$('form#form_ajout_autre').removeClass('hidden');
		$(this).addClass('disabled');
		var selection = $("button#selectionner ").filter(function(){
			return $(this).hasClass('hidden');
		});
		if(selection.length <= 0){
			$('button#modal_demande').prop('disabled', false);
			$('button#modal_demande').addClass('btn-primary')
		}

	});

	$('#modalAjouter').on('hidden.bs.modal', function () {
		$('form#form_ajout_autre').addClass('hidden');
		$('#modal_ajout_autre').removeClass('disabled');
	});

	$("form#form_ajout_ue").each(function() {
		$(this).submit(function(e){
			e.preventDefault();
		});

		$(this).find('button#selectionner').click(function(){
			$(this).toggleClass('hidden');
			$(this).parent().find('button#selectionner').val(false);
			$(this).parent().find('button#annuler').toggleClass('hidden');
			$(this).val(true);
			$('button#modal_demande').prop('disabled', false);
			$('button#modal_demande').addClass('btn-primary')
		});

		$(this).find('button#annuler').click(function(){
			$(this).toggleClass('hidden');
			$(this).parent().find('button#selectionner').val(false);
			$(this).parent().find('button#selectionner').toggleClass('hidden');
			var selection = $("button#selectionner ").filter(function(){
				return $(this).hasClass('hidden');
			});
			if(selection.length <= 0 && !$('#modal_ajout_autre').hasClass('disabled')){
				$("button#modal_demande").prop('disabled', true);
				$("button#modal_demande").removeClass('btn-primary');
			}
		});
	});

	$("button#modal_demande").click(function() {
		$("form#form_ajout_ue").each(function() {
			var id_UE = $(this).find('input#id_UE').val();
			var selectionner = $(this).find('button#selectionner').val();
			selectionner = (selectionner == 'true');
			var tr = $(this).parent().parent();

			if(selectionner){
				$.ajax({
					url : lien,
					type: 'post',
					data: { 'id_UE' : id_UE },
					dataType: 'json',
					success: function(json){
						tr.remove();
					}
				});
			}
		});

		$("form#form_ajout_autre").each(function() {
			var nom_nouvelle_UE = $(this).find('input#ajout_autre_ue');
			var nom_nouvelle_formation = $(this).find('input#ajout_autre_formation');
			if(nom_nouvelle_UE.val() != "" && nom_nouvelle_formation.val() != ""){
				$.ajax({
					url : lien_autre,
					type: 'post',
					data: { 'nom_UE' : nom_nouvelle_UE.val(), 'nom_formation' : nom_nouvelle_formation.val()},
					dataType: 'json',
					success: function(json){
						if(json.error){
							$("div#erreur_ajout_autre").removeClass('hidden');
						} else {
							$("div#erreur_ajout_autre").addClass('hidden');
							nom_nouvelle_UE.text("");
							nom_nouvelle_formation.text("");
						}
					}
				});
			}
		});
	});
}
