<?php

class ApplicationResponse extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'applicationResponses';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
     * must define a specific key for our database table
     */
   // protected $primaryKey = 'applicationID';
//    protected $primaryKey = array('studentID', 'fundCode', 'aidyear');

   // protected $appID;
    protected $student;
    protected $fund;
    protected $year;

   // public function __construct($appID = null, $studentID = null)
    public function __construct($studentID = null, $fundCode = null, $aidyear= null)
    {
       // $this->appID   = $appID;
        $this->student 	= $studentID;
        $this->fund     = $fundCode;
        $this->year 	= $aidyear;
    }

    public function newResponse()
    {
       // $this->applicationID   = $this->appID;
        $this->studentID       = $this->student;
        $this->fundCode	       = $this->fund;
        $this->aidyear         = $this->year;
        $this->requirementDate = date('m/d/y');
        $this->save();
    }

    public function updateResponse($data)
    {
        $date = date('m/d/y');
        $usedApps = array(); // This value will be used to maintain who gets automatically updated to status accepted on their awards.
        $unusedApps = array();

        $hiddenTY = $data['hiddenThankYou'];
        $ty = $data['thankYou'];
        $hiddenACPT = $data['hiddenAcceptance'];
        $ACPT = $data['acceptance'];
        $hiddenCV = $data['hiddenConvocation'];
        $CV = $data['convocation'];
        $return = FALSE;

        foreach($hiddenTY as $value)
        {
            if (in_array($value, $ty))
            {
               // $update = $this->find($value);
		$id = substr($value, 0, 9);
		$fundCode = substr($value, 9, 13);
		
		$update = DB::table('applicationResponses')
				->where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->first();

                if ($update->thankyou != 1)
                {
                    /*$update->thankyou = 1;
                    $update->TYupdate = $date;
                   // $return = $update->save();
		    $return = 1;*/

		    ApplicationResponse::where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->update(array('thankyou' => '1', 'TYupdate' => $date));

		    $return = 1;

                    $usedApps[$value] = TRUE;
                }
            }
            else
            {
                //$update = $this->find($value);
		$id = substr($value, 0, 9);
		$fundCode = substr($value, 9, 13);
		
		$update = DB::table('applicationResponses')
				->where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->first();

                if ($update->thankyou != 0)
                {
                    /*$update->thankyou = 0;
                    $update->TYupdate = $date;
                    $return = $update->save();
		    $return = 1;*/

		    ApplicationResponse::where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->update(array('thankyou' => '0', 'TYupdate' => $date));

		    $return = 1;

                    $unusedApps[$value] = TRUE;
                }
            }
        }

        foreach($hiddenACPT as $value)
        {
            if(in_array($value, $ACPT))
            {
                //$update = $this->find($value);
		$id = substr($value, 0, 9);
		$fundCode = substr($value, 9, 13);
		
		$update = ApplicationResponse::where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->first();
                
		if ($update->acceptance != 1)
                {
                    /*$update->acceptance = 1;
                    $update->ACCPTUpdate = $date;
                    $return = $update->save();
		    $return = 1;*/
		    ApplicationResponse::where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->update(array('acceptance' => '1', 'ACCPTUpdate' => $date));

		    $return = 1;
                    $usedApps[$value] = TRUE;
                }
            }
            else
            {
                //$update = $this->find($value);
		$id = substr($value, 0, 9);
		$fundCode = substr($value, 9, 13);
		
		$update = DB::table('applicationResponses')
				->where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->first();
                
		if ($update->acceptance != 0)
                {
                   /* $update->acceptance = 0;
                    $update->ACCPTUpdate = $date;
                   // $return = $update->save();
		    $return = 1;*/

		    ApplicationResponse::where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->update(array('acceptance' => '0', 'ACCPTUpdate' => $date));

		    $return = 1;

                    $unusedApps[$value] = TRUE;
                }
            }
        }

        foreach($hiddenCV as $value)
        {
            if(in_array($value, $CV))
            {
                //$update = $this->find($value);
		$id = substr($value, 0, 9);
		$fundCode = substr($value, 9, 13);
		
		$update = DB::table('applicationResponses')
				->where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->first();
                
		if ($update->convocation != 1)
                {
                   /* $update->convocation = 1;
                    $update->CVUpdate = $date;
                   // $return = $update->save();
		    $return = 1;*/

		    ApplicationResponse::where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->update(array('convocation' => '1', 'CVUpdate'=> $date));

		    $return = 1;

                    $usedApps[$value] = TRUE;
                }
            }
            else
            {
               // $update = $this->find($value);
		$id = substr($value, 0, 9);
		$fundCode = substr($value, 9, 13);
		
		$update = DB::table('applicationResponses')
				->where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->first();
                
		if ($update->convocation != 0)
                {
                   /* $update->convocation = 0;
                    $update->CVUpdate = $date;
                   // $return = $update->save();
		    $return = 1;*/

		    ApplicationResponse::where('studentID', '=', $id)
				->where('fundCode', '=', $fundCode)
				->where('aidyear', '=', Session::get('currentAidyear'))
				->update(array('convocation'=> '0', 'CVUpdate' => $date));

		    $return = 1;

                    $unusedApps[$value] = TRUE;
                }
            }
        }

        foreach ($usedApps as $used => $notUsed)
        {
            //$comp = $this->find($used);
	    $id = substr($used, 0, 9);
	    $fundCode = substr($used, 9, 13);
		
	    $comp = DB::table('applicationResponses')
			->where('studentID', '=', $id)
			->where('fundCode', '=', $fundCode)
			->where('aidyear', '=', Session::get('currentAidyear'))
			->first();

            if($comp->thankyou == 1 && $comp->acceptance == 1)
            {
                DB::table('scholarshipAwards')
			->where('studentID', '=', $comp->studentID)
			->where('fundCode', '=', $comp->fundCode)
			->where('aidyear', '=', $comp->aidyear)
			->update(array('awardStatus' => 2));
            }
        }

        foreach ($unusedApps as $used => $notUsed)
        {
            //$comp = $this->find($used);
	    $id = substr($used, 0, 9);
	    $fundCode = substr($used, 9, 13);
		
	    $comp = DB::table('applicationResponses')
			->where('studentID', '=', $id)
			->where('fundCode', '=', $fundCode)
			->where('aidyear', '=', Session::get('currentAidyear'))
			->first();
            
	    if($comp->thankyou == 0 || $comp->acceptance == 0)
            {
                DB::table('scholarshipAwards')
			->where('studentID', '=', $comp->studentID)
			->where('fundCode', '=', $comp->fundCode)
			->where('aidyear', '=', $comp->aidyear)
			->update(array('awardStatus' => 1));
            }
        }

        return $return;
    }

    //public function makeUpdatesToResponses($GUID, $status)
    public function makeUpdatesToResponses($studentID, $aidyear, $fundCode, $status)
    {
       // $info = DB::table('applications')->where('GUID', '=', $GUID)->get(array('applicationID', 'studentID'));
	$info = DB::table('applicationResponses')
			->where('studentID', '=', $studentID)
			->where('aidyear', '=', $aidyear)
			->where('fundCode', '=', $fundCode)
			->get(array('studentID', 'fundCode', 'aidyear'));

        $update = $this->find(array($info[0]->studentID, $info[0]->fundCode, $info[0]->aidyear));
        $update->thankYou = $status;
        $update->acceptance =  $status;
        $update->TYupdate = date('m/d/y');
        $update->ACCPTUpdate = date('m/d/y');
        $return = $update->save();

        if($status == 0)
        {
            $return = DB::table('scholarshipAwards')
			->where('studentID', '=', $info[0]->studentID)
			->where('fundCode', '=', $info[0]->fundCode)
			->where('aidyear', '=', $info[0]->aidyear)
			->update(array('awardStatus' => 1));
        }
        else
        {
            $return = DB::table('scholarshipAwards')
			->where('studentID', '=', $info[0]->studentID)
			->where('fundCode', '=', $info[0]->fundCode)
			->where('aidyear', '=', $info[0]->aidyear)
			->update(array('awardStatus' => 2));
        }

        return $return;
    }
}
