$(function() {

	$('#retrievalName').attr('disabled', 'disabled');
	$('#retrievalKana').attr('disabled', 'disabled');

	$("input[name='name_f_retrieval'], input[name='name_s_retrieval']").on('click change blur keyup paste', function(event) {
		console.log("TEST");
		if ($("input[name='name_f_retrieval']").val() == "" && $("input[name='name_s_retrieval']").val() == ""  ) {
			$('#retrievalName').attr('disabled', 'disabled');
		} else {
			$('#retrievalName').removeAttr('disabled');
		}
	});

	$("input[name='kana_f_retrieval'], input[name='kana_s_retrieval']").on('click change blur keyup paste', function(event) {
		if ($("input[name='kana_f_retrieval']").val() == "" && $("input[name='kana_s_retrieval']").val() == ""  ) {
			$('#retrievalKana').attr('disabled', 'disabled');
		} else {
			$('#retrievalKana').removeAttr('disabled');
		}
	});

});
