$(document).ready(function() {

	var selectType = $('input[name=selecttype]:checked').val();
	$("#ath_fresh").hide();
	$("#returning").hide();
	$("#submit").hide();
	
	if (selectType == '2')
	{
		$("#skip").hide();
		$("#ath_fresh").show();
		$("#submit").show();
	}

	if (selectType == '6')
	{
		$("#skip").hide();
		$("#returning").show();
		$("#submit").show();
	}

	$('input[name=selecttype]').click(function()
	{
		var selectType = $('input[name=selecttype]:checked').val();

		if (selectType == '2')
		{
			$("input[type=text]").val("");
			$("#skip").hide('slow');
			$("#returning").hide('slow');
			$("#ath_fresh").show('slow');
			$("#submit").show('slow');
		}

		if (selectType == '6')
		{
			$("input[type=text]").val("");
			$("#skip").hide('slow');
			$("#ath_fresh").hide('slow');
			$("#returning").show('slow');
			$("#submit").show('slow');
		}

		if (selectType == '')
		{
			$("input[type=text]").val("");
			$(".collegeInfo").hide('slow');	
			$("#submit").hide('slow');
			$("#skip").show('slow');
		}
	});

	$('#highschool').datepicker( 
    {
	    changeMonth: true,
       	changeYear: true,
       	showButtonPanel:  true,
       	altFormat: 'mm/y',
       	altField: "#realHighGrad",
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) 
        { 
	    	var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
           	var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });

    $('#college').datepicker( 
    {
	    changeMonth: true,
       	changeYear: true,
       	showButtonPanel:  true,
       	altFormat: 'mm/y',
       	altField: "#realCollGrad",
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) 
        { 
	    	var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
           	var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
        }
    });
});