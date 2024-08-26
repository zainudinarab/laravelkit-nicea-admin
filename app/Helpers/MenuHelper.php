<?php

namespace App\Helpers;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function Menu()
    {
        $user = Auth::user();
        $roleId = $user->roles->first()->id;
        $menu_roles = DB::table('role_has_menus')
            ->where('role_id', $roleId)
            ->get();
        // dd($menuRoles);
        // $menu_roles = DB::table('role_has_menus')->where('role_id', auth()->user()->id)->get();
        // dd(auth()->user()->roles->pluck('id')->toArray());
        foreach ($menu_roles as  $value) {

            $array_menu_roles[] = $value->menu_id;
        }

        $menus = Menu::where('parent_id', 0)
            ->with('submenus', function ($query) use ($array_menu_roles) {
                $query->whereIn('id', $array_menu_roles);
                $query->with('submenus', function ($query) use ($array_menu_roles) {
                    $query->whereIn('id', $array_menu_roles);
                });
            })
            ->whereIn('id', $array_menu_roles)
            ->get();
        return json_encode($menus);
    }
}
