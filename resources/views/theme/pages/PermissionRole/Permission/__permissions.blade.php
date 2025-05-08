<div class="col-12">
    <div class="card">
        <div class="card-body">
            @if (session('permissions'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session('permissions') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="col-12">
                <div class="card-header bg-transparent border-primary">
                    <h5 class="my-0 ">Permissions</h5>
                </div>
            </div>
            <hr class="my-3">
            <form action="{{ route('admin:admins.syncPermissions', $admin->uuid) }}" method="post">
                @csrf
                @honeypot
                @method('PUT')
                <input type="hidden" name="adminId" value="{{ $admin->uuid }}">
                <div class="row">
                    @php
                        $selected = $admin->getPermissionNames()->toArray();
                    @endphp
                    @foreach ($permissions as $model => $permission)
                        <div class="col-xl-3 col-sm-6">
                            <div class="mt-4 mt-xl-0">
                                <h4 class="font-size-14 my-3 text-danger">{{ __('permission.' . $model) }}</h4>
                                <div class="docs-toggles">
                                    <ul class="list-group">
                                        @foreach ($permission as $per)
                                            <li class="list-group-item">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="permission-{{ $per['id'] }}"
                                                        type="checkbox" name="permissions[]" value="{{ $per['name'] }}"
                                                        @checked(in_array($per['name'], $selected))>

                                                    <label class="form-check-label"
                                                        for="permission-{{ $per['id'] }}">
                                                        {{-- $permission->name --}}
                                                        {{ __('permission.' . $per['name']) }}
                                                    </label>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="mt-5">
                    <button type="submit" class="btn btn-primary w-md">
                        synchroniser les permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
