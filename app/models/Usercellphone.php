<?php

class Usercellphone extends Eloquent
{
    /**
     * The database table
     */
    protected $table = 'userCellPhone';

    /**
     * We don't want any default time stamps
     */
    public $timestamps = FALSE;

    /**
     * must define a specific key for our database table
     */
    protected $primaryKey = 'userId';

    public function newEntry($number, $carrier)
    {
        if (count($this->find(Auth::user()->userId)) == 0)
        {
            $this->userId       = Auth::user()->userId;
            $this->cellPhoneNum = \Crypt::encrypt($number);
            $this->carrierId    = $carrier;
            $this->save();
        }

        else
        {
            $update               = $this->find(Auth::user()->userId);
            $update->cellPhoneNum = \Crypt::encrypt($number);
            $update->carrierId    = $carrier;
            $update->save();
        }
    }

    public function getAddress()
    {
        $carrier = DB::table('cellCarriers')->where('carrierId', '=', $this->carrierId)->get(array('smsAddress'));

        return Crypt::decrypt($this->cellPhoneNum) . '@' . $carrier[0]->smsAddress;
    }

    public function getHiddenNum()
    {
        return '***-***-' . substr(Crypt::decrypt($this->cellPhoneNum), 6);
    }
}