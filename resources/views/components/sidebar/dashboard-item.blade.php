@props(['currentYear' => true])

<li>
    <a href="{{ route('admin:home') }}?appFilter[DateBetween]={{ now()->startOfYear()->format('Y-m-d') }},{{ now()->endOfYear()->format('Y-m-d') }}&appFilter[GetCompany]=1&currentYear=true"
        class="waves-effect">
        <i class="bx bx-home-circle"></i>
        <span key="t-dashboards">{{ __('navbar.dashboard') }}</span>
    </a>
</li>
