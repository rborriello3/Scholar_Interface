<?php

class ApplicationAssessment extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'applicationAssessment';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    public function assessment()
    {
        return $this->belongsTo('Application', 'applicationID', 'applicationID');
    }

    public function initialize($app, $appType)
    {
        $committeeMembers = User::where('userRole', 'LIKE', '%4%')->where('status', '=', 'Active')->where('gradeGroup', 'LIKE', '%' . $appType . '%')->get(array('userId'));
        foreach ($committeeMembers as $user)
        {
            if ($this->where('applicationID', '=', $app)->where('userId', '=', $user->userId)->count() == 0)
            {
                $assessment                = new $this;
                $assessment->userId        = $user->userId;
                $assessment->applicationID = $app;
                $assessment->save();
            }
        }

        Session::forget('gradeGroup');
    }

    public function updateAssessment($values, $guid)
    {
        $appID      = $this->getApplicationID($guid);
        $updates    = array();
        $insertInfo = array_except($values, array('_token', 'GUID', 'action'));

        if ($values['action'] == 'Finish Assessment')
        {
            $insertInfo = array_add($insertInfo, 'total', $this->calculateTotalScore($insertInfo, $guid));
            $insertInfo = array_add($insertInfo, 'status', 'Graded');
            $insertInfo = array_add($insertInfo, 'assessmentDate', date('m/d/y'));
            $return     = 'Complete';
        }
        elseif ($values['action'] == 'Save Progress')
        {
            $insertInfo = array_add($insertInfo, 'status', 'Incomplete');
            $insertInfo = array_add($insertInfo, 'assessmentDate', date('m/d/y'));
            $return     = 'Incomplete';
        }

        foreach ($insertInfo as $k => $v)
        {
            $updates[$k] = $v;
        }

        $this->where('applicationID', '=', $appID)->where('userId', '=', Auth::user()->userId)->update($updates);
        $this->checkAllAssessments($guid);

        return $return;
    }

    private function calculateTotalScore($scores, $guid)
    {
        $sum = 0;

        foreach ($scores as $score)
        {
            $sum += $score;
        }

        return $sum;
    }

    private function getApplicationID($guid)
    {
        $app = Application::where('GUID', '=', $guid)->get(array('applicationID'));

        return $app[0]->applicationID;
    }

    public function getValues($guid, $userId)
    {
        $app = Application::where('GUID', '=', $guid)->get(array('applicationID'));

        return $this->where('applicationID', '=', $app[0]->applicationID)->where('userId', '=', $userId)->get();
    }

    public function checkAllAssessments($guid)
    {
        $appID       = $this->getApplicationID($guid);
        $app         = Application::find($appID);
        $type        = $app->typeID;
        $user        = User::where('userRole', 'LIKE', '%4%')->where('status', '=', 'Active')->where('gradeGroup', 'LIKE', '%' . $type . '%')->get(array('userId'));
        $userCount   = 0;
        $assess      = $this->where('applicationID', '=', $app->applicationID)->whereIn('status', array('Graded', 'Deactivated'))->lists('userId');
        $assessCount = 0;

        foreach ($user as $v)
        {
            ++$userCount;
            foreach ($assess as $v1)
            {
                if ($v->userId == $v1)
                {
                    ++$assessCount;
                }
            }
        }

        if ($userCount == $assessCount && ($userCount > 0 || $assessCount > 0))
        {
            $application           = Application::find($appID);
            $application->statusID = 5;
        }
        else
        {
            $application           = Application::find($appID);
            $application->statusID = 3;
        }

        $application->save();
    }

    public function newCommitteeMemberAssessment($userId, $group)
    {
        // If it is a completely new member with 0 assessments
        if ($this->where('userId', '=', $userId)->count() == 0)
        {
            $apps = Application::whereRaw('typeID in (' . implode(',', $group) . ')')->where('aidyear', '=', Session::get('currentAidyear'))
                ->whereIn('statusID', array(
                    '3', '5'
                ))->get(array('applicationID')); // Where aid year is active and application is complete or Waiting to be awarded
            // We than take all the applications that were collected from the above query and "un-finish them" putting them back into
            // the waiting for a grade queue so that the new committee member needs to finish up.

            foreach ($apps as $v)
            {
                $app           = Application::find($v->applicationID);
                $app->statusID = 3;
                $app->save();
                $assess                = new $this;
                $assess->userId        = $userId;
                $assess->applicationID = $v->applicationID;
                $assess->save();
            }
        }

        // Has assessments for the current aidyear but are not in use
        // and if there any assessments that fit in the grade group of the userId, than also make it for them. 
        // Currently the problem is that Andrea has never had a freshmen assessment which will in turn 
        // never make her the 8 missing ones. We want the computer to automate this for us.
        else
        {
            foreach ($group as $g)
            {
                $assessments = DB::table('applications')->join('aidyears', 'aidyears.aidyear', '=', 'applications.aidyear')->join('applicationAssessment', 'applicationAssessment.applicationID', '=', 'applications.applicationID')->select('applications.applicationID', 'applicationAssessment.status', 'applications.GUID', 'applicationAssessment.userId')->where('aidyears.status', '=', 1)->where('applications.typeID', '=', $g)->whereIn('statusID', array(
                        '3', '5'
                    ))->get();

                foreach ($assessments as $v1)
                {
                    if ($v1->userId == $userId)
                    {
                        if ($v1->status != 'Incomplete')
                        {
                            $updates = array();
                            if ($v1->status == 'Unused Grade')
                            {
                                $updates['status'] = 'Graded';
                            }
                            elseif ($v1->status == 'Deactivated')
                            {
                                $updates['status'] = 'Waiting';
                            }

                            if (count($updates) == 1)
                            {
                                $this->where('applicationID', '=', $v1->applicationID)->where('userId', '=', $userId)->update($updates);
                            }
                        }

                        $this->checkAllAssessments($v1->GUID);
                    }
                    else
                    {
                        $this->initialize($v1->applicationID, $g);
                    }
                }
            }
        }
    }

    public function deactivateAssessments($userId)
    {
        $assessments = DB::table('applicationAssessment')->join('applications', 'applications.applicationID', '=', 'applicationAssessment.applicationID')->join('aidyears', 'aidyears.aidyear', '=', 'applications.aidyear')->select('GUID', 'applicationAssessment.status', 'applicationAssessment.applicationID', 'userId')->where('userId', '=', $userId)->where('aidyears.status', '=', 1)->get();

        foreach ($assessments as $v)
        {
            $updates = array();

            if ($v->status == 'Graded')
            {
                $updates['status'] = 'Unused Grade';
            }
            elseif ($v->status == 'Waiting')
            {
                $updates['status'] = 'Deactivated';
            }
            else
            {
                continue;
            }

            $this->checkAllAssessments($v->GUID);
            $this->where('applicationID', '=', $v->applicationID)->where('userId', '=', $v->userId)->update($updates);
        }
    }

    public function getPaginatedValue($group, $offset = NULL)
    {
        if (!$this->checkPaginate($group))
        {
            return FALSE;
        }

        if ($group != 0)
        {
            $guid = DB::table('applicationAssessment')->join('applications', 'applications.applicationID', '=', 'applicationAssessment.applicationID')->join('aidyears', 'aidyears.aidyear', '=', 'applications.aidyear')->select('applications.applicationID')->where('userId', '=', Auth::user()->userId)->whereIn('applicationAssessment.status', array(
                'Waiting', 'Incomplete'
            ))->where('applications.typeID', '=', $group)->where('aidyears.status', '=', 1)->take(1)->skip($offset)->orderBy('assessmentID', 'asc')->get();
        }

        else
        {
            $guid = DB::table('applicationAssessment')->join('applications', 'applications.applicationID', '=', 'applicationAssessment.applicationID')->join('aidyears', 'aidyears.aidyear', '=', 'applications.aidyear')->select('applications.applicationID')->where('userId', '=', Auth::user()->userId)->whereIn('applicationAssessment.status', array(
                'Waiting', 'Incomplete'
            ))->where('aidyears.status', '=', 1)->take(1)->skip($offset)->orderBy('assessmentID', 'asc')->get();
        }

        if (empty($guid))
        {
            return FALSE;
        }

        $app = Application::find($guid[0]->applicationID);

        return $app->GUID;
    }

    private function checkPaginate($group)
    {
        if ($group != 0)
        {
            if (Application::where('typeID', '=', $group)->where('aidyear', '=', Session::get('currentAidyear'))->count() == 0)
            {
                return FALSE;
            }
        }

        else
        {
            if (Application::where('aidyear', '=', Session::get('currentAidyear'))->count() == 0)
            {
                return FALSE;
            }
        }

        if ($this->where('userId', '=', Auth::user()->userId)->where('status', '=', 'Waiting')->orWhere('status', '=', 'Incomplete')->count() == 0)
        {
            return FALSE;
        }

        return TRUE;
    }
}