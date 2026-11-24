<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Livewire\Livewire;

/**
 * Composer of the UI kit layouts (resources/views/layouts: admin, platform, guest).
 *
 * The app gets Alpine.js from Livewire, and the kit layouts do not call @livewireScripts.
 * Livewire only injects its script on pages that rendered a Livewire component, so this
 * forces the injection: every kit page then has Alpine, with the kit's Alpine components
 * registered by resources/js/app.js.
 */
class UiKitLayoutComposer
{
    /**
     * Ask Livewire to inject its assets (and Alpine) into the current response.
     */
    public function compose(View $view): void
    {
        Livewire::forceAssetInjection();
    }
}
