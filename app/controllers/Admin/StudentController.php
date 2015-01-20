<?php

class StudentController extends BaseController
{
	public function showHome()
	{
		return View::make('Content.Admin.Students.home');
	}

    public function showEditStudent($studentID)
    {
        $data['student'] = Student::find($studentID);
        $data['demo']    = StudentDemo::find($studentID);
        $data['address'] = StudentAddress::find($studentID);
        $data['need']    = DB::table('unmetNeed')->where('studentID', '=', $studentID);
        $data['ID']      = $studentID;
        $data['carrier'] = DB::table('cellCarriers')->lists('carrier', 'carrierID');
        $data['states']  = DB::table('states')->lists('stateFull', 'state');

        return View::make('Content.Admin.Students.edit', $data);
    }

    public function doEditStudent($studentID)
    {
        $rules = array(
                'studentID'      => 'Required|studentid',
                'firstName'      => 'Required|full_name',
                'lastName'       => 'Required|full_name',
                'personalEmail'  => 'email|max:80',
                'sunyEmail'      => 'email|max:80',
                'homephone'      => 'phone',
                'cellCarrier'    => 'Required|over:0,cellPhone|numeric',
                'cellPhone'      => 'phone',
                'address'        => 'Required|address', 
                'city'           => 'Required|alpha_space_dash',
                'state'          => 'Required|alpha_space_dash', 
                'zipCode'        => 'Required|numeric|digits:5',
                'county'         => 'Required|alpha_space_dash',
                'major'          => 'majors',
                'GPA'            => 'gpa',
                'collegeGrad'    => 'date_format:m/y', 
                'transferMaj'    => 'alpha_space_dash',
                'transferInsti'  => 'text',
                'creditsEarned'  => 'decimal',
                'highSchoolAvg'  => 'decimal',
                'highSchoolName' => 'alpha_space_dash',
                'highGrad'       => 'date_format:m/y',
                'creditHourFA'   => 'decimal',
                'creditHourSP'   => 'decimal',
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $student = new Student();
            $student->manualStudentUpdate($studentID, Input::all());

            return Redirect::route('showEditStudent', Input::get('studentID'))->with('success', 'Student Updated!');
        }

        return Redirect::route('showEditStudent', $studentID)->withInput()->withErrors($v->messages())->with('error', 'Errors detected in form');
    }

    public function showNewStudent()
    {
        $data['carrier'] = DB::table('cellCarriers')->lists('carrier', 'carrierID');
        $data['states']  = array_add(DB::table('states')->lists('stateFull', 'state'), '', 'Choose State');

        return View::make('Content.Admin.Students.new', $data);
    }

    public function doNewStudent()
    {
        $rules = array(
                'studentID'      => 'Required|studentid',
                'firstName'      => 'Required|full_name',
                'lastName'       => 'Required|full_name',
                'personalEmail'  => 'email|max:80',
                'sunyEmail'      => 'email|max:80',
                'homephone'      => 'phone',
                'cellCarrier'    => 'Required|over:0,cellPhone|numeric',
                'cellPhone'      => 'phone',
                'address'        => 'Required|address', 
                'city'           => 'Required|alpha_space_dash',
                'state'          => 'Required|alpha_space_dash', 
                'zipCode'        => 'Required|numeric|digits:5',
                'county'         => 'Required|alpha_space_dash',
                'major'          => 'majors',
                'GPA'            => 'gpa',
                'collegeGrad'    => 'date_format:m/y', 
                'transferMaj'    => 'alpha_space_dash',
                'transferInsti'  => 'text',
                'creditsEarned'  => 'decimal',
                'highSchoolAvg'  => 'decimal',
                'highSchoolName' => 'alpha_space_dash',
                'highGrad'       => 'date_format:m/y',
                'creditHourFA'   => 'decimal',
                'creditHourSP'   => 'decimal',
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $student = new Student();
            $studentCreation = $student->createStudent(Input::except('_token'));

            if ($studentCreation)
            {
                return Redirect::route('showEditStudent', Input::get('studentID'))->with('success', 'Student Created');
            }

            return Redirect::route('showEditStudent', Input::get('studentID'))->with('error', 'Student Already Exists - You May Edit Below');
        }

        return Redirect::route('showNewStudent')->withInput()->withErrors($v->messages())->with('error', 'Errors detected in form');
    }

}