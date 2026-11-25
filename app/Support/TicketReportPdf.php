<?php

namespace App\Support;

use App\Models\Finance\Company;
use App\Models\Ticket;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * The ticket's full report (theme/pages/Ticket/__pdf/Report), served by tickets.report.generate
 * and rapports.report.generate: identification, device, diagnostic, repair, delivery, history and
 * photographs, under the letterhead of the company that invoiced (or quoted) the ticket.
 */
class TicketReportPdf
{
    public function build(Ticket $ticket): PdfDocument
    {
        $ticket->load([
            'client', 'technicien', 'diagnoseReports.technicien', 'reparationReports.technicien',
            'delivery.reception', 'statuses', 'invoices.company', 'estimates.company',
        ])->loadCount('delivery');

        $company = $ticket->invoices->first()?->company
            ?? $ticket->estimates->first()?->company
            ?? Company::query()->orderBy('id')->first();

        return \PDF::loadView('theme.pages.Ticket.__pdf.Report.index', [
            'ticket' => $ticket,
            'company' => $company,
            'companyLogo' => CompanyLogoDataUri::fromPublicDisk($company?->logo),
            'photos' => $this->photos($ticket),
            'issuedAt' => now(),
        ]);
    }

    public function fileName(Ticket $ticket): string
    {
        return 'Rapport-ticket-'.$ticket->code.'.pdf';
    }

    /**
     * The ticket's photos as data URIs (the 800 px "normal" conversion, else the original); a
     * ticket without photo gets an empty list (the former report crashed on it).
     *
     * @return Collection<int, string>
     */
    private function photos(Ticket $ticket): Collection
    {
        return $ticket->getMedia('tickets-images')
            ->map(function (Media $media): ?string {
                $path = collect([$media->getPath('normal'), $media->getPath()])->first(fn (string $candidate): bool => is_file($candidate));

                return $path ? 'data:'.($media->mime_type ?: 'image/jpeg').';base64,'.base64_encode((string) file_get_contents($path)) : null;
            })
            ->filter()
            ->values();
    }
}
