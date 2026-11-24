{{--
    Sidebar navigation (desktop sidebar and phone drawer). Groups collapse and are remembered
    in localStorage (adminNavGroups in ui-kit/laravel/js/nav-groups.js); the group holding the
    current page is always expanded. Expects $navigation (see layouts/admin.blade.php) and
    $navId; items are already filtered for the current user.
--}}
<nav aria-label="Navigation principale" class="space-y-4" x-data="adminNavGroups">
    @foreach ($navigation as $index => $section)
        @php
            $visibleItems = collect($section['items'] ?? []);
            $hasCurrent = $visibleItems->contains(fn ($item) => (bool) ($item['active'] ?? false));
            $groupKey = \Illuminate\Support\Str::slug($section['label'] ?? 'principal');
            $listId = $navId.'-'.$groupKey;
        @endphp

        @if ($visibleItems->isNotEmpty())
            <div>
                @if ($section['label'] ?? null)
                    <button type="button" class="admin-nav-group"
                        aria-controls="{{ $listId }}"
                        aria-expanded="true"
                        :aria-expanded="isOpen(@js($groupKey), @js($hasCurrent)).toString()"
                        @if ($hasCurrent) aria-disabled="true" title="Groupe de la page courante" @else x-on:click="toggle(@js($groupKey))" @endif>
                        <span>{{ $section['label'] }}</span>
                        <x-admin.icon name="chevron-down" class="size-3.5 transition-transform duration-150" ::class="isOpen({{ \Illuminate\Support\Js::from($groupKey) }}, {{ \Illuminate\Support\Js::from($hasCurrent) }}) ? '' : '-rotate-90'" />
                    </button>
                @endif

                <ul id="{{ $listId }}" class="mt-1 space-y-0.5" x-show="isOpen(@js($groupKey), @js($hasCurrent))">
                    @foreach ($visibleItems as $item)
                        <li>
                            <x-admin.nav-link href="{{ $item['href'] }}" :active="(bool) ($item['active'] ?? false)" :icon="$item['icon'] ?? null">{{ $item['label'] }}</x-admin.nav-link>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endforeach
</nav>
