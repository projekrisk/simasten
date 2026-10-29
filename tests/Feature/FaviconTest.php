<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaviconTest extends TestCase
{
    use RefreshDatabase;

    public function test_icon_route_returns_png_response(): void
    {
        $response = $this->get('/icon.png');

        $response->assertOk();
        $this->assertEquals('image/png', $response->headers->get('Content-Type'));
    }

    public function test_favicon_ico_route_returns_png_response(): void
    {
        $response = $this->get('/favicon.ico');

        $response->assertOk();
        $this->assertEquals('image/png', $response->headers->get('Content-Type'));
    }

    public function test_admin_pages_render_icon_in_favicon_link(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk();
        $response->assertSee('rel="icon"', false);
        $response->assertSee('icon.png', false);
    }

    public function test_authenticated_admin_pages_render_icon_in_favicon_link(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertOk();
        $response->assertSee('rel="icon"', false);
        $response->assertSee('icon.png', false);
    }
}
