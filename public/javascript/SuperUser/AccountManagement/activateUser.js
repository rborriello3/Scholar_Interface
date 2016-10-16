$(document).ready(function() 
{
	if($('#committee').is(':checked')) 
	{ 
		$("#gradeGroup").css('display');
	}	

	else
	{
		$("#gradeGroup").css('display', 'none');
	}	

	$("#committee").click(function()
	{
		if($('#committee').is(':checked')) 
		{ 
			$("#gradeGroup").show("fast")
		}	

		else
		{
			$("#gradeGroup").hide("fast");
		}	
	});
});
