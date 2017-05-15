$(function(){
	$("form#form_actions").each(function() {
		$(this).submit(function(e){
			e.preventDefault();
		});

		$(this).find('div#validation').click(function(){
			$(this).toggleClass('hidden');
			$(this).parent().find('div#annulation').toggleClass('hidden');
			$('button#appliquer').prop('disabled', false);
			$('button#appliquer').addClass('btn-primary')
		});

		$(this).find('button#valide').click(function(){
			$(this).val(true);
			$(this).parent().find('button#refuse').val(false);
		});

		$(this).find('button#refuse').click(function(){
			$(this).val(true);
			$(this).parent().find('button#valide').val(false);
		});

		$(this).find('button#annule').click(function(){
			$(this).parent().toggleClass('hidden');
			$(this).parent().parent().find('button#refuse').val(false);
			$(this).parent().parent().find('button#valide').val(false);
			$(this).parent().parent().find('div#validation').toggleClass('hidden');
			var selection = $("form#form_actions div#validation").filter(function(){
				return $(this).hasClass('hidden');
			});
			console.log(selection);
			if(selection.length <= 0){
				$('button#appliquer').prop('disabled', true);
				$('button#appliquer').removeClass('btn-primary')
			}
		});

	});
	$('#appliquer').click(function(){
		$("form#form_actions").each(function() {
			var valide = $(this).find('button#valide').val();
			var refuse = $(this).find('button#refuse').val();
			var id = $(this).find('input#id').val();
			$.ajax({
				url : "journal/actionNotification",
				type: 'post',
				data: { 'valider': (valide == 'true'), 'refuser': (refuse == 'true'), 'id': id},
				success: function(){
					$('tr#'+id).remove();
				}
			});

		});
	});
});
