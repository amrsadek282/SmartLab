<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

// ──────────────────────────────────────────────────────
// Access control
// ──────────────────────────────────────────────────────

test('admin can access staff management index', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertStatus(200);
});

test('non-admin users are redirected away from staff management', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);

    $this->actingAs($receptionist)->get(route('admin.users.index'))->assertForbidden();
    $this->actingAs($receptionist)->get(route('admin.users.create'))->assertForbidden();
});

// ──────────────────────────────────────────────────────
// Index – filtering & pagination
// ──────────────────────────────────────────────────────

test('staff index search filters by name email or phone', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['name' => 'Nadia Youssef', 'role' => 'technician']);
    User::factory()->create(['name' => 'Khalid Mansour', 'role' => 'receptionist']);

    $response = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'Nadia']));

    $response->assertStatus(200)->assertSee('Nadia Youssef')->assertDontSee('Khalid Mansour');
});

test('staff index can be filtered by role', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['name' => 'Tech User', 'role' => 'technician']);
    User::factory()->create(['name' => 'Recept User', 'role' => 'receptionist']);

    $response = $this->actingAs($admin)->get(route('admin.users.index', ['role' => 'technician']));

    $response->assertStatus(200)->assertSee('Tech User')->assertDontSee('Recept User');
});

// ──────────────────────────────────────────────────────
// Create
// ──────────────────────────────────────────────────────

test('admin can create a new staff member with valid data', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name'                  => 'Sara Khalil',
        'email'                 => 'sara.khalil@smartlab.test',
        'phone'                 => '01098765432',
        'role'                  => 'technician',
        'password'              => 'Password@123',
        'password_confirmation' => 'Password@123',
        'is_active'             => true,
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'email' => 'sara.khalil@smartlab.test',
        'role'  => 'technician',
    ]);
});

test('staff creation fails with invalid or duplicate email', function () {
    $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@smartlab.test']);

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name'                  => 'Duplicate Admin',
        'email'                 => 'admin@smartlab.test',
        'role'                  => 'admin',
        'password'              => 'Password@123',
        'password_confirmation' => 'Password@123',
    ]);

    $response->assertSessionHasErrors('email');
});

// ──────────────────────────────────────────────────────
// Show profile
// ──────────────────────────────────────────────────────

test('admin can view a staff member profile page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $staff = User::factory()->create(['name' => 'Hassan Farouk', 'role' => 'receptionist']);

    $response = $this->actingAs($admin)->get(route('admin.users.show', $staff));

    $response->assertStatus(200)->assertSee('Hassan Farouk');
});

// ──────────────────────────────────────────────────────
// Update
// ──────────────────────────────────────────────────────

test('admin can update a staff member name and role', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $staff = User::factory()->create(['name' => 'Old Name', 'role' => 'receptionist']);

    $response = $this->actingAs($admin)->put(route('admin.users.update', $staff), [
        'name'  => 'New Name',
        'email' => $staff->email,
        'role'  => 'technician',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('success');

    $staff->refresh();
    expect($staff->name)->toBe('New Name')
        ->and($staff->role)->toBe('technician');
});

test('password is re-hashed when updated', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $staff = User::factory()->create();

    $this->actingAs($admin)->put(route('admin.users.update', $staff), [
        'name'                  => $staff->name,
        'email'                 => $staff->email,
        'role'                  => $staff->role,
        'password'              => 'NewPass@456',
        'password_confirmation' => 'NewPass@456',
    ]);

    $staff->refresh();
    expect(Hash::check('NewPass@456', $staff->password))->toBeTrue();
});

test('password is not changed when left blank during update', function () {
    $admin  = User::factory()->create(['role' => 'admin']);
    $staff  = User::factory()->create(['password' => Hash::make('OriginalPass@1')]);
    $oldHash = $staff->password;

    $this->actingAs($admin)->put(route('admin.users.update', $staff), [
        'name'  => $staff->name,
        'email' => $staff->email,
        'role'  => $staff->role,
    ]);

    $staff->refresh();
    expect($staff->password)->toBe($oldHash);
});

// ──────────────────────────────────────────────────────
// Toggle status
// ──────────────────────────────────────────────────────

test('admin can toggle a staff member active status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $staff = User::factory()->create(['is_active' => true]);

    $this->actingAs($admin)->patch(route('admin.users.toggle-status', $staff));

    $staff->refresh();
    expect($staff->is_active)->toBeFalse();
});

test('cannot deactivate the only active administrator', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->patch(route('admin.users.toggle-status', $admin));

    $response->assertRedirect()->assertSessionHas('error');
    $admin->refresh();
    expect($admin->is_active)->toBeTrue();
});

// ──────────────────────────────────────────────────────
// Destroy / safe deletion
// ──────────────────────────────────────────────────────

test('admin can hard-delete a staff member with no associated records', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $staff = User::factory()->create(['role' => 'receptionist']);

    $this->actingAs($admin)->delete(route('admin.users.destroy', $staff));

    $this->assertDatabaseMissing('users', ['id' => $staff->id]);
});

test('staff member with associated orders is deactivated instead of hard-deleted', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $staff = User::factory()->create(['role' => 'receptionist', 'is_active' => true]);
    Order::factory()->create(['created_by' => $staff->id]);

    $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $staff));

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('warning');

    $staff->refresh();
    expect($staff->is_active)->toBeFalse();
    $this->assertDatabaseHas('users', ['id' => $staff->id]);
});

test('admin cannot delete their own account', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('cannot delete the last active administrator', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

    $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('error');
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});
