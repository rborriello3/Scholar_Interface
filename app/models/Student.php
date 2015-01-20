<?php

class Student extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'student';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'studentID';

    public function StudentAddress()
    {
        return $this->hasOne('StudentAddress', 'studentID', 'studentID');
    }

    public function StudentDemo()
    {
        return $this->hasOne('StudentDemo', 'studentID', 'studentID');
    }

    public function addID($id)
    {
        if (count($this->find($id)) == '1')
        {
            return TRUE;
        }

        $this->studentID = $id;

        return $this->save();
    }

    public function upDateStudent($values)
    {
        $student = $this->find($values['studentID']);

        if (count($student) == '1')
        {
            $studentInfo = array_except($values, array(
                '_token', 'GUID', 'address', 'city', 'state', 'zipCode', 'county', 'studentID'
            ));

            foreach ($studentInfo as $k => $v)
            {
                if ($k == 'criteria')
                {
                    $student->$k = implode(',', $v);
                    unset($studentInfo[$k]);
                }

                if ($k == 'minority')
                {
                    $student->$k = implode(',', $v);
                    unset($studentInfo[$k]);
                }

                if ($k == 'homephone')
                {
                    if ($v != '')
                    {
                        $student->homephone = $v;
                        unset($studentInfo[$k]);
                    }
                }

                if ($k == 'cellPhone')
                {
                    if ($v != '')
                    {
                        $student->cellPhone = $v;
                        unset($studentInfo[$k]);
                    }
                }

                if ($k == 'cellCarrier')
                {
                    if ($v > 0)
                    {
                        $student->cellnotifications = 1;
                        unset($studentInfo[$k]);
                    }
                }

                if (!is_array($v))
                {
                    $student->$k = $v;
                }
            }

            if ($student->save())
            {
                $address = new StudentAddress();
                $add     = array(
                    'studentID' => $values['studentID'], 'address' => $values['address'], 'city' => $values['city'],
                    'state'     => $values['state'], 'zipCode' => $values['zipCode'], 'county' => $values['county']
                );

                return $address->upDateAddress($add);
            }
        }
        else
        {
            return FALSE;
        }
    }

    /*This function is used when explicity editing students in the main students tab. The url is: /students/edit/{studentID}*/
    public function manualStudentUpdate($id, $values)
    {
        if ($id != $values['studentID'])
        {
            $this->where('studentID', '=', $id)->update(array('studentID' => $values['studentID']));
        }

        $student = array_only($values, array('studentID', 'firstName', 'lastName', 'personalEmail', 
                                            'sunyEmail', 'homephone', 'cellPhone', 'cellCarrier', 
                                            'address', 'city', 'state', 'zipCode', 'county'
                                            )
                            );

        $this->upDateStudent($student);

        $demo = array_only($values, array('major', 'creditHourSP', 'creditHourFA',
                                         'creditsEarned', 'GPA', 'collegeGrad', 'highGrad', 
                                         'highSchoolAvg', 'highGrad', 'transferMaj', 'transferInsti'));

        $demograph = new StudentDemo();
        $demograph->insertDemographics($demo, $values['studentID']);
    }

    public function createStudent($values)
    {
        if (count($this->find($values['studentID'])) == 1)
        {
            return false;
        }
        else
        {
            $student = array_only($values, array('studentID', 'firstName', 'lastName', 'personalEmail', 
                                                'sunyEmail', 'homephone', 'cellPhone', 'cellCarrier'));

            $add = array_only($values, array('studentID', 'address', 'city', 'state', 'zipCode', 'county'));

            $this->insert($student);

            $address = new StudentAddress();
            $address->insert($add);

            $demo = array_only($values, array('major', 'creditHourSP', 'creditHourFA',
                                            'creditsEarned', 'GPA', 'collegeGrad', 'highGrad', 
                                            'highSchoolAvg', 'highGrad', 'transferMaj', 'transferInsti'));

            $demograph = new StudentDemo();
            $demograph->insertDemographics($demo, $values['studentID']);

            return true;
        }
    }

    public function getName($studentID)
    {
        $student = $this->find($studentID);
        return $student->firstName . ' ' . $student->lastName;
    }




}