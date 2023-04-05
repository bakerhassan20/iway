<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Http\traits\ImageTrait;
use Illuminate\Support\Facades\Storage;
use Hash;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Support\Str;
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */

     use ImageTrait;

     public function index(Request $request)
     {
         return view('profiles.index');
     }
     public function edit(Request $request)
     {
         return view('profiles.edit');
     }


    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user= User::where('id',auth()->id())->firstOrFail();
        $request->validate([
            'name'=>"required|min:3|max:190",
        ]);
        $user->update([
            'name'=>$request->name,
        ]);
/*
        if($request->file('user_img')){

             if(Auth::user()->avatar != 'avatar.png'){
                Storage::disk('users-avatar')->delete(Auth::user()->avatar);
              }
        $dataimage = $this->insertImage(Auth::user()->id,$request->user_img,'storage/users-avatar/');

        $user->update([
                    'avatar' => $dataimage,
                ]);

             }  */

                      // if there is a [file]
        if ($request->hasFile('user_img')) {
            // allowed extensions
            $allowed_images = Chatify::getAllowedImages();

            $file = $request->file('user_img');
            // check file size
            if ($file->getSize() < Chatify::getMaxUploadSize()) {
                if (in_array(strtolower($file->extension()), $allowed_images)) {
                    // delete the older one
                    if (Auth::user()->avatar != config('chatify.user_avatar.default')) {
                        $avatar = Auth::user()->avatar;
                        if (Chatify::storage()->exists($avatar)) {
                            Chatify::storage()->delete($avatar);
                        }
                    }
                    // upload
                    $avatar = Str::uuid() . "." . $file->extension();
                    $update = User::where('id', Auth::user()->id)->update(['avatar' => $avatar]);
                    $file->storeAs(config('chatify.user_avatar.folder'), $avatar, config('chatify.storage_disk_name'));
                    $success = $update ? 1 : 0;
                } else {
                    $msg = "File extension not allowed!";
                    $error = 1;
                }
            } else {
                $msg = "File size you are trying to upload is too large!";
                $error = 1;
            }
        }

        //toastr()->success('تمت العملية بنجاح');
        //emotify('info','تمت العملية بنجاح');
           return redirect()->route('profile.index');
    }

    public function update_email(Request $request){

        $request->validate([
        //'email_phone'=>"required",
        'email'=>"required|email|confirmed|unique:users,email,".auth()->user()->id

        ]);
        auth()->user()->update([
            'email'=>$request->email
        ]);

      //  toastr()->success('تمت عملية تغيير الهاتف بنجاح','عملية ناجحة');
      return redirect()->route('profile.index');
    }

    public function update_password(Request $request){
        $request->validate([
            'old_password'=>"required|string|min:8|max:190",
            'password'=>"required|string|confirmed|min:8|max:190"
        ]);
        if(Hash::check($request->old_password, auth()->user()->password)){
            auth()->user()->update([
                'password'=>Hash::make($request->password)
            ]);
           // toastr()->success('تم تغيير كلمة المرور بنجاح','عملية ناجحة');
           return redirect()->route('profile.index');

        }else{
            //flash()->error('كلمة المرور الحالية التي أدخلتها غير صحيحة','عملية غير ناجحة');
            return redirect()->back();
        }
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
