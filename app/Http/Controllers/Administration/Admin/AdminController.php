<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Admin\AdminFormRequest;
use App\Http\Requests\Application\Admin\AdminPermissionFormRequest;
use App\Http\Requests\Application\Admin\AdminUpdateFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        // $admins = User::with('roles')->where('active',true)->get();
        $admins = User::with('roles')->orderBy('active', 'desc')->get();

        return view('theme.pages.Admin.index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::all()->reject(function ($role, $key) {
            return $role->name === 'Developper';
        });

        return view('theme.pages.Admin.__create.index', compact('roles'));
    }

    public function store(AdminFormRequest $request)
    {
        $user = new User();
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->telephone = $request->telephone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->public_password = $request->password;
        //$user->super_admin = $request->super_admin;
        $user->save();

        $user->assignRole($request->role);

        return redirect()->back()->with('success', "L'ajoute a éte effectuer avec success");
    }

    public function edit(User $admin)
    {
        abort_if($admin->email === 'abdelgha4or@gmail.com' || $admin->hasRole('Developper'), 403);

        $groupOrder = array_flip(array_keys(Lang::get('permission_groups')));
        $permissionLabels = Lang::get('permissions');
        $technicienRole = Role::query()
            ->where('guard_name', 'admin')
            ->where('name', 'Technicien')
            ->with('permissions')
            ->first();
        $technicienPermissions = $technicienRole
            ? $technicienRole->permissions->pluck('name')->all()
            : [];

        $permissions = Permission::query()
            ->where('guard_name', 'admin')
            ->orderBy('name')
            ->get()
            ->groupBy(function (Permission $permission) use ($groupOrder, $technicienPermissions) {
                if (in_array($permission->name, $technicienPermissions, true)) {
                    return 'technicien';
                }

                $group = Str::startsWith($permission->name, 'ticket.delivery.')
                    ? 'ticket_delivery'
                    : Str::before($permission->name, '.');

                return array_key_exists($group, $groupOrder) ? $group : 'other';
            })
            ->sortKeysUsing(function (string $left, string $right) use ($groupOrder) {
                return ($groupOrder[$left] ?? PHP_INT_MAX) <=> ($groupOrder[$right] ?? PHP_INT_MAX);
            })
            ->map(fn ($group) => $group->map(fn (Permission $permission) => [
                'name' => $permission->name,
                'id' => $permission->id,
                'public_name' => $permission->public_name
                    ?: (is_array($permissionLabels) && array_key_exists($permission->name, $permissionLabels)
                        ? $permissionLabels[$permission->name]
                        : Str::headline(str_replace('.', ' ', $permission->name))),
            ]));

        // dd($permissions);
        $roles = Role::all()->reject(function ($role, $key) {
            return $role->name === 'Developper';
        });

        $directPermissionNames = $admin->getDirectPermissions()->pluck('name')->all();
        $rolePermissionNames = $admin->getPermissionsViaRoles()->pluck('name')->all();

        return view('theme.pages.Admin.__profile.index', compact(
            'admin',
            'permissions',
            'roles',
            'directPermissionNames',
            'rolePermissionNames',
        ));
    }

    public function update(AdminUpdateFormRequest $request, User $admin)
    {

        abort_if($admin->email === 'abdelgha4or@gmail.com' || $admin->hasRole('Developper'), 403);

        $admin->nom = $request->nom;
        $admin->prenom = $request->prenom;
        $admin->telephone = $request->telephone;
        $admin->email = $request->email;

        $admin->active = $request->boolean('active');

        $request->whenFilled('password', function ($input) use ($admin) {
            $admin->password = Hash::make($input);
            $admin->public_password = $input;
        });

        $admin->save();

        $admin->syncRoles($request->roles);

        return redirect()->back()->with('success', 'Update a éte effectuer avec success');
    }

    public function syncPermission(AdminPermissionFormRequest $request, User $admin)
    {
        //  dd($request->all());
        abort_if($admin->email === 'abdelgha4or@gmail.com' || $admin->hasRole('Developper'), 403);

        $admin->syncPermissions($request->input('permissions', []));

        return redirect()->back()->with('permissions', 'Les permissions sont synchronisée avec succès');
    }

    public function anon() {}

    public function delete(Request $request)
    {
        $request->validate(['adminId' => 'required|uuid']);

        $admin = User::whereUuid($request->adminId)->firstOrFail();

        abort_if($admin->email === 'abdelgha4or@gmail.com' || $admin->hasRole('Developper'), 403);

        if ($admin) {
            // dd('Ouuuui roole admin');
            //$admin->roles()->detach();
            // $admin->permissions()->detach();

            // $admin->forgetCachedPermissions();

            //  $admin->delete();

            return redirect()->back()->with('success', "L' Admin  a éte supprimer  avec success");
        }

        return redirect()->back()->with('success', 'un problem a été détécter ... ');
    }
}
