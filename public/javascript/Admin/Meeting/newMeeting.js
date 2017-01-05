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

    $(document).on('click', '.btn-add', function(e)
    {
	e.preventDefault();

	var controlForm = $('.controls form:first'),
	    currentEntry = $(this).parents('.entry:first'),
	    newEntry = $(currentEntry.clone()).appendTo(controlForm);

	newEntry.find('input').val('');
	controlForm.find('.entry:not(:last) .btn-add')
	    .removeClass('btn-add').addClass('btn-remove')
	    .removeClass('btn-success').addClass('btn-danger')
	    .html('<span class="glyphicon glyphicon-minus"></span>');
    }).on('click', '.btn-remove', function(e)
    {
	    $(this).parents('.entry:first').remove();
	    e.preventDefault();
	    return false;
    });
});

