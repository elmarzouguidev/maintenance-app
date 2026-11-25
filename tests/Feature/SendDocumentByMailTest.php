<?php

use App\Actions\Commercial\SendDocumentByMail;
use App\Mail\Commercial\Invoice\SendInvoiceMail;
use App\Models\Client;
use App\Models\Finance\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;

/*
 * BL-80: sending a document by e-mail, through App\Actions\Commercial\SendDocumentByMail
 * (it always failed: the former check needed SwiftMailer and Mail::failures()).
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    $user->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
    $this->actingAs($user);

    DB::table('companies')->insert(['id' => 1, 'uuid' => (string) Str::uuid(), 'name' => 'CASA MAINTENANCE', 'addresse' => 'Casablanca', 'ice' => '001234567890123', 'email' => 'contact@casamaintenance.test']);
    $this->client = Client::factory()->create(['email' => 'compta@atlas.test'])->refresh();
    $this->invoice = Invoice::findOrFail(DB::table('invoices')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => '00042', 'full_number' => 'FAC-00042', 'company_id' => 1, 'client_id' => $this->client->id,
        'status' => 'non-paid', 'invoice_date' => '2026-11-20', 'due_date' => '2026-12-20', 'price_ht' => 100, 'price_tva' => 20, 'price_total' => 120,
    ]));
});

/** A mailable without a PDF, to exercise the transport. */
class PlainDocumentMail extends Mailable
{
    public function __construct(public Invoice $document) {}

    public function build(): static
    {
        return $this->from('contact@casamaintenance.test')->subject('Document')->html('<p>Document</p>');
    }
}

it('sends the invoice to the client and the extra addresses, then marks it sent', function () {
    Mail::fake();

    $this->from($this->invoice->edit_url)
        ->post(route('commercial:invoices.send'), ['invoice' => $this->invoice->uuid, 'emails' => [$this->invoice->id => ['direction@atlas.test']]])
        ->assertRedirect($this->invoice->edit_url)
        ->assertSessionHas('success');

    Mail::assertSent(SendInvoiceMail::class, fn (SendInvoiceMail $mail): bool => $mail->hasTo('compta@atlas.test'));
    Mail::assertSent(SendInvoiceMail::class, fn (SendInvoiceMail $mail): bool => $mail->hasTo('direction@atlas.test'));
    expect($this->invoice->fresh()->is_send)->toBeTrue()
        ->and($this->invoice->histories()->where('action', 'send')->count())->toBe(1);
});

it('reports a mail server error instead of failing, and leaves the document unsent', function () {
    Mail::extend('failing', fn () => new class extends AbstractTransport
    {
        protected function doSend(SentMessage $message): void
        {
            throw new TransportException('Connection refused');
        }

        public function __toString(): string
        {
            return 'failing';
        }
    });
    config(['mail.mailers.failing' => ['transport' => 'failing'], 'mail.default' => 'failing']);

    $sent = app(SendDocumentByMail::class)($this->invoice, PlainDocumentMail::class, ['compta@atlas.test'], 'a envoyé la facture par mail');

    expect($sent)->toBeFalse()
        ->and($this->invoice->fresh()->is_send)->toBeFalse()
        ->and($this->invoice->histories()->count())->toBe(0);
});

it('does not send without any address', function () {
    Mail::fake();
    $this->client->forceFill(['email' => null])->save();

    $this->post(route('commercial:invoices.send'), ['invoice' => $this->invoice->uuid])
        ->assertSessionHas('error');

    Mail::assertNothingSent();
});
