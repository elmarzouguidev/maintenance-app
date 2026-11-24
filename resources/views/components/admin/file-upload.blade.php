{{--
    File upload drop zone. The native file input covers the zone (click, keyboard, drop) and
    keeps its name; `hint` replaces the default format line when the accepted types differ; the script below (drag state and previews) runs once the tenant layout
    renders @stack('scripts').
--}}
@props([
    'name',
    'label' => null,
    'accept' => 'image/*',
    'multiple' => false,
    'maxSize' => '5MB',
    'required' => false,
    'error' => null,
    'help' => null,
    'hint' => null,
    'preview' => false,
    'previewUrl' => null,
    'previewAlt' => null,
])

@php
    $inputId = 'file-'.$name;
    $message = $error ?? ($errors->has($name) ? $errors->first($name) : null);
    $describedBy = trim(($help ? $inputId.'-help ' : '').$inputId.'-hint'.($message ? ' '.$inputId.'-error' : ''));
@endphp

<div @class(['min-w-0'])>
    @if ($label)
        <label for="{{ $inputId }}" class="admin-form-label">
            {{ $label }}
            @if ($required)
                <span class="text-status-danger" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div class="file-upload-container space-y-3">
        @if ($preview && $previewUrl)
            <figure>
                <img src="{{ $previewUrl }}" alt="{{ $previewAlt ?? 'Image actuelle' }}"
                    class="size-32 rounded-control border border-line bg-surface-muted object-cover">
                <figcaption class="mt-1 text-caption text-fg-muted">{{ $label }} actuel</figcaption>
            </figure>
        @endif

        <div class="file-upload-area admin-drop-zone">
            <input type="file" name="{{ $name }}" accept="{{ $accept }}"
                @if ($multiple) multiple @endif @if ($required) required @endif
                @if ($message) aria-invalid="true" @endif
                aria-describedby="{{ $describedBy }}"
                class="absolute inset-0 size-full cursor-pointer opacity-0" id="{{ $inputId }}">

            <div class="file-upload-content pointer-events-none">
                <x-admin.icon name="upload" class="mx-auto mb-3 size-8 text-fg-muted" />
                <p class="text-body text-fg"><span class="font-semibold text-accent">Téléverser un fichier</span> ou glisser-déposer</p>
                <p id="{{ $inputId }}-hint" class="mt-1 text-caption text-fg-muted">
                    @if ($hint)
                        {{ $hint }}
                    @elseif (str_contains($accept, 'image'))
                        PNG, JPG, GIF, WebP jusqu'à {{ $maxSize }}
                    @else
                        Fichiers jusqu'à {{ $maxSize }}
                    @endif
                </p>
            </div>

            <div class="file-upload-loading absolute inset-0 hidden items-center justify-center rounded-panel bg-surface/90" role="status">
                <span class="flex items-center gap-2 text-body text-fg-muted">
                    <span class="admin-spinner text-accent" aria-hidden="true"></span>
                    Téléversement…
                </span>
            </div>
        </div>

        <div class="file-preview hidden">
            <div class="grid grid-cols-2 gap-3 md:grid-cols-4" id="preview-{{ $name }}"></div>
        </div>
    </div>

    @if ($help)
        <p id="{{ $inputId }}-help" class="admin-form-help">{{ $help }}</p>
    @endif

    @if ($message)
        <p id="{{ $inputId }}-error" class="admin-form-error" role="alert">
            <x-admin.icon name="exclamation-circle" class="size-4" />
            <span>{{ $message }}</span>
        </p>
    @endif
</div>

{{--
    One script for every upload on the page (@once). Each .file-upload-container is bound on its
    own: its input, its preview area and its remove buttons are looked up inside it, and
    removeFile is local to it, so several uploads on one page (and names such as files[]) work
    independently.
--}}
@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.file-upload-container').forEach(initFileUpload);

                function initFileUpload(container) {
                    const uploadArea = container.querySelector('.file-upload-area');
                    const fileInput = uploadArea.querySelector('input[type="file"]');
                    const previewContainer = container.querySelector('.file-preview');
                    const previewGrid = previewContainer.firstElementChild;
                    const loadingDiv = uploadArea.querySelector('.file-upload-loading');

                    // Drag and drop functionality
                    uploadArea.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        uploadArea.dataset.dragover = 'true';
                    });

                    uploadArea.addEventListener('dragleave', function(e) {
                        e.preventDefault();
                        delete uploadArea.dataset.dragover;
                    });

                    uploadArea.addEventListener('drop', function(e) {
                        e.preventDefault();
                        delete uploadArea.dataset.dragover;

                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            fileInput.files = files;
                            handleFiles(files);
                        }
                    });

                    // File input change
                    fileInput.addEventListener('change', function(e) {
                        handleFiles(e.target.files);
                    });

                    function handleFiles(files) {
                        // Clear previous previews, also when the last file was just removed
                        previewGrid.innerHTML = '';

                        if (files.length === 0) {
                            previewContainer.classList.add('hidden');
                            return;
                        }

                        // Show loading
                        loadingDiv.classList.replace('hidden', 'flex');

                        // Process files
                        Array.from(files).forEach((file, index) => {
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const preview = createImagePreview(e.target.result, file.name, index);
                                    previewGrid.appendChild(preview);
                                    previewContainer.classList.remove('hidden');
                                };
                                reader.readAsDataURL(file);
                            } else {
                                const preview = createFilePreview(file.name, index);
                                previewGrid.appendChild(preview);
                                previewContainer.classList.remove('hidden');
                            }
                        });

                        // Hide loading after a short delay
                        setTimeout(() => {
                            loadingDiv.classList.replace('flex', 'hidden');
                        }, 1000);
                    }

                    function createImagePreview(src, name, index) {
                        const figure = document.createElement('figure');
                        figure.className = 'relative min-w-0';
                        const image = document.createElement('img');
                        image.src = src;
                        image.alt = name;
                        image.className = 'h-24 w-full rounded-control border border-line object-cover';
                        const remove = document.createElement('button');
                        remove.type = 'button';
                        remove.className = 'admin-icon-button admin-icon-button-sm absolute end-1 top-1 bg-surface';
                        remove.setAttribute('aria-label', 'Retirer ' + name);
                        remove.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>';
                        remove.addEventListener('click', () => removeFile(index));
                        figure.append(image, remove, createCaption(name));
                        return figure;
                    }

                    function createFilePreview(name, index) {
                        const figure = document.createElement('figure');
                        figure.className = 'min-w-0';
                        const box = document.createElement('div');
                        box.className = 'flex h-24 w-full items-center justify-center rounded-control border border-line bg-surface-muted text-fg-muted';
                        box.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="size-8"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>';
                        figure.append(box, createCaption(name));
                        return figure;
                    }

                    function createCaption(name) {
                        const caption = document.createElement('figcaption');
                        caption.className = 'mt-1 truncate text-caption text-fg-muted';
                        caption.textContent = name;
                        return caption;
                    }

                    // Removes one file from this upload's own input: local, never a shared global.
                    function removeFile(index) {
                        const dt = new DataTransfer();
                        const files = fileInput.files;

                        for (let i = 0; i < files.length; i++) {
                            if (i !== index) {
                                dt.items.add(files[i]);
                            }
                        }

                        fileInput.files = dt.files;
                        handleFiles(dt.files);
                    }
                }
            });
        </script>
    @endpush
@endonce
