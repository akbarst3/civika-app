<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class dashboardStatistikaIpk extends TestCase
{
    /**
     * A basic feature test example.
     */

     // apa yang di test?
     // 1. harus login untuk mengakses dashboard statistik
     // 2. user login bisa mengakses dashboard statistik
     // 

     public function test_harus_login()
    {
        $response = $this->get('/dashboard/ipk/prodi');

        $response->assertRedirect('/login'); // sesuaikan homepage nya ya bro bro
    }

    public function test_user_login_bisa_mengakses_dashboard_statistik()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/ipk/prodi');

        $response->assertStatus(200);
        //200 = OK
        //302 = Redirect pada 5/25/2025 masih redirect 
    }

    

}
