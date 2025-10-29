<!-- ADD WASTE RECORD MODAL -->
<div class="modal fade" id="addWasteModal" tabindex="-1" aria-labelledby="addWasteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-semibold" id="addWasteModalLabel">
                        <i class="la la-recycle me-1 text-success"></i> Add Waste Record
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- WASTE CATEGORY -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Waste Category</label>
                            <select name="waste_category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                @foreach($wasteCategories as $cat)
                                    <option value="{{ $cat->waste_category_id }}">{{ $cat->waste_category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- WASTE TYPE -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Waste Sub-category</label>
                            <select name="waste_sub_category_id" class="form-select">
                                <option value="">Select Sub-category</option>
                                @foreach($wasteSubCategories as $sub)
                                    <option value="{{ $sub->waste_sub_category_id }}">{{ $sub->waste_sub_category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- SOURCE BATCH -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Batch Source</label>
                            <select name="batch_id" class="form-select">
                                <option value="">Select Batch</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->batch_code }} — {{ $batch->product_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- PRODUCTION DATE -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Generation Date</label>
                            <input type="date" name="generation_date" class="form-control" required>
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Waste Description</label>
                            <textarea name="description" rows="2" class="form-control" placeholder="E.g. Residual sludge, chemical residue, etc."></textarea>
                        </div>

                        <!-- QUANTITY -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Quantity</label>
                            <input type="number" name="quantity" class="form-control" step="0.01" placeholder="0.00" required>
                        </div>

                        <!-- UNIT -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Unit</label>
                            <select name="unit" class="form-select">
                                <option value="kg">kg</option>
                                <option value="L">L</option>
                                <option value="ton">ton</option>
                                <option value="m³">m³</option>
                            </select>
                        </div>

                        <!-- DISPOSAL METHOD -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Disposal Method</label>
                            <select name="disposal_method" class="form-select">
                                <option value="Recycled">Recycled</option>
                                <option value="Disposed">Disposed</option>
                                <option value="Reused">Reused</option>
                                <option value="Composted">Composted</option>
                            </select>
                        </div>

                        <!-- DISPOSAL SITE -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Disposal Site / Contractor</label>
                            <input type="text" name="disposal_site" class="form-control" placeholder="E.g. City landfill, ABC Recyclers Ltd.">
                        </div>

                        <!-- STATUS -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Processed">Processed</option>
                                <option value="Disposed">Disposed</option>
                            </select>
                        </div>

                        <!-- NOTES -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Notes</label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Additional remarks or observations"></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="la la-save me-1"></i>Save Record
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
