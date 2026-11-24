<?php

namespace UiKitExport;

use App\Models\Admin;
use App\Models\Agency;
use App\Models\Organisation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use RuntimeException;
use Tests\Support\BrowserWorkspace;
use Tests\Support\TenantTestFixture;
use Tests\TestCase;

/**
 * Renders the layout pages through the app's real shells, signed in as fictional users:
 * an owner with every permission (two organisations, notifications), a restricted member,
 * a platform administrator, and a guest. Demo routes are named inside an existing route group
 * (admin.reservations.*, platform.organisations.*) so the matching navigation item is current.
 */
final class LayoutWorkspace
{
    /** Permissions of the owner: every navigation item of the tenant shell is visible. */
    private const OWNER_PERMISSIONS = [
        'view-quotations', 'view-users', 'view-roles', 'view-permissions', 'view-activity-logs', 'view-vehicles',
    ];

    /** @param array<string, string> $page */
    public static function render(TestCase $test, array $page): string
    {
        return match ($page['kind']) {
            'tenant', 'tenant-field' => self::tenant($test, $page, restricted: false),
            'tenant-restricted' => self::tenant($test, $page, restricted: true),
            'platform' => self::platform($test, $page),
            'guest' => self::guest($test, $page),
            default => throw new RuntimeException('Unknown layout kind: '.$page['kind']),
        };
    }

    /** @var array{organisation: Organisation, agency: Agency, owner: User, member: User}|null */
    private static ?array $tenant = null;

    /**
     * One organisation ("Atlas Location", agencies Rabat Centre and Casablanca Maarif) for every
     * tenant page of an export run: an owner with every permission, a second organisation and
     * three notifications, and a restricted member.
     *
     * @return array{organisation: Organisation, agency: Agency, owner: User, member: User}
     */
    private static function tenantWorkspace(): array
    {
        if (self::$tenant !== null && self::$tenant['organisation']->exists && Organisation::query()->whereKey(self::$tenant['organisation']->id)->exists()) {
            return self::$tenant;
        }

        ['organisation' => $organisation, 'agency' => $agency, 'owner' => $owner] = BrowserWorkspace::create();
        $organisation->forceFill(['name' => 'Atlas Location'])->save();
        $agency->forceFill(['name' => 'Rabat Centre'])->save();
        Agency::query()->where('organisation_id', $organisation->id)->whereKeyNot($agency->id)->update(['name' => 'Casablanca Maarif']);

        $owner->forceFill(['nom' => 'Idrissi', 'prenom' => 'Salma', 'email' => 'salma.idrissi@example.com'])->save();
        TenantTestFixture::grantPermissions($owner, $organisation, self::OWNER_PERMISSIONS);
        $second = Organisation::factory()->create(['name' => 'Atlas Location Nord']);
        TenantTestFixture::attachMember($owner, $second, Agency::factory()->for($second)->create(['name' => 'Tanger Ville']), isOwner: true);
        foreach ([
            ['Retour en retard', 'RES-2026-0145 · Dacia Logan devait revenir hier à 18:00.', null],
            ['Caution à sécuriser', 'RES-2026-0148 · 3 000,00 MAD avant le départ de 14:00.', null],
            ['Document expiré', 'Assurance du véhicule 12345-أ-6 expirée le 02/11/2026.', now()->subDay()],
        ] as [$title, $body, $readAt]) {
            $owner->notifications()->create(['id' => (string) Str::uuid(), 'type' => 'App\\Notifications\\KitDemo', 'data' => ['title' => $title, 'body' => $body], 'read_at' => $readAt]);
        }

        $member = User::factory()->create(['nom' => 'Tazi', 'prenom' => 'Karim', 'email' => 'karim.tazi@example.com', 'is_active' => true]);
        TenantTestFixture::attachMember($member, $organisation, $agency);
        TenantTestFixture::grantPermissions($member, $organisation, ['access-dashboard', 'view-reservations', 'view-customers']);

        return self::$tenant = compact('organisation', 'agency', 'owner', 'member');
    }

    /** @param array<string, string> $page */
    private static function tenant(TestCase $test, array $page, bool $restricted): string
    {
        ['organisation' => $organisation, 'agency' => $agency, 'owner' => $owner, 'member' => $member] = self::tenantWorkspace();
        $user = $restricted ? $member : $owner;

        Route::middleware(['web', 'auth:web', 'onboarding.complete', 'organisation.context', 'organisation.access', 'agency.context'])
            ->get('/app/__kit-layout/'.$page['key'], fn () => view($page['view'], ['page' => $page]))
            ->name('admin.reservations.kit-layout-'.$page['key']);
        app('router')->getRoutes()->refreshNameLookups();

        $session = ['current_organisation_id' => $organisation->id, 'current_agency_id' => $agency->id];
        if (! $restricted) {
            $session['success'] = 'La réservation RES-2026-0148 a été confirmée.';
        }

        return $test->actingAs($user)->withSession($session)->get('/app/__kit-layout/'.$page['key'])->assertOk()->getContent();
    }

    /** @param array<string, string> $page */
    private static function platform(TestCase $test, array $page): string
    {
        $admin = Admin::factory()->create(['email' => 'platform@example.com', 'nom' => 'Alaoui', 'prenom' => 'Sara', 'telephone' => '0600000000']);
        $role = Role::query()->firstOrCreate(['name' => 'platform-super-admin', 'guard_name' => 'admin', 'team_id' => 0]);
        setPermissionsTeamId(0);

        try {
            $admin->assignRole($role);
        } finally {
            setPermissionsTeamId(null);
        }

        Route::middleware(['web', 'auth:admin', 'platform.admin'])
            ->get('/admin/__kit-layout/'.$page['key'], fn () => view($page['view'], ['page' => $page]))
            ->name('platform.organisations.kit-layout');
        app('router')->getRoutes()->refreshNameLookups();

        return $test->actingAs($admin, 'admin')->get('/admin/__kit-layout/'.$page['key'])->assertOk()->getContent();
    }

    /** @param array<string, string> $page */
    private static function guest(TestCase $test, array $page): string
    {
        Route::middleware(['web'])->get('/__kit-layout/'.$page['key'], fn () => view($page['view'], ['page' => $page]));

        return $test->withSession(['status' => 'Un lien de réinitialisation vous a été envoyé.', 'error' => 'Ces identifiants ne correspondent à aucun compte.'])
            ->get('/__kit-layout/'.$page['key'])->assertOk()->getContent();
    }
}
