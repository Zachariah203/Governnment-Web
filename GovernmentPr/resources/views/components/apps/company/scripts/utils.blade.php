<script>
    /**
     * TaggingComponent class for managing tagging functionality.
     * - Handles user input for tagging.
     * - Fetches user suggestions from the server.
     * - Allows adding and removing tags.
     */
    class TaggingComponent {
        constructor(containerId, TagInput) {
            this.container = document.getElementById(containerId);
            this.tagInput = this.container.querySelector(`.${TagInput}`);
            this.tags = [];
            this.userMap = {}; // Maps user names to ids

            this.renderInputField();
            this.renderSuggestions();
        }

        renderInputField() {
            const inputField = document.createElement('input');
            inputField.type = 'text';
            inputField.placeholder = 'Tag someone...';
            inputField.className = 'form-control';
            inputField.classList.add('form-control');
            inputField.addEventListener('input', (e) => this.fetchUsers(e.target.value.trim()));
            inputField.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const name = e.target.value.trim();
                console.log("Adding tag:", name);
                
                if (this.userMap[name]) {
                    this.addTag(this.userMap[name], name);
                }
            }
            });
            this.tagInput.appendChild(inputField);
            this.inputField = inputField;
        }

        renderSuggestions() {
            const suggestionsDiv = document.createElement('div');
            suggestionsDiv.className = 'suggestions';
            this.tagInput.appendChild(suggestionsDiv);
            this.suggestionsDiv = suggestionsDiv;
        }

        async fetchUsers(query) {
            if (!query) {
            this.suggestionsDiv.innerHTML = '';
            return;
            }
            try {
            const companyId = "{{ $company->company_id }}";
            const response = await fetch(`{{ url('/admin/search-employee') }}?search=${encodeURIComponent(query)}&company_id=${encodeURIComponent(companyId)}`);
            const users = await response.json();
            console.log("Fetched users:", users);
            this.showSuggestions(users.users || []);
            } catch (error) {
            console.error("Failed to fetch users:", error);
            }
        }

        showSuggestions(users) {
            const filteredUsers = users.filter(user => !this.tags.includes(user.name));
            this.suggestionsDiv.innerHTML = '';
            filteredUsers.forEach(user => {
            const suggestionElement = document.createElement('div');
            suggestionElement.className = 'suggestion';
            suggestionElement.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px;">
                <img src="${user.profilePic}" alt="${user.name}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid #e0e7ff;">
                <div>
                    <strong style="font-size: 15px; color: #1d4ed8;">${user.name}</strong><br>
                    <span style="font-size: 13px; color: #64748b;">${user.role}</span><br>
                    <span style="font-size: 12px; color: #6366f1;">${user.email}</span>
                </div>
                </div>
            `;
            suggestionElement.addEventListener('click', () => this.addTag(user.id, user.name));
            this.suggestionsDiv.appendChild(suggestionElement);
            
            // Update the user map
                this.userMap[user.name] = user.id;
            });
        }

        addTag(userId, userName) {
            if (userId && !this.tags.includes(userId)) {
                this.tags.push(userId);
                this.renderTags();
                this.inputField.value = '';
                this.suggestionsDiv.innerHTML = '';
            }
        }

        removeTag(userId) {
            this.tags = this.tags.filter(id => id !== userId);
            this.renderTags();
        }

        renderTags() {
            this.tagInput.innerHTML = '';
            this.tags.forEach(userId => {
                const userName = Object.keys(this.userMap).find(name => this.userMap[name] === userId);
                const tagElement = document.createElement('div');
                tagElement.className = 'tag';
                tagElement.innerHTML = `${userName} <span>&times;</span>`;
                tagElement.querySelector('span').addEventListener('click', () => this.removeTag(userId));
                this.tagInput.appendChild(tagElement);
            });
            this.tagInput.appendChild(this.inputField);
            this.tagInput.appendChild(this.suggestionsDiv);
        }
        // Get selected user IDs and names
        getSelectedUserIds() {
            return this.tags;
        }

        getSelectedUserNames() {
            return this.tags.map(id => Object.keys(this.userMap).find(name => this.userMap[name] === id));
        }
    }
    
    let  manager_1 =  new TaggingComponent('manager-tag-input-1', 'manager-tag-input-1');
    let  manager_2 =  new TaggingComponent('manager-tag-input-2', 'manager-tag-input-2');
    let  manager_3 =  new TaggingComponent('manager-tag-input-3', 'manager-tag-input-3');
    let  manager_4 =  new TaggingComponent('manager-tag-input-4', 'manager-tag-input-4');
    let  manager_5 =  new TaggingComponent('manager-tag-input-5', 'manager-tag-input-5');
    let  manager_6 =  new TaggingComponent('manager-tag-input-6', 'manager-tag-input-6');
</script>
<script>
        // --- Helper utilities (define if missing) ---
    window.showElement = window.showElement || function(el) {
        try {
            if (!el) return;
            if (typeof el === 'string') el = document.querySelector(el);
            el.classList.remove('d-none');
        } catch (e) { console.warn('showElement error', e); }
    };
    window.hideElement = window.hideElement || function(el) {
        try {
            if (!el) return;
            if (typeof el === 'string') el = document.querySelector(el);
            el.classList.add('d-none');
        } catch (e) { console.warn('hideElement error', e); }
    };

    // Fallback for displayMessage in case it's not present
    window.displayMessage = window.displayMessage || function(type, text) {
        // simple fallback: console + small toast (if bootstrap available)
        console.log(`[${type}]`, text);
        if (window.bootstrap && document.body) {
            const toastContainerId = 'cg-message-container';
            let container = document.getElementById(toastContainerId);
            if (!container) {
                container = document.createElement('div');
                container.id = toastContainerId;
                container.style.position = 'fixed';
                container.style.right = '1rem';
                container.style.top = '1rem';
                container.style.zIndex = 1080;
                document.body.appendChild(container);
            }
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-bg-light border';
            toast.role = 'status';
            toast.ariaLive = 'polite';
            toast.ariaAtomic = 'true';
            toast.style.minWidth = '220px';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">${text}</div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            container.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast, { delay: 3500 });
            bsToast.show();
            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        } else {
            // fallback DOM alert
            const div = document.createElement('div');
            div.className = 'alert alert-secondary';
            div.style.position = 'fixed';
            div.style.right = '1rem';
            div.style.top = '1rem';
            div.style.zIndex = 1081;
            div.innerText = text;
            document.body.appendChild(div);
            setTimeout(() => div.remove(), 3500);
        }
    };
</script>
<script>
    async function fetchFieldInput(url, options = {}) {
        try {
            const response = await fetch(url, {
                method: options.method || "GET", // Default to GET
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    ...options.headers, // Include additional headers if provided
                },
                credentials: 'same-origin', // Include credentials for same-origin requests
                ...options, // Merge any additional options
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            console.log("Fetched data:", data);
            return data;
        } catch (error) {
            console.error("Error fetching data:", error);
            throw error; // Re-throw the error to handle it in the calling function
        }
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ----------------------- LOAD DEPARTMENTS -----------------------
    async function loadDepartments(selectSelector) {
        console.log("Trigering the function");
        
        const companyId = "{{ $company->company_id ?? '' }}";
        const url = `{{ route('admin.get-departments', ['companyId' => 'COMPANY_ID']) }}`.replace('COMPANY_ID', companyId);

        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const result = await response.json();

            if (result.status === 'success' && Array.isArray(result.departments)) {
                const select = document.querySelector(selectSelector);
                if (!select) return;

                // Clear existing options
                select.innerHTML = '<option value="" selected disabled>Choose...</option>';

                // Populate departments
                result.departments.forEach(dep => {
                    const option = document.createElement('option');
                    option.value = dep.DepartmentID;
                    option.textContent = dep.DepartmentName;
                    select.appendChild(option);
                });
            } else {
                console.warn('No departments found or invalid response.');
            }
        } catch (error) {
            console.error('Error loading departments:', error);
        }
    }

    // ----------------------- POPULATE ON MODAL SHOW -----------------------
    const addModal = document.getElementById('addEmployeeModal');
    if (addModal) {
        addModal.addEventListener('show.bs.modal', () => {
            console.log('Employee modal opened');
            
            loadDepartments('#employee_department')
        });
    }

    const editModal = document.getElementById('editEmployeeModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', () => loadDepartments('#editEmployeeModal select[name="DepartmentID"]'));
    }

});
</script>