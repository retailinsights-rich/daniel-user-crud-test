<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\OrganizationUser;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\PasswordValidationRules;

class UserController extends Controller
{
    use PasswordValidationRules;

    public function index(Request $request)
    {
        $organization = session('organization');

        $users = User::select('users.id', 'users.name', 'users.email', 'organization_user.is_active', 'organization_user.is_admin')
            ->join('organization_user', function($query) use ($organization) {
                $query->on('organization_user.user_id', '=', 'users.id')
                    ->where('organization_user.organization_id', '=', $organization->id);
            })
            ->orderBy('name')
            ->paginate(200);

        return response()->json([
            'users' => $users,
        ]);
    }

    public function getLoggedInUser(Request $request) {
        if (!$request->user()) {
            return ['loggedIn' => false, 'user' => null, 'organization' => null, 'is_admin' => false];
        }

        $organization = $request->user()->organizations()->first();
        session(['organization' => $organization]);

        $organization_user = OrganizationUser::where('user_id', $request->user()->id)->where('organization_id', $organization->id)->first();

        return [
            'must_reset_password' => $request->user()->must_reset_password,
            'loggedIn' => true,
            'user' => $request->user(), 
            'organization' => $organization, 
            'is_admin' => $organization_user->is_admin
        ];
    }

    public function create(Request $request) {
        $logged_in_user = $request->user();
        $organization = session('organization');
        $organization_user = OrganizationUser::where('user_id', $logged_in_user->id)->where('organization_id', $organization->id)->first();

        if (!$organization_user->is_admin) {
            return response(json_encode(['message' => 'You are not allowed to create a user.']), 422);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('Temp1234!'),
            'must_reset_password' => true,
        ]);

        $organization = session('organization');
        $is_admin = $request->is_admin === 'true';
        $user->organizations()->attach($organization->id, ['is_admin' => $is_admin]);

        return response()->json([
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user) {
        $logged_in_user = $request->user();
        $organization = session('organization');
        $logged_in_organization_user = OrganizationUser::where('user_id', $logged_in_user->id)->where('organization_id', $organization->id)->first();

        if (!$logged_in_organization_user->is_admin) {
            return response(json_encode(['message' => 'You are not allowed to update a user.']), 422);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        $organization_user = OrganizationUser::where('user_id', $user->id)->where('organization_id', $organization->id)->first();
        $organization_user->is_admin = $request->is_admin === 'true';
        $organization_user->save();

        return response()->json([
            'user' => $user,
        ]);
    }
    
    public function delete(Request $request, User $user) {
        $logged_in_user = $request->user();
        $organization = session('organization');
        $logged_in_organization_user = OrganizationUser::where('user_id', $logged_in_user->id)->where('organization_id', $organization->id)->first();

        if (!$logged_in_organization_user->is_admin) {
            return response(json_encode(['message' => 'You are not allowed to remove a user.']), 422);
        }

        $user->delete();

        return response()->json([
            'user' => $user,
        ]);
    }

    public function show(Request $request, User $user) {
        $organization = session('organization');
        $organization_user = OrganizationUser::where('user_id', $user->id)->where('organization_id', $organization->id)->first();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $organization_user->is_admin,
            ],
        ]);
    }

    public function resetPassword(Request $request) {
        $user = $request->user();

        $request->validate([
            'password' => $this->passwordRules(),
        ]);

        $user->password = Hash::make($request->password);
        $user->must_reset_password = false;
        $user->save();

        return response()->json([
            'message' => 'Password Reset Successful',
        ]);
    }
}