<?php

Route::get('scoring', array(
	'uses' => 'ApplicationsDataController@gradingApplications',
	'as'   => 'allGradingApplicationsJson'	
));