<?php

class ResponseDataController extends BaseController
{
    public function allResponses()
    {
        return Datatable::query(DB::table('applicationResponses')
                ->join('student', 'student.studentID', '=', 'applicationResponses.studentID')
                ->join('applications', 'applications.applicationID', '=', 'applicationResponses.applicationID')
                ->join('scholarshipAwards', 'scholarshipAwards.studentID', '=', 'applicationResponses.studentID')
                ->select('applicationResponses.applicationID', 'applications.GUID', 'applicationResponses.studentID', 'thankYou', 'acceptance', 'convocation',
                        'student.firstName', 'student.lastName', 'applications.aidyear', 'requirementDate', 'TYupdate', 'ACCPTUpdate', 'CVUpdate')
                ->orderBy('student.lastName', 'asc')
                ->where('applications.aidyear', '=', Session::get('currentAidyear'))
                ->groupBy('applicationResponses.studentID')

        )
            ->addColumn('actions', function($resp)
            {
                $crudLinks = '<div class="btn-group">';

                if (($resp->thankYou == 0 && $resp->acceptance == 0) && $resp->convocation == 0)
                {
                    $crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">Incomplete <span class="glyphicon glyphicon-arrow-down"></span></button>';
                }
                elseif (($resp->thankYou == 0 && $resp->acceptance == 0) && $resp->convocation == 1)
                {
                    $crudLinks .= '<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Incomplete <span class="glyphicon glyphicon-arrow-down"></span></button>';
                }
                elseif ($resp->thankYou == 0 || $resp->acceptance == 0)
                {
                    $crudLinks .= '<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Incomplete <span class="glyphicon glyphicon-arrow-down"></span></button>';
                }
                elseif ($resp->thankYou == 1 && $resp->acceptance == 1 && $resp->convocation == 0)
                {
                    $crudLinks .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Convocation <span class="glyphicon glyphicon-arrow-down"></span></button>';
                }
                else
                {
                    $crudLinks .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">Complete <span class="glyphicon glyphicon-arrow-down"></span></button>';
                }

                $crudLinks .= '<ul class="dropdown-menu" role="menu">';
                    $crudLinks .= '<li>' . link_to_route('showEditStudent', 'View/Edit Student', $parameters = array($resp->studentID), $attributes = array('alt'   => 'editStudent', 'title' => 'Edit ' . $resp->firstName)) . '</li>';
                    $crudLinks .= '<li>' . link_to_route('showMessageStudent', 'Message Student', $parameters = array($resp->studentID), $attributes = array('alt' => 'messageStudent')) . '</li>';

                    if($resp->thankYou != 1 && $resp->acceptance != 1)
                    {
                        $crudLinks .= '<li>' . link_to_route('doAcceptAwardsManagement', 'Accept Award Offer', $parameters = array($resp->GUID), $attributes = array('alt' =>'acceptAward')) . '</li>';
                    }
                    else
                    {
                        $crudLinks .= '<li>' . link_to_route('doRedoAward', 'Revoke Acceptance', $parameters = array($resp->GUID), $attributes = array('alt' =>'acceptAward')) . '</li>';
                    }

                $crudLinks .= '</ul>';
                $crudLinks .= '</div>';

                return $crudLinks;
            })
            ->showColumns('firstName', 'lastName', 'studentID', 'aidyear')
            ->addColumn('thankYou', function($resp)
            {
                if ($resp->thankYou == 1)
                {
                    $return = '<input type="checkbox" name="thankYou[]" value="' . $resp->applicationID . '" checked="checked"> Yes';
                    $return .= '<input type="hidden" name="hiddenThankYou[]" value="' . $resp->applicationID . '">';
                    $return .= '<input type="hidden" name="thankYou[]" value="">';

                }
                else
                {
                    $return = '<input type="checkbox" name="thankYou[]" value="' . $resp->applicationID . '"> Yes';
                    $return .= '<input type="hidden" name="hiddenThankYou[]" value="' . $resp->applicationID . '">';
                    $return .= '<input type="hidden" name="thankYou[]" value="">';
                }

                $return .= '<br>' . $resp->TYupdate;
                return $return;

            })
            ->addColumn('acceptance', function($resp)
            {
                if ($resp->acceptance == 1)
                {
                    $return = '<input type="checkbox" name="acceptance[]" value="' . $resp->applicationID . '" checked="checked"> Yes';
                    $return .= '<input type="hidden" name="hiddenAcceptance[]" value="' . $resp->applicationID . '">';
                    $return .= '<input type="hidden" name="acceptance[]" value="">';
                }
                else
                {
                    $return = '<input type="checkbox" name="acceptance[]" value="' . $resp->applicationID . '"> Yes';
                    $return .= '<input type="hidden" name="hiddenAcceptance[]" value="' . $resp->applicationID . '">';
                    $return .= '<input type="hidden" name="acceptance[]" value="">';
                }

                $return .= '<br>' . $resp->ACCPTUpdate;
                return $return;
            })
            ->addColumn('CV', function($resp)
            {
                if ($resp->convocation == 1)
                {
                    $return = '<input type="checkbox" name="convocation[]" value="' . $resp->applicationID . '" checked="checked"> Yes';
                    $return .= '<input type="hidden" name="hiddenConvocation[]" value="' . $resp->applicationID . '">';
                    $return .= '<input type="hidden" name="convocation[]" value="">';
                }
                else
                {
                    $return = '<input type="checkbox" name="convocation[]" value="' . $resp->applicationID . '"> Yes';
                    $return .= '<input type="hidden" name="hiddenConvocation[]" value="' . $resp->applicationID . '">';
                    $return .= '<input type="hidden" name="convocation[]" value="">';
                }

                $return .= '<br>' . $resp->CVUpdate;
                return $return;

            })
            ->showColumns('requirementDate')
            ->setSearchWithAlias()
            ->searchColumns('applicationResponses.studentID', 'firstName', 'lastName')
            ->make();

    }
}