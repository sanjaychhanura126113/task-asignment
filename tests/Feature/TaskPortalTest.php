<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login_page(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');

        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign In to Portal');
    }

    public function test_quick_demo_login_as_admin(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        $response = $this->get(route('quick.login', $admin->id));
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_quick_demo_login_as_member(): void
    {
        $member = User::where('role', 'member')->first();
        $this->assertNotNull($member);

        $response = $this->get(route('quick.login', $member->id));
        $response->assertRedirect(route('member.dashboard'));
        $this->assertAuthenticatedAs($member);
    }

    public function test_member_cannot_access_admin_dashboard(): void
    {
        $member = User::where('role', 'member')->first();
        $this->actingAs($member);

        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('member.dashboard'));
    }

    public function test_admin_can_view_dashboard_and_task_listings(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Administrative Dashboard');

        $response = $this->get(route('admin.tasks.index'));
        $response->assertStatus(200);
        $response->assertSee('Task Assignment Directory');
    }

    public function test_task_creation_validation(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        // Submit empty form to trigger validation errors
        $response = $this->post(route('admin.tasks.store'), []);
        $response->assertSessionHasErrors(['title', 'assigned_to', 'priority', 'status', 'due_date']);
    }

    public function test_admin_can_create_edit_and_delete_task(): void
    {
        $admin = User::where('role', 'admin')->first();
        $member = User::where('role', 'member')->first();
        $this->actingAs($admin);

        // 1. Create Task
        $response = $this->post(route('admin.tasks.store'), [
            'title' => 'Automated Test Assignment',
            'description' => 'Verifying full CRUD flow and validation.',
            'assigned_to' => $member->id,
            'priority' => 'high',
            'status' => 'pending',
            'due_date' => now()->addDays(4)->format('Y-m-d'),
            'initial_comment' => 'Initial test comment',
        ]);

        $response->assertRedirect(route('admin.tasks.index'));
        $response->assertSessionHas('success');

        $task = Task::where('title', 'Automated Test Assignment')->first();
        $this->assertNotNull($task);

        // 2. Update Task
        $response = $this->put(route('admin.tasks.update', $task), [
            'title' => 'Automated Test Assignment (Updated)',
            'description' => 'Updated description.',
            'assigned_to' => $member->id,
            'priority' => 'urgent',
            'status' => 'in_progress',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'update_note' => 'Admin changed status to In Progress',
        ]);

        $response->assertRedirect(route('admin.tasks.show', $task));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Automated Test Assignment (Updated)',
            'status' => 'in_progress',
        ]);

        // 3. Delete Task
        $response = $this->delete(route('admin.tasks.destroy', $task));
        $response->assertRedirect(route('admin.tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_member_can_update_their_own_task_status(): void
    {
        $member = User::where('role', 'member')->first();
        $this->actingAs($member);

        $task = Task::where('assigned_to', $member->id)->first();
        $this->assertNotNull($task);

        $response = $this->post(route('member.tasks.status.update', $task), [
            'status' => 'completed',
            'note' => 'Successfully finished all requirements.',
        ]);

        $response->assertRedirect(route('member.tasks.show', $task));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
    }
}
