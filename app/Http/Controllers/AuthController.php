<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $input = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $distinationPath = public_path('/images');
            $image->move($distinationPath, $name);
            $input['profile_pic'] = $name;
        }

        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('salaitapp')->accessToken;
            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }
    }
    
    public function update(Request $request){
        $data = $request->all();
        $user = Auth::user(); // get the authenticated user

        if($user != null){
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $distinationPath = public_path('/images');
                $image->move($distinationPath, $name);
                $data['profile_pic'] = $name;

                $oldImage = $user->profile_pic;
                if($oldImage != null){
                    $oldImagePath = public_path('/images/') . $oldImage;
                    if(file_exists($oldImagePath)){
                        unlink($oldImagePath);
                    }
                }
            }
            $user->update($data);
            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user
            ]);
        }
        return response()->json([
            'message' => 'User not found'
        ]);
       
    }

    public function destroy($id){
        $user = User::find($id); /// find the user by id
        if($user != null){
            $user->delete();
            return response()->json([
                'message' => 'User deleted successfully'
            ]);
        }
        return response()->json([
            'message' => 'User not found'
        ]);

    }

    /// get user logged in 
    public function me(){
        return response()->json([
            'user' => Auth::user()
        ]);
    }
}
