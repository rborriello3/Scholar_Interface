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
    protected $primaryKey = 'applicationID';

    protected $appID;
    protected $student;

    public function __construct($appID = null, $studentID = null)
    {
        $this->appID   = $appID;
        $this->student = $studentID;
    }

    public function newResponse()
    {
        $this->applicationID   = $this->appID;
        $this->studentID       = $this->student;
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
                $update = $this->find($value);

                if ($update->thankYou != 1)
                {
                    $update->thankYou = 1;
                    $update->TYupdate = $date;
                    $return = $update->save();
                    $usedApps[$value] = TRUE;
                }
            }
            else
            {
                $update = $this->find($value);

                if ($update->thankYou != 0)
                {
                    $update->thankYou = 0;
                    $update->TYupdate = $date;
                    $return = $update->save();
                    $unusedApps[$value] = TRUE;
                }
            }
        }

        foreach($hiddenACPT as $value)
        {
            if(in_array($value, $ACPT))
            {
                $update = $this->find($value);

                if ($update->acceptance != 1)
                {
                    $update->acceptance = 1;
                    $update->ACCPTUpdate = $date;
                    $return = $update->save();
                    $usedApps[$value] = TRUE;
                }
            }
            else
            {
                $update = $this->find($value);

                if ($update->acceptance != 0)
                {
                    $update->acceptance = 0;
                    $update->ACCPTUpdate = $date;
                    $return = $update->save();
                    $unusedApps[$value] = TRUE;
                }
            }
        }

        foreach($hiddenCV as $value)
        {
            if(in_array($value, $CV))
            {
                $update = $this->find($value);

                if ($update->convocation != 1)
                {
                    $update->convocation = 1;
                    $update->CVUpdate = $date;
                    $return = $update->save();
                    $usedApps[$value] = TRUE;
                }
            }
            else
            {
                $update = $this->find($value);

                if ($update->convocation != 0)
                {
                    $update->convocation = 0;
                    $update->CVUpdate = $date;
                    $return = $update->save();
                    $unusedApps[$value] = TRUE;
                }
            }
        }

        foreach ($usedApps as $used => $notUsed)
        {
            $comp = $this->find($used);

            if($comp->thankYou == 1 && $comp->acceptance == 1)
            {
                DB::table('scholarshipAwards')->where('studentID', '=', $comp->studentID)->update(array('awardStatus' => 2));
            }
        }

        foreach ($unusedApps as $used => $notUsed)
        {
            $comp = $this->find($used);

            if($comp->thankYou == 0 || $comp->acceptance == 0)
            {
                DB::table('scholarshipAwards')->where('studentID', '=', $comp->studentID)->update(array('awardStatus' => 1));
            }
        }

        return $return;
    }

    public function makeUpdatesToResponses($GUID, $status)
    {
        $info = DB::table('applications')->where('GUID', '=', $GUID)->get(array('applicationID', 'studentID'));
        $update = $this->find($info[0]->applicationID);
        $update->thankYou = $status;
        $update->acceptance =  $status;
        $update->TYupdate = date('m/d/y');
        $update->ACCPTUpdate = date('m/d/y');
        $return = $update->save();

        if($status == 0)
        {
            $return = DB::table('scholarshipAwards')->where('studentID', '=', $info[0]->studentID)->update(array('awardStatus' => 1));
        }
        else
        {
            $return = DB::table('scholarshipAwards')->where('studentID', '=', $info[0]->studentID)->update(array('awardStatus' => 2));
        }

        return $return;
    }
}
