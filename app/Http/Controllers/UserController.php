<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use App\Models\product;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    use GeneralTrait;
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
       
        $name = request()->name;
        $email = request()->email;
        $password = request()->password;
        $facebook_url = request()->facebook_url;
        $phone_number = request()->phone_number;
        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'facebook_url' => $facebook_url,
            'phone_number' => $phone_number,
            // 'image_url'=>$image

        ]);

        $credentials = $request->only(['email', 'password']);

        $token = Auth::guard('user-api')->attempt($credentials);
        $user = Auth::guard('user-api')->user();
        $user->api_token = $token;
        //return token
        return $this->returnData('user', $user,'Account has been successfully registered');
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }

    public function login(Request $request)
    {

        try {
            $rules = [
                "email" => "required",
                "password" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            //login

            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('user-api')->attempt($credentials);

            if (!$token)
                return $this->returnError('E001', 'The login information is incorrect');

            $user = Auth::guard('user-api')->user();
            $user->api_token = $token;
            return $this->returnData('user', $user,'You are logged in successfully');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $token = $request->header('auth-token');
        if ($token) {
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return  $this->returnError('', 'some thing went wrongs');
            }
            return $this->returnSuccessMessage('Logged out successfully');
        } else {
           return $this->returnError('', 'some thing went wrongs');
        }
    }
    public function profile()
    {
        return Auth::user();
    }
    public function my_products()
{
    $id = Auth::guard('user-api')->user()->id;
    $user=User::find($id);
    $my_products=$user->products;
if(isset($my_products))
{
    return $this->returnError('', 'You have no products yet');
}
	return  $this->returnData('My_Products',$my_products,);
}
public function uploadImage(Request $request)
{

 $validator = Validator::make($request->all(), [
            'image_url' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($validator->fails()) {
            return $this->returnError(0000, $validator->errors());
        }
        $uploadFolder = 'wasem';
        $image = $request->file('image_url');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $image=Storage::disk('public')->url($image_uploaded_path);
$id = Auth::guard('user-api')->user()->id;
    $user=User::find($id);
    $user->update([
        'image_url'=>$image,
    ]);
    return $this->returnSuccessMessage('The image has been added successfully');

}
   
}
