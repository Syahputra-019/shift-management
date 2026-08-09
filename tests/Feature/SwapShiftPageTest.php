<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SwapShiftPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_swap_shift_page_is_accessible_to_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/swap-shift');

        $response->assertStatus(200);
    }
}
