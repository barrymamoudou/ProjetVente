<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDestroy(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function AdminLogoutPage(Request $request){

    }

    public function AdminProfile(Request $request){
        //ici on recupere l'id de la personne qui est connecte
        $id=Auth::user()->id;
        $adminData=User::find($id);
        return view('admin.admin_profile',compact('adminData'));
    }

    public function AdminProfileStore(Request $request){
        //L'enregistrement de la personne qui est connecte 
        $id=Auth::user()->id;
        $data=User::find($id);
        $data->name=$request->name;
        $data->email=$request->email;
        $data->phone=$request->phone;
        //Chargement de la photo 
        if($request->file('photo')){
            $file=$request->file('photo');
            //pour change  la photo d'origine si on change l'image
            @unlink(public_path('upload/admin_image/'.$data->photo));
            $filename=date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_image'),$filename);
            $data['photo']=$filename;
        }

        $data->save();
        $notification=array(
            'message' =>'Enregistrement effectuer avec success',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);
    }

    public function ChangePassword(){
        return view('admin.change_password');
    }

    public function UpdatePassword(Request $request){
        // changement de la password
          /// Validation 
          $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);
        if(!Hash::check($request->old_password, auth::user()->password)){
            $notification = array(
                'message' => 'Old Password Dones not Match!!',
                'alert-type' => 'error'
            ); 
           return back()->with($notification);
        }

        // on recupere l'identifient de la personne connecte et mettre a jour le mot de pass de l'utilisateur qu'il a change 
        User::whereId(auth()->user()->id)->update([
            // 
            'password'=>Hash::make($request->new_password)
        ]);
        $notification = array(
            'message' => 'Success!!',
            'alert-type' => 'error'
        ); 
        return back()->with($notification);
    }

}
