<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\StatistikController;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\IndeksPrestasiSemester;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery;

class StatistikControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new StatistikController();
    }

    public function test_MahasiswaWithRelations()
    {
        $queryBuilder = Mockery::mock();
        $queryBuilder->shouldReceive('with')
            ->with(['kelas.prodi', 'indeksPrestasiSemester'])
            ->andReturnSelf();
        $queryBuilder->shouldReceive('get')
            ->andReturn(collect([]));

        Mockery::mock('alias:App\Models\Mahasiswa')
            ->shouldReceive('with')
            ->with(['kelas.prodi', 'indeksPrestasiSemester'])
            ->andReturn($queryBuilder);

        $method = new \ReflectionMethod(StatistikController::class, 'getMahasiswaWithRelations');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->controller);
        
        $this->assertInstanceOf(Collection::class, $result);
    }

    public function test_groupMahasiswaByAngkatanAndProdi()
    {
        // sample data
        $mahasiswa = new Mahasiswa();
        $kelas = new Kelas(['angkatan' => '2020']);
        $prodi = new Prodi(['nama_prodi' => 'Informatika']);
        $kelas->prodi = $prodi;
        $mahasiswa->kelas = $kelas;
        
        $collection = collect([$mahasiswa]);

        $method = new \ReflectionMethod(StatistikController::class, 'groupMahasiswaByAngkatanAndProdi');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->controller, $collection);
        
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertArrayHasKey('2020_Informatika', $result->toArray());
    }

    public function test_calculateAverageIpsPerGroup()
    {
        // sample data
        $mahasiswa = new Mahasiswa();
        $kelas = new Kelas(['angkatan' => '2020']);
        $prodi = new Prodi(['nama_prodi' => 'Informatika']);
        $kelas->prodi = $prodi;
        $mahasiswa->kelas = $kelas;
        
        $ips = new IndeksPrestasiSemester(['indeks_prestasi' => 3.5]);
        $mahasiswa->indeksPrestasiSemester = collect([$ips]);
        
        $grouped = collect([
            '2020_Informatika' => collect([$mahasiswa])
        ]);

        $method = new \ReflectionMethod(StatistikController::class, 'calculateAverageIpsPerGroup');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->controller, $grouped);
        
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals([
            [
                'angkatan' => '2020',
                'prodi' => 'Informatika',
                'rata_rata_ips' => 3.50
            ]
        ], $result->toArray());

    }

    public function test_AngkatanPerProdi()
    {
        // sample data
        $data = collect([
            ['angkatan' => '2020', 'prodi' => 'Informatika', 'rata_rata_ips' => 3.5],
            ['angkatan' => '2021', 'prodi' => 'Informatika', 'rata_rata_ips' => 3.7],
            ['angkatan' => '2020', 'prodi' => 'Sistem Informasi', 'rata_rata_ips' => 3.6],
        ]);

        
        $method = new \ReflectionMethod(StatistikController::class, 'getAngkatanPerProdi');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->controller, $data);
        
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals([
            'Informatika' => ['2020', '2021'],
            'Sistem Informasi' => ['2020']
        ], $result->toArray());
    }
    # ini bakal gagal sekarang karena belum ada aturan redirect nya
    public function test_harus_login()
    {
        $response = $this->get('/statistik');

        $response->assertRedirect('/login');
    }

    #yang memiliki akses adalah KaProdi dan Staff Tata Usaha
    # ini bakal gagal sekarang karena belum ada middleware yang mengatur dan belum di merge juga
    public function test_user_memiliki_akses()
    {
        $user = \App\Models\User::factory()->create(['role' => 'KaProdi']);

        $response = $this->actingAs($user)->get('/statistik');

        $response->assertStatus(200);// akses sukses

    }

    # ini bakal gagal sekarang karena belum ada middleware yang mengatur dan belum di merge juga
    public function test_user_tidak_memiliki_akses()
    {
        $user = \App\Models\User::factory()->create(['role' => 'Mahasiswa']);

        $response = $this->actingAs($user)->get('/statistik');

        $response->assertStatus(403);//Forbiden
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}