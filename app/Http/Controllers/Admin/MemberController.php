<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    /**
     * Display a listing of all team members.
     */
    public function index()
    {
        $members = User::withCount([
            'assignedTasks as total_tasks',
            'assignedTasks as pending_tasks' => function ($q) {
                $q->whereIn('status', ['pending', 'in_progress']);
            },
            'assignedTasks as completed_tasks' => function ($q) {
                $q->where('status', 'completed');
            },
        ])->orderBy('name')->paginate(10);

        return view('admin.members.index', compact('members'));
    }

    /**
     * Show the form for creating a new team member.
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * Store a newly created team member.
     */
    public function store(StoreMemberRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'department' => $validated['department'] ?? 'General',
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', "Team member \"{$user->name}\" added successfully!");
    }

    /**
     * Show the form for editing the team member.
     */
    public function edit(User $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    /**
     * Update the team member details.
     */
    public function update(UpdateMemberRequest $request, User $member)
    {
        $validated = $request->validated();

        $member->name = $validated['name'];
        $member->email = $validated['email'];
        $member->role = $validated['role'];
        $member->department = $validated['department'] ?? 'General';
        $member->phone = $validated['phone'] ?? null;

        if (!empty($validated['password'])) {
            $member->password = Hash::make($validated['password']);
        }

        $member->save();

        return redirect()->route('admin.members.index')
            ->with('success', "Team member \"{$member->name}\" updated successfully!");
    }

    /**
     * Remove the team member from storage.
     */
    public function destroy(User $member)
    {
        if ($member->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own active administrator account.');
        }

        $name = $member->name;
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', "Team member \"{$name}\" was removed successfully.");
    }
}
