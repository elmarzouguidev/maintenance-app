<div class="col-lg-12 mb-4">
    <label for="example-password-input" class="col-md-2 col-form-label">Tickets</label>
    @php
        $selected = $estimate->tickets?->pluck('code')->toArray();
        
    @endphp
    <select name="tickets[]" class="select2 form-control select2-multiple @error('tickets') is-invalid @enderror"
        multiple="multiple" data-placeholder="Select ..." required>

        <optgroup label="tickets">

            @foreach ($estimate->tickets as $ticket)
                <option value="{{ $ticket->id }}" {{ in_array($ticket->code, $selected) ? 'selected' : '' }}>
                    @if ($ticket->is_retour)
                        {{ $ticket->code_retour }}
                    @else
                        {{ $ticket->code }}
                    @endif

                </option>
            @endforeach

        </optgroup>

    </select>
</div>
