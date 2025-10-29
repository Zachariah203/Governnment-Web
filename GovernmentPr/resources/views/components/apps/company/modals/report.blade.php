<div class="modal fade" id="annualPerformanceReportModal" tabindex="-1" aria-labelledby="annualPerformanceReportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-semibold" id="annualPerformanceReportModalLabel">
          <i class="la la-chart-line text-primary me-1"></i> Annual Performance Report
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold">Select Year</label>
            <select class="form-select" id="reportYear">
              @foreach($years as $year)
                <option value="{{ $year }}">{{ $year }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4 align-self-end">
            <button class="btn btn-primary w-100"><i class="la la-search me-1"></i> Generate</button>
          </div>
        </div>

        <hr>

        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-light">
              <tr>
                <th>Department</th>
                <th>Expected Production</th>
                <th>Actual Production</th>
                <th>Expected Waste</th>
                <th>Actual Waste</th>
                <th>Efficiency (%)</th>
              </tr>
            </thead>
            <tbody>
              @foreach($reportData as $data)
                <tr>
                  <td>{{ $data->department }}</td>
                  <td>{{ number_format($data->expected_production, 2) }}</td>
                  <td>{{ number_format($data->actual_production, 2) }}</td>
                  <td>{{ number_format($data->expected_waste, 2) }}</td>
                  <td>{{ number_format($data->actual_waste, 2) }}</td>
                  <td><span class="badge bg-success">{{ $data->efficiency }}%</span></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div id="annualPerformanceChart" class="mt-4" style="height: 300px;"></div>
      </div>
    </div>
  </div>
</div>
