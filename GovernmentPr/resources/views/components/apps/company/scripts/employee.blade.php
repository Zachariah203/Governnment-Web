<script>
document.addEventListener('DOMContentLoaded', function () {
    let employeesLoaded = false;
    let employeeCache = [];

    // Initialize DataTable
    const employeesTable = $('#tbl-employees').DataTable({
        destroy: true,
        searching: true,
        responsive: true,
        columns: [
            { title: "Employee" },
            { title: "Email" },
            { title: "Department" },
            { title: "Action", orderable: false, searchable: false }
        ]
    });

    // Load employees when tab is shown
    const employeesTab = document.querySelector('button[data-bs-toggle="tab"]#employees-tab');
    if (employeesTab) {
        employeesTab.addEventListener('shown.bs.tab', function () {
            if (!employeesLoaded) {
                loadEmployees();
                employeesLoaded = true;
            }
        });
    }

    // Refresh Button
    const addEmployeeBtn = document.querySelector('#employees .btn.btn-primary');
    if (addEmployeeBtn && !document.querySelector('#refreshEmployeesBtn')) {
        const refreshBtn = document.createElement('button');
        refreshBtn.id = 'refreshEmployeesBtn';
        refreshBtn.className = 'btn btn-outline-secondary btn-sm ms-2';
        refreshBtn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Refresh';
        refreshBtn.addEventListener('click', () => loadEmployees(true));
        addEmployeeBtn.parentNode.appendChild(refreshBtn);
    }

    // ----------------------- LOAD EMPLOYEES -----------------------
    async function loadEmployees(forceReload = false) {
        if (employeeCache.length && !forceReload) {
            populateTable(employeeCache);
            return;
        }

        const companyId = "{{ $company->company_id ?? '' }}";
        const url = `{{ route('admin.company-employees', ['company' => 'COMPANY_ID']) }}`.replace('COMPANY_ID', companyId);

        displayMessage('info', 'Loading employees...');
        showLoadingSpinner();

        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            const result = await response.json();

            document.querySelectorAll('#employees .alert').forEach(a => a.remove());

            if (result.status === 'success' && Array.isArray(result.employees)) {
                employeeCache = result.employees;
                populateTable(employeeCache);
                displayMessage('success', `Loaded ${employeeCache.length} employees successfully.`);
            } else {
                employeesTable.clear().draw();
                displayMessage('warning', result.message || 'No employees found.');
            }
        } catch (error) {
            console.error('Error loading employees:', error);
            employeesTable.clear().draw();
            displayMessage('danger', 'Error loading employee data.');
        }
    }

    function populateTable(employees) {
        employeesTable.clear();

        employees.forEach(emp => {
            const name = `${emp.FirstName ?? ''} ${emp.LastName ?? ''}`.trim() || 'N/A';
            const email = emp.Email ?? 'N/A';
            const department = emp.department?.DepartmentName ?? 'N/A';
            const id = emp.EmployeeID;
            const profilePic = emp.ProfilePicture 
                ? `{{ asset('storage/') }}/${emp.ProfilePicture}`
                : `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=0D8ABC&color=fff&size=64`;

            employeesTable.row.add([
                `<div class="d-flex align-items-center">
                    <img src="${profilePic}" alt="Profile of ${name}" class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;">
                    <span>${name}</span>
                </div>`,
                email,
                department,
                `<div class="text-end">
                    <button class="btn btn-sm btn-primary" data-action="edit" data-id="${id}"><i class="la la-pencil"></i></button>
                    <button class="btn btn-sm btn-danger" data-action="delete" data-id="${id}"><i class="la la-trash"></i></button>
                </div>`
            ]);
        });

        employeesTable.draw();
    }

    function showLoadingSpinner() {
        employeesTable.clear().draw();
        $('#tbl-employees tbody').html(`
            <tr>
                <td colspan="4" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </td>
            </tr>
        `);
    }

    // ----------------------- BOOTSTRAP ALERT -----------------------
    function displayMessage(type, message) {
        document.querySelectorAll('#employees .alert').forEach(a => a.remove());
        const alertBox = document.createElement('div');
        alertBox.className = `alert alert-${type} alert-dismissible fade show mt-2`;
        alertBox.role = 'alert';
        alertBox.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.querySelector('#employees').prepend(alertBox);
        setTimeout(() => alertBox.remove(), 4000);
    }

    // ----------------------- FORM VALIDATION HELPER -----------------------
    function enableBootstrapValidation(formSelector) {
        const forms = document.querySelectorAll(formSelector);
        forms.forEach(form => {
            form.addEventListener('submit', e => {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return false; // Stop AJAX submission
                }
                form.classList.add('was-validated');
            }, false);
        });
    }

    enableBootstrapValidation('.needs-validation');

    // ----------------------- ADD EMPLOYEE -----------------------
    const addEmployeeForm = document.querySelector('#employee-form');
    addEmployeeForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!addEmployeeForm.checkValidity()) return; // Stop if invalid

        const formData = new FormData(addEmployeeForm);
        const companyId = "{{ $company->company_id ?? '' }}";
        const url = `{{ route('admin.store-company-employee', ['company' => 'COMPANY_ID']) }}`.replace('COMPANY_ID', companyId);

        displayMessage('info', 'Adding new employee...');

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });
            const result = await response.json();

            if (result.status === 'success') {
                displayMessage('success', 'Employee added successfully!');
                addEmployeeForm.reset();
                addEmployeeForm.classList.remove('was-validated');
                bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal')).hide();
                loadEmployees(true);
            } else {
                displayMessage('warning', result.message || 'Failed to add employee.');
            }
        } catch (error) {
            console.error('Error adding employee:', error);
            displayMessage('danger', 'Error adding employee.');
        }
    });

    // ----------------------- EDIT / DELETE -----------------------
    $('#tbl-employees').on('click', 'button[data-action]', function () {
        const id = $(this).data('id');
        const action = $(this).data('action');

        if (action === 'edit') openEditModal(id);
        if (action === 'delete') confirmDelete(id);
    });

    function openEditModal(employeeId) {
        const employee = employeeCache.find(emp => emp.EmployeeID === employeeId);
        if (!employee) return displayMessage('danger', 'Employee not found.');

        const modal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
        document.querySelector('#editEmployeeModal input[name="FirstName"]').value = employee.FirstName ?? '';
        document.querySelector('#editEmployeeModal input[name="LastName"]').value = employee.LastName ?? '';
        document.querySelector('#editEmployeeModal input[name="Email"]').value = employee.Email ?? '';
        document.querySelector('#editEmployeeModal select[name="DepartmentID"]').value = employee.department?.DepartmentID ?? '';
        document.querySelector('#editEmployeeModal input[name="EmployeeID"]').value = employeeId;

        modal.show();
    }

    document.querySelector('#editEmployeeForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = e.target;

        if (!form.checkValidity()) return; // Stop if invalid

        const employeeId = form.EmployeeID.value;
        const formData = new FormData(form);

        const url = `{{ route('admin.update-company-employee', ['employee' => 'EMP_ID']) }}`.replace('EMP_ID', employeeId);
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            });
            const result = await response.json();
            if (result.status === 'success') {
                displayMessage('success', 'Employee updated successfully.');
                form.classList.remove('was-validated');
                loadEmployees(true);
                bootstrap.Modal.getInstance(document.getElementById('editEmployeeModal')).hide();
            } else {
                displayMessage('warning', result.message || 'Failed to update employee.');
            }
        } catch (error) {
            console.error(error);
            displayMessage('danger', 'Error updating employee.');
        }
    });

    function confirmDelete(employeeId) {
        if (!confirm('Are you sure you want to delete this employee?')) return;
        deleteEmployee(employeeId);
    }

    async function deleteEmployee(employeeId) {
        const url = `{{ route('admin.delete-company-employee', ['employee' => 'EMP_ID']) }}`.replace('EMP_ID', employeeId);
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const result = await response.json();
            if (result.status === 'success') {
                displayMessage('success', 'Employee deleted successfully.');
                loadEmployees(true);
            } else {
                displayMessage('warning', result.message || 'Failed to delete employee.');
            }
        } catch (error) {
            console.error(error);
            displayMessage('danger', 'Error deleting employee.');
        }
    }

    // ----------------------- LOAD DEPARTMENTS -----------------------
    async function loadDepartments(selectSelector) {
        const url = `{{ route('admin.company-departments', ['company' => 'COMPANY_ID']) }}`.replace('COMPANY_ID', "{{ $company->company_id ?? '' }}");
        try {
            const response = await fetch(url);
            const result = await response.json();

            if (result.status === 'success' && Array.isArray(result.departments)) {
                const select = document.querySelector(selectSelector);
                select.innerHTML = '<option value="" selected disabled>Choose...</option>';
                result.departments.forEach(dep => {
                    const option = document.createElement('option');
                    option.value = dep.DepartmentID;
                    option.textContent = dep.DepartmentName;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading departments:', error);
        }
    }

    // Load departments on modal show
    document.getElementById('addEmployeeModal').addEventListener('show.bs.modal', () => loadDepartments('#employee_department'));
    document.getElementById('editEmployeeModal').addEventListener('show.bs.modal', () => loadDepartments('#editEmployeeModal select[name="DepartmentID"]'));
});
</script>
