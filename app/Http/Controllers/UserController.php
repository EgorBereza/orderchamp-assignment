<?php


namespace App\Http\Controllers;

use App\Contracts\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected UserServiceInterface $userService)
    {
        //$this->middleware('auth');
    }

    /**
     * Returns carts content.
     *
     * @return JsonResponse
     */
    public function create(Request $request):JsonResponse
    {
        return response()->json($this->userService->createUser($request));
    }


}
