<?php

namespace App\Http\Controllers;
use Auth;
use Mail;
use App\User;
use App\Mail\PasswordReset;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class UserController extends Controller
{


    public function showUser(Request $request){
        $user = User::paginate(10);

        return response()->json([
            'user' => $user,
            'success' => true
        ],200);
    }
    public function showUserApi(Request $request){
    $users = User::all();

        return response()->json([
            'users' => $users,
            'success' => true
        ],200);
    }

    public function login(Request $request)
    {
        trim($request->email," ");
        trim($request->password," ");

            if((User::where('email', $request->email)->count())==0){
                return redirect("/login")->with('message',"Incorrect Email!");
            }
            else if (Auth::attempt(['email' => $request->email, 'password' => $request->password ])) {

               $user = Auth::user();
            //    \Log::info($user);
               if($user->userType == 'admin' || $user->userType == 'journalist'){
                        return redirect("/dashboard");
                }else{
                    Auth::logout();
                    Auth::check();
                    return  redirect("/login")->with('message',"You are not Admin");
                 }
             }
             else{
                return  redirect("/login")->with('message',"Incorrect Password!");
             }

    }

    public function editProfile(Request $request){
        $data = $request->all();
        if (!isset($data['fullName'])){
            return response()->json([
                'msg' => 'Full Name is required!',
                'success' => false
            ],500);
        }

        if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $update_user = User::where('id',$data['id'])->update([
                'fullName' => $data['fullName'],
                'email' => $data['email'],
                'address' => $data['address'],
                'profilePic' => $data['profilePic'],
            ]);

            $user = User::where('id',$data['id'])->first();
            return response()->json([
                'user' => $user,
                'success' => true
            ],200);

        }
        else{
            return response()->json([
                'msg' => 'Invalid Email Format',
                'success' => false
            ],500);
        }

    }

    public function updateAppToken(Request $request)
    {
        User::where('id',$request['id'])->update([
            'app_token' => $request['app_token']
        ]);
        $user = User::where('id',$request['id'])->first();
        return response()->json([
            'user' => $user,
            'success' => true
        ],200);
    }
    public function updateLanguage(Request $request)
    {
        User::where('id',$request['id'])->update([
            'language' => $request['language']
        ]);
        $user = User::where('id',$request['id'])->first();
        return response()->json([
            'user' => $user,
            'success' => true
        ],200);
    }

    public function api_login(Request $request)
    {
        trim($request->email," ");
        trim($request->password," ");

            if((User::where('email', $request->email)->count())==0){
                return response()->json([
                    'msg' => "Incorrect Email",
                    'success' => false
                ], 200);
            }
            else if (Auth::attempt(['email' => $request->email, 'password' => $request->password ])) {
                $user = Auth::user();
                if($user){
                    return response()->json([
                        'user' => $user,
                        'success' => true
                    ], 200);
                }else{
                    return response()->json([
                        'msg' => "Wrong Password",
                        'success' => false
                    ], 200);
                }
             }

    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }



    public function api_register(Request $request){
        $data = $request->all();
        if (!isset($data['fullName'])){
            return response()->json([
                'msg' => 'Full Name is required!',
                'success' => false
            ],500);
        }
        if($data['email']){
            $validator3 = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
            ]);
            if ($validator3->fails()) {
                return response()->json([
                    'success' => false,
                    'msg' => "Email is already registered",
                ], 422);
            }
            if(filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                if(strlen($data['password']) < 6){
                    return response()->json([
                        'msg' => 'Password must be at least 6 character',
                        'success' => false
                    ],500);
                }

                $data['password'] = Hash::make($data['password']);
                $user = User::create($data);

                if($user){
                    if(Auth::attempt(['email' => $request['email'], 'password' => $request['password'] ])){
                        $newuser = Auth::user();
                        if($newuser){
                            return response()->json([
                                'user' => $newuser,
                                'msg' => 'Login Successful',
                                'success' => true
                            ], 200);
                        }
                        else{
                            return response()->json([
                                'msg' => "Please Login",
                                'success' => false
                            ], 500);
                        }
                    }
                }
            }
            else{
                return response()->json([
                    'msg' => 'Invalid Email Format',
                    'success' => false
                ],500);
            }

        }

    }
    public function changePassword(Request $request){
        $data = $request->all();
        $user = Auth::user();
        if(!Hash::check($request->current_password, $user->password)){
            return response()->json([
                'msg' => 'Old password is not correct.',
                'success' => false
            ],401);
        }

        $password = Hash::make($data['new_password']);
        $user = User::where('id', $user->id)->update(['password' => $password]);
        return response()->json([
        'user' => $user,
        'success' => true
        ],200);
    }

    //password reset after forget ==> start
    public function resetPasswordGetEmail(Request $request)
    {
        $data = $request->all();
        $isFound = User::where('email', $request->email)->count();

        if ($isFound > 0) {
            $code = rand(1000, 9999);

            $data['code'] = $code;
            \DB::table('password_resets')->where('email', $request->email)->delete();
            $savedData = \DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $code, //change 60 to any length you want
                'created_at' => \Carbon\Carbon::now()
                ]);

                $email = $data['email'];

                $mail = Mail::send('password', $data, function ($message) use ($email) {
                    $message->from('mahmood.rajeh1@gmail.com', 'use to reset');
                    $message->to($email)->subject('Password reset');
                });
                \Log::info($mail);


            return response()->json([
                'message' => "Successfully Send token",
                'success' => true
            ], 200);
        } else {

            return response()->json([
                'message' => "Email not Found",
                'success' => false
            ], 422);
        }
    }

    public function matchPasswordCode(Request $request)
    {
        $code = $request->code;
        $isTokenFound = \DB::table('password_resets')->where('token', $code)->first();
        if ($isTokenFound) {
            return response()->json([
                'msg' => "Please Reset Password now",
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'message' => "Does not match code",
                'success' => false
            ], 200);
        }
    }

    public function resetPasswordEmail(Request $request)
    {
        $data = $request->all();

        $ob = [
            'password' => Hash::make($data['password'])
        ];
        $falg1 = \DB::table('password_resets')->where('email', $data['email'])->first();

        if (!$falg1) {
            return response()->json([
                'message' => "Your Session Ended!!!",
                'success' => false
            ], 422);
        }

        $flag = User::where("email", $data['email'])->update($ob);
        if ($flag) {
            \DB::table('password_resets')->where('email', $request->email)->delete();

            return response()->json([
                'message' => "Your Password has been reset . Please Login with your new password",
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'message' => "Password Reset failed!!!",
                'success' => false
            ], 422);
        }
    }

    // create a journalist
    public function createJournalist(Request $request){
        $data = $request->all();
        $user = Auth::user();
        if($user->userType != "admin"){
            return response()->json([
                'msg' => "Only admin can add journalist",
                'success' => false
                ],401);
        }

        $password = Hash::make($data['password']);

        $journalist = User::create([
            'email' => $data['email'],
            'password' => $password,
            'fullName' => $data['fullName'],
            'userType' => 'journalist'
        ]);
        
        return response()->json([
        'journalist' => $journalist,
        'success' => true
        ],200);
    }
}




