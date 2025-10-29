<div class="tab-pane fade" id="operations" role="tabpanel">
    <div class="card card-wm shadow-sm border-0">
        <div class="card-header bg-light d-flex align-items-center justify-content-between">
            <h4 class="mb-0 fw-bold text-primary"><i class="la la-cogs me-2"></i> Operations Management</h4>
            <button class="btn btn-gradient btn-sm" data-bs-toggle="modal" data-bs-target="#annualPlanModal">
                <i class="la la-calendar-plus me-1"></i> Add Annual Plan
            </button>
        </div>

        <div class="card-body">
            <!-- Operations Sub-tabs -->
            <ul class="nav nav-pills nav-wm mb-4" id="operationsSubTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#op-overview" role="tab">
                        
                        Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#op-annual" role="tab">Annual Plans</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#op-batches" role="tab">Batch Operations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#op-performance" role="tab">Performance Tracking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#op-resources" role="tab">Waste & Resource Tracking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#op-reports" role="tab">Reports</a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Overview -->
                <div class="tab-pane fade show active" id="op-overview" role="tabpanel">
                    <div class="text-muted mb-3">
                        <p>Manage and monitor your organization’s annual operations, production batches, waste generation, and performance efficiency.</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="card text-center shadow-sm border-0 p-3">
                                <h6 class="text-muted">Total Plans</h6>
                                <h3 class="fw-bold text-primary">{{ $annualPlans->count() ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center shadow-sm border-0 p-3">
                                <h6 class="text-muted">Total Batches</h6>
                                <h3 class="fw-bold text-primary">{{ $batches->count() ?? 0 }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center shadow-sm border-0 p-3">
                                <h6 class="text-muted">Avg. Efficiency</h6>
                                <h3 class="fw-bold text-success">{{ $avg_efficiency ?? '92%' }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center shadow-sm border-0 p-3">
                                <h6 class="text-muted">Total Waste (tons)</h6>
                                <h3 class="fw-bold text-danger">{{ $total_waste ?? '18.4' }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Annual Plans -->
                <div class="tab-pane fade" id="op-annual" role="tabpanel">
                    <h5 class="fw-bold mb-3 text-primary">Annual Operations Plan</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Year</th>
                                    <th>Expected Operation</th>
                                    <th>Expected Production</th>
                                    <th>Water Usage (m³)</th>
                                    <th>Expected Waste (tons)</th>
                                    <th>Chemical Usage</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($annualPlans as $plan)
                                <tr>
                                    <td>{{ $plan->year }}</td>
                                    <td>{{ $plan->expected_operation }}</td>
                                    <td>{{ $plan->expected_production }}</td>
                                    <td>{{ $plan->expected_water_usage }}</td>
                                    <td>{{ $plan->expected_waste_generated }}</td>
                                    <td>{{ $plan->expected_chemical_usage }}</td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm"><i class="la la-eye"></i></button>
                                        <button class="btn btn-outline-secondary btn-sm"><i class="la la-edit"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">No annual plans found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Batch Operations -->
                <div class="tab-pane fade" id="op-batches" role="tabpanel">
                    <h5 class="fw-bold mb-3 text-primary">Batch Production Management</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Batch Code</th>
                                    <th>Product</th>
                                    <th>Production Date</th>
                                    <th>Water Used (m³)</th>
                                    <th>Waste Generated (kg)</th>
                                    <th>Chemicals Used</th>
                                    <th>Efficiency (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($batches as $batch)
                                <tr>
                                    <td>{{ $batch->batch_code }}</td>
                                    <td>{{ $batch->product->name ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($batch->production_date)->format('M d, Y') }}</td>
                                    <td>{{ $batch->water_used }}</td>
                                    <td>{{ $batch->waste_generated }}</td>
                                    <td>{{ $batch->chemical_usage }}</td>
                                    <td>{{ $batch->efficiency }}%</td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">No batch data available.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Performance Tracking -->
                <div class="tab-pane fade" id="op-performance" role="tabpanel">
                    <h5 class="fw-bold mb-3 text-primary">Annual Performance Overview</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold">Waste by Category</h6>
                                    <canvas id="wasteByCategoryChart" height="180"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold">Chemical Usage per Batch</h6>
                                    <canvas id="chemicalUsageChart" height="180"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold">Efficiency Ratios (Output per Water Unit)</h6>
                                    <canvas id="efficiencyRatioChart" height="150"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Waste & Resource Tracking -->
                <div class="tab-pane fade" id="op-resources" role="tabpanel">
                    <h5 class="fw-bold mb-3 text-primary">Resource & Waste Tracking</h5>
                    <p class="text-muted">Monitor waste generated, chemical usage, and water consumption across all operations.</p>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Batch</th>
                                    <th>Waste Type</th>
                                    <th>Quantity</th>
                                    <th>Chemical Used</th>
                                    <th>Water Used (m³)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resources as $res)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($res->date)->format('M d, Y') }}</td>
                                    <td>{{ $res->batch->batch_code ?? '-' }}</td>
                                    <td>{{ $res->waste_type }}</td>
                                    <td>{{ $res->quantity }}</td>
                                    <td>{{ $res->chemical_used }}</td>
                                    <td>{{ $res->water_used }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No resource data logged.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Reports -->
                <div class="tab-pane fade" id="op-reports" role="tabpanel">
                    <h5 class="fw-bold mb-3 text-primary">Operational Reports & Analytics</h5>
                    <p class="text-muted">Generate and export reports for audits, sustainability analysis, and performance tracking.</p>
                    <button class="btn btn-outline-primary"><i class="la la-download me-1"></i> Download Annual Report</button>
                    <button class="btn btn-outline-success"><i class="la la-chart-bar me-1"></i> View Performance Dashboard</button>
                </div>
            </div>
        </div>
    </div>
</div>
