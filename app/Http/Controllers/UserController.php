<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index(){

        $users = User::all();

        if($users->count() > 0){
            return response()->json([
                'status' => 200,
                'users' => $users 
            ], 200);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'No users records found!'
            ], 404);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|email',
            'phone' => 'required|digits:10',
            'technologies' => 'required|string|max:191',
            'description' => 'required|string|max:250',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
        else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'technologies' => $request->technologies,
                'description' => $request->description,
                'password' => md5($request->password),
            ]);

            if($user){
                return response()->json([
                    'status' => 200,
                    'message' => 'User registered successfully!'
                ], 200);
            }
            else{
                return response()->json([
                    'status' => 500,
                    'message' => 'Oops! something went wrong...'
                ], 500);
            }
        }
    }

    public function show($id){

        $user = User::find($id);
        if($user){
            return response()->json([
                'status' => 200,
                'student' => $user
            ], 200);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'Oops! no data found for the user...'
            ], 404);
        }
    }

    public function edit($id){

        $user = User::find($id);
        if($user){
            return response()->json([
                'status' => 200,
                'student' => $user
            ], 200);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'Oops! no data found for the user to edit...'
            ], 404);
        }
    }

    public function update(Request $request, int $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'email' => 'required|email',
            'phone' => 'required|digits:10',
            'technologies' => 'required|string|max:191',
            'description' => 'required|string|max:250',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }
        else {
            $user = User::find($id);
            if($user){
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'technologies' => $request->technologies,
                    'description' => $request->description,
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'User profile updated successfully!'
                ], 200);
            }
            else{
                return response()->json([
                    'status' => 500,
                    'message' => 'Oops! something went wrong...'
                ], 500);
            }
        }
    }

    public function login(Request $request) {

        $validator = Validator::make($request->all(),
            [
                "email" => "required|email",
                "password" => "required"
            ]
        );

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_error" => $validator->errors()]);
        }

        // check if entered email exists in db
        $email_status = User::where("email", $request->email)->first();

        // if email exists then we will check password for the same email
        if(!is_null($email_status)) {
            $password_status = User::where("email", $request->email)->where("password", md5($request->password))->first();

            // if password is correct
            if(!is_null($password_status)) {
                $user = $this->userDetail($request->email);
                return response()->json(["status" => $this->status_code, "success" => true, "message" => "You have logged in successfully", "data" => $user]);
            }
            else {
                return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Incorrect password."]);
            }
        }
        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Email doesn't exist."]);
        }
    }
}
