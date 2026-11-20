<?php

namespace Tests\Feature;

use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private function owner(): User
    {
        return User::where('email', 'owner@percetakan.com')->firstOrFail();
    }

    public function test_owner_can_view_company_profile_page(): void
    {
        $this->actingAs($this->owner())->get('/company')->assertOk();
    }

    public function test_non_owner_users_cannot_manage_company_profile(): void
    {
        foreach (['kasir@percetakan.com', 'operator@percetakan.com'] as $email) {
            $user = User::where('email', $email)->firstOrFail();

            $this->actingAs($user)->get('/company')->assertForbidden();
            $this->actingAs($user)->patch('/company', ['name' => 'Bukan Hak Saya'])->assertForbidden();
        }
    }

    public function test_owner_can_update_company_profile(): void
    {
        $this->actingAs($this->owner())
            ->patch('/company', [
                'name' => 'Percetakan Berkah Jaya',
                'address' => 'Jl. Merdeka No. 123',
                'phone' => '081234567890',
                'email' => 'halo@berkahjaya.com',
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $company = CompanyProfile::firstOrFail();

        $this->assertSame('Percetakan Berkah Jaya', $company->name);
        $this->assertSame('Jl. Merdeka No. 123', $company->address);
        $this->assertSame('081234567890', $company->phone);
        $this->assertSame('halo@berkahjaya.com', $company->email);
        $this->assertSame(1, CompanyProfile::count());
    }

    public function test_owner_can_upload_company_logo(): void
    {
        Storage::fake('public');

        $this->actingAs($this->owner())
            ->patch('/company', [
                'name' => 'Percetakan Berkah Jaya',
                'logo' => UploadedFile::fake()->image('logo.png', 200, 200),
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $logo = CompanyProfile::firstOrFail()->logo;

        $this->assertNotNull($logo);
        Storage::disk('public')->assertExists($logo);
    }

    public function test_replacing_company_logo_deletes_the_previous_file(): void
    {
        Storage::fake('public');

        $this->actingAs($this->owner())->patch('/company', [
            'name' => 'Percetakan Berkah Jaya',
            'logo' => UploadedFile::fake()->image('logo-lama.png', 200, 200),
        ]);

        $oldLogo = CompanyProfile::firstOrFail()->logo;

        $this->actingAs($this->owner())->patch('/company', [
            'name' => 'Percetakan Berkah Jaya',
            'logo' => UploadedFile::fake()->image('logo-baru.png', 200, 200),
        ]);

        $newLogo = CompanyProfile::firstOrFail()->logo;

        $this->assertNotSame($oldLogo, $newLogo);
        $this->assertSame(1, CompanyProfile::count());
        Storage::disk('public')->assertMissing($oldLogo);
        Storage::disk('public')->assertExists($newLogo);
    }

    public function test_company_name_is_required(): void
    {
        $this->actingAs($this->owner())
            ->patch('/company', ['name' => ''])
            ->assertSessionHasErrors('name');
    }

    public function test_logo_must_be_an_image(): void
    {
        Storage::fake('public');

        $this->actingAs($this->owner())
            ->patch('/company', [
                'name' => 'Percetakan Berkah Jaya',
                'logo' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
            ])
            ->assertSessionHasErrors('logo');

        Storage::disk('public')->assertDirectoryEmpty('logos');
    }
}
