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
			valide = (valide == 'true');
			refuse = (refuse == 'true')
			var id = $(this).find('input#id').val();
			if(valide || refuse){
				$.ajax({
					url : "journal/actionNotification",
					type: 'post',
					data: { 'valider': valide, 'refuser': refuse, 'id': id},
					success: function(tab){
						$('tr#'+id).remove();
						var notifications_count = $("form").length;
						var text = $('a#notifications_count');
						//affichage();
						if(notifications_count > 0){
							text.text('Journal');
							$('font#notifications_count_font').text('(' + notifications_count + ')' );
						} else {
							text.text('Journal');
							$('#tableNotifs').html("<label>Aucune Notification</label>");
						}
					}
				});
			}
		});
	});
});
