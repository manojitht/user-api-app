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

    public function register(Request $request){
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
                'password' => Hash::make(($request->password))
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

    public function destroy($id){

        $user = User::find($id);
        if($user){
            $user->delete();
            return response()->json([
                'status' => 200,
                'message' => 'User successfully deleted!'
            ], 200);
        }
        else {
            return response()->json([
                'status' => 404,
                'message' => 'Oops! no data found for the user to delete...'
            ], 404);
        }
    }



    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'errors' => $validator->messages()
            ]);
        } else {
            $email = $request->input('email');
            $password = $request->input('password');

            $user = User::where("email", $email)->first();

            if(!Hash::check($password, $user->password)){
                return response()->json([
                    'status' => 401,
                    'message' => 'Oops! Entered credentials are invalid.'
                ], 401);
            }
            else{

                $token = $user->createToken($user->email.'_Token')->plainTextToken;

                return response()->json([
                    'status' => 200,
                    'userid' => $user->id,
                    'username' => $user->name,
                    'useremail' => $user->email,
                    'userphone' => $user->phone,
                    'usertechnologies' => $user->technologies,
                    'userdescription' => $user->description,
                    'token' => $token,
                    'message' => 'Logged in successfully!'
                ], 200);
            }
        }

    }

}
