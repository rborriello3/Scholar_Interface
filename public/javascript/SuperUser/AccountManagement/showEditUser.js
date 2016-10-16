$(document).ready(function()
{
        if($('#committee').is(':checked'))
        {
                $("#gradingGroup").css('display');
        }

        else
        {
                $("#gradingGroup").css('display', 'none');
        }

        $("#committee").click(function()
        {
                if($('#committee').is(':checked'))
                {
                        $("#gradingGroup").show("fast")
                }

                else
                {
                        $("#gradingGroup").hide("fast");
                }
        });
});

