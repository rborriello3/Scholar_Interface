<?php

class ScholarshipsController extends BaseController
{
	public function showAll()
	{
        return View::make('Content.Admin.Scholarships.home');
	}

	public function showUpdateSchol($fundCode)
	{
		$data['fundCode'] = $fundCode;
		$data['scholarship'] = Scholarships::find($fundCode);
		$type = new ApplicationType();
		$data['app'] = $type->getAll(true);

		return View::make('Content.Admin.Scholarships.editScholarship', $data);
	}

	public function doUpdateSchol($fundCode)
	{
		$rules = array(
				'fundCode' 		    => 'Required|fund',
				'scholarshipName'   => 'Required|text',
				'scholarshipAmount' => 'Required|decimal',
				'scholarshipDesc'   => 'text',
				'programRequired'   => 'text',
				'yearFrom'          => 'Required|date_format:Y',
				'yearTo'            => 'Required|date_format:Y',
				'recipients'        => 'Required|numeric',
				'equalAwarding'     => 'Required|numeric',
				'appType'			=> 'Required|array_num'
			);

		$v = Validator::make(Input::all(), $rules);

		if ($v->passes())
		{
			$scholarship = new Scholarships();
			$scholarship->updateScholarship($fundCode, Input::all());		
			return Redirect::route('showUpdateSchol', Input::get('fundCode'))->with('success', 'Scholarship Updated');
		}

		return Redirect::route('showUpdateSchol', $fundCode)->withInput()->withErrors($v->messages())->with('error', 'Errors detected in form');
	}

	public function showCreateSchol()
	{
		$type = new ApplicationType();
		$data['app'] = $type->getAll(true);
		return View::make('Content.Admin.Scholarships.new', $data);
	}

	public function doCreateSchol()
	{
		$rules = array(
				'fundCode' 		    => 'Required|fund|unique:scholarships,fundCode',
				'scholarshipName'   => 'Required|text',
				'scholarshipAmount' => 'Required|decimal',
				'scholarshipDesc'   => 'text',
				'programRequired'   => 'text',
				'yearFrom'          => 'Required|date_format:Y',
				'yearTo'            => 'Required|date_format:Y',
				'recipients'        => 'Required|numeric',
				'equalAwarding'     => 'Required|numeric',
				'appTypes'			=> 'Required|array_num'
			);

		$v = Validator::make(Input::all(), $rules);

		if ($v->passes())
		{
			$scholarship = new Scholarships();
			$scholarship->createScholarship(Input::all());		
			return Redirect::route('showUpdateSchol', Input::get('fundCode'))->with('success', 'Scholarship Updated');
		}

		return Redirect::route('showCreateSchol')->withInput()->withErrors($v->messages())->with('error', 'Errors detected in form');
	}

	public function doActivateScholarship($fundCode)
	{
		$schol = new Scholarships();

		if ($schol->activate($fundCode))
		{
			return Redirect::route('showAllScholarships')->with('success', 'Scholarship (' . $fundCode . ') Activated');
		}

		return Redirect::route('showAllScholarships')->with('error', 'Scholarship (' . $fundCode . ') Can Not Be Activated');
	}

	public function doDeactivateScholarship($fundCode)
	{
		$schol = new Scholarships();

		if ($schol->deactivate($fundCode))
		{
			return Redirect::route('showAllScholarships')->with('success', 'Scholarship (' . $fundCode . ') Deactivated');
		}

		return Redirect::route('showAllScholarships')->with('success', 'Scholarship (' . $fundCode . ') Can Not Be Deactivated');
	}
}
