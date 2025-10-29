<!-- ADD QC RECORD MODAL -->
<div class="modal fade" id="addQCModal" tabindex="-1" aria-labelledby="addQCModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-semibold" id="addQCModalLabel">
                        <i class="la la-vial me-1 text-primary"></i> Add Quality Control Record
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- BATCH SELECTION -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Batch</label>
                            <select name="batch_id" class="form-select" required>
                                <option value="">Select Batch</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}">{{ $batch->batch_code }} — {{ $batch->product_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- QC DATE -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Inspection Date</label>
                            <input type="date" name="inspection_date" class="form-control" required>
                        </div>

                        <!-- PRODUCT NAME -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Product</label>
                            <select name="product_id" class="form-select" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- INSPECTOR NAME -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Inspector</label>
                            <input type="text" name="inspector" class="form-control" placeholder="Inspector name" required>
                        </div>

                        <!-- PARAMETERS TESTED -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Parameters Tested</label>
                            <textarea name="parameters_tested" rows="2" class="form-control" placeholder="E.g. pH, viscosity, temperature"></textarea>
                        </div>

                        <!-- RESULTS -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Test Results</label>
                            <textarea name="results" rows="2" class="form-control" placeholder="E.g. pH: 7.2, Viscosity: 15cP, Temperature: 25°C"></textarea>
                        </div>

                        <!-- REMARKS -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Remarks</label>
                            <textarea name="remarks" rows="2" class="form-control" placeholder="Observations, anomalies, corrective actions"></textarea>
                        </div>

                        <!-- STATUS -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>

                        <!-- APPROVED BY -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Approved By</label>
                            <input type="text" name="approved_by" class="form-control" placeholder="Supervisor / QA Officer">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="la la-save me-1"></i>Save Record
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>