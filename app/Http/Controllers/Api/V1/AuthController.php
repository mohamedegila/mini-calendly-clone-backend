<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\SigninRequest;
use App\Repository\UserRepository;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function __construct(private UserRepository $userRepository){}
   /*
     * signin api
     *
     * @return \Illuminate\Http\Response
     */
    public function signin(SigninRequest $request){

        $input = $request->validated();
        $input['password'] = bcrypt($input['password']);
        $user = $this->userRepository->store($input);
        $success['email'] =  $user->email;
        return new SuccessResource(Response::HTTP_OK, 'User signin successfully', $success);
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function login(Request $request){

        $user = $this->userRepository->show($request->username, false, false, 'email');

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['user']  =  $user;

            return new SuccessResource(Response::HTTP_OK, 'User login successfully', $success);
        }
        else{
            return new ErrorResource(Response::HTTP_UNAUTHORIZED, 'email or password is not correct', 'login');
        }
    }


    public function authenticatedUser(){
        return UserResource::make(Auth::user());
    }

    public function logout(){
        Auth::user()->tokens()->delete();
        return new SuccessResource(200, 'User log out successfully');
    }
}
