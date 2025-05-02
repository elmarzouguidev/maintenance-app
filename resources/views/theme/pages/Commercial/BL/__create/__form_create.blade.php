<div class="row">
    <div class="col-lg-12">
        <form class="repeater" action="{{ route('commercial:blivraison.createPost') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-body">

                    <p class="card-title-desc">Entrer les information de BL</p>

                    <div class="row">
                        <div class="col-lg-6">
                            @livewire('commercial.b-l.b-l-info')
                            <div class="col-lg-12">
                                <div class="row">
                                    
                                        <label>Numéro de BC</label>
                                        <div class="input-group" >
                                            <input type="text" name="bc_number"
                                                class="form-control @error('bc_number') is-invalid @enderror"
                                                value="" >

                                            
                                            @error('bc_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                  
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label>Date de BL</label>
                                        <div class="input-group" id="datepicker1">
                                            <input type="text" name="date_bl"
                                                class="form-control @error('date_bl') is-invalid @enderror"
                                                value="{{ now()->format('Y-m-d') }}" data-date-format="yyyy-mm-dd"
                                                data-date-container='#datepicker1' data-provide="datepicker">

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            @error('date_bl')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- @include('theme.pages.Commercial.Invoice.__create.__send_invoice_section') --}}

                        </div>

                        <div class="col-lg-6">
                            {{-- @include('theme.pages.Commercial.Invoice.__create.__javascript.__ajax_client') --}}
                            <div class=" mb-4">
                                <label>Note d'administration</label>
                                <textarea name="admin_notes" id="textarea"
                                    class="form-control @error('admin_notes') is-invalid @enderror" maxlength="225"
                                    rows="8"></textarea>

                                @error('admin_notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="card-title-desc">Entrer les information de BON</p>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            @include('theme.pages.Commercial.BL.__create.__add_articles')
                        </div>
                        <div class="col-lg-6">
                            <div class="justify-content-end">
                                <div class="card border border-primary">
                                    <div class="card-header bg-transparent border-primary">
                                        <h5 class="my-0 text-primary">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            Total HT :
                                        </h5>
                                        <hr>
                                        <h5 class="my-0 text-danger">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            Total TTC :
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @livewire('commercial.invoice.create.articles') --}}

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="card-title-desc">{{ __('invoice.form.title') }}</p>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="condition_general">{{ __('invoice.form.condition_general') }}</label>
                            <textarea name="condition_general" id="condition_general" rows="5"
                                      class="form-control @error('condition_general') is-invalid @enderror"></textarea>
                            @error('condition_general')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-end mb-4">
                <div class="">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        {{__('buttons.store')}}
                    </button>
                    <button type="submit" class="btn btn-secondary waves-effect waves-light">
                        {{__('buttons.store_draft')}}
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
