@can('admin.permissions.manage')
    <div class="col-lg-12">
        <section class="card permission-panel">
            <div class="card-body p-4 p-xl-5">
                @if (session('permissions'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-circle-outline me-2" aria-hidden="true"></i>
                        {{ session('permissions') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif

                <div class="permission-panel-heading">
                    <div class="d-flex align-items-start gap-3">
                        <span class="permission-panel-icon" aria-hidden="true">
                            <i class="mdi mdi-shield-key-outline"></i>
                        </span>
                        <div>
                            <h4 class="mb-1">Autorisations d’accès</h4>
                            <p class="text-muted mb-0">
                                Gérez les droits de <strong>{{ $admin->full_name }}</strong> par module.
                                Les droits hérités d’un rôle sont affichés en lecture seule.
                            </p>
                        </div>
                    </div>
                    <span class="permission-total">
                        <i class="mdi mdi-key-outline me-1" aria-hidden="true"></i>
                        {{ $permissions->flatten(1)->count() }} droits
                    </span>
                </div>

                <form action="{{ route('admin:admins.syncPermissions', $admin->uuid) }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="adminId" value="{{ $admin->uuid }}">

                    <div class="row g-3 g-xl-4 permission-groups">
                        @foreach ($permissions as $model => $permission)
                            <div class="col-12 col-md-6 col-xl-4">
                                <section class="permission-group h-100" aria-labelledby="permission-group-{{ $loop->index }}">
                                    <header class="permission-group-heading">
                                        <h5 id="permission-group-{{ $loop->index }}" class="mb-0">
                                            {{ __('permission_groups.' . $model) }}
                                        </h5>
                                        <span class="permission-group-count">{{ count($permission) }}</span>
                                    </header>

                                    <div class="permission-options">
                                        @foreach ($permission as $per)
                                            @php
                                                $isRolePermission = in_array($per['name'], $rolePermissionNames, true);
                                                $isDirectPermission = in_array($per['name'], $directPermissionNames, true);
                                            @endphp
                                            <div class="permission-option {{ $isRolePermission ? 'permission-option--inherited' : '' }}">
                                                @if ($isRolePermission && $isDirectPermission)
                                                    <input type="hidden" name="permissions[]" value="{{ $per['name'] }}">
                                                @endif
                                                <input class="form-check-input" id="permission-{{ $per['id'] }}"
                                                    type="checkbox" @unless ($isRolePermission) name="permissions[]" value="{{ $per['name'] }}" @endunless
                                                    @checked($isRolePermission || $isDirectPermission)
                                                    @disabled($isRolePermission)>
                                                <label class="permission-option-label" for="permission-{{ $per['id'] }}">
                                                    <span>{{ $per['public_name'] }}</span>
                                                    @if ($isRolePermission)
                                                        <span class="permission-source-badge">Héritée du rôle</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            </div>
                        @endforeach
                    </div>

                    <div class="permission-panel-footer">
                        <p class="text-muted mb-0">
                            <i class="mdi mdi-information-outline me-1" aria-hidden="true"></i>
                            Les changements prennent effet après l’enregistrement.
                        </p>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="mdi mdi-content-save-outline me-1" aria-hidden="true"></i>
                            Enregistrer les autorisations
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endcan
