<?php

namespace App\Http\Controllers\Administration\Profil;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Profile\ProfileUpdateFormRequest;
use App\Http\Requests\Settings\GeneralSettingRequest;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('theme.pages.Profile.index', compact('user'));
    }

    public function settings(GeneralSettings $setting)
    {
        $user = auth()->user();

        return view('theme.pages.Profile.settings.index', compact('user', 'setting'));
    }

    public function update(ProfileUpdateFormRequest $request)
    {
        $user = auth()->user();

        if ($user) {
            $user->nom = $request->nom;
            $user->prenom = $request->prenom;
            $user->email = $request->email;
            $user->telephone = $request->telephone;
            if (
                isAdmin() && $request->has(['oldpassword', 'new_password', 'new_confirm_password']) &&
                $request->filled(['oldpassword', 'new_password', 'new_confirm_password'])
            ) {
                $user->password = Hash::make($request->new_password);
            }

            $user->save();

            return back()->with('success', 'Profile Updated');
        }

        return back()->with('success', 'Profile Not Updated');
    }

    public function updateCompany(GeneralSettingRequest $request, GeneralSettings $settings)
    {
        if ($request->hasFile('logo')) {
            $old = $settings->logo;
            $settings->logo = $request->file('logo')->store('company', ['disk' => 'public']);

            Storage::disk('public')->delete($old);
        }
        $settings->save();

        return redirect()->back()->with('success', 'Update a éte effectuer avec success');
    }
}
