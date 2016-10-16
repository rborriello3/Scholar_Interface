<?php

class ApplicationRecommendation extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'applicationRecommendations';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = false;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'applicationID';
    protected $appId;
    protected $studentId;

    public function __construct($appId = NULL, $studentId = NULL)
    {
        if ($appId)
        {
            $this->appId = $appId;
        }

        if ($studentId)
        {
            $this->studentId = $studentId;
        }
    }

    public function newRecommendation()
    {
        $this->applicationID = $this->appId;
        $this->studentID     = $this->studentId;
        $this->save();
    }

    public function updateRecommendation($values, $guid)
    {
        $info       = Application::where('GUID', '=', $guid)->get(array('studentID', 'applicationID'));
        $insertInfo = array_except($values, array('_token', 'recomms', 'GUID'));
        $recomm     = $this->find($info[0]->applicationID);

        if ($values['recomms'] == '0')
        {
            // We do this because if the radio button is not checked the name of the radio buttons will not be submitted.
            // So when we are doing the $recomm->$k = null we will not be able to set the DB records to null because the
            // Radio button names are not posting. We get around that by doing adding each name of the radios to the
            // $array array. Its a hack, but it works - and works good to!
            $array      = array('character1' => NULL, 'character2' => NULL, 'emotionalMaturity1' => NULL, 'emotionalMaturity2' => NULL, 'academicPotential1' => NULL, 'academicPotential2' => NULL);
            $insertInfo = array_merge($insertInfo, $array);
        }
        if (count($recomm) == 1)
        {
            foreach ($insertInfo as $k => $v)
            {
                if ($values['recomms'] == '0')
                {
                    $recomm->$k = NULL;
                }

                elseif ($values['recomms'] == '1')
                {
                    if (substr($k, strpos($k, '2')) != '2')
                    {
                        $recomm->$k = $v;
                    }

                    else
                    {
                        $recomm->$k = NULL;
                    }
                }

                else
                {
                    if ($v != '')
                    {
                        if ($recomm->$k != $v)
                        {
                            $recomm->$k = $v;
                        }
                    }
                }
            }
        }

        else
        {
            foreach ($insertInfo as $k => $v)
            {
                if ($v != '')
                {
                    $recomm->$k = $v;
                }
            }
        }

        if ($values['recomms'] == '2')
        {
            $recomm->complete = 1;
            $recomm->received = date('m/d/y');
            $recomm->updated  = date('m/d/y');
            Session::put('recommendations', 2);
        }

        elseif ($values['recomms'] == '0')
        {
            $recomm->complete = 0;
            $recomm->received = NULL;
            Session::put('recommendations', 0);
        }

        else
        {
            $recomm->complete = 0;
            $recomm->received = date('m/d/y');
            Session::put('recommendations', 1);
        }

        $recomm->save();
    }

    public function completeApplication($guid, $values)
    {
        $types = $values['types'];
        unset($values['types']);
        unset($values['_token']);
        unset($values['one']);

        $id            = Application::where('GUID', '=', $guid)->get(array('applicationID', 'studentID'));
        $app           = Application::find($id[0]->applicationID);
        $rec           = $this->find($id[0]->applicationID);
        $rec->complete = 1;
        $app->statusID = 3;

        foreach ($values as $k => $v)
        {
            if ($v != '')
            {
                $rec->$k = $v;
            }
        }

        $rec->updated = date('m/d/y');

        if ($rec->received == NULL)
        {
            $rec->received = date('m/d/y');
        }

        $student = Student::find($id[0]->studentID);

        if ($app->save() && $rec->save())
        {
            if ($student->cellnotifications == 1)
            {
                $SMS = new Text($loggedIn = '');
                $SMS->applicationNotifcation($student->cellPhone, $student->cellCarrier, true);
            }

            $email = new Email();
            $email->emailSentToSUNY($student->personalEmail, $student->firstName, $student->lastName);
            $email->completedApplication($student->sunyEmail, $student->firstName, $student->lastName, true);
            $assessments = new ApplicationAssessment();
            $assessments->initialize($id[0]->applicationID, $types);

            return true;
        }

        return false;
    }
}