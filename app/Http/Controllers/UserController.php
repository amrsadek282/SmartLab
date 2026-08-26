<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users/staff.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($role, fn ($query, $role) => $query->where('role', $role))
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->withCount(['createdOrders', 'enteredResults', 'generatedReports'])
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $roles = ['admin' => 'Administrator', 'receptionist' => 'Receptionist', 'technician' => 'Lab Technician'];

        return view('admin.users.index', compact('users', 'search', 'role', 'status', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Staff member '{$user->name}' ({$user->role}) created successfully.");
    }

    /**
     * Display the specified user profile and activity.
     */
    public function show(User $user): View
    {
        $user->loadCount(['createdOrders', 'enteredResults', 'generatedReports']);

        $recentOrders = $user->createdOrders()->with('patient')->latest()->limit(5)->get();
        $recentResults = $user->enteredResults()->with('orderItem.test')->latest()->limit(5)->get();
        $recentReports = $user->generatedReports()->with('order.patient')->latest()->limit(5)->get();

        return view('admin.users.show', compact('user', 'recentOrders', 'recentResults', 'recentReports'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Staff member '{$user->name}' updated successfully.");
    }

    /**
     * Quick toggle user active/inactive status.
     */
    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        if ($user->isAdmin() && $user->isActive()) {
            $adminCount = User::where('role', 'admin')->where('is_active', true)->count();
            if ($adminCount <= 1) {
                return redirect()
                    ->back()
                    ->with('error', 'Cannot deactivate the only active Administrator in the system.');
            }
        }

        $newStatus = ! $user->is_active;
        $user->update(['is_active' => $newStatus]);

        $statusLabel = $newStatus ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "User '{$user->name}' has been {$statusLabel}.");
    }

    /**
     * Remove the specified user from storage safely.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        // 1. Prevent deleting self
        if ($request->user() && $request->user()->id === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'You cannot delete your own active administrator account.');
        }

        // 2. Prevent deleting last admin
        if ($user->isAdmin()) {
            $adminCount = User::where('role', 'admin')->where('is_active', true)->count();
            if ($adminCount <= 1) {
                return redirect()
                    ->route('admin.users.index')
                    ->with('error', 'Cannot delete the only Administrator in the system.');
            }
        }

        // 3. Prevent foreign key violations: If user has associated orders/results/reports, deactivate instead
        if ($user->hasAssociatedRecords()) {
            $user->update(['is_active' => false]);

            return redirect()
                ->route('admin.users.index')
                ->with('warning', "User '{$user->name}' cannot be hard deleted because they are linked to historical orders, results, or reports. The account has been deactivated instead.");
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User '{$name}' deleted successfully.");
    }
}
