<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    "accepted"      => "The :attribute must be accepted.", "active_url" => "The :attribute is not a valid URL.", "after" => "The :attribute must be a date after :date.", "alpha" => "The :attribute may only contain letters.", "alpha_dash" => "The :attribute may only contain letters, numbers, and dashes.", "alpha_num" => "The :attribute may only contain letters and numbers.", "array" => "The :attribute must be an array.", "before" => "The :attribute must be a date before :date.", "between" => array("numeric" => "The :attribute must be between :min and :max.", "file" => "The :attribute must be between :min and :max kilobytes.", "string" => "The :attribute must be between :min and :max characters.", "array" => "The :attribute must have between :min and :max items.",), "confirmed" => "Confirmation does not match.", "date" => "The :attribute is not a valid date.", "date_format" => "The :attribute does not match the format :format.", "different" => "The :attribute and :other must be different.", "digits" => "The :attribute must be :digits digits.", "digits_between" => "The :attribute must be between :min and :max digits.", "email" => "The :attribute format is invalid.", "exists" => "The selected :attribute is invalid.", "image" => "The :attribute must be an image.", "in" => "The selected :attribute is invalid.", "integer" => "The :attribute must be an integer.", "ip" => "The :attribute must be a valid IP address.", "max" => array("numeric" => "The :attribute may not be greater than :max.", "file" => "The :attribute may not be greater than :max kilobytes.", "string" => "The :attribute may not be greater than :max characters.", "array" => "The :attribute may not have more than :max items.",), "mimes" => "The :attribute must be a file of type: :values.", "min" => array("numeric" => "The :attribute must be at least :min.", "file" => "The :attribute must be at least :min kilobytes.", "string" => "The :attribute must be at least :min characters.", "array" => "The :attribute must have at least :min items.",), "not_in" => "The selected :attribute is invalid.", "numeric" => "The :attribute must be a number.", "regex" => "The :attribute format is invalid.", "required" => "The :attribute field is required.", "required_if" => "The :attribute field is required.", "Required_in" => "The :attribute field is required.", //"required_if"      => "The :attribute field is required when :other is :value.",
    "required_with" => "The :attribute field is required when :values is present.", "required_without" => "The :attribute field is required when :values is not present.", "same" => "The :attribute and :other must match.", "size" => array("numeric" => "The :attribute must be :size.", "file" => "The :attribute must be :size kilobytes.", "string" => "The :attribute must be :size characters.", "array" => "The :attribute must contain :size items.",), "unique" => "The :attribute has already been taken.", "url" => "The :attribute format is invalid.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention 'attribute' => array('rule name' => 'message') to name the lines.
    | This makes it quick to specify a specific custom language line for a given
    | attribute rule.
    |
    */

    'custom'        => array(
            
            'name'                  => array('full_name' => 'You must provide a valid full name.',),
            
            'password'              => array('password' => 'Illegal password detected'),
            
            'cellPhone'             => array('phone' => '# must be 9 digits long.'),
            
            'answ1'                 => array('date_format' => 'Year must be a 4 digit representation'),
            
            'answ2'                 => array('alpha_space_dash' => 'Alphabetical characters only'),
            
            'password_confirmation' => array('required' => 'Confirmation Required.'),
            
            'roles'                 => array('integer_array' => 'Invalid Role Selection', 'required_if_in_array_digit' => 'Grade group is required',),
            
            'studentID'             => array('studentid' => 'Invalid Student ID.'),
            
            'address'               => array(
            
            'address' => 'Invalid Address.'),
            
            'goal'                  => array('text' => 'The Goal Field Has Invalid Characters.'),
            
            'cellCarrier'           => array('over' => 'Cellphone Number Is Required.'),
            
            'transferMaj'           => array('alpha_space_dash' => 'Invalid characters found in Transfering Major.'),
            
            'transferInsti'         => array('alpha_space_dash' => 'Invalid characters found in Transfering Institution'),
            
            'essay'                 => array('essay' => 'Invalid characters detected in the essay.', 'words' => 'Essay must be at least 250 words.'),
            
            'extraCurricular'       => array('essay' => 'Invalid characters detected in the essay.'),
            
            'overallRank1'          => array('rank' => 'Invalid characters detected in rank.'),
            
            'overallRank2'          => array('rank' => 'Invalid characters detected in rank.'),
            
            'processName'           => array('alpha_space_dash_num' => 'Invalid characters detected in process name.'),
            
            'processDescription'    => array('alpha_space_dash_num' => 'Invalid characters detected in process description.'),
            
            'scriptLocation'        => array('alpha_space_dash' => 'Invalid characters found in processes field.'),
            
            'major'                 => array('majors' => 'Invalid characters detected in major.'),
            
            'creditsEarned'         => array('decimal' => 'Earned credits must be in a decimal format.'),
            
            'GPA'                   => array('gpa' => 'GPA must be in the #.## format.'),
            
            'highSchoolAvg'         => array('decimal' => 'Average must be in a decimal format.'),
            
            'expectedCredits'       => array('decimal' => 'Expected credits must be in a decimal format.'),
            
            'highSchoolAvg'         => array('decimal' => 'Average must be in decimal format.'),
            
            'subject'               => array('text' => 'Email Subject has invalid characters.'),
            
            'messageBody'           => array('essay' => 'Message Body has invalid characters.'),

            'roles'                 => array('required_if_in_array_digit' => 'Grade group is required.'),

            'fundCode'              => array('fund' => 'Invalid Fund Code.'),

            'scholarshipAmount'     => array('decimal' => 'Amount must only contain numbers and a decimal.'),

            'scholarshipName'       => array('text' => 'Invalid characters detected in name.'),

            'scholarshipDesc'       => array('text' => 'Invalid characters detected in description.'),

            'programRequired'       => array('text' => 'Invalid characters detected in the program field.'),

            'appTypes'              => array('Required_if_in_array_digit' => 'Equal awarding is a required field.')
    ), 
    /*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

    'attributes'    => array('sunyEmail' => 'Official Email', 'personalEmail' => 'Personal Email', 'appType' => 'Application Types', 'equalAwarding' => 'Equal Awarding', 'scholarshipDesc' => 'Scholarship Description', 'scholarshipName' => 'Scholarship Name', 'scholarshipAmount' => 'Amount', 'fundCode' => 'Fund Code', 'email' => 'E-Mail', 'user' => 'E-Mail', 'firstName' => 'First Name', 'lastName' => 'Last Name', 'roleSelect' => 'User Group', 'ques1' => 'Question', 'ques2' => 'Question', 'answ1' => 'Answer', 'answ2' => 'Answer', 'cellPhone' => 'Cell Phone', 'cellnotify' => 'Mobile Notifications', 'cellCarrier' => 'Carrier', 'roles' => 'User Role', 'monthFrom' => 'Begining Month', 'monthTo' => 'Ending Month', 'yearFrom' => 'Begining Year', 'yearTo' => 'Ending Year', 'StudentID' => 'Student ID', 'zipCode' => 'Zip Code', 'highSchoolName' => 'High School', 'expectedCredits' => 'Expected Credits', 'highSchoolAvg' => 'Average', 'highGrad' => 'Graduation Date', 'diploma' => 'Diploma', 'creditsEarned' => 'Earned Credits', 'collegeGrad' => 'Graduation Date', 'major' => 'Major', 'essay' => 'Essay', 'extraCurricular' => 'Extra Curricular', 'recommender1' => 'Recommender', 'recommender2' => 'Recommender', 'email1' => 'Email', 'email2' => 'Email', 'department1' => 'Department', 'department2' => 'Department', 'courseName1' => 'Course Name', 'courseName2' => 'Course Name', 'academicPotential1' => 'Academic Potential', 'academicPotential2' => 'Academic Potential', 'character1' => 'Character', 'character2' => 'Character', 'emotionalMaturity1' => 'Emotional Maturity', 'emotionalMaturity2' => 'Emotional Maturity', 'overallRank1' => 'Rank', 'overallRank2' => 'Rank', 'comments1' => 'Additional Comments', 'comments2' => 'Additional Comments', 'hour' => 'Hour', 'min' => 'Minute', 'meridian' => 'Meridian', 'processName' => 'Process Name', 'processDescription' => 'Process Description', 'subject' => 'Email Subject', 'messageBody' => 'Email Body', 'massGradeType' => 'Group', 'days' => 'Days', 'repeat' => 'Repition Choice'),

);
