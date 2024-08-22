<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        $users = User::orderBy('created_at','ASC')->paginate(10);
        return view('admin.users.list',[
            'users' => $users
        ]);
    }

    public function edit($id) {
        $user = User::findOrFail($id);
        return view('admin.users.edit',[
            'user' => $user,
        ]);
    }

    public function update($id, Request $request) {

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5|max:30',
            'email' => 'required|email|unique:users,email,'.$id.',id'
        ]);

        if ($validator->passes()) {

            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->role = $request->role;
            $user->save();

            session()->flash('success','Información de Usuario actualizada satisfactoriamente');

            return response()->json([
                'status' => true,
                'errors' => []
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request) {
        $id = $request->id;

        $user = User::find($id);

        if ($user == null) {
            session()->flash('error','Usuario no encontrado');
            return response()->json([
                'status' => false,
            ]);
        }

        $user->delete();
        session()->flash('success','Usuario eliminado satisfactoriamente');
        return response()->json([
            'status' => true,
        ]);
    }
}
