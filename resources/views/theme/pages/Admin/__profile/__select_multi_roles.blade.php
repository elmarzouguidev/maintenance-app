<div class="mb-3 row">
    <label for="example-password-input" class="col-md-2 col-form-label">Roles</label>
    <div class="col-md-10">

        @php
            $selected = $admin->getRoleNames()->toArray();
            //dd($selected)
        @endphp
        <select name="roles[]" class="select2 form-control select2-multiple @error('roles') is-invalid @enderror"
            multiple="multiple" data-placeholder="Select ...">

            <optgroup label="roles">

                @foreach ($roles as $role)

                 <option 
                   value="{{$role->name}}"
                   
                   {{ (in_array($role->name, $selected)) ? 'selected' : '' }}
                 >
                    {{$role->name}}

                </option>

                @endforeach

            </optgroup>

        </select>
        @error('permissions')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>
</div>