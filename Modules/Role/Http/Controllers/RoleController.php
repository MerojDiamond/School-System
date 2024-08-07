<?php

namespace Modules\Role\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SSRRequest;
use App\Interfaces\RoleInterface;
use App\Services\Response\ResponseService;
use Modules\Role\Http\Requests\RoleRequest;
use \Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private $repository;
    public function __construct(RoleInterface $repository)
    {
        $this->repository = $repository;
    }
    public function index(SSRRequest $request)
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "roles" => $this->repository->roles($request),
            "total" => $this->repository->total($request),
            "message" => "getted roles"
        ]);
    }
    public function allRoles()
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "roles" => $this->repository->allRoles(),
        ]);
    }
    public function permissions()
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "permissions" => $this->repository->permissions()
        ]);
    }
    public function store(RoleRequest $request)
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "role" => $this->repository->store($request),
            "message" => "created role"
        ]);
    }
    public function update(Role $role, RoleRequest $request)
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "role" => $this->repository->update($role, $request),
            "message" => "updated role"
        ]);
    }
    public function delete(Role $role)
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "role" => $this->repository->delete($role),
            "message" => "deleted role"
        ]);
    }
}
