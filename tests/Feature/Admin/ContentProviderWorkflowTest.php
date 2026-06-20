<?php

namespace Tests\Feature\Admin;

use App\Models\ContentProviderApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentProviderWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_approve_application_and_provider_gets_notification(): void
    {
        $admin = $this->adminUser();
        $provider = User::factory()->create([
            'is_content_provider' => true,
            'content_provider_status' => 'pending',
            'can_login' => false,
        ]);

        $application = ContentProviderApplication::create([
            'user_id' => $provider->id,
            'full_name' => $provider->name,
            'email' => $provider->email,
            'activity_type' => 'hotel',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->post(route('admin.content-providers.approve', $application), [
                'admin_notes' => 'تمت الموافقة بعد التحقق من المستندات.',
            ]);

        $response->assertRedirect(route('admin.content-providers.show', $application));
        $provider->refresh();
        $application->refresh();

        $this->assertSame('approved', $application->status);
        $this->assertSame('approved', $provider->content_provider_status);
        $this->assertTrue((bool) $provider->can_login);
        $this->assertDatabaseCount('notifications', 1);
    }

    public function test_reject_requires_reason_and_does_not_update_without_it(): void
    {
        $admin = $this->adminUser();
        $provider = User::factory()->create([
            'is_content_provider' => true,
            'content_provider_status' => 'pending',
            'can_login' => false,
        ]);

        $application = ContentProviderApplication::create([
            'user_id' => $provider->id,
            'full_name' => $provider->name,
            'email' => $provider->email,
            'activity_type' => 'hotel',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin, 'admin')
            ->from(route('admin.content-providers.show', $application))
            ->post(route('admin.content-providers.reject', $application), [
                'admin_notes' => '',
            ]);

        $response->assertRedirect(route('admin.content-providers.show', $application));
        $response->assertSessionHasErrors('admin_notes');
        $application->refresh();

        $this->assertSame('pending', $application->status);
    }

    public function test_admin_can_view_uploaded_document_and_missing_file_returns_404(): void
    {
        $admin = $this->adminUser();
        $provider = User::factory()->create([
            'is_content_provider' => true,
            'content_provider_status' => 'pending',
            'can_login' => false,
        ]);

        Storage::fake('public');
        $path = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf')
            ->store('provider-documents', 'public');

        $application = ContentProviderApplication::create([
            'user_id' => $provider->id,
            'full_name' => $provider->name,
            'email' => $provider->email,
            'activity_type' => 'hotel',
            'status' => 'pending',
            'commercial_registration_path' => $path,
        ]);

        $okResponse = $this->actingAs($admin, 'admin')
            ->get(route('admin.content-providers.documents.view', [$application, 'document' => 'commercial-registration']));
        $okResponse->assertOk();

        Storage::disk('public')->delete($path);
        $missingResponse = $this->actingAs($admin, 'admin')
            ->get(route('admin.content-providers.documents.view', [$application, 'document' => 'commercial-registration']));
        $missingResponse->assertNotFound();
    }
}

