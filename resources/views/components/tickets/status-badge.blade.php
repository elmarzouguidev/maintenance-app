{{--
    A ticket's status (App\Constants\Status, labels in lang/fr/status.php) as a kit badge, followed
    by its state (réparable / non réparable) once diagnosed. Replaces the Bootstrap dot icons and
    the dynamic text-{{ $color }} classes of the former ticket pages.
--}}
@props(['ticket'])

@php
    use App\Constants\Etat;
    use App\Constants\Status;

    $statusVariants = [
        Status::NON_TRAITE => 'warning',
        Status::EN_COURS_DE_DIAGNOSTIC => 'info',
        Status::EN_COURS_DE_REPARATION => 'info',
        Status::RETOUR_NON_REPARABLE => 'danger',
        Status::RETOUR_DEVIS_NON_CONFIRME => 'danger',
        Status::RETOUR_LIVRE => 'neutral',
        Status::EN_ATTENTE_DE_DEVIS => 'warning',
        Status::EN_ATTENTE_DE_BON_DE_COMMAND => 'warning',
        Status::DEVIS_CONFIRME => 'success',
        Status::A_REPARER => 'info',
        Status::PRET_A_ETRE_LIVRE => 'success',
        Status::PRET_A_ETRE_FACTURE => 'success',
        Status::LIVRE => 'neutral',
    ];
    $status = (int) $ticket->status;
    $etat = (int) $ticket->etat;
@endphp

<span {{ $attributes->class('inline-flex flex-wrap items-center gap-1.5') }}>
    <x-admin.badge :variant="$statusVariants[$status] ?? 'neutral'">{{ __('status.statuses.'.$status) }}</x-admin.badge>
    @if ($etat !== Etat::NON_DIAGNOSTIQUER)
        <x-admin.badge :variant="$etat === Etat::REPARABLE ? 'success' : 'danger'">{{ __('etat.etats.'.$etat) }}</x-admin.badge>
    @endif
</span>
