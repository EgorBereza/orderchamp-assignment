<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Http\Request;

interface UserServiceInterface
{

    /**
     * Create New User
     * @return array
    */
    public function createUser(Request $request);

}