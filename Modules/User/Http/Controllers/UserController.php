<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SSRRequest;
use App\Interfaces\RoleInterface;
use App\Interfaces\UserInterface;
use App\Models\User;
use App\Services\Response\ResponseService;
use Modules\User\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    private $repository;
    private $roleRepository;
    public function __construct(UserInterface $repository, RoleInterface $roleRepository)
    {
        $this->repository = $repository;
        $this->roleRepository = $roleRepository;
    }
    public function index(SSRRequest $request)
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "users" => $this->repository->users($request),
            "total" => $this->repository->total($request),
            "message" => "getted users"
        ]);
    }
    public function update(User $user, UpdateUserRequest $request)
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "user" => $this->repository->update($user, $request),
            "message" => "updated user"
        ]);
    }
    public function delete(User $user)
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "user" => $this->repository->trash($user),
            "message" => "deleted user"
        ]);
    }
    public function trashed(SSRRequest $request)
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "users" => $this->repository->trashed($request),
            "total" => $this->repository->totalTrashed($request),
            "message" => "getted trashedusers"
        ]);
    }
    public function restore($user)
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "user" => $this->repository->restore($user),
            "message" => "restored user"
        ]);
    }
    public function destroyForever($user)
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "user" => $this->repository->delete($user),
            "message" => "deleted user"
        ]);
    }
}
