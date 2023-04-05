<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

use App\Models\User;

class UserController extends Controller
{
    public function createUser(Request $request)
    {

        $rules = array(
            'fullname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            
        );
        $messages = [

            'fullname.required' => '* Name is required',
            'fullname.string' => '* Invalid characters',
            'fullname.max' => '* name is too long',

            'lastname.string' => '* Invalid characters',
            'lastname.max' => '* name is too long',

            'middlename.string' => '* Invalid characters',
            'middlename.max' => '* name is too long',

            'email.required' => '* Your email is required',
            'email.string' => '* Invalid characters',
            'email.email' => '* Must be of email format with \'@\' symbol',
            'email.max' => '* Email is too long',
            'email.unique' => 'This email already exist',

            'password.required' => 'Please enter a password',
            'password.string' => 'Invalid characters',
            'password.min' => 'Password must be minimum of 6 characters',

            'profile_picture.image' => 'File must be an image',
            'profile_picture.mimes' => 'Image format must be of type jpg, png, jpeg, gif or svg',
            'profile_picture.max' => 'Image size must be less than 2MB',

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            $user = new User();
            $user->name = $request->fullname;
            $user->lastname = empty($request->lastname) ? $request->lastname : null;
            $user->middlename = empty($request->middlename) ? $request->middlename : null;
            $user->email = $request->email;
            $user->completion_rate = 30;
            $user->password = Hash::make($request->password);

            if ($request->profile_picture) {
                //image
                $imageName = time().'.'.$request->profile_picture->extension();
                //store products in folder
                $request->profile_picture->storeAs('user', $imageName, 'public');
                $user->profile_picture = $imageName;
            }
            
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => [
                    'user' => $user,
                ],
            ]);
        }
    }

    public function updateUser(Request $request, $id)
    {

        $rules = array(
            'fullname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',  
        );
        $messages = [

            'fullname.required' => '* Name is required',
            'fullname.string' => '* Invalid characters',
            'fullname.max' => '* name is too long',

            'lastname.string' => '* Invalid characters',
            'lastname.max' => '* name is too long',

            'middlename.string' => '* Invalid characters',
            'middlename.max' => '* name is too long',

            'email.required' => '* Your email is required',
            'email.string' => '* Invalid characters',
            'email.email' => '* Must be of email format with \'@\' symbol',
            'email.max' => '* Email is too long',

            'profile_picture.image' => 'File must be an image',
            'profile_picture.mimes' => 'Image format must be of type jpg, png, jpeg, gif or svg',
            'profile_picture.max' => 'Image size must be less than 2MB',

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {

            $user = User::find($id);
            if(!isset($user)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'User Not Found',
                    'data' => []
                ]);
            }
            $user->name = $request->fullname;
            $user->lastname = empty($request->lastname) ? $request->lastname : null;
            $user->middlename = empty($request->middlename) ? $request->middlename : null;
            $user->email = $request->email;

            if ($request->profile_picture) {
                $oldImage = $user->profile_picture; //1.jpg
                if(Storage::disk('public')->exists('user/'.$oldImage)){
                    Storage::disk('public')->delete('user/'.$oldImage);
                }
                $imageName = time().'.'.$request->profile_picture->extension();
                //store user in folder
                $request->profile_picture->storeAs('user', $imageName, 'public');
                $user->profile_picture = $imageName;
            }
            
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => [
                    'user' => $user,
                ],
            ]);
        }
    }

    //by id
    public function singleUser($id)
    {
        try {
            $user = User::find($id);
            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => "Something went wrong"
            ]);
        }
    }

    
    public function allUser()
    {
        try {
            $users = User::all();
            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => "Something went wrong"
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
