<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ChangePassword extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        request()->validate([
            'oldpassword' => 'required',
            'newpassword' => 'required|string|min:8|max:255',
            'newpassword_confirm' => 'required',
          ]);
          if(strcmp(request()->newpassword_confirm, request()->newpassword)) {
            //return redirect()->back()->withErrors('newpassword', 'New passwords are not matched');
            return back()->withErrors(['newpassword' => 'New passwords are not matched']);
          }
          $hashedPassword = Auth::user()->getAuthPassword();
          if(!Hash::check(request()->oldpassword, $hashedPassword)) {
            return back()->withErrors(['oldpassword' => 'Wrong password']);
          } else {
            $user = User::find(Auth::user()->id);
            $user->password = bcrypt(request()->newpassword);
            $user->update(array('password' => $user->password));
            return back()->with('flash-message', 'Password changed!');
          }
    }
}
