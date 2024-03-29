<div class="row">
    <div class="col-xl-12">

        <div class="card">
            <div class="card-body">

                <h4 class="card-title">List de Diagnostique</h4>

                <!-- Nav tabs -->
                @include('theme.pages.Diagnostic.__tap_view.tables.__taps')

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">
                    <div class="tab-pane active" id="diagnistique-open-tech" role="tabpanel">

                        @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-open')
                    </div>

                    <div class="tab-pane" id="diagnistique-wait-tech" role="tabpanel">
                        @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-wait')
                    </div>

                    <div class="tab-pane" id="diagnistique-attend-bc-tech" role="tabpanel">

                        @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-attend-bc')

                    </div>

                    <div class="tab-pane" id="diagnistique-repare-tech" role="tabpanel">
                        @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-a-reparer')
                    </div>

                    <div class="tab-pane" id="diagnistique-repare-encours-tech" role="tabpanel">
                        @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-encours-de-reparation')
                    </div>

                    <div class="tab-pane" id="diagnistique-repare-done-tech" role="tabpanel">
                        @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-pret-a-livre')
                    </div>

                    <div class="tab-pane" id="diagnistique-cancled-tech" role="tabpanel">
                        @include('theme.pages.Diagnostic.__tap_view.tables.diagnistique-cancled')
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>
