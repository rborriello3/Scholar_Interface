<?php

class UsersDataController extends BaseController
{
    public function JsonCRUD()
    {
        return Datatable::query(DB::table('user')->select('userId', 'name', 'email', 'status', 'userRole', 'yearTo')->where('userId', '!=', '1'))->addColumn('Actions', function ($user)
        {
            $crudLinks = '<div class="btn-group">';

            if ($user->status == 'Inactive' && $user->yearTo == NULL)
            {
                $crudLinks .= '<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">' . $user->status . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $statusLinks = '<li>' . link_to_route('showActivateUser', 'Activate User', $parameters = array($user->userId), $attributes = array('alt'   => 'activateUser',
                                                                                                                                                   'title' => 'Activate ' . $user->name
                    )) . '</li>';
            }

            elseif ($user->status == 'Active' && $user->yearTo !== NULL)
            {
                $crudLinks .= '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">' . $user->status . '<span class="glyphicon glyphicon-arrow-down"></span></button>';
                $statusLinks = '<li>' . link_to_route('showDeactivateUser', 'Deactive User', $parameters = array($user->userId), $attributes = array('alt'   => 'Deactivate',
                                                                                                                                                     'title' => 'Deactivate ' . $user->name
                    )) . '</li>';
                $statusLinks .= '<li>' . link_to_route('showSuperResetPW', 'Reset Password', $parameters = array($user->userId), $attributes = array('alt'   => 'Reset password',
                                                                                                                                                     'title' => 'Reset password for ' . $user->name
                    )) . '</li>';
            }

            $crudLinks .= '<ul class="dropdown-menu" role="menu">';
            $crudLinks .= '<li>' . link_to_route('showUser', 'Show User', $parameters = array($user->userId), $attributes = array('alt'   => 'showUser',
                                                                                                                                  'title' => 'View ' . $user->name
                )) . '</li>';
            $crudLinks .= '<li>' . link_to_route('showEditUser', 'Edit User', $parameters = array($user->userId), $attributes = array('alt'   => 'editUser',
                                                                                                                                      'title' => 'Edit ' . $user->name
                )) . '</li>';
            $crudLinks .= $statusLinks;
            $crudLinks .= '</ul>';
            $crudLinks .= '</div>';

            return $crudLinks;
        })->showColumns('name', 'email', 'yearTo')->searchColumns('name', 'email')->make();
    }
}