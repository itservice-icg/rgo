<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Gate;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('role_or_permission:Role read|Role create|Role update|Role delete', ['only' => ['index', 'show']]);
        $this->middleware('role_or_permission:Role create', ['only' => ['create', 'store']]);
        $this->middleware('role_or_permission:Role update', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:Role delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Role::latest()->get();

        return view('setting.role.index', ['roles' => $role]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        // return view('setting.role.new',['permissions'=>$permissions]);
        $actions = ['read', 'create', 'update', 'delete'];

        // กำหนดชื่อเมนูภาษาไทย
        $menuNames = [
            // 'Report' => 'รายงาน',
            // 'Role' => 'สิทธิ์',
            // 'User' => 'ผู้ใช้',
            'Inregister' => 'ทะเบียนนำเข้าทั้งหมด',
            'RegisterManufacture' => 'ทะเบียนผลิตทั้งหมด',
            'RegisterAll' => 'ทะเบียนสินค้าทั้งหมด',
            'RegisterNew' => 'ขึ้นทะเบียนใหม่',
             'Company' => 'บริษัท',
            'import_data_staple' => 'อัพโหลดทะเบียนนำเข้า',
            'import_data_manufacture' => 'อัพโหลดทะเบียนผลิต',
            // เพิ่มเมนูอื่นๆ ตามต้องการ
        ];

        $menus = [];
        foreach ($permissions as $permission) {
            [$menu, $action] = explode(' ', $permission->name . ' ');

            if (in_array($action, $actions)) {
                // ถ้ามีชื่อไทยให้ใช้ชื่อไทย ไม่มีก็ใช้ชื่อเดิม
                $menuDisplay = $menuNames[$menu] ?? $menu;
                $menus[$menuDisplay][$action] = $permission;
            }
        }

        return view('setting.role.new', ['permissions' => $menus]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        $role = Role::create(['name' => $request->name]);

        $role->syncPermissions($request->permissions);

        return redirect()->back()->withSuccess('Role created !!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::get();
        $actions = ['read', 'create', 'update', 'delete'];

        // กำหนดชื่อเมนูภาษาไทย
        $menuNames = [
            'Inregister' => 'ทะเบียนนำเข้าทั้งหมด',
            'RegisterManufacture' => 'ทะเบียนผลิตทั้งหมด',
            'RegisterAll' => 'ทะเบียนสินค้าทั้งหมด',
            'RegisterNew' => 'ขึ้นทะเบียนสินค้าใหม่',
            'Company' => 'บริษัท',
            'import_data_staple' => 'อัพโหลดทะเบียนนำเข้า',
            'import_data_manufacture' => 'อัพโหลดทะเบียนผลิต',
            // เพิ่มเมนูอื่นๆ ตามต้องการ
        ];

        $menus = [];
        foreach ($permissions as $permission) {
            [$menu, $action] = explode(' ', $permission->name . ' ');

            if (in_array($action, $actions)) {
                $menuDisplay = $menuNames[$menu] ?? $menu;
                $menus[$menuDisplay][$action] = $permission;
            }
        }

        return view('setting.role.edit', [
            'role' => $role,
            'permissions' => $menus
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $role->update([
            'name' => $request->filled('name') ? $request->name : $role->name,
        ]);

        $hiddenMenus = ['Report', 'Role', 'User', 'RegisterContinue'];
        $hiddenPermissionIds = $role->permissions()
            ->where(function ($query) use ($hiddenMenus) {
                foreach ($hiddenMenus as $menu) {
                    $query->orWhere('name', 'like', $menu . ' %');
                }
            })
            ->pluck('id');

        $permissions = collect($request->input('permissions', []))
            ->merge($hiddenPermissionIds)
            ->unique()
            ->values()
            ->all();

        $role->syncPermissions($permissions);
        return redirect()->back()->withSuccess('Role updated !!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back()->withSuccess('Role deleted !!!');
    }
}
