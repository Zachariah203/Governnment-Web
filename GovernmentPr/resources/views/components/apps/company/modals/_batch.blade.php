<!-- ADD BATCH MODAL -->
<div class="modal fade" id="addBatchModal" tabindex="-1" aria-labelledby="addBatchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Batch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Batch Code</label>
                            <input type="text" name="batch_code" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Production Date</label>
                            <input type="date" name="production_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Product</label>
                            <select name="product_id" class="form-select">
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Output Quantity</label>
                            <input type="number" name="output_quantity" step="0.01" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Water Used (L)</label>
                            <input type="number" name="water_used" step="0.01" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Waste Generated (kg)</label>
                            <input type="number" name="waste_generated" step="0.01" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Efficiency (%)</label>
                            <input type="number" name="efficiency" step="0.1" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Chemicals Used</label>
                            <textarea name="chemical_summary" class="form-control" rows="2" placeholder="e.g. NaOH - 3kg, HCl - 2L"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Batch</button>
                </div>
            </div>
        </form>
    </div>
</div>