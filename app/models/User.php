<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Remove the default time stamps from the user table
     */

    public $timestamps = FALSE;

    /**
     * Define a specific id
     */

    protected $primaryKey = 'userId';

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    public function CellPhone()
    {
        return $this->hasOne('Usercellphone', 'userId', 'userId');
    }

    public function getName($id)
    {
        $user = $this->find($id);
        return $user->name;
    }

    public function getMobileData()
    {
        $carrier = DB::table('cellCarriers')->where('carrierId', '=', $this->carrierId)->get(array('smsAddress'));

        $cellData['number']   = Crypt::decrypt($this->cellPhoneNum);
        $cellData['address']  = $cellData['number'] . '@' . $carrier[0]->smsAddress;
        $cellData['lastFour'] = '***-***-' . substr($cellData['number'], 6);

        return $cellData;
    }

    public function activateAccount($values, $password)
    {
        $this->userRole  = implode('', $values['roles']);
        $this->yearTo    = $values['monthTo'] . '/' . $values['yearTo'];
        $this->status    = 'Active';
        $this->password  = Hash::make($password, array('rounds' => 17));
        $this->updateAcc = 1;

        if (in_array('4', $values['roles']))
        {
            $this->gradeGroup = implode(',', $values['group']);
        }

        return $this->save();
    }

    public function deactivateUser()
    {
        $this->yearTo     = NULL;
        $this->status     = 'Inactive';
        $this->password   = NULL;
        $this->ques1      = NULL;
        $this->ques2      = NULL;
        $this->answ1      = NULL;
        $this->answ2      = NULL;

        return $this->save();
    }

    public function firstLoginUpdate($values)
    {
        $this->password  = Hash::make($values['password'], array('rounds' => 17));
        $this->ques1     = $values['ques1'];
        $this->ques2     = $values['ques2'];
        $this->answ1     = Hash::make($values['answ1'], array('rounds' => 14));
        $this->answ2     = Hash::make($values['answ2'], array('rounds' => 14));
        $this->updateAcc = 0;
        $this->save();
    }

    public function superUserResetPW()
    {
        $password = bin2hex(openssl_random_pseudo_bytes('7'));

        $this->updateAcc = 1;
        $this->ques1     = NULL;
        $this->ques2     = NULL;
        $this->answ1     = NULL;
        $this->answ2     = NULL;
        $this->password  = Hash::make($password, array('rounds' => 17));

        $email = new Email($this->email, $this->name);
        $email->sendSuperResetPW($password);

        return $this->save();
    }

    public function createUser($values, $password)
    {
        $values = array_except($values, '_token');

        $this->name      = $values['name'];
        $this->email     = $values['email'];
        $this->updateAcc = 1;
        $this->password  = Hash::make($password, array('rounds' => 17));
        $this->yearTo    = $values['monthTo'] . '/' . $values['yearTo'];
        $this->userRole  = implode('', $values['roles']);
        $this->status    = 'Active';

        if ($this->save())
        {
            $email = new Email($values['email'], $values['name']);
            $email->sendActivationEmail($password, $this->yearTo);
        }

        if (strpos($this->userRole, '4') !== FALSE)
        {
            $this->gradeGroup = implode(',', $values['group']);
            $this->save();
            $assessment = new ApplicationAssessment();
            $assessment->newCommitteeMemberAssessment($this->userId, $values['group']);
        }
    }

    public function updateYearsActive($id)
    {
        $aidyear = new Aidyear();
        $aid = $aidyear->getCurrentAidyear();
        $user = $this->find($id)->get(array('yearTo'));

        if (strrpos($user[0]->yearTo, $aid) === FALSE)
        {
	   DB::table('user')->where('userId', '=', $id)->update(array('yearTo' => $user[0]->yearsActive . '*' . $aid . '*'));
        }
    }

   public function editUser($values)
   {
        $yearsActive = $values['monthTo'] . '/' . $values['yearTo'];
	/*DB::table('user') ->where('userId', '=', $id)->update(array('name' => $values['name'] ));
        DB::table('user') ->where('userId', '=', $id)->update(array('email' => $values['email']));
	DB::table('user') ->where('userId', '=', $id)->update(array('yearto' => $yearsActive ));
	DB::table('user') ->where('userId', '=', $id)->update(array('userRole' => implode('',  $values['availableRoles'])));
	if(in_array('4', $values['availableRoles']) !== FALSE)
	{
		DB::table('user') ->where('userId', '=', $id)->update(array('gradeGroup' => implode(',' , $values['availableGroups'])));
	}
	else if(in_array('4', $values['availableRoles']) == FALSE)
	{
		
		DB::table('user') ->where('userId', '=', $id)->update(array('gradeGroup' => 0));
	}*/
	
	$this->name = $values['name'];
	$this->email = $values['email'];
	$this->yearTo = $yearsActive;
	$this->userRole = implode('', $values['availableRoles']);
	
	if(in_array('4', $values['availableRoles']) !== FALSE)
	{
		$this->gradeGroup = implode(',', $values['availableGroups']);
	}
	else
	{
		$this->gradeGroup = 0;
	}
	$this->save();
	
   }
}
