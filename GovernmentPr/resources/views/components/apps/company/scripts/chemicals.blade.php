<!-- Enhanced Chemical Management Script with UI Feedback -->
<script>
(() => {
    /*** Utility Functions ***/
    const escapeHTML = (str = '') => str.replace(/[&<>"']/g, (m) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;'
    }[m]));

    const showToast = (message, type = 'info') => {
        const toastContainer = document.querySelector('#toast-container') || document.body;
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${escapeHTML(message)}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        toastContainer.appendChild(toast);
        new bootstrap.Toast(toast, { delay: 3000 }).show();
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
    };

    const validateFields = (fields) => {
        for (const { value, message } of fields) {
            if (!value || !value.toString().trim()) {
                showToast(message, 'danger');
                return false;
            }
        }
        return true;
    };

    const postData = async (url, formData) => {
        const res = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        return await res.json();
    };

    const highlightRow = (tr, color = '#d4edda', duration = 1500) => {
        tr.style.transition = 'background-color 0.5s';
        const original = tr.style.backgroundColor;
        tr.style.backgroundColor = color;
        setTimeout(() => {
            tr.style.backgroundColor = original || '';
        }, duration);
    };

    const renderChemicalTable = (chemicals, highlightId = null) => {
        const tbody = document.querySelector('#chemicalTable tbody');
        if (!tbody) return;
        tbody.innerHTML = chemicals.length
            ? chemicals.map((chem, i) => `
                <tr data-id="${chem.company_chemical_id}">
                    <td>${i + 1}</td>
                    <td>${escapeHTML(chem.name)}</td>
                    <td>${escapeHTML(chem.type)}</td>
                    <td>${escapeHTML(chem.quantity)}</td>
                    <td>${escapeHTML(chem.unit)}</td>
                    <td>
                        <span class="badge bg-${chem.is_hazardous ? 'danger' : 'success'}">
                            ${chem.is_hazardous ? 'Yes' : 'No'}
                        </span>
                    </td>
                    <td>${escapeHTML(chem.storage_location)}</td>
                    <td>${chem.updated_at}</td>
                    <td class="text-end">
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#"><i class="las la-ellipsis-v fs-20"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item btn-view" href="#">View</a></li>
                                <li><a class="dropdown-item btn-update" href="#">Update</a></li>
                                <li><a class="dropdown-item btn-delete" href="#">Delete <span class="action-loader d-none ms-2 spinner-border spinner-border-sm"></span></a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item btn-checkin" href="#">Check In <span class="action-loader d-none ms-2 spinner-border spinner-border-sm"></span></a></li>
                                <li><a class="dropdown-item btn-checkout" href="#">Check Out <span class="action-loader d-none ms-2 spinner-border spinner-border-sm"></span></a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `).join('')
            : `<tr><td colspan="9" class="text-center">No chemicals found</td></tr>`;

        if (highlightId) {
            const row = tbody.querySelector(`tr[data-id="${highlightId}"]`);
            if (row) highlightRow(row);
        }
    };

    /*** Form Submission Functions ***/
    const submitChemicalForm = async (formId, url, modalId) => {
        const form = document.querySelector(formId);
        const modalEl = document.querySelector(modalId);
        const loader = modalEl.querySelector('.loader');
        loader.style.display = 'inline-block';

        if (!validateFields([
            { value: form.querySelector('input[name="company_id"]').value, message: 'Company ID is required' },
            { value: form.querySelector('select[name="chemical"]').value, message: 'Chemical selection is required' },
            { value: form.querySelector('input[name="unit_of_measurement"]').value, message: 'Unit of measurement is required' }
        ])) {
            loader.style.display = 'none';
            return;
        }

        try {
            const result = await postData(url, new FormData(form));
            loader.style.display = 'none';
            if (result.status === 'success') {
                renderChemicalTable(result.company_chemical, form.querySelector('select[name="chemical"]').value);
                showToast('Chemical saved successfully', 'success');
                bootstrap.Modal.getInstance(modalEl)?.hide();
            } else {
                showToast(result.message || 'Failed to save chemical', 'danger');
            }
        } catch (err) {
            console.error(err);
            loader.style.display = 'none';
            showToast('An error occurred', 'danger');
        }
    };

    const submitCheckInOut = async (modalId, url) => {
        const modal = document.querySelector(modalId);
        const form = modal.querySelector('form');
        const loader = modal.querySelector('.loader');
        loader.style.display = 'inline-block';

        if (!validateFields([
            { value: form.querySelector('[name="quantity"]').value, message: 'Quantity is required' },
            { value: form.querySelector('[name="date"]').value, message: 'Date is required' },
            { value: form.querySelector('[name="chemical_id"]').value, message: 'Chemical ID is required' }
        ])) {
            loader.style.display = 'none';
            return;
        }

        try {
            const result = await postData(url, new FormData(form));
            loader.style.display = 'none';
            if (result.status === 'success') {
                renderChemicalTable(result.company_chemical, form.querySelector('[name="chemical_id"]').value);
                showToast('Operation successful', 'success');
                bootstrap.Modal.getInstance(modal)?.hide();
            } else {
                showToast(result.message || 'Operation failed', 'danger');
            }
        } catch (err) {
            console.error(err);
            loader.style.display = 'none';
            showToast('An error occurred', 'danger');
        }
    };

    const triggerModal = (modalId, fields = []) => {
        const modal = document.querySelector(modalId);
        fields.forEach(({ name, value }) => {
            const input = modal.querySelector(`[name="${name}"]`);
            if (input) input.value = value;
        });
        new bootstrap.Modal(modal).show();
    };

    /*** Event Listeners ***/
    document.querySelector('#btn-submit-chemical')?.addEventListener('click', () => {
        submitChemicalForm('#chemical-form', "{{ route('admin.store-company-chemical') }}", '#addChemicalModal');
    });

    document.querySelector('#btn-submit-check-in-chemical')?.addEventListener('click', () => {
        submitCheckInOut('#checkInChemicalModal', "{{ route('admin.save-company-chemical-check-in') }}");
    });

    document.querySelector('#btn-submit-check-out-chemical')?.addEventListener('click', () => {
        submitCheckInOut('#checkOutChemicalModal', "{{ route('admin.save-company-chemical-check-out') }}");
    });

    document.querySelector('#chemicalTable tbody').addEventListener('click', async (e) => {
        const tr = e.target.closest('tr');
        if (!tr) return;
        const chemicalId = tr.dataset.id;

        const loaderSpan = e.target.closest('.dropdown-item')?.querySelector('.action-loader');
        if (loaderSpan) loaderSpan.classList.remove('d-none');

        try {
            // Update
            if (e.target.closest('.btn-update')) {
                triggerModal('#addChemicalModal', [
                    { name: 'company_id', value: tr.dataset.companyId },
                    { name: 'chemical', value: chemicalId },
                    { name: 'unit_of_measurement', value: tr.children[4].textContent },
                    { name: 'threshold', value: tr.dataset.threshold || '' }
                ]);
            }

            // Delete
            if (e.target.closest('.btn-delete')) {
                if (!confirm('Are you sure you want to delete this chemical?')) return;
                const result = await postData(`{{ route('admin.delete-company-chemical', '') }}/${chemicalId}`, new FormData());
                if (result.status === 'success') {
                    tr.remove();
                    showToast('Chemical deleted successfully', 'success');
                } else {
                    showToast(result.message || 'Delete failed', 'danger');
                }
            }

            // Check-in
            if (e.target.closest('.btn-checkin')) {
                triggerCheckInChemical(chemicalId, chemicalId, tr.dataset.companyId, tr.children[1].textContent);
            }

            // Check-out
            if (e.target.closest('.btn-checkout')) {
                triggerCheckOutChemical(chemicalId, chemicalId, tr.dataset.companyId, tr.children[1].textContent);
            }
        } catch (err) {
            console.error(err);
            showToast('An error occurred', 'danger');
        } finally {
            if (loaderSpan) loaderSpan.classList.add('d-none');
        }
    });

    window.triggerCheckInChemical = (companyChemicalID, chemicalID, companyID, chemicalName) => {
        triggerModal('#checkInChemicalModal', [
            { name: 'checkIn_chemical_id', value: companyChemicalID },
            { name: 'chemical_id', value: chemicalID },
            { name: 'company_id', value: companyID },
            { name: 'checkIn_chemical_name', value: chemicalName }
        ]);
    };

    window.triggerCheckOutChemical = (companyChemicalID, chemicalID, companyID, chemicalName) => {
        triggerModal('#checkOutChemicalModal', [
            { name: 'checkOut_chemical_id', value: companyChemicalID },
            { name: 'chemical_id', value: chemicalID },
            { name: 'company_id', value: companyID },
            { name: 'checkOut_chemical_name', value: chemicalName }
        ]);
    };
})();
</script>
