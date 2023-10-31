<?php

namespace App\Services;
use App\Contracts\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class UserService implements UserServiceInterface
{

    /**
     * Create New User
     * @return array
     */
    public function createUser(Request $request)
    {
        $validator=$this->createRegisterValidator($request);

        if ($validator->fails())
        {
            return ['validation'=>false,'error' => $validator->errors()->all()];
        }
        else
        {
            $userData = $this->retrivesUserData($request);
            // Hash Password
            $userData['password'] = bcrypt($userData['password']);
            // Create User
            $user = User::create($userData);
            // Login
            auth()->login($user);
               return ['validation'=>true,'error' => ''];
        }
    }


    /**
     * retrives user information from the request
     * @return array
     */
    private function retrivesUserData(Request $request)
    {
        $userData = [];
        $userData['name']=$request->name;
        $userData['email']=$request->email;
        $userData['country']=$request->country;
        $userData['city']=$request->city;
        $userData['address']=$request->address;
        $userData['password']=$request->password;
        return $userData;
    }

    /**
     * creates Validator for register form
     *
     * @return Validator
     */
    private function createRegisterValidator(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => ['required', 'min:3'],
            'country' => ['required', 'min:3'],
            'city' => ['required', 'min:3'],
            'address' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6'
        ]);
    }




}