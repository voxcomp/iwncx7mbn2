<?php

namespace App\Http\Repositories;

use App\User;
use DB;

class ValidationRepo
{
    protected $table = 'user_activations';

    public function __construct()
    {
    }

    public function getValidation(User $user)
    {
        return DB::table($this->table)->where('user_id', $user->id)->first();
    }

    public function getValidationByToken($token)
    {
        return DB::table($this->table)->where('token', $token)->first();
    }

    public function deleteValidation($token)
    {
        DB::table($this->table)->where('token', $token)->delete();
    }

    public function createValidation($user,$notify = false)
    {
        $validation = $this->getValidation($user);

		if($notify) {
			$this->saveNotifyDate($user);
		}
        if (!$validation) {
            return $this->createToken($user);
        }
        
        return $this->regenerateToken($user);
    }
    
    protected function saveNotifyDate(User $user) {
	    $user->notified_date = time();
	    $user->save();
    }

    protected function getToken()
    {
        return substr(hash_hmac('sha256', str_random(20), config('app.key')),0,20);
    }

    private function regenerateToken(User $user)
    {
        $token = $this->getToken();
        DB::table($this->table)->where('user_id', $user->id)->update([
            'token' => $token,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        return $token;
    }

    private function createToken(User $user)
    {
        $token = $this->getToken();
        DB::table($this->table)->insert([
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        return $token;
    }
}