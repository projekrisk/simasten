<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\EditProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_profile_page(): void
    {
        $response = $this->get('/admin/profile');

        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_access_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Administrator Utama',
            'email' => 'admin@sman1malingping.sch.id',
        ]);

        $response = $this->actingAs($user)->get('/admin/profile');

        $response->assertOk();
        $response->assertSee('Profil Saya');
        $response->assertSee('Informasi Akun');
        $response->assertSee('Ubah Kata Sandi');
        $response->assertSee('Administrator Utama');
    }

    public function test_user_menu_contains_profile_link(): void
    {
        $user = User::factory()->create([
            'name' => 'Kepala Lab',
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
        $response->assertSee(route('filament.admin.auth.profile'));
    }

    public function test_admin_can_update_name(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);

        Livewire::actingAs($user)
            ->test(EditProfile::class)
            ->fillForm([
                'name' => 'Nama Baru Administrator',
                'email' => 'admin@test.com',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru Administrator',
            'email' => 'admin@test.com',
        ]);
    }

    public function test_admin_can_update_password_with_valid_current_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin Password Test',
            'email' => 'pwd@test.com',
            'password' => Hash::make('old-password-123'),
        ]);

        Livewire::actingAs($user)
            ->test(EditProfile::class)
            ->fillForm([
                'name' => 'Admin Password Test',
                'email' => 'pwd@test.com',
                'currentPassword' => 'old-password-123',
                'password' => 'new-secure-password-456',
                'passwordConfirmation' => 'new-secure-password-456',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $user->refresh();
        $this->assertTrue(Hash::check('new-secure-password-456', $user->password));
    }

    public function test_admin_cannot_update_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin Wrong Pwd',
            'email' => 'wrong@test.com',
            'password' => Hash::make('correct-password-123'),
        ]);

        Livewire::actingAs($user)
            ->test(EditProfile::class)
            ->fillForm([
                'name' => 'Admin Wrong Pwd',
                'email' => 'wrong@test.com',
                'currentPassword' => 'wrong-password-here',
                'password' => 'new-secret-password-789',
                'passwordConfirmation' => 'new-secret-password-789',
            ])
            ->call('save')
            ->assertHasFormErrors(['currentPassword']);

        $user->refresh();
        $this->assertTrue(Hash::check('correct-password-123', $user->password));
    }
}
