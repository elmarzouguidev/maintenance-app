<div class="card filemanager-sidebar me-md-2">
    <div class="card-body">

        <div class="d-flex flex-column h-100">
            <div class="mb-4">
                <div class="mb-3">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle w-100" type="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-plus me-1"></i> Créer
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#"
                             onclick="document.getElementById('makeBackup').submit();"
                            >
                                <i class="bx bx-folder me-1"></i> 
                                SAUVEGARDE TOTAL
                            </a>
                            <a class="dropdown-item" href="#"><i class="bx bx-file me-1"></i> File</a>
                        </div>
                        <form id="makeBackup" method="post"
                            action="{{ route('admin:backup:make') }}">
                            @csrf
                            <input type="hidden" name="backup" value="ok">
                        </form>
                    </div>
                </div>
                <ul class="list-unstyled categories-list">
                    <li>
                        <div class="custom-accordion">
                            <a class="text-body fw-medium py-1 d-flex align-items-center"
                                data-bs-toggle="collapse" href="#categories-collapse" role="button"
                                aria-expanded="false" aria-controls="categories-collapse">
                                <i class="mdi mdi-folder font-size-16 text-warning me-2"></i> Fichiers <i
                                    class="mdi mdi-chevron-up accor-down-icon ms-auto"></i>
                            </a>
                            <div class="collapse show" id="categories-collapse">
                                <div class="card border-0 shadow-none ps-2 mb-0">
                                    <ul class="list-unstyled mb-0">
                                        <li><a href="javascript: void(0);"
                                                class="d-flex align-items-center"><span
                                                    class="me-auto">Design</span></a></li>
                                        <li><a href="javascript: void(0);"
                                                class="d-flex align-items-center"><span
                                                    class="me-auto">Development</span> <i
                                                    class="mdi mdi-pin ms-auto"></i></a></li>
                                        <li><a href="javascript: void(0);"
                                                class="d-flex align-items-center"><span
                                                    class="me-auto">Project A</span></a></li>
                                        <li><a href="javascript: void(0);"
                                                class="d-flex align-items-center"><span
                                                    class="me-auto">Admin</span> <i
                                                    class="mdi mdi-pin ms-auto"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="text-body d-flex align-items-center">
                            <i class="mdi mdi-google-drive font-size-16 text-muted me-2"></i> <span
                                class="me-auto">Google Drive</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="text-body d-flex align-items-center">
                            <i class="mdi mdi-dropbox font-size-16 me-2 text-primary"></i> <span
                                class="me-auto">Dropbox</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="text-body d-flex align-items-center">
                            <i class="mdi mdi-share-variant font-size-16 me-2"></i> <span
                                class="me-auto">Shared</span> <i
                                class="mdi mdi-circle-medium text-danger ms-2"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="text-body d-flex align-items-center">
                            <i class="mdi mdi-star-outline text-muted font-size-16 me-2"></i> <span
                                class="me-auto">Starred</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="text-body d-flex align-items-center">
                            <i class="mdi mdi-trash-can text-danger font-size-16 me-2"></i> <span
                                class="me-auto">Trash</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript: void(0);" class="text-body d-flex align-items-center">
                            <i class="mdi mdi-cog text-muted font-size-16 me-2"></i> <span
                                class="me-auto">Setting</span><span
                                class="badge bg-success rounded-pill ms-2">01</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="mt-auto">
                <div class="alert alert-success alert-dismissible fade show px-3 mb-0" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                    <div class="mb-3">
                        <i class="bx bxs-folder-open h1 text-success"></i>
                    </div>

                    <div>
                        <h5 class="text-success">Upgrade Features</h5>
                        <p>Cum sociis natoque penatibus et</p>
                        <div class="text-center">
                            <button type="button"
                                class="btn btn-link text-decoration-none text-success">Upgrade <i
                                    class="mdi mdi-arrow-right"></i></button>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </div>
</div>