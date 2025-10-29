<div class="modal fade" id="addProcessModal" tabindex="-1" aria-labelledby="addProcessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-semibold" id="addProcessModalLabel">
                        <i class="la la-cogs text-primary me-1"></i>Add Production Process Step
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Step No.</label>
                            <input type="number" name="step_number" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Process Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Responsible Unit</label>
                            <input type="text" name="responsible_unit" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expected Duration (hrs)</label>
                            <input type="number" name="expected_duration" class="form-control" step="0.1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Resources Required</label>
                            <input type="text" name="resources_required" class="form-control" placeholder="E.g. Mixer, Heat, Water">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Step</button>
                </div>
            </div>
        </form>
    </div>
</div>
