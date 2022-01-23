<div class="row">
    <div class="col-lg-12">
       
        <div class="card">
            <div class="card-body">

                <p class="card-title-desc">A mobile and touch friendly input spinner component for</p>

                <form action="{{route('commercial:invoices.store')}}" method="post">
                    @csrf
                    @honeypot
                    <div class="row">
                        <div class="col-lg-6">

                        
                        <div class="mb-3">
                            <label class="form-label">Client *</label>
                            <select class="form-control select2">
                                <option>Select</option>
                                <optgroup label="Alaskan/Hawaiian Time Zone">
                                    <option value="AK">Alaska</option>
                                    <option value="HI">Hawaii</option>
                                </optgroup>
                            </select>
                        
                        </div>
                        

                        </div>

                        <div class="col-lg-6">
                            {{--@include('theme.pages.Commercial.Invoice.__create.__javascript.__ajax_client')--}}
                            <div class="templating-select">
                                <label class="form-label">Templating</label>
                                <select name="client" class="form-control select2-templating">
                                    <optgroup label="Alaskan/Hawaiian Time Zone">
                                        <option value="AK">Alaska</option>
                                        <option value="HI">Hawaii</option>
                                    </optgroup>
                                    <optgroup label="Pacific Time Zone">
                                        <option value="CA">California</option>
                                        <option value="NV">Nevada</option>
                                        <option value="OR">Oregon</option>
                                        <option value="WA">Washington</option>
                                    </optgroup>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Enregistrer</button>
                      
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>