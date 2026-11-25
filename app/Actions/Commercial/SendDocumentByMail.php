<?php

namespace App\Actions\Commercial;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * Send a commercial document (invoice, estimate, credit note, purchase order) by e-mail, one
 * message per address, then mark it sent and record it in its history. A mail server error is
 * reported and returns false instead of a 500: the former check built a SwiftMailer transport
 * (not installed since Laravel 9) and called Mail::failures() (removed in Laravel 9), so every
 * send failed (BL-80).
 */
class SendDocumentByMail
{
    /**
     * @param  Model  $document  an Invoice, Estimate, InvoiceAvoir or BCommand (is_send, histories())
     * @param  class-string<Mailable>  $mailable  built with the document
     * @param  array<int, mixed>  $recipients  the main address first, then the extra ones (nested lists are flattened, blanks ignored)
     * @return bool true when every message left
     */
    public function __invoke(Model $document, string $mailable, array $recipients, string $historyDetail): bool
    {
        $addresses = collect(Arr::flatten($recipients))
            ->filter(fn (mixed $email): bool => is_string($email) && trim($email) !== '')
            ->map(fn (string $email): string => trim($email))
            ->unique()
            ->values();

        if ($addresses->isEmpty()) {
            return false;
        }

        try {
            foreach ($addresses as $address) {
                Mail::to($address)->send(new $mailable($document));
            }
        } catch (TransportExceptionInterface $exception) {
            report($exception);

            return false;
        }

        $document->forceFill(['is_send' => true])->save();

        $document->histories()->create([
            'user_id' => auth()->id(),
            'user' => auth()->user()->full_name,
            'detail' => $historyDetail,
            'action' => 'send',
        ]);

        return true;
    }

    /**
     * The flash message for the result, for redirect()->with().
     *
     * @return array{success: string}|array{error: string}
     */
    public function flash(bool $sent): array
    {
        return $sent
            ? ['success' => 'L’e-mail a été envoyé avec succès.']
            : ['error' => 'L’e-mail n’a pas pu être envoyé : vérifiez l’adresse du destinataire et la configuration de la messagerie.'];
    }
}
