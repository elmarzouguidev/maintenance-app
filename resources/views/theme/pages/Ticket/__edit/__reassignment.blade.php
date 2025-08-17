@if(auth()->user()->hasRole('SuperAdmin'))
<div class="row" id="reassignment">
    <div class="card mb-4">
        <div class="card-body">
            <p class="card-title-desc">
                <i class="fas fa-exchange-alt me-2"></i>
                Réassignation du Ticket (Super Admin)
            </p>
            
            <form action="{{ route('admin:tickets.reassign', $ticket->uuid) }}" method="POST" id="reassignment-form" novalidate>
                @csrf
                
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Technicien Actuel</label>
                        <input type="text" class="form-control" 
                               value="{{ optional($ticket->technicien)->full_name ?? 'Non assigné' }}" 
                               readonly>
                    </div>
                    
                    <div class="col-lg-6 mb-3">
                        <label class="form-label" for="technicien_id">Nouveau Technicien *</label>
                        <select name="technicien_id" id="technicien_id" class="form-control @error('technicien_id') is-invalid @enderror" required>
                            <option value="">Sélectionner un technicien</option>
                            @foreach($techniciens as $technicien)
                                <option value="{{ $technicien->id }}" 
                                        {{ old('technicien_id') == $technicien->id ? 'selected' : '' }}
                                        {{ $ticket->user_id == $technicien->id ? 'disabled' : '' }}>
                                    {{ $technicien->full_name }} 
                                    @if($ticket->user_id == $technicien->id)
                                        (Actuellement assigné)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('technicien_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label" for="reassignment_reason">Raison de la réassignation *</label>
                        <textarea name="reassignment_reason" 
                                  id="reassignment_reason"
                                  class="form-control no-tinymce @error('reassignment_reason') is-invalid @enderror" 
                                  rows="3" 
                                  placeholder="Expliquez la raison de cette réassignation..."
                                  required>{{ old('reassignment_reason') }}</textarea>
                        @error('reassignment_reason')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Cette action sera enregistrée dans l'historique du ticket et ne peut être effectuée que par un Super Admin.
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-warning waves-effect waves-light">
                            <i class="fas fa-exchange-alt me-2"></i>
                            Réassigner le Ticket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Ensure the reassignment form is always visible */
#reassignment {
    display: block !important;
    visibility: visible !important;
}

#reassignment-form {
    display: block !important;
    visibility: visible !important;
}

#reassignment_reason {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: relative !important;
    z-index: 1 !important;
}

/* Remove any hidden attributes */
#reassignment_reason[aria-hidden="true"] {
    aria-hidden: false !important;
}

/* Prevent TinyMCE from initializing on this textarea */
.no-tinymce {
    /* This class will be used to exclude from TinyMCE */
}
</style>

<script>
// Prevent TinyMCE from initializing on the reassignment textarea
document.addEventListener('DOMContentLoaded', function() {
    const reassignmentTextarea = document.getElementById('reassignment_reason');
    if (reassignmentTextarea) {
        // Remove any TinyMCE instances that might have been created
        if (window.tinymce && window.tinymce.get(reassignmentTextarea.id)) {
            window.tinymce.remove(reassignmentTextarea.id);
        }
        
        // Ensure it's a simple textarea
        reassignmentTextarea.style.display = 'block';
        reassignmentTextarea.style.visibility = 'visible';
    }
});
</script>
@endif
