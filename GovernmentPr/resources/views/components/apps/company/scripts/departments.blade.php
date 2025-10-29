<script>
(() => {


    // === DOM Elements ===
    const companyId = "{{ json_encode($company->company_id) }}";
    const tableEl = $('#tbl-departments');
    const emptyStateEl = document.getElementById('emptyItems');
    const spinner = document.getElementById('loading-spinner');
    const addModalEl = document.getElementById('addDepartmentModal');
    const departmentForm = document.getElementById('department-form');

    // === DataTable Initialization ===
    const departmentsTable = tableEl.DataTable({
        paging: true,
        searching: true,
        ordering: false,
        responsive: true,
        columnDefs: [{ orderable: false, targets: [4] }],
        data: [],
        columns: [
            { data: 'name', title: 'Name' },
            {
                data: 'managers',
                title: 'Managers',
                render: function (data) {
                    if (Array.isArray(data) && data.length > 0) {
                        return `
                            <div class="d-flex flex-wrap gap-2">
                                ${data.map(mgr => `
                                    <div class="card border-0 shadow-sm" style="min-width:220px; max-width:260px;">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <img src="${mgr.profilePic || '/adminAssets/images/avatar-default.png'}"
                                                    alt="${mgr.name ?? mgr.full_name ?? 'N/A'}"
                                                    class="rounded-circle me-2"
                                                    style="width:32px;height:32px;object-fit:cover;">
                                                <div class="text-truncate">
                                                    <div class="fw-semibold">${mgr.name ?? mgr.full_name ?? 'N/A'}</div>
                                                    <div class="small text-muted">${mgr.email ?? ''}</div>
                                                    <div class="small text-secondary">${mgr.jobTitle ?? ''}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    }
                    return '<span class="text-muted">None</span>';
                }
            },
            { data: 'employee_count', title: 'Employees', defaultContent: '0' },
            {
                data: 'status',
                title: 'Status',
                render: (data) => {
                    const status = data ? data.toLowerCase() : 'inactive';
                    const badge = status === 'active' ? 'bg-success' : 'bg-secondary';
                    return `<span class="badge ${badge} text-uppercase">${status}</span>`;
                }
            },
            {
                data: null,
                title: 'Actions',
                className: 'text-end',
                render: (data, type, row) => `
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" onclick="editDepartment('${row.id}')">
                            <i class="las la-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteDepartment('${row.id}')">
                            <i class="las la-trash-alt"></i>
                        </button>
                    </div>
                `
            }
        ]
    });

    // === Fetch and Load Departments ===
    async function loadDepartments() {
        showElement(spinner);
        try {
            const url = `/admin/get-departments/${companyId}`;
            const data = await fetchFieldInput(url);

            if (data.status === "success" && Array.isArray(data.departments)) {
                const departments = data.departments.map(d => ({
                    id: d.DepartmentID,
                    name: d.DepartmentName,
                    managers: d.managers || [],
                    employee_count: d.employee_count || 0,
                    status: d.status || 'Inactive'
                }));

                departmentsTable.clear().rows.add(departments).draw();
                emptyStateEl.classList.toggle('d-none', departments.length > 0);
            } else {
                departmentsTable.clear().draw();
                emptyStateEl.classList.remove('d-none');
            }
        } catch (error) {
            console.error("Error fetching departments:", error);
            displayMessage('danger', 'Unable to load departments.');
        } finally {
            hideElement(spinner);
        }
    }

    // === Load Departments when Tab is Active ===
    document.querySelector('#departments-subtab')?.addEventListener('shown.bs.tab', loadDepartments);

    // === Bootstrap Form Validation ===
    Array.from(document.querySelectorAll('.needs-validation')).forEach(form => {
        form.addEventListener('submit', e => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // === Submit Department Form (Create & Edit) ===
    if (departmentForm) {
        departmentForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!departmentForm.checkValidity()) {
                departmentForm.classList.add('was-validated');
                return;
            }

            const formData = new FormData(departmentForm);
            const managerIds = (typeof manager_1 !== 'undefined' && manager_1.getSelectedUserIds)
                ? manager_1.getSelectedUserIds() : [];
            managerIds.forEach(id => formData.append('manager_ids[]', id));

            const editingId = departmentForm.dataset.editing || null;
            let url, method;
            if (editingId) {
                url = `{{ url('admin/company-departments') }}/${id}`;

                method = 'PUT';
            } else {
                url = "{{ route('admin.store-company-department') }}";
                method = 'POST';
            }

            try {
                showElement(spinner);
                const result = await fetch_cycle(editingId ? '--Update Department' : '--Store Department', url, method, formData);

                if (result && result.status === 'success') {
                    displayMessage('success', editingId ? 'Department updated successfully.' : 'New department created.');

                    const bsModal = bootstrap.Modal.getInstance(addModalEl) || new bootstrap.Modal(addModalEl);
                    bsModal.hide();

                    departmentForm.reset();
                    departmentForm.classList.remove('was-validated');
                    delete departmentForm.dataset.editing;

                    // 🔄 Always reload from backend to reflect accurate data
                    await loadDepartments();
                } else {
                    displayMessage('warning', (result && result.message) || 'Could not save department.');
                }
            } catch (err) {
                console.error('departmentForm submit error', err);
                displayMessage('danger', 'Unexpected error while saving department.');
            } finally {
                hideElement(spinner);
            }
        });
    }

    // === Edit Department ===
    window.editDepartment = async function (id) {
        try {
            const url = `{{ url('admin/company-departments') }}/${id}`;
;
            const data = await fetchFieldInput(url);

            if (data.status === 'success' && data.department) {
                const dept = data.department;
                departmentForm.querySelector('[name="department_name"]').value = dept.DepartmentName;
                if (typeof manager_1 !== 'undefined' && manager_1.setSelectedUsers) {
                    manager_1.setSelectedUsers(dept.managers || []);
                }
                departmentForm.dataset.editing = id;

                new bootstrap.Modal(addModalEl).show();
            } else {
                displayMessage('warning', 'Department not found.');
            }
        } catch (error) {
            console.error(error);
            displayMessage('danger', 'Could not load department data.');
        }
    };

    // === Delete Department ===
    window.deleteDepartment = async function (id) {
        if (!confirm('Are you sure you want to delete this department?')) return;

        try {
            showElement(spinner);
            const url = `/admin/delete-department/${id}`;
            const result = await fetch_cycle('--Delete Department', url, 'DELETE');

            if (result.status === 'success') {
                displayMessage('success', 'Department deleted successfully!');
                await loadDepartments();
            } else {
                displayMessage('warning', result.message || 'Unable to delete department.');
            }
        } catch (error) {
            console.error(error);
            displayMessage('danger', 'Error deleting department.');
        } finally {
            hideElement(spinner);
        }
    };
    // Initial load (optional): call loadDepartments() here if you want immediate load on page load
    loadDepartments();
})();

</script>
