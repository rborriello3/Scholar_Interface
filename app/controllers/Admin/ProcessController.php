<?php

class ProcessController extends BaseController
{
    public function showProcess()
    {
        return View::make('Content.Admin.Processes.processHome');
    }

    public function showNewProcess()
    {
        $data['jobs'] = array_add(DB::table('processes')->where('active', '=', 1)->lists('processName', 'scriptLocation'), '', 'Process');

        return View::make('Content.Admin.Processes.newProcess', $data);
    }

    public function doNewProcess()
    {
        $rules = array(
            'processName' => 'Required|alpha_space_dash_num', 'processDescription' => 'Required|alpha_space_dash_num',
            'hour'        => 'Required|numeric', 'min' => 'Required|numeric', 'meridian' => 'Required',
            'days'        => 'array_text', 'scriptLocation' => 'Required|alpha', 'repeat' => 'Required|numeric'
        );

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            $values  = array(
                'userID'             => Auth::user()->userId, 'processName' => Input::get('processName'),
                'processDescription' => Input::get('processDescription'),
                'executionTime'      => Input::get('hour') . ':' . Input::get('min') . ' ' . Input::get('meridian'),
                'days'               => Input::get('days'), 'status' => 'Uninitialized',
                'scriptLocation'     => Input::get('scriptLocation'), 'repeat' => Input::get('repeat')
            );
            $process = new Processes();
            $process->newProcess($values);

            return Redirect::route('showProcesses')->with('success', 'Job created.');
        }
        else
        {
            return Redirect::route('showNewProcess')->withInput()->withErrors($v->messages());
        }
    }

    public function doStopProcess($id)
    {
        Processes::where('jobID', '=', $id)->update(array('status' => 'Stopped'));

        return Redirect::route('showProcesses')->with('success', 'Process Stopped');
    }

    public function doDeleteProcess($id)
    {
        Processes::where('jobID', '=', $id)->update(array('status' => 'Deleted'));

        return Redirect::route('showProcesses')->with('success', 'Process Deleted');
    }

    public function showUploadData()
    {
        return View::make('Content.Admin.Processes.uploadData');
    }

    public function doUploadData()
    {
        if (Input::hasFile('dataFiles'))
        {
            $count = 0;
            ini_set('upload_max_filesize', '20M');

            foreach (Input::file('dataFiles') as $file)
            {
                if ($file->getClientOriginalExtension() !== 'txt' && $file->getClientOriginalExtension() !== 'csv')
                {
                    return Redirect::route('showDataUpload')->with('error', 'Only text and csv files permitted');
                }
                else
                {
                    $originalFile    = $file->getClientOriginalName();
                    $destinationPath = '/var/www/ScholarshipInterface/app/Uploads/';
                    ++$count;

                    if (!$file->move($destinationPath, $originalFile))
                    {
                        return Redirect::route('showDataUpload')->with('error', $originalFile . ' could not be uploaded');
                    }
                }
            }

            return Redirect::route('showDataUpload')->with('success', $count . ' file(s) successfully uploaded');
        }
        else
        {
            return Redirect::route('showDataUpload')->with('error', 'No file(s) chosen');
        }
    }
}