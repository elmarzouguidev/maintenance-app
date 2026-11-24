{{--
    Top view of a vehicle with clickable zones (pointer only, aria-hidden: pair it with a
    keyboard control). Each zone calls toggle(key) and isSelected(key) from the surrounding
    Alpine scope. Keys: front, rear, left_side, right_side, roof and the four wheels
    (front_left_wheel, rear_left_wheel, front_right_wheel, rear_right_wheel).
    Used by x-admin.vehicle-condition-map (check-out / check-in) and the damage form.
    Optional domain component (vehicle rental): copy the image from theme/images/.
--}}
<div {{ $attributes->class('min-w-0') }}>
    <div class="relative w-full" style="aspect-ratio: 1692 / 820">
        <img src="{{ asset(config('brand.images').'/vehicle-condition-top-view.png') }}" alt="" class="pointer-events-none absolute inset-0 size-full object-contain" draggable="false">
        <svg viewBox="0 0 1692 820" class="absolute inset-0 size-full" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
            <g class="cursor-pointer" pointer-events="all">
                <path d="M0 280C30 175 135 105 286 84l221 18 31 148v321l-31 148-221 18C135 716 30 645 0 540L0 280Z" @click="toggle('front')" class="admin-zone-shape" :class="isSelected('front') ? 'is-selected' : ''" />
                <path d="m1154 102 252-18c151 21 256 91 286 196v260c-30 105-135 176-286 197l-252-18-31-148V250z" @click="toggle('rear')" class="admin-zone-shape" :class="isSelected('rear') ? 'is-selected' : ''" />
                <path d="m287 84 220 18 50 72-20 97-73-89-178-30C135 131 30 201 0 306v-26C30 175 135 105 287 84zM1154 102l252-18c151 21 256 91 286 196v26c-30-105-135-175-286-196l-178 30-74 89-20-97z" @click="toggle('left_side')" class="admin-zone-shape" :class="isSelected('left_side') ? 'is-selected' : ''" />
                <path d="m287 736 220-18 50-72-20-97-73 89-178 30C135 689 30 619 0 514v26c30 105 135 176 287 196zM1154 718l252 18c151-20 256-91 286-196v-26c-30 105-135 175-286 196l-178-30-74-89-20 97z" @click="toggle('right_side')" class="admin-zone-shape" :class="isSelected('right_side') ? 'is-selected' : ''" />
                <path d="M510 120h656l94 111v358l-94 111H510l-94-111V231z" @click="toggle('roof')" class="admin-zone-shape" :class="isSelected('roof') ? 'is-selected' : ''" />
                <rect x="224" y="70" width="302" height="108" rx="42" @click="toggle('front_left_wheel')" class="admin-zone-shape" :class="isSelected('front_left_wheel') ? 'is-selected' : ''" />
                <rect x="1190" y="70" width="302" height="108" rx="42" @click="toggle('rear_left_wheel')" class="admin-zone-shape" :class="isSelected('rear_left_wheel') ? 'is-selected' : ''" />
                <rect x="224" y="642" width="302" height="108" rx="42" @click="toggle('front_right_wheel')" class="admin-zone-shape" :class="isSelected('front_right_wheel') ? 'is-selected' : ''" />
                <rect x="1190" y="642" width="302" height="108" rx="42" @click="toggle('rear_right_wheel')" class="admin-zone-shape" :class="isSelected('rear_right_wheel') ? 'is-selected' : ''" />
            </g>
        </svg>
    </div>
</div>
