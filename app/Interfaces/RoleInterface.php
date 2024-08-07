<?php

namespace App\Interfaces;

use App\Http\Requests\SSRRequest;
use Modules\Role\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Role;

interface RoleInterface
{
    public function total(SSRRequest $request);
    public function allRoles();
    public function roles(SSRRequest $request);
    public function permissions();
    public function store(RoleRequest $request);
    public function update(Role $role, RoleRequest $request);
    public function delete(Role $role);
}
