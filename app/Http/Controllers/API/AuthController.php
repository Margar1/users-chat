<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            return response()->json([
                'success' => true,
                'data' => $success
            ], $this->successStatus);
        } else {
            return response()->json([
                'error' => 'Unauthorised'
            ], 401);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user = User::find($user->id);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json([
            'success' => true,
            'data' => $success
        ], $this->successStatus);
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetails()
    {
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'data' => $user
        ], $this->successStatus);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatedUser(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }
        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['password'] = $request->password;
        $input['password'] = bcrypt($input['password']);
        User::where('id',$id)->update([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);
        $user = User::find($id)->toArray();
        $user['token'] = $request->token;
        return response()->json([
            'success' => true,
            'data' => $user
        ], $this->successStatus);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletedUser($id)
    {
        $user =  User::where('id',$id)->delete();
        return response()->json([
            'success' => true,
            'data' => $user
        ], $this->successStatus);
    }
}