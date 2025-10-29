    <!-- Add Chemical Modal -->
    <div class="modal fade" id="addChemicalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Add Chemical</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form id="addChemicalForm">
            @csrf
            <input type="hidden" name="type" value="chemical">
            <div class="modal-body">
            <div class="mb-3"><label class="form-label">Name</label><input name="name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Type</label><input name="type" class="form-control"></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label>Quantity</label><input name="quantity" type="number" step="0.01" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label>Unit</label><input name="unit" class="form-control"></div>
            </div>
            <div class="mb-3"><label>Hazardous?</label>
                <select name="is_hazardous" class="form-select">
                <option value="0" selected>No</option>
                <option value="1">Yes</option>
                </select>
            </div>
            <div class="mb-3"><label>Storage Location</label><input name="storage_location" class="form-control"></div>
            <div class="mb-3"><label>SDS URL</label><input name="sds_url" class="form-control" placeholder="https://..."></div>
            </div>
            <div class="modal-footer"><button class="btn btn-primary">Save Chemical</button></div>
        </form>
        </div>
    </div>
    </div>

    <!-- Edit Chemical Modal (reused, fill via AJAX) -->
    <div class="modal fade" id="editChemicalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-secondary text-white">
            <h5 class="modal-title">Edit Chemical</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form id="editChemicalForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="chemical">
            <input type="hidden" name="id" id="editChemicalId">
            <div class="modal-body">
            <!-- same fields as add -->
            <div class="mb-3"><label class="form-label">Name</label><input id="editChemName" name="name" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Type</label><input id="editChemType" name="type" class="form-control"></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label>Quantity</label><input id="editChemQty" name="quantity" type="number" step="0.01" class="form-control" required></div>
                <div class="col-md-6 mb-3"><label>Unit</label><input id="editChemUnit" name="unit" class="form-control"></div>
            </div>
            <div class="mb-3"><label>Hazardous?</label>
                <select id="editChemHaz" name="is_hazardous" class="form-select">
                <option value="0">No</option>
                <option value="1">Yes</option>
                </select>
            </div>
            <div class="mb-3"><label>Storage Location</label><input id="editChemStore" name="storage_location" class="form-control"></div>
            <div class="mb-3"><label>SDS URL</label><input id="editChemSds" name="sds_url" class="form-control"></div>
            </div>
            <div class="modal-footer"><button class="btn btn-primary">Save Changes</button></div>
        </form>
        </div>
    </div>
    </div>