<?php

class ScholarshipsDataController extends BaseController
{
    public function scholarshipsListJSON()
    {
        return Datatable::query(DB::table('scholarships')
                    ->leftjoin('scholarshipAwards', 'scholarshipAwards.fundCode', '=', 'scholarships.fundCode')
                    ->select('scholarships.fundCode', 'scholarshipName', 'scholarshipAmount', 'programRequired', 'yearFrom', 'yearTo', 
                        'awardBasis', 'recipients', 'equalAwarding', 'active')
                    ->orderBy('scholarshipAmount', 'desc')
                    ->groupBy('scholarships.fundCode')
                    )
                ->addColumn('actions', function($scholarships)
                {
                    $crudLinks = '<div class="btn-group">';

                        $statusLinks = "";

                        if ($scholarships->active == 1)
                        {
                            $crudLinks .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">' . $scholarships->fundCode . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                            $statusLinks .= '<li>' . link_to_route('doDeactiveScholarship', 'Deactivate Scholarship', $parameters = array($scholarships->fundCode), $attributes = array('alt' => 'DeactivateScholarship')) . '</li>';
                        }
                        else
                        {
                            $crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">' . $scholarships->fundCode . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                            $statusLinks .= '<li>' . link_to_route('doActiveScholarship', 'Activate Scholarship', $parameters = array($scholarships->fundCode), $attributes = array('alt' => 'ActivateScholarship')) . '</li>';
                        }

                        $crudLinks .= '<ul class="dropdown-menu" role="menu">';
                        $crudLinks .= '<li>' . link_to_route('showUpdateSchol', 'Edit Scholarship', $parameters = array($scholarships->fundCode), $attributes = array('alt' => 'EditScholarship')) . '</li>';
                        $crudLinks .= $statusLinks;
                        $crudLinks .= '</ul>';
                    $crudLinks .= '</div>';

                    return $crudLinks;
                })
                ->showColumns('scholarshipName', 'scholarshipAmount', 'programRequired')
                ->addColumn('years', function ($scholarships)
                {
                    return $scholarships->yearFrom . ' - ' . $scholarships->yearTo;
                })
                ->showColumns('awardBasis', 'recipients')
                ->addColumn('awards', function ($scholarships)
                {
                   if ($scholarships->equalAwarding == 1)
                   {
                        return 'TRUE';
                   }
                   else
                   {
                        return 'FALSE';
                   }
                })
                ->setSearchWithAlias()
                ->searchColumns('scholarships.fundCode', 'scholarshipName', 'scholarshipAmount', 'programRequired', 'awardBasis')
                ->make();
    }
}
