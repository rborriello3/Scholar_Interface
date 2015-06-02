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
		$scholarships = Scholarships::orderBy('scholarshipName', 'asc')->where('active', '=', 1)->remember(1)->lists('scholarshipName', 'fundCode');
		$data['scholarships'] = array_merge($data['scholarships'], $scholarships);

		return View::make('Content.Admin.Awards.showNewAwards', $data);
	}

	public function doNewAwards()
	{
		$rules = array(
				'fundCode'    => 'array_fundCode',
				'studentID'   => 'array_studentID',
				'awardAmount' => 'array_awardAmount',
				'department'  => 'array_text',
				'notes'       => 'array_text'
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
		Awards::where('fundCode', '=', $fundCode)->where('studentID', '=', $studentID)->update(array('awardStatus' => 3));		

		return Redirect::route('showAllAwards')->with('success', 'Successfully deactivated award');
	}

	public function doActivateAward($fundCode, $studentID)
	{
		Awards::where('fundCode', '=', $fundCode)->where('studentID', '=', $studentID)->update(array('awardStatus' => 1));		

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

	public function doAcceptAward($fundCode, $studentID)
	{
		Awards::where('fundCode', '=', $fundCode)->where('studentID', '=', $studentID)->update(array('awardStatus' => 2));

		return Redirect::route('showAllAwards')->with('success', 'Award has been accepted');
	}

	public function doRevokeAward($fundCode, $studentID)
	{
		Awards::where('fundCode', '=', $fundCode)->where('studentID', '=', $studentID)->update(array('awardStatus' => 1));

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
            'notes'       => 'array_text'
        );

        $v = Validator::make(Input::all(), $rules);

        if($v->passes())
        {
            $awards  = new Awards();
            $awarded = $awards->insertAward(Input::all(), TRUE, $guid);

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
}
