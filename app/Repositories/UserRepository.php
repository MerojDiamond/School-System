<?php

namespace App\Repositories;

use App\Http\Requests\SSRRequest;
use App\Interfaces\UserInterface;
use App\Models\User;
use Modules\User\Http\Requests\UpdateUserRequest;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class UserRepository implements UserInterface
{
    public function total(SSRRequest $request)
    {
        $total = (new User())->where("name", "!=", "Super Admin");
        if ($request->search) $total = User::where("name", "LIKE", "%{$request->search}%")->orWhere("email", "LIKE", "%{$request->search}%");
        return $total->count();
    }

    public function totalTrashed(SSRRequest $request)
    {
        $total = (new User())->onlyTrashed()->where("name", "!=", "Super Admin");
        if ($request->search) $total = User::where("name", "LIKE", "%{$request->search}%")->orWhere("email", "LIKE", "%{$request->search}%");
        return $total->count();
    }

    public function users(SSRRequest $request)
    {
        $users = (new User())->where("name", "!=", "Super Admin");
        if ($request->filters != null) {
            foreach ($request->filters as $k => $v) {
                if ($k != "role" && $v["value"] !== null)
                    $users = $users->where($k, "like", "%" . $v['value'] . "%");
                else if ($k == "role" && $v["value"] != null)
                    $users = $users->whereHas("roles", function ($query) use ($v) {
                        $query->where("id", $v["value"]);
                    });
            }
        }
        if ($request->search) $users = $users->where(function ($query) use ($request) {
            $query->where("name", "LIKE", "%{$request->search}%")
                ->orWhere("email", "LIKE", "%{$request->search}%");
            return $query;
        });
        if ($request->sortBy) $users = $users->orderBy($request->sortBy[0], $request->sortDesc[0] ? "desc" : "asc");
        if ($request->itemsPerPage > 0) $users = $users->offset(($request->page - 1) * $request->itemsPerPage)->limit($request->itemsPerPage);
        $users = $users->with("roles")->get();
        return $users;
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $user = User::where("name", "!=", "Super Admin")->findOrFail($user->id);
        $user->update(["name" => $request->name]);
        $role = Role::where(["id" => $request->role, ["name", "!=", "Super-Admin"]])->get();
        $user->syncRoles([$role]);
        return $user;
    }

    public function trash(User $user)
    {
        $user->destroy($user->id);
        return $user;
    }

    public function trashed(SSRRequest $request)
    {
        $users = (new User())->onlyTrashed()->where("name", "!=", "Super Admin");
        if ($request->search) {
            $users = $users->where(function ($query) use ($request) {
                $query->where("name", "LIKE", "%{$request->search}%")
                    ->orWhere("email", "LIKE", "%{$request->search}%");
            });
        }
        if ($request->sortBy) $users = $users->orderBy($request->sortBy[0], $request->sortDesc[0] ? "desc" : "asc");
        if ($request->itemsPerPage > 0) $users = $users->offset(($request->page - 1) * $request->itemsPerPage)->limit($request->itemsPerPage);
        $users = $users->with("roles")->get();
        return $users;
    }

    public function restore($user)
    {
        $user = User::withTrashed()->find($user);
        $user->restore();
        return $user;
    }

    public function delete($user)
    {
        $user = User::withTrashed()->find($user);
        $user->syncRoles([]);
        Activity::destroy(Activity::select("id")->where("causer_id", $user->id)->get());
        $user->forceDelete();
        // DeleteForeverUser::dispatch($user);
        return $user;
    }
}
