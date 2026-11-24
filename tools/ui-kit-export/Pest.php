<?php

/*
 * Loads the kit support classes for ui-kit/laravel/support/tests (they live outside app/, so
 * Composer does not autoload them here; a project that installs the kit copies them to
 * app/Support/UiKit/).
 */
foreach (glob(dirname(__DIR__, 2).'/ui-kit/laravel/support/*.php') as $kitSupportFile) {
    require_once $kitSupportFile;
}
