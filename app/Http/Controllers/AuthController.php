<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

use App\Models\User;

class AuthController extends Controller
{
    /**
     * Register new user.
     */
    public function register(Request $request)
    {

        $rules = array(
            'fullname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|string|min:6|same:password',
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

            'confirm_password.required' => 'Please enter a password',
            'confirm_password.string' => 'Invalid characters',
            'confirm_password.min' => 'Password must be minimum of 6 characters',
            'confirm_password.same' => 'Confirm Password must be same as password',

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

            $token = Auth::login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ],
            ]);
        }
    }

    public function login(Request $request)
    {

        $rules = array(
            'email' => 'required|string|email',
            'password' => 'required|string',
        );
        $messages = [
            'email.required' => '* Your Email is required',
            'email.string' => '* Invalid Characters',
            'email.email' => '* Must be of Email format with \'@\' symbol',

            'password.required'   => 'This field is required',
            'password.string'   => 'Invalid Characters',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            $credentials = request(['email', 'password']);
            $token = auth()->attempt($credentials);
            if($token) {
                return $this->respondWithToken($token);
            } else {
                return response()->json(['error' => 'These credentials do not match our records.'], 422);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function me()
    {
        
        try {
            $user = Auth::user();
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

    public function logout()
    {
        Auth::logout();
        $message = "User logged out";
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function respondWithToken($token){
        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged In Successfully',
            'data' => [
                'access_token' => $token,
                'type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60,
                'user' => $user,
            ]
            
            
            
        ]);
    }
}
