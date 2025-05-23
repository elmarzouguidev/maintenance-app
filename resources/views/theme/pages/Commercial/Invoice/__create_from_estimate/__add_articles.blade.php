<div data-repeater-list="articles">

    @foreach ($estimate->articles as $article)
        
        <div data-repeater-item class="row">
            <input type="hidden" name="articleuuid" value="{{$article->uuid}}" >
            <div class="mb-3 col-lg-5">
                <label for="designation">Désignation</label>
                <textarea name="designation" id="designation" rows="5"
                    class="form-control @error('articles.*.designation') is-invalid @enderror">{{ str_replace('<br />', '', $article->designation) }}
                </textarea>
                @error('articles.*.designation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{--<div class="mb-3 col-lg-3">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="5"
                    class="form-control @error('articles.*.description') is-invalid @enderror">{{ str_replace('<br />', '', $article->description) }}
                </textarea>
                @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>--}}

            <div class="mb-3 col-lg-1">
                <label for="quantity">Qté.</label>
                <input type="number" name="quantity" id="quantity" value="{{ $article->quantity }}" min="1" step="0.1"
                    class="form-control @error('articles.*.quantity') is-invalid @enderror" />
                @error('quantity')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 col-lg-2">
                <label for="prix_unitaire">Prix unitaire</label>
                <input type="number" name="prix_unitaire" id="prix_unitaire" value="{{ $article->prix_unitaire }}" step="0.1"
                    class="form-control @error('articles.*.prix_unitaire') is-invalid @enderror" />

                @error('prix_unitaire')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3 col-lg-1">
                <label for="remise">{{__('Remise%')}} </label>
                <input type="number" min="0" name="remise" id="remise" value="{{ $article->remise}}" step="0.1"
                    class="form-control @error('articles.*.remise') is-invalid @enderror"  />
                @error('remise')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3 col-lg-2">
                <label for="montant_ht">Montant HT</label>
                <input type="text" name="montant_ht" id="montant_ht" value="{{ $article->formated_montant_ht }}"
                    class="form-control @error('articles.*.montant_ht') is-invalid @enderror" readonly />
                @error('montant_ht')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3 col-lg-1">

                <button type="button" class="deleteArticle mt-4 btn btn-danger waves-effect waves-light"
                    data-article="{{ $article->uuid }}" data-estimate="{{ $estimate->uuid }}">
                    <i class="fas fa-trash-alt font-size-16"></i>
                </button>

            </div>

        </div>
    @endforeach
    {{--<button data-repeater-create type="button" class="btn btn-success waves-effect waves-light">
        <i class="bx bx-check-double font-size-16 align-middle"></i>
    </button>--}}
</div>