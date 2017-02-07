$(function()
{
    $("#datepicker").datepicker();

    $('#gradeGroup').multiselect({
        columns: 1,
        placeholder: 'Choose Applicable Grade Group(s)',
        search: true,
	selectAll: true
    });
});
