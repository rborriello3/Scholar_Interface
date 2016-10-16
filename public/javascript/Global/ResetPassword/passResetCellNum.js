$(document).ready(function() {

	// On first load or on redirect with errors, check if the value of cellCode is either 0 or 1
	var cellphone = $('input[name=cellCode]:checked').val();
	if (cellphone == '0')
	{
		$('#hashedNumber').css('display', 'none');
	}
	else (cellphone == '1')
	{
		$('#hashedNumber').css('display');
	}

	// Click functions to hide or show the hashed cell phone number
	$('#noCell').click(function()
	{
		$('#hashedNumber').hide('slow');
	});

	$('#yesCell').click(function()
	{
		$('#hashedNumber').show('slow');
	});
});