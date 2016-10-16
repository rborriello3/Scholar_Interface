<?php

class RoleController extends BaseController
{
    /**
     * Not default behavior, only gets triggered when the user has multiple roles in the database.
     * This goes for showSelect and doSelect.
     */
    public function showSelect()
    {
        Session::forget('role');
        $data['email'] = Auth::user()->email;
        $role          = new Userrole();
        $data["roles"] = array_add($role->descriptions(Auth::user()->userRole), '', 'Choose User Group');
        Session::put('roles', array_except($data["roles"], ''));

        return View::make('Content.Global.Roles.roleSelect', $data);
    }

    public function doSelect()
    {
        $rules = array('roleSelect' => 'Required|digits:1|max:7');

        $v = Validator::make(Input::all(), $rules);

        if ($v->passes())
        {
            Session::put('multiRoles', 1);
            Session::put('role', Input::get('roleSelect'));

            return Redirect::route('showDashboard');
        }

        return Redirect::route('doRoleSelect')->withInput()->withErrors($v->messages());

    }
}