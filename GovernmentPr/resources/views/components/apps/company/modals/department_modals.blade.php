<!-- 🧱 Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="addDepartmentModalLabel" class="modal-title">Add New Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="department-form" class="needs-validation" novalidate>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                    <div class="row g-3 align-items-staert">
                        <div class="col-md-6">
                            <label for="department_name" class="form-label fw-bold">Department Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control border-primary" id="department_name" name="department_name" required placeholder="Enter department name">
                            <div class="invalid-feedback">Please enter a department name.</div>
                        </div>
                        <div class="col-md-6">
                            <div class="taggable-container " id="manager-tag-input-1">
                                <label for="manager" class="form-label fw-bold">Department Head / Manager</label>
                                <div class="manager-tag-input-1 manager-tag-input border-primary bg-light">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Department</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- add department -->