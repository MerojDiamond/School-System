<?php

namespace App\Http\Controllers;

use App\Http\Requests\SSRRequest;
use App\Models\File;
use App\Models\Settings;
use App\Models\User;
use App\Services\Response\ResponseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\User\Http\Requests\UserRequest;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class MainController extends Controller
{
    public function login(UserRequest $request)
    {
        Auth::attempt($request->all());
        $user = User::where("email", $request->email)->first();
        if (!Auth::user()) {
            return ResponseService::sendJsonResponse(false, 401, [
                "message" => ["Invalid email or password"],
            ], []);
        }
        if ($user->status == 0) {
            return ResponseService::sendJsonResponse(false, 402, [
                "message" => ["Your status is not activated"],
            ], []);
        }
        activity("user")->causedBy($user)->log("entered");
        return ResponseService::sendJsonResponse(true, 200, [], [
            "user" => $user,
            "token" => $user->createToken("login_token")->plainTextToken,
            "role" => $user->roles[0],
            "permissions" => $user->roles[0]->permissions
        ]);
    }

    public function register(Request $request)
    {
        if ($request->step > 1) {
            $user = User::find($request->user_id);
            if ($request->photo) {
                list($mime_type, $encoded_image) = explode(';', $request->photo);
                list(, $file_extension) = explode('/', $mime_type);
                $encoded_image = str_replace('data:image/' . $file_extension . ';base64,', '', $request->photo);
                $decoded_image = base64_decode($encoded_image);
                $filename = uniqid() . '.' . $file_extension;
                Storage::put($filename, $decoded_image);
                $user->update(["photo" => Storage::url($filename)]);
            } else if ($request->storage) {
                list($mime_type, $encoded_image) = explode(';', $request->storage);
                list(, $file_extension) = explode('/', $mime_type);
                $encoded_file = str_replace('data:image/' . $file_extension . ';base64,', '', $request->storage);
                $decoded_file = base64_decode($encoded_file);
                $filename = "{$user->id}/" . uniqid() . '.' . $file_extension;
                Storage::put($filename, $decoded_file);
                File::create([
                    "user_id" => $user->id,
                    "url" => Storage::url($filename)
                ]);
            } else {
                $user->update($request->all());
            }
        } else {
            $user = User::where("email", $request->email)->first();
            if ($user) {
                return ResponseService::sendJsonResponse(false, 401, [
                    "message" => "errors.emailTaken",
                ]);
            }
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password
            ]);
            $user->assignRole(Role::find(2)->name);
        }
        return ResponseService::sendJsonResponse(true, 200, [], [
            "user" => $user,
            "message" => $request->step == 4 ? "success.registered" : "success.savedData"
        ]);
    }

    public function registerData(User $id)
    {
        return ResponseService::sendJsonResponse(true, 200, [], [
            "user" => $id,
        ]);
    }

    public function logs(SSRRequest $request)
    {
        if ($request->search) {
            $total = Activity::where("log_name", "LIKE", "%{$request->search}%")->count();
        } else {
            $total = Activity::count();
        }
        $logs = new Activity();
        if ($request->search) $logs = $logs->where("log_name", "LIKE", "%{$request->search}%");
        if ($request->sortBy) $logs = $logs->orderBy($request->sortBy[0], $request->sortDesc[0] ? "desc" : "asc");
        if ($request->itemsPerPage > 0) $logs = $logs->offset(($request->page - 1) * $request->itemsPerPage)->limit($request->itemsPerPage);
        $logs = $logs->get();
        return ResponseService::sendJsonResponse(true, 200, [], [
            "logs" => $logs,
            "total" => $total,
            "message" => "getted logs"
        ]);
    }

    public function log($id)
    {
        $log = Activity::find($id);
        $diff = Carbon::parse($log->created_at);
        $causer = null;
        try {
            $causer = $log->causer;
            $causer->roles;
        } catch (\Throwable $e) {
        }
        return ResponseService::sendJsonResponse(true, 200, [], [
            "date" => $diff->diffForHumans(),
            "log" => $log,
            "causer" => $causer,
            "changes" => $log->changes,
            "message" => "gettedone log"
        ]);
    }

    public function settings(Request $request)
    {
        $settings = Settings::where("user_id", Auth::user()->id)->first();
        if (!$settings)
            $settings = Settings::create(["user_id" => Auth::user()->id]);
        $settings->update([
            "lang" => $request->lang,
            "theme" => $request->theme,
            "users_table" => $request->users_table,
            "roles_table" => $request->roles_table,
        ]);
        return ResponseService::sendJsonResponse(true, 200, [], [
            "message" => "sent settings"
        ]);
    }

    public function getSettings($id)
    {
        $settings = Settings::where("user_id", $id)->first();
        return ResponseService::sendJsonResponse(true, 200, [], [
            "settings" => $settings,
            "message" => "get settings"
        ]);
    }
}
