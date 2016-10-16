<?php

class AwardsController extends BaseController
{
	public function index()
	{
        return View::make('Content.Admin.Awards.showAwardsTable');
	}

	public function newAwards()
	{
		$data['scholarships'] = array('' => 'Choose Scholarship (Alphabetical Order)');
		$scholarships = Scholarships::orderBy('scholarshipName', 'asc')->where('active', '=', 1)->remember(2)->lists('scholarshipName', 'fundCode', 'scholarshipAmount');
		$amounts = Scholarships::orderBy('scholarshipName', 'asc')->where('active', '=', 1)->remember(1)->lists('scholarshipAmount', 'fundCode');

//		var_dump($amounts);
		//Attach fundCode to each scholarship name
		foreach($scholarships as $k => $v)
		{
			$scholarships[$k] = $v . " - " . $k . " ($" . $amounts[$k] . ")";
		}
//		var_dump($scholarships);
	
		//Attach scholarship amount to each name/fundCode combo
/*		foreach($amounts as $k => $v)
		{
			$amounts[$k] = $scholarships[$k] . "(" . $v . ")";
		}
*/		

		$data['scholarships'] = array_merge($data['scholarships'], $scholarships);

		$data['typeID'] = array('' => 'Choose scholarship type (Alphabetical Order');
		$typeID = ApplicationType::orderBy('typeName', 'asc')
				->where('typeID', '!=', 0)
				->where('typeID', '!=', 1)
				->where('typeID', '!=', 8)
				->orWhere('typeID', '>=', 9)	
				->remember(1)->lists( 'typeName', 'typeID');
		

		//Make typeID look nice (format: "typeID - typeName")
	
		$data['typeID'] = array_merge($data['typeID'], $typeID);

		return View::make('Content.Admin.Awards.showNewAwards', $data);
	}

	public function doNewAwards()
	{
		$rules = array(
				'fundCode'    => 'array_fundCode',
				'studentID'   => 'array_studentID',
				'awardAmount' => 'array_awardAmount',
				'department'  => 'array_text',
				'notes'       => 'array_text',
				'typeID'      => 'array_num'
			);

		$v = Validator::make(Input::all(), $rules);

		if ($v->passes())
		{
			$awards  = new Awards();
			$awarded = $awards->insertAward(Input::all(), FALSE);

			if ($awarded[0])
			{
				return Redirect::route('showNewAwards')->with('success', 'Successfully saved ' . $awarded[1] . ' award(s)');
			}
			else
			{
				return Redirect::route('showNewAwards')->with('error', 'Awards could not be saved due to a processing error');
			}
		}

		return Redirect::route('showNewAwards')->withErrors($v->messages())->withInput()->with('error', 'Awards could not be saved due to invalid characters in text fields');
	}

	public function doDeactivateAward($fundCode, $studentID)
	{	
		Awards::where('fundCode', '=', $fundCode)->where('studentID', '=', $studentID)->where('aidyear', '=', Session::get('currentAidyear'))->update(array('awardStatus' => 3));		

		return Redirect::route('showAllAwards')->with('success', 'Successfully deactivated award');
	}

	public function doActivateAward($fundCode, $studentID)
	{
		Awards::where('fundCode', '=', $fundCode)->where('studentID', '=', $studentID)->where('aidyear', '=', Session::get('currentAidyear'))->update(array('awardStatus' => 1));		

		return Redirect::route('showAllAwards')->with('success', 'Award has been set back to awarded - must be set to accepted');
	}

	public function showAwardHistory($studentID)
	{
		$awards = new Awards();
		$data['awardInfo'] = $awards->getHistory($studentID);

		if (count($data['awardInfo']) > 0)
		{
			return View::make('Content.Admin.Awards.history', $data);
		}
		else
		{
			return Redirect::route('showAllAwards')->with('error', 'No award history for ' . $studentID);
		}
	}

	public function doAcceptAward($fundCode, $studentID, $aidyear)
	{
		Awards::where('fundCode', '=', $fundCode)->where('studentID', '=', $studentID)->where('aidyear', '=', $aidyear)->update(array('awardStatus' => 2));

		return Redirect::route('showAllAwards')->with('success', 'Award has been accepted');
	}

	public function doRevokeAward($fundCode, $studentID, $aidyear)
	{
		Awards::where('fundCode', '=', $fundCode)->where('studentID', '=', $studentID)->where('aidyear', '=', $aidyear)->update(array('awardStatus' => 1));

		return Redirect::route('showAllAwards')->with('success', 'Award has been revoked');
	}

    public function showAwardSingleStudent($guid)
    {
        $app = Application::where('GUID', '=', $guid)->get(array('studentID'));
        $data['studentID'] = $app[0]->studentID;
        $data['guid'] = $guid;
        $data['scholarships'] = array('' => 'Choose Scholarship (Alphabetical Order)');
        $scholarships = Scholarships::orderBy('scholarshipName', 'asc')->remember(2)->lists('scholarshipName', 'fundCode');
        $data['scholarships'] = array_merge($data['scholarships'], $scholarships);

        return View::make('Content.Admin.Awards.award_single_student', $data);
    }

    public function doAwardSingleStudent($guid)
    {
        $rules = array(
            'fundCode'    => 'array_fundCode',
            'studentID'   => 'array_studentID',
            'awardAmount' => 'array_awardAmount',
            'department'  => 'array_text',
            'notes'       => 'array_text',
	    'typeID'	  => 'array_num'
        );

        $v = Validator::make(Input::all(), $rules);

        if($v->passes())
        {
            $awards  = new Awards();
            $awarded = $awards->insertAward(Input::all(), null, null, TRUE, $studentID, $fundCode, null);

            if ($awarded[0] == TRUE && $awarded != 'Invalid')
            {
                return Redirect::route('showAllAwards')->with('success', 'Student has been awarded.');
            }
            elseif ($awarded == 'Invalid')
            {
                return Redirect::route('showAwardSingleStudent', $guid)->with('error', 'The Student ID does not match the application owners Student ID');
            }
            else
            {
                return Redirect::route('showAwardSingleStudent', $guid)->with('error', 'Award could not be saved due to a processing error');
            }
        }

        return Redirect::route('showAwardSingleStudent', $guid)->withErrors($v->messages())->withInput()->with('error', 'Awards could not be saved due to invalid characters in text fields');
    }

    public function showEditAward($fundCode, $studentID)
    {
        $data['studentID'] = $studentID;
        $data['fundCode'] = $fundCode;
        $scholarships = Scholarships::orderBy('scholarshipName', 'asc')->remember(2)->lists('scholarshipName', 'fundCode');
        $data['scholarships'] = array($fundCode => $scholarships[$fundCode]);
        $data['scholarships'] = array_merge($data['scholarships'], $scholarships);
	$data['typeID'] = array('' => 'Choose scholarship type (alphabetical order');
	
	$typeID = ApplicationType::orderBy('typeName', 'asc')
			->where('typeID', '!=', 0)
			->where('typeID', '!=', 1)
			->where('typeID', '!=', 8)
			->orWhere('typeID', '>=', 9)
			->remember(1)->lists('typeName', 'typeID');

	$data['typeID'] = array_merge($data['typeID'], $typeID);

        return View::make('Content.Admin.Awards.edit', $data);
    }

    public function doEditAward($fundCode, $studentID)
    {
        $rules = array(
            'fundCode'    => 'array_fundCode',
            'studentID'   => 'array_studentID',
            'awardAmount' => 'array_awardAmount',
            'department'  => 'array_text',
            'notes'       => 'array_text',
	    'typeID'	  => 'array_num'
        );

        $v = Validator::make(Input::all(), $rules);

        if($v->passes())
        {
            $awards  = new Awards();
            $awarded = $awards->insertAward(Input::all(), null, null, true, $studentID, $fundCode, null);
        }

        if ($awarded[0] == TRUE)
        {
            return Redirect::route('showEditAward', array($awarded[1], $studentID))->with('success', 'Student award has been updated');
        }
        else
        {
            return Redirect::route('showEditAward', array($fundCode, $studentID, null))->with('error', 'Award could not be saved due to a processing error');
        }
    }
}
