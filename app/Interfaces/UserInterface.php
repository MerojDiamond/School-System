<?php

namespace App\Interfaces;

use App\Http\Requests\SSRRequest;
use App\Models\User;
use Modules\User\Http\Requests\UpdateUserRequest;

interface UserInterface
{
    public function total(SSRRequest $request);

    public function totalTrashed(SSRRequest $request);

    public function users(SSRRequest $request);

    public function update(User $user, UpdateUserRequest $request);

    public function trash(User $user);

    public function trashed(SSRRequest $request);

    public function restore($user);

    public function delete($user);
}
