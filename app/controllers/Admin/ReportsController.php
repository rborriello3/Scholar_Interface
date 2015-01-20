<?php

class ReportsController extends BaseController
{
    /**
     * Show all the report options
     *
     * @return Response
     */
    public function showHome()
    {
        $reports = new Reports();
        // Auth::user->viewReports is used to get only the reports this user has access to. This can be editable in the create / edit user screens
        $data['reports'] = array_add($reports->getReports(Auth::user()->viewReports), '', 'Select Report');
        return View::make('Content.Admin.Reports.reportsHome', $data);
    }

    /**
     * Receives the input from the user for the specific report to run then it delegates what to run.
     * that is done via a route to the report. So we need to define the route to the report
     * than with the route we define a place in the Data/ReportsDataController.php file that will
     * run the query and than return the data back to the view that is calling the report data.
     *
     * @return Response
     */
    public function doReports()
    {
        $rules = array(
            'report' => 'Required|numeric'
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $report = Reports::find(Input::get('report'));
            return Redirect::route($report->reportLocation);
        }
    }

    /**
     * This is a report that shows all the students that are graduating in a rank grid
     *
     * @return Response
     */
    public function graduating_rank()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.graduating_rank');
    }

    /**
     * This report will show all assessments from users within the graduating grade group.
     * The view itself is just a foreach of all committee members with their own separate tables.
     *
     * @return mixed
     */
    public function graduating_assessments()
    {
        $data['commMembers'] = User::where('userRole', 'LIKE', '%4%')->where('Status', '=', 'Active')->where('gradeGroup', 'LIKE', '%4%')->get(array(
            'userId', 'name'
        ));

        return View::make('Content.Admin.Reports.GeneratedReports.graduating_assessments', $data);
    }

   /**
    * This report will show all students that are entering in a rank grid
    *
    * @return Response
    */
    public function entering_rank()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.entering_rank');
    }

    /**
     * This report will show all assessments from users within the graduating grade group.
     * The view itself is just a foreach of all committee members with their own seprate tables.
     */
    public function entering_assessments()
    {
        $data['commMembers'] = User::where('userRole', 'LIKE', '%4%')->where('Status', '=', 'Active')->where('gradeGroup', 'LIKE', '%2%')->get(array(
            'userId', 'name'
        ));

        return View::make('Content.Admin.Reports.GeneratedReports.entering_assessments', $data);
    }

    public function graduating_awarded_address()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.graduating_awarded_address');
    }

    public function graduating_regret()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.graduating_regret');
    }

    public function faculty_graduating_address()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.faculty_graduating_address');
    }

    public function returning_rank()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.returning_rank');
    }

    public function returning_assessments()
    {
        $data['commMembers'] = User::where('userRole', 'LIKE', '%4%')->where('Status', '=', 'Active')->where('gradeGroup', 'LIKE', '%6%')->get(array(
            'userId', 'name'
        ));

        return View::make('Content.Admin.Reports.GeneratedReports.returning_assessments', $data);
    }

    public function returning_awarded_address()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.returning_awarded_address');
    }

    public function returning_regret()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.returning_regret');
    }

    public function faculty_returning_address()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.faculty_returning_address');
    }

    public function entering_awarded_address()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.entering_award_address');
    }

    public function entering_regret()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.entering_regret');
    }

    public function faculty_entering_address()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.faculty_entering_address');
    }

    public function all_awards()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.all_awards');
    }

    public function all_students()
    {
        return View::make('Content.Admin.Reports.GeneratedReports.all_students');
    }
}
