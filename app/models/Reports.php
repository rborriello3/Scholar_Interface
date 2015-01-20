<?php

class Reports extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'reportsTable';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'reportId';

    /**
     * This will take the reports viewable from the users table and return the reportID and description.
     *
     * @param reports
     *
     * @return array
     */
    public function getReports($reports)
    {
        $return = array();
        $report = explode(',', $reports);

        foreach ($report as $v)
        {
            $name = $this->where('reportId', '=', $v)->get(array('reportName'));
            $return[$v] = $name[0]->reportName;
        }

        return $return;
    }

}