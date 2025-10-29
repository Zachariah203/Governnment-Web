<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="employee-form" class="needs-validation" novalidate enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="company_id" value="{{ $company->company_id }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Employee Number</label>
                            <input type="text" class="form-control" name="employee_number" required placeholder="EMP12345">
                            <div class="invalid-feedback">Employee number is required.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="employee_first_name" required>
                            <div class="invalid-feedback">First name is required.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="employee_last_name" required>
                            <div class="invalid-feedback">Last name is required.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" name="employee_email" required placeholder="example@company.com">
                            <div class="invalid-feedback">Valid email is required.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="employee_phone" placeholder="+234 800 000 0000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="employee_dob">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="employee_gender">
                                <option value="" selected disabled>Choose...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Job Title</label>
                            <input type="text" class="form-control" name="employee_job_title">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="employee_department" id="employee_department" required>
                                <option value="" selected disabled>Choose...</option>
                            </select>
                            <div class="invalid-feedback">Please select a department.</div>
                        </div>
                        <div class="col-md-3">
                            <label for="">HireDate</label>
                            <input type="date" class="form-control" name="employee_hire_date">
                        </div>
                        <div class="col-md-3">
                            <label for="">Status</label>
                            <select class="form-select" name="employee_status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Terminated">Terminated</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="employee_address">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="employee_city">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control" name="employee_state">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Zip Code</label>
                            <input type="text" class="form-control" name="employee_zip">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Country</label>
                            <input type="text" class="form-control" name="employee_country">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Emergency Contact</label>
                            <input type="text" class="form-control" name="employee_emergency_contact">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Emergency Phone</label>
                            <input type="text" class="form-control" name="employee_emergency_phone">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" name="employee_profile_picture" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editEmployeeForm" class="needs-validation" novalidate enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="EmployeeID">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">First Name</label>
                            <input type="text" class="form-control" name="FirstName" required>
                            <div class="invalid-feedback">First name is required.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Last Name</label>
                            <input type="text" class="form-control" name="LastName" required>
                            <div class="invalid-feedback">Last name is required.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control" name="Email" required>
                            <div class="invalid-feedback">Valid email is required.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Department</label>
                            <select class="form-select" name="DepartmentID" required>
                                <option value="" selected disabled>Choose...</option>
                            </select>
                            <div class="invalid-feedback">Please select a department.</div>
                        </div>
                        <div class="col-md-3">
                            <label for="">HireDate</label>
                            <input type="date" class="form-control" name="HireDate">
                        </div>
                        <div class="col-md-3">
                            <label for="">Status</label>
                            <select class="form-select" name="Status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Terminated">Terminated</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" name="ProfilePicture" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>
