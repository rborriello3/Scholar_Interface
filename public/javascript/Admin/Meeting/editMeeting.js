$(function()
{
    $("#datepicker").datepicker();
    $("#timepicker").timepicker({
        timeFormat: 'hh:mm p',
	interval: 15,
	minTime: '8',
	maxTime: '6:00pm',
	dynamic: false,
	dropdown: true,
	scrollbar: true,
    });

    $('#participants').multiselect({
        columns: 1,
        placeholder: 'Choose Meeting Participant(s)',
        search: true,
	selectAll: true
    });
});
