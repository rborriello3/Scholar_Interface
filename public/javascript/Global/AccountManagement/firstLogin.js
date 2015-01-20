$(document).ready(function() {

	// On first load or on redirect with errors, check if the value of cellnotify is either 0 or 1
	var cellphoneNote = $('input[name=cellnotify]:checked').val();
	if (cellphoneNote == '0')
	{
		$('#cellPhoneInfo').css('display', 'none');
	}
	else (cellphoneNote == '1')
	{
		$('#cellPhoneInfo').css('display');
	}

	// Click functions to hide or show the exta cell info stuff
	$('#noCell').click(function()
	{
		$('#cellPhoneInfo').hide('slow');
	});

	$('#yesCell').click(function()
	{
		$('#cellPhoneInfo').show('slow');
	});
});