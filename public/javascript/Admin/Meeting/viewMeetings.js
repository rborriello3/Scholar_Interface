$(document).ready(function()
{
    $(function()
    {
	$( "#dashboardMenu:nth-child(1n)" ).accordion(
	{
	    heightStyle: "content",
	    collapsible: true,
	    active: 0,
	    event: "click"	   
	});
    });
});
