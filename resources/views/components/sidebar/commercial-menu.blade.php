@props(['estimates_not_send' => 0])

<li class="menu-title" key="t-apps">Gestion commercial</li>
<li>
    <a href="javascript: void(0);" class="has-arrow waves-effect">
        <i class="bx bx-grid-alt"></i>
        <span key="t-factures">{{ __('navbar.commercial') }}</span>
    </a>
    <ul class="sub-menu" aria-expanded="false">

        @can('estimates.browse')
            <li>
                <a href="{{ route('commercial:estimates.index') }}" key="t-factures-devis">
                    <i class="bx bx-file-blank"></i>
                    {{ __('navbar.estimates') }}
                </a>
            </li>
        @endcan

        @can('invoices.browse')
            <li>
                <a href="{{ route('commercial:invoices.index') }}" key="t-invoice-list">
                    <i class="bx bx-food-menu"></i>
                    {{ __('navbar.invoices') }}
                </a>
            </li>
        @endcan

        @can('invoices.browse')
            <li>
                <a href="{{ route('commercial:invoices.index.avoir') }}" key="t-avoir-list">
                    <i class="bx bx-food-menu"></i>
                    Avoirs
                </a>
            </li>
        @endcan

        @can('payments.browse')
            <li>
                <a href="{{ route('commercial:bills.index') }}" key="t-factures-list">
                    <i class="bx bx-money"></i>
                    Règlements
                </a>
            </li>
        @endcan

        @can('bcommandes.browse')
            <li>
                <a href="{{ route('commercial:bcommandes.index') }}" key="t-bc-list">
                    <i class="bx bx-file"></i>
                    {{ __('navbar.bc') }}
                </a>
            </li>
        @endcan

        @can('blivraison.browse')
            <li>
                <a href="{{ route('commercial:blivraison.index') }}" key="t-bl-list">
                    <i class="bx bx-file"></i>
                    Bon de livraison
                </a>
            </li>
        @endcan

        @can('providers.browse')
            <li>
                <a href="{{ route('commercial:providers.index') }}" key="t-factures-devis">
                    <i class="bx bx-user"></i>
                    Fournisseurs
                </a>
            </li>
        @endcan

        @can('client.browse')
            <li>
                <a href="{{ route('admin:clients.index') }}" key="t-clients">
                    <i class="bx bx-user"></i>
                    {{ __('navbar.clients') }}
                </a>
            </li>
        @endcan

        @hasanyrole('Admin|SuperAdmin')
            <li>
                <a href="{{ route('commercial:reports.index') }}" key="t-reports">
                    <i class="bx bx-line-chart"></i>
                    Rapport Clients
                </a>
            </li>
        @endhasanyrole
    </ul>
</li>
