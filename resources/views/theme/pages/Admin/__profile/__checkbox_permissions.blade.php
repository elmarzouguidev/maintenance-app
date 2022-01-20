<div class="mb-3 row">
    <label for="example-password-input" class="col-md-2 col-form-label"></label>
    <div class="col-md-10">
        @php
            $selected = $admin->getPermissionNames()->toArray();
            //dd($selected)
        @endphp
   
            @foreach ($permissions as $permission)
                <div class="form-check">
                    <input 
                    class="form-check-input" 
                    type="checkbox"
                    name="permissions[]"
                    value="{{$permission->name}}" 
                    id="permission-{{$permission->id}}"
                    {{ (in_array($permission->name, $selected)) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="permission-{{$permission->id}}">
                        {{$permission->name}}
                    </label>
                </div>
            @endforeach
            @error('permissions')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

    </div>
</div>