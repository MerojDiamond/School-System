<?php

namespace App\Repositories;

use App\Http\Requests\SSRRequest;
use App\Interfaces\RoleInterface;
use Modules\Role\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleInterface
{
    public function total(SSRRequest $request)
    {
        $total = (new Role())->where("name", "!=", "Super-Admin");
        if ($request->search) $total = Role::where("name", "LIKE", "%{$request->search}%");
        return $total->count();
    }
    public function allRoles()
    {
        $roles = Role::where("name", "!=", "Super-Admin")->orderBy("name")->get();
        return $roles;
    }
    public function roles(SSRRequest $request)
    {
        $roles = (new Role())->where("name", "!=", "Super-Admin");
        if ($request->filters != null) {
            foreach ($request->filters as $k => $v) {
                if ($v["value"] !== null)
                    $roles = $roles->where($k, "like", "%" . $v['value'] . "%");
            }
        }
        if ($request->search) $roles = $roles->where("name", "LIKE", "%{$request->search}%");
        if ($request->sortBy) $roles = $roles->orderBy($request->sortBy[0], $request->sortDesc[0] ? "desc" : "asc");
        if ($request->itemsPerPage > 0) $roles = $roles->offset(($request->page - 1) * $request->itemsPerPage)->limit($request->itemsPerPage);
        $roles = $roles->with("permissions")->get();
        return $roles;
    }
    public function permissions()
    {
        $permissions = Permission::orderBy("name")->get();
        return $permissions;
    }
    public function store(RoleRequest $request)
    {
        $role = Role::create(["name" => $request->name, "guard_name" => "web"]);
        $permissions = Permission::whereIn("id", $request->permissions)->get();
        $role->syncPermissions($permissions);
        return $role;
    }
    public function update(Role $role, RoleRequest $request)
    {
        $role = Role::where("name", "!=", "Super Admin")->findOrFail($role->id);
        $role->update(["name" => $request->name, "guard_name" => "web"]);
        $permissions = Permission::whereIn("id", $request->permissions)->get();
        $role->syncPermissions($permissions);
        return $role;
    }
    public function delete(Role $role)
    {
        $role->syncPermissions([]);
        $role->destroy($role->id);
        return $role;
    }
}
