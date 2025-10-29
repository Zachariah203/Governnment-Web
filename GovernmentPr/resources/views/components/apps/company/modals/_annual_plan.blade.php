<div class="modal fade" id="addAnnualPlanModal" tabindex="-1" aria-labelledby="addAnnualPlanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Annual Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Year</label>
                            <input type="number" name="year" class="form-control" placeholder="e.g. 2025">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Expected Operations</label>
                            <input type="text" name="expected_operations" class="form-control" placeholder="e.g. Manufacturing, Packaging, Logistics">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Expected Production (Units)</label>
                            <input type="number" name="expected_production" class="form-control" placeholder="Enter expected units">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Expected Water Usage (m³)</label>
                            <input type="number" name="expected_water_usage" class="form-control" placeholder="Enter volume in cubic meters">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Expected Waste Generated (kg)</label>
                            <input type="number" name="expected_waste_generated" class="form-control" placeholder="Enter total waste">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Expected Chemical Usage (L)</label>
                            <input type="number" name="expected_chemical_usage" class="form-control" placeholder="Enter liters used">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="la la-save me-1"></i> Save Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
