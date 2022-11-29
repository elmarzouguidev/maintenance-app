<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#user-info" role="tab">
                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                            <span class="d-none d-sm-block">User info</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#settings-pro" role="tab">
                            <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                            <span class="d-none d-sm-block">Settings</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content p-3 text-muted">

                    <div class="tab-pane active" id="user-info" role="tabpanel">
                        @include('theme.pages.Profile.settings._user_info_form')
                    </div>

                    <div class="tab-pane" id="settings-pro" role="tabpanel">
                        @include('theme.pages.Profile.settings._company_form')
                    </div>
                    
                </div>
            </div>
        </div>
    </div>


</div>
