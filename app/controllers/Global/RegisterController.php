<?php

class RegisterController extends BaseController
{
  	public function showAccountCreate()
  	{
  		return View::make('Content.Global.AccountManagement.register');
  	}


	public function doAccountCreate()
	{
		$rules = array(
			'name'     => 'Required|full_name|max:101',
			'email'    => 'Required|email|unique:user' 
		);

		$v = Validator::make(Input::all(), $rules);

		if ($v->passes())
		{
			$user           = new User();
			$user->name     = Input::get('name');
			$user->email    = Input::get('email');
			
			$email = new Email();
			if ($email->sendRegisterMail() && $email->notifyAdminRegister())
			{
				$user->save();
				return Redirect::route('home.index')->with('success', 'Account created - check email');
			}

			return Redirect::route('account.showCreate')->with('error', 'Account not created - notify admins');
		}

		return Redirect::route('account.showCreate')->withInput()->withErrors($v->messages());
	}
}
