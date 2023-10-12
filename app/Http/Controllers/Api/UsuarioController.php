<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function login(Request $request){
        $request->validate([
            "usernom" =>"required",
            "password" =>"required|min:6"
        ]);

        $user = Usuario::where("usernom","=",$request->usernom)->first();

        if(isset($user->id))
       {
          if(hash::check($request->password,$user->password))
          {
             //Creamos token
             $token = $user->createToken('auth_token')->plainTextToken;
             return response()->json([
                'status' => 1,
                'mensaje'=> "Usuario Logueado.",
                'data' =>$user,
                'acces_token' => $token
             ],Response::HTTP_OK);
          }
          else
          {
            return response()->json([
                'status' => 0,
                'mensaje'=> "Password Incorrecto."
            ],Response::HTTP_FORBIDDEN);
          }
       }
       else
       {
            return response()->json([
                'status' => 0,
                'mensaje'=> "Usuario Invalido."
            ],Response::HTTP_NOT_FOUND);
       }
    }

    public function register(Request $request){
        $request->validate([
            'usernom' => 'required|unique:usuarios',
            'nombres' => 'required',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|min:6|confirmed',
            //'password_confirmation'
        ]);

        $user = new Usuario();
        $user->usernom = $request->usernom;
        $user->nombres = $request->nombres;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            "status" => 1,
            "mensaje" => "Registro de usuario exitoso"
        ],Response::HTTP_CREATED);
    }

    public function userProfile()
    {
        return response()->json([
            'status' => 1,
            'mensaje'=> "Acerca del Perfil de usuario",
            'data' => auth()->user() 
        ],Response::HTTP_OK);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 1,
            'mensaje'=> "Cierre de sesion"
         ],Response::HTTP_OK);
    }

    public function userList()
    {
        //$users = Usuario::all()->paginate(10);
        $users = Usuario::select("*")
                         ->get()->paginate(10);

        return response()->json([
            'status' => 1,
            'data' => $users
        ],Response::HTTP_OK);
    }

    public function userUpdate(Request $request, $id)
    {
        if(Usuario::where(['id' => $id])->exists())
        {
            $usuario = Usuario::find($id);
            $usuario->usernom = isset($request->usernom) ? $request->usernom: $usuario->usernom;
            $usuario->nombres = isset($request->nombres) ? $request->nombres: $usuario->nombres;
            $usuario->email = isset($request->email) ? $request->email: $usuario->email;
            $usuario->email = isset($request->password) ? Hash::make($request->password) : $usuario->password;
            $usuario->save();
            
            return response()->json([
                "status" => 1,
                "mensaje" =>"Usuario modificado con exito.",
                "data" => $usuario
            ],Response::HTTP_OK);
        }
        else
        {
            return response()->json([
                "status" => 0,
                "mensaje" =>"Usuario no existe."
            ],Response::HTTP_NO_CONTENT);
        }
    } 

}
