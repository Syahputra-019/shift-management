<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest is redirected to login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user can access the profile page.
     */
    public function test_profile_page_is_accessible_to_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    /**
     * Test authenticated user can update their profile.
     */
    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'avatar_color' => 'bg-blue-600',
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'avatar_color' => 'bg-emerald-600',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Profil Anda berhasil diperbarui.');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals('bg-emerald-600', $user->avatar_color);
    }

    /**
     * Test authenticated user can update their password.
     */
    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Kata sandi berhasil diperbarui.');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /**
     * Test password validation error when current password is wrong.
     */
    public function test_user_cannot_update_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $user->refresh();
        $this->assertTrue(Hash::check('oldpassword', $user->password));
    }
}
