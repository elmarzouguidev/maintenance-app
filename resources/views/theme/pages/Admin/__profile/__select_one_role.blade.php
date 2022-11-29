<div class="mb-3 row">
    <label class="col-md-2 col-form-label">Role</label>
    <div class="col-md-10">

        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="">Select role</option>
            @foreach ($roles as $role)
                <option {{ $role->name === $admin->getRoleNames()->first() ? 'selected' : '' }}
                    value="{{ $role->name }}">
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>