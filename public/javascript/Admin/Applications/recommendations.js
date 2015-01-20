$(document).ready(function() {

	var recommCount = $('input[name=recomms]:checked').val();
	$("#recomm1").hide();
	$("#recomm2").hide();
	$("#submit").hide();

	if (recommCount == '0')
	{
		$("#submit").show();
		$("#recomm1").hide();
		$("#recomm2").hide();
	}

	if (recommCount == '1')
	{
		$("#recomm1").show();
		$("#recomm2").hide();
		$("#submit").show();
	}

	if (recommCount == '2')
	{
		$("#recomm1").show();
		$("#recomm2").show();			
		$("#submit").show();
	}

	$('input[name=recomms]').click(function() {
		var recommCount = $('input[name=recomms]:checked').val();

		if (recommCount == '0')
		{
			$("input[type=text]").val("");
			$("#recomm1 input:radio").prop('checked', false);
			$("#recomm2 input:radio").prop('checked', false);
			$("#submit").show('slow');
			$("#recomm1").hide('slow');
			$("#recomm2").hide('slow');
		}

		if (recommCount == '1')
		{
			$("#recomm2 input:radio").prop('checked', false);
			$("#recomm2 input[type=text]").val("");
			$("#recomm1").show('slow');
			$("#recomm2").hide('slow');
			$("#submit").show();
		}

		if (recommCount == '2')
		{
			$("#recomm1").show('slow');
			$("#recomm2").show('slow');			
			$("#submit").show('slow');
		}
	});
});