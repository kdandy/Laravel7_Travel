<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::all();
        return view('user.index', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'role'              => ['required'],
            'email'             => ['required', 'email', 'max:64', 'unique:users,email'],
            'name'              => ['required', 'string', 'max:64'],
            'phone'             => ['required', 'digits_between:6,13'],
            'phone_emergency'   => ['nullable', 'digits_between:6,13'],
            'address'           => ['required', 'string'],
        ]);

        $user = User::create($data);
        $user->sendEmailVerificationNotification();
        alert()->success(__('alert.success-create',['attribute' => __('User')]), __('Success'));
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $roles = Role::all();
        return view('user.show', compact("user",'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('user.edit', compact("user",'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role'              => ['required'],
            'email'             => ['required', 'email', 'max:64', Rule::unique('users','email')->ignore($user)],
            'name'              => ['required', 'string', 'max:64'],
            'phone'             => ['required', 'digits_between:6,13'],
            'phone_emergency'   => ['nullable', 'digits_between:6,13'],
            'address'           => ['required', 'string'],
        ]);

        if ($request->email != $user->email) {
            $user->sendEmailVerificationNotification();
        }

        $user->update($data);
        alert()->success(__('alert.success-update',['attribute' => __('User')]), __('Success'));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->avatar != 'noavatar.jpg') {
            File::delete(storage_path('app/'.$user->avatar));
        }
        $user->delete();
        alert()->success(__('alert.success-delete',['attribute' => __('User')]), __('Success'));
        return redirect()->back();
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function editProfile()
    {
        return view('user.edit-profile');
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:32'],
            'phone'             => ['required', 'digits_between:6,13'],
            'phone_emergency'   => ['nullable', 'digits_between:6,13'],
            'address'           => ['required', 'string']
        ]);

        $user->update($data);

        alert()->success(__('alert.success-update',['attribute' => __('Profile')]), __('Success'));
        return redirect()->back();
    }

    public function setting()
    {
        return view('user.setting');
    }

    public function updateSetting(Request $request)
    {
        $user = User::find(auth()->user()->id);

        $request->validate([
            'email'         => ['nullable', 'string', 'email', 'max:32', Rule::unique('users', 'email')->ignore($user)],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
            'old_password'  => ['required', 'string', 'min:8'],
        ]);

        if (Hash::check($request->old_password, $user->password)) {
            $user->name = $request->name;

            if ($request->email) {
                $user->email = $request->email;
                $user->email_verified_at = null;
                $user->sendEmailVerificationNotification();
            }

            if ($request->password && $request->password_confirmation) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            alert()->success(__('alert.success-update',['attribute' => __('Setting')]), __('Success'));
        return redirect()->back();
        } else {
            alert()->error(__('The password you entered is incorrect'),__('Failed'))->persistent();
            return redirect()->back();
        }
    }

    public function updateAvatar(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => ['required', 'image', 'mimes:png,jpeg', 'max:2048']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'     => true,
                'message'   => $validator->errors()->all(),
                'avatar'    => $user->avatar
            ]);
        }

        if ($user->avatar != 'noavatar.jpg') {
            File::delete(storage_path('app/' . $user->avatar));
        }

        $user->avatar = $request->file('avatar')->store('public/avatar');
        $user->save();

        return response()->json([
            'error'     => false,
            'message'   => __('alert.success-update', ['attribute' => __('Profile picture')]),
            'avatar'    => $user->avatar
        ]);
    }
}
