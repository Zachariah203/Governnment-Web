
    @section('scripts')
    <!-- DataTables and Bootstrap JavaScript -->
    <script src="{{ asset('adminAssets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminAssets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('adminAssets/js/toastify.js') }}"></script>

    <script src="{{ asset('adminAssets/libs/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('adminAssets/js/pages/datatable.init.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('adminAssets/js/location.js') }}"></script>
    <script src="{{ asset('adminAssets/js/industry.js') }}"></script>

    <script src="{{ asset('adminAssets/libs/vanillajs-datepicker/js/datepicker-full.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.min.js"></script>
    <script src="{{ asset('adminAssets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{ asset('adminAssets/libs/mobius1-selectr/selectr.min.js')}}"></script>
    <script src="{{ asset('adminAssets/libs/huebee/huebee.pkgd.min.js')}}"></script>
    <script src="{{ asset('adminAssets/js/moment.js')}}"></script>
    <script src="{{ asset('adminAssets/libs/imask/imask.min.js')}}"></script>
    <script src="{{ asset('adminAssets/js/pages/forms-advanced.js')}}"></script>
    <script src="{{ asset('adminAssets/js/app.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('#generalTable, #chemicalTable, #waterTable, #equipmentTable, #rawTable').DataTable();
        });
    </script>

    <script>
        const inputElm = document.querySelector("input[name='prepared_by']");

        const tagify = new Tagify(inputElm, {
            tagTextProp: 'name',
            skipInvalid: true,
            dropdown: {
                closeOnSelect: false,
                enabled: 1,
                classname: 'users-list',
                searchKeys: ['name', 'email'],
                position: "text",
                mapValueTo: "email",
            },
            templates: {
                tag: tagTemplate,
                dropdownItem: suggestionItemTemplate,
                dropdownHeader: dropdownHeaderTemplate
            },
            whitelist: [],
            transformTag: transformTagData,
            validate: validateTagData
        });

        function tagTemplate(tagData) {
            return `
                <tag title="${tagData.email}" contenteditable='false' spellcheck='false' tabIndex="-1" class="tagify__tag ${tagData.class || ""}" ${this.getAttributes(tagData)}>
                    <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                    <div>
                        <div class='tagify__tag__avatar-wrap'>
                            <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
                        </div>
                        <span class='tagify__tag-text'>${tagData.name}</span>
                    </div>
                </tag>
            `;
        }

        function suggestionItemTemplate(tagData) {
            return `
                <div ${this.getAttributes(tagData)} class='tagify__dropdown__item ${tagData.class || ""}' tabindex="0" role="option">
                    ${tagData.avatar ? `<div class='tagify__dropdown__item__avatar-wrap'><img onerror="this.style.visibility='hidden'" src="${tagData.avatar}"></div>` : ''}
                    <strong>${tagData.name}</strong>
                    <span>${tagData.email}</span>
                </div>
            `;
        }

        function dropdownHeaderTemplate(suggestions) {
            return `
                <header class="${this.settings.classNames.dropdownItem} ${this.settings.classNames.dropdownItem}__addAll">
                    <strong>${this.value.length ? `Add Remaining` : 'Add All'}</strong>
                    <span>${suggestions.length} members</span>
                    <a class='remove-all-tags'>Remove all</a>
                </header>
            `;
        }

        function transformTagData(tagData) {
            const { name, email } = parseFullValue(tagData.name);
            tagData.name = name;
            tagData.email = email || tagData.email;
        }

        function validateTagData({ name, email }) {
            if (!email && name) {
                const parsed = parseFullValue(name);
                name = parsed.name;
                email = parsed.email;
            }
            if (!name) return "Missing name";
            if (!validateEmail(email)) return "Invalid email";
            return true;
        }

        function escapeHTML(s) {
            return typeof s === 'string' ? s
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/`|'/g, "&#039;")
                : s;
        }

        tagify.dropdown.createListHTML = (suggestionsList) => {
            const rolesOfUsers = suggestionsList.reduce((acc, suggestion) => {
                const role = suggestion.role || 'Not Assigned';
                acc[role] = acc[role] || [];
                acc[role].push(suggestion);
                return acc;
            }, {});

            const getUsersSuggestionsHTML = (roleUsers) => roleUsers.map((suggestion) => {
                suggestion.value = escapeHTML(tagify.dropdown.getMappedValue.call(tagify, suggestion));
                return tagify.settings.templates.dropdownItem.call(tagify, suggestion);
            }).join("");

            return Object.entries(rolesOfUsers).map(([role, roleUsers]) => {
                return `<div class="tagify__dropdown__itemsGroup" data-title="Role ${role}:">${getUsersSuggestionsHTML(roleUsers)}</div>`;
            }).join("");
        };

        tagify.on('input', debounce(async (e) => {
            const searchTerm = e.detail.value.trim();
            if (searchTerm.length < 2) return;

            tagify.settings.whitelist.length = 0;
            tagify.loading(true).dropdown.hide();

            try {
                const url = new URL("{{ route('admins.details') }}");
                url.searchParams.append("query", searchTerm);

                const response = await fetch(url.toString());
                const users = await response.json();

                if (!users || !Array.isArray(users.admin) || !Array.isArray(users.users)) {
                    console.error('Unexpected API response structure:', users);
                    return;
                }

                const formattedAdmins = users.admin.map(user => formatUser(user, 'admin'));
                const formattedUsers = users.users.map(user => formatUser(user, 'user'));

                tagify.settings.whitelist = [...formattedAdmins, ...formattedUsers];
                tagify.loading(false).dropdown.show(searchTerm);
            } catch (error) {
                console.error('Error fetching user data:', error);
                tagify.settings.whitelist = [];
                tagify.dropdown.show('Error fetching data. Try again later.');
            }
        }, 300));

        tagify.on('dropdown:select', onSelectSuggestion)
            .on('edit:start', onEditStart);

        function onSelectSuggestion(e) {
            if (e.detail.event.target.matches('.remove-all-tags')) {
                tagify.removeAllTags();
            } else if (e.detail.elm.classList.contains(`${tagify.settings.classNames.dropdownItem}__addAll`)) {
                tagify.dropdown.selectAll();
            }
        }

        function onEditStart({ detail: { tag, data } }) {
            tagify.setTagTextNode(tag, `${data.name} <${data.email}>`);
        }

        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function parseFullValue(value) {
            const parts = value.split(/<(.*?)>/g);
            return {
                name: parts[0].trim(),
                email: parts[1]?.replace(/<(.*?)>/g, '').trim()
            };
        }

        function formatUser(user, role) {
            return {
                value: user.id,
                name: `${user.first_name} ${user.last_name}`,
                avatar: user.profile_photo_path || 'https://via.placeholder.com/80',
                email: user.email,
                role
            };
        }

        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    </script>
    <script>
        /**
         * Show a confirmation dialog and delete the operation type if confirmed.
         * @param {number|string} id - The ID of the operation type to delete.
         */
        function deleteOperationType(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        // Replace with your actual delete endpoint
                        const url = `/admin/delete-operation-type/${id}`;
                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.status === 'success') {
                            Swal.fire('Deleted!', 'Operation type has been deleted.', 'success');
                            // Optionally refresh the table or remove the row
                            $('#tbl-operation-types').DataTable().ajax.reload();
                        } else {
                            Swal.fire('Error', data.message || 'Failed to delete operation type.', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Error', 'An error occurred while deleting.', 'error');
                    }
                }
            });
        }
    </script>
    <script>
        /**
         * JavaScript logic for dynamic dropdown population, data fetching, and UI interactions
         * in the company profile component.
         *
         * Features:
         * - Dynamically populates dropdowns for calendar years, operation types, operation categories,
         *   materials, chemicals, products, wastes, and operations based on company ID.
         * - Uses fetch API with CSRF protection to retrieve data from server endpoints.
         * - Handles dropdown population on focus/click to ensure up-to-date data.
         * - Provides utility functions for showing/hiding elements, scrolling, and displaying messages.
         * - Integrates DataTables for displaying operation types, categories, and operations log with
         *   custom rendering for actions (edit/delete).
         * - Includes logic for editing and deleting operation types, categories, and operation logs,
         *   with confirmation dialogs using SweetAlert.
         * - Formats dates and statuses for display in tables.
         * - Handles UI updates for product setup, operation log setup, and dynamic placeholders.
         *
         * Dependencies:
         * - jQuery
         * - DataTables
         * - SweetAlert2
         * - Bootstrap (for collapse and alert components)
         *
         * Assumptions:
         * - Server routes return JSON responses with expected data structures.
         * - Blade template provides a valid $company object with company_id.
         * - HTML structure includes elements with specific IDs and classes referenced in the script.
         */
        /**
         * Populates a dropdown element with options.
         * 
         * @param {HTMLElement} dropdown - The dropdown element to populate.
         * @param {Array} data - An array of objects representing the options. Each object should have a `value` and `label`.
         */
        function populateDropdown(dropdown, data) {
            // Clear existing options
            dropdown.innerHTML = "";

            // Add a default placeholder option
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select a year";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            dropdown.appendChild(defaultOption);
            // Add options from the data
            data.calendar_years.forEach(item => {
                const option = document.createElement("option");
                option.value = item.calendar_year_id; // Use the value from the data
                option.textContent = item.name; // Use the label from the data
                dropdown.appendChild(option);
            });
            console.log("Dropdown populated with options:", data);
        }

        // Generic function to fetch data from a URL
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


        // Fetch calendar years on page load
        document.querySelectorAll('.calendar-year').forEach(dropdown => {
            dropdown.addEventListener('focus', async function () {
                console.log('Selection detected');
                const companyId = "{{ json_encode($company->company_id) }}"; // Ensure valid JSON encoding on the server
                console.log("Company ID:", companyId);

                const url = `/admin/get-calendar-years/${companyId}`;
                console.log("Fetching data from URL:", url);

                try {
                    const data = await fetchFieldInput(url);
                    console.log("Fetched calendar years:", data);
                    populateDropdown(dropdown, data);
                } catch (error) {
                    console.error("Error fetching calendar years:", error);
                }
            });
        });
        // end fetch calendar years on page load
        // fetch operation type
        // populate the dropdown
        function populateOperationTypeDropdown(dropdown, data) {
            // Clear existing options
            dropdown.innerHTML = "";
            // Add a default placeholder option
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select operation type";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            dropdown.appendChild(defaultOption);
            // Add options from the data
            data.operation_types.forEach(item => {
                const option = document.createElement("option");
                option.value = item.operation_type_id; // Use the value from the data
                option.textContent = item.name; // Use the label from the data
                dropdown.appendChild(option);
            });
            console.log("Dropdown populated with options:", data);
        }
        // end populate dropdown
        // Fetch operation type on focus
        document.querySelectorAll('.operation-type').forEach(dropdown => {
            dropdown.addEventListener('focus', async () => {
                const companyId = "{{ json_encode($company->company_id) }}";
                const url = `/admin/get-operation-types/${companyId}`;
                console.log("Fetching operation types from URL:", url);

                try {
                    const data = await fetchFieldInput(url);
                    console.log("Fetched Operation Types:", data);
                    populateOperationTypeDropdown(dropdown, data);
                } catch (error) {
                    console.error("Error fetching operation types:", error);
                }
            });
        });
        // end fetch operation type

        // fetch operation category
        // populate the dropdown
        function populateOperationCategoryDropdown(dropdown, data) {
            // Clear existing options
            dropdown.innerHTML = "";
            // Add a default placeholder option
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select operation category";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            dropdown.appendChild(defaultOption);
            // Add options from the data
            data.operation_categories.forEach(item => {
                const option = document.createElement("option");
                option.value = item.operation_category_id; // Use the value from the data
                option.textContent = item.name; // Use the label from the data
                dropdown.appendChild(option);
            });
            console.log("Dropdown populated with options:", data);
        }
        // end populate dropdown
        // Fetch operation category on focus
        document.querySelectorAll('.operation-category').forEach(dropdown => {
            dropdown.addEventListener('focus', async () => {
                console.log('Operation category dropdown focused');
                const companyId = "{{ json_encode($company->company_id) }}"; // Ensure valid JSON encoding
                const url = `/admin/get-operation-category/${companyId}`;
                console.log("Fetching data from URL:", url);

                try {
                    const data = await fetchFieldInput(url);
                    console.log("Fetched Operation Categories:", data);
                    populateOperationCategoryDropdown(dropdown, data);
                } catch (error) {
                    console.error("Error fetching operation categories:", error);
                }
            });
        });
        // Fetch material and populate dropdown
        function populateMaterialDropdown(dropdown, data) {
            // Clear existing options
            dropdown.innerHTML = "";

            // Add a default placeholder option
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select material";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            dropdown.appendChild(defaultOption);

            // Add options from the data
            if (data.company_materials && Array.isArray(data.company_materials)) {
                data.company_materials.forEach(item => {
                    if (item.material != null) {
                        const option = document.createElement("option");
                        option.value = item.materialID || ""; // Use the value from the data
                        option.textContent = item.material.material || ""; // Use the label from the data
                        dropdown.appendChild(option);
                    }
                });
                console.log("Dropdown populated with materials:", data);
            } else {
                console.warn("Invalid materials data structure:", data);
            }
        }

        // Fetch material on page load
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".material-select").forEach(dropdown => {
                dropdown.addEventListener("focus", async () => {
                    const companyID = "{{ json_encode($company->company_id) }}";
                    const url = `/admin/get-materials/${companyID}`;

                    try {
                        const data = await fetchFieldInput(url);
                        populateMaterialDropdown(dropdown, data);
                    } catch (error) {
                        console.error("Error fetching materials:", error);
                        alert("Failed to load materials. Please try again.");
                    }
                });
            });
        });

        // Fetch chemical and populate dropdown
        function populateChemicalDropdown(dropdown, data) {
            // Clear existing options
            dropdown.innerHTML = "";

            // Add a default placeholder option
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select chemical";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            dropdown.appendChild(defaultOption);

            // Add options from the data
            if (data.company_chemicals && Array.isArray(data.company_chemicals)) {
                data.company_chemicals.forEach(item => {
                    if (item.chemical) {
                        const option = document.createElement("option");
                        option.value = item.chemicalID || ""; // Use the value from the data
                        option.textContent = item.chemical.name || ""; // Use the label from the data
                        dropdown.appendChild(option);
                    }
                });
                console.log("Dropdown populated with chemicals:", data);
            } else {
                console.warn("Invalid data structure for chemicals:", data);
            }
        }

        // Fetch chemicals on page load
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".chemical-select").forEach(element => {
                element.addEventListener("focus", async () => {
                    // Dynamically retrieve the company ID
                    const companyID = "{{ json_encode($company->company_id) }}"; // Ensure valid JSON encoding on the server
                    console.log("Company ID:", companyID);
                    const url = `/admin/get-chemicals/${companyID}`;
                    console.log("Fetching data from URL:", url);
                    try {
                        const data = await fetchFieldInput(url); // Await the data fetch
                        console.log("Fetched Chemicals:", data);
                        // Populate the dropdown with the fetched data
                        populateChemicalDropdown(element, data);
                    } catch (error) {
                        console.error("Error fetching chemicals:", error);
                        alert("Failed to load chemicals. Please try again.");
                    }
                });
            });
        });
        // fetch product
        // populate the dropdown
        function populateProductDropdown(dropdown, data) {
            // Clear existing options
            dropdown.innerHTML = "";
            // Add a default placeholder option
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select product";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            dropdown.appendChild(defaultOption);
            // Add options from the data
            data.products.forEach(item => {
                const option = document.createElement("option");
                option.value = item.product_id; // Use the value from the data
                option.textContent = item.name; // Use the label from the data
                dropdown.appendChild(option);
            });
            console.log("Dropdown populated with options:", data);
        }
        // end populate dropdown
        // Fetch product on page load
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.product-select').forEach(element => {
                element.addEventListener('focus', async () => {
                    const companyId = "{{ json_encode($company->company_id) }}"; // Ensure valid JSON encoding
                    const url = `/admin/get-product/${companyId}`;
                    console.log("Fetching data from URL:", url);

                    try {
                        const data = await fetchFieldInput(url);
                        console.log("Fetched Product:", data);

                        if (populateProductDropdown(element, data)) {
                            console.log("Product dropdown populated successfully.");
                        } else {
                            console.error("Failed to populate product dropdown.");
                        }
                    } catch (error) {
                        console.error("Error fetching product:", error);
                    }
                });
            });
        });

        async function populateWasteDropdown(ele, data) {
            // Clear existing options
            ele.innerHTML = "";

            // Add a default placeholder option
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select waste";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            ele.appendChild(defaultOption);

            // Add options from the data
            data.company_wastes.forEach(item => {
                const option = document.createElement("option");
                option.value = item.company_waste_id; // Use the value from the data
                option.textContent = item.waste_name; // Use the label from the data
                ele.appendChild(option);
            });

            return "true";
            console.log("Dropdown populated with options:", data);
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.waste-select').forEach(element => {
                element.addEventListener('focus', async function () {
                    console.log('Waste dropdown clicked:', element);

                    const companyId = "{{ json_encode($company->company_id) }}"; // Ensure valid JSON encoding
                    console.log("Company ID:", companyId);

                    const url = `/admin/get-waste/${companyId}`;
                    console.log("Fetching data from URL:", url);

                    try {
                        const data = await fetchFieldInput(url); // Assuming fetchFieldInput is defined
                        console.log("Fetched Waste:", data, element);

                        if (await populateWasteDropdown(element, data)) {
                            console.log("Waste dropdown populated successfully.");
                            element.dataset.populated = "true"; // Mark as populated
                        } else {
                            console.error("Failed to populate waste dropdown.");
                        }
                    } catch (error) {
                        console.error("Error fetching waste:", error);
                        alert("Failed to load waste data. Please try again.");
                    }
                });
            });
        });

        async function populateOperationDropdown(dropdown, data) {
            // Clear existing options
            dropdown.innerHTML = "";
            // Add a default placeholder option
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select operation";
            defaultOption.disabled = true;
            defaultOption.selected = true;
            dropdown.appendChild(defaultOption);
            // Add options from the data
            data.company_operations.forEach(item => {
                const option = document.createElement("option");
                option.value = item.operation_id; // Use the value from the data
                option.textContent = item.operation_name; // Use the label from the data
                dropdown.appendChild(option);
            });
            console.log("Dropdown populated with options:", data);
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.operation-select').forEach(dropdown => {
                let isLoading = false;

                dropdown.addEventListener('click', async function () {
                    if (isLoading || dropdown.dataset.populated === "true") return;

                    console.log('Operation dropdown clicked:', dropdown);
                    const companyId = "{{ json_encode($company->company_id) }}";
                    const url = `/admin/get-operations/${companyId}`;
                    console.log("Fetching data from URL:", url);

                    isLoading = true;
                    try {
                        const data = await fetchFieldInput(url);
                        console.log("Fetched Operations:", data);
                        populateOperationDropdown(dropdown, data);
                        dropdown.dataset.populated = "true";
                    } catch (error) {
                        console.error("Error fetching operations:", error);
                        alert("Failed to load operation data. Please try again.");
                    } finally {
                        isLoading = false;
                    }
                });
            });
        });

        // triger product setup
        let new_product_setup_card = document.getElementById('new_product_setup_card');
        document.querySelector('#btn-setup-products').addEventListener('click', () => {
            new_product_setup_card.classList.remove('d-none');
            new_product_setup_card.scrollIntoView({ behavior: 'smooth' });
        });

        // Dynamically update placeholder based on currency selection
        document.getElementById('currency').addEventListener('change', function () {
            const currency = this.value;
            const priceInput = document.getElementById('product_price');
            priceInput.placeholder = `Enter product price in ${currency}`;
        });

        // Function to populate the product table
        function populateProductTable() {
            const tbody = document.querySelector("#tbl-products tbody");
            tbody.innerHTML = ""; // Clear existing content

            const companyId = "{{ json_encode($company->company_id) }}"; // Ensure valid JSON encoding
            const url = `/admin/get-product/${companyId}`;

            fetchFieldInput(url)
                .then(data => {
                    if (data.products && data.products.length > 0) {
                        data.products.forEach(product => {
                            tbody.innerHTML += `
                                <tr>
                                    <td>${product.name}</td>
                                    <td>${product.product_category.name}</td>
                                    <td>${product.currency} ${product.price}</td>
                                    <td>${product.quantity_per_unit}</td>
                                    <td>${product.unit}</td>
                                    <td class="text-end">
                                        <button class="btn btn-primary btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>`;
                        });
                    } else {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center">No products available</td>
                            </tr>`;
                    }
                })
                .catch(error => console.error("Error fetching products:", error));
        }

        // Attach event listener for when the accordion section is shown
        document.getElementById('productListCollapse').addEventListener('shown.bs.collapse', populateProductTable);

        // show, hide, scroll to element and display message functions
        const showElement = (element) => element.classList.remove('d-none');
        const hideElement = (element) => element.classList.add('d-none');
        const scrollToElement = (element) => element.scrollIntoView({ behavior: 'smooth' });
        function displayMessage(type, message) {
            const messageContainer = document.getElementById('message-container');
            messageContainer.innerHTML = `<div class="alert alert-${type}" role="alert">${message}</div>`;
            setTimeout(() => (messageContainer.innerHTML = ''), 3000);
        }
        // end show, hide, scroll to element and display message functions

        // operation type and operation category
        // Initialize DataTable for operation type
        let table = $('#tbl-operation-types').DataTable({
            paging: true,
            searching: true,
            ordering: false,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [3] } // Disable sorting on the "Action" column
            ],
            data: [], // Start with an empty data array
            columns: [
                { data: 'name' },
                { data: 'description' },
                { data: 'sequenceOrder' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="editOperationType(${row.operation_type_id}, '${row.name}', '${row.description ?? ""}', '${row.sequenceOrder ?? "0"}')">
                                        <i class="las la-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="confirmTypeDeletion(${row.operation_type_id}, '${row.name}')">
                                        <i class="las la-trash-alt"></i> Delete
                                    </button>
                                </div>`;
                    }
                }
            ]
        });
        // Function to create an object for each operation type
        function OperationTypeObject(operation_type_id, name, description, sequenceOrder) {
            this.operation_type_id = operation_type_id;
            this.name = name;
            this.description = description;
            this.sequenceOrder = sequenceOrder;
        }
        // Fetch data and populate table when the accordion is expanded
        document.getElementById('operationTypeCollapse').addEventListener('shown.bs.collapse', async () => {
            const companyId = "{{ json_encode($company->company_id) }}";
            const url = `/admin/get-operation-types/${companyId}`;
            const spinner = document.getElementById('loading-spinner');

            try {
                showElement(spinner);
                const data = await fetchFieldInput(url);

                if (data.status === "success") {
                    const operationTypes = data.operation_types.map(type => 
                        new OperationTypeObject(type.operation_type_id, type.name, type.description, type.sequence_order)
                    );

                    table.clear();
                    table.rows.add(operationTypes);
                    table.draw();
                }
            } catch (error) {
                console.error("Error fetching operation types:", error);
                displayMessage('danger', 'An error occurred while fetching data.');
            } finally {
                hideElement(spinner);
            }
        });
        // end fetch data and populate table when the accordion is expanded
        // Edit operation type
        function editOperationType(operation_type_id, name, description, sequenceOrder) {
            const editCard = document.getElementById('edit-operation-type-card');
            showElement(editCard);
            scrollToElement(editCard);
            const form = document.getElementById('edit_operation_type_form');
            form.querySelector('[name="operation_type_id"]').value = operation_type_id;
            form.querySelector('[name="operation_type_name"]').value = name;
            form.querySelector('[name="operation_type_description"]').value = description;
            form.querySelector('[name="operation_type_sequence_order"]').value = sequenceOrder ?? "0";
        }
        // Confirm deletion
        async function confirmTypeDeletion(operation_type_id, name) {
            const result = await Swal.fire({
                title: `Are you sure you want to delete "${name}"?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/admin/operation-type/${operation_type_id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });

                    if (response.ok) {
                        Swal.fire('Deleted!', 'The operation type has been deleted.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error!', 'There was an issue deleting the operation type.', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                }
            }
        }

        // Trigger operation setup card
        const setupCard = document.getElementById('operation-type-card');
        document.querySelector('#setup-operation-type').addEventListener('click', () => {
            showElement(setupCard);
            scrollToElement(setupCard);
        });

        // Initialize DataTable for operation categories
        const categoriesTable = $('#tbl-operation-categories').DataTable({
            paging: true,
            searching: true,
            ordering: false,
            responsive: true,
            columnDefs: [{ orderable: false, targets: [2] }],
            data: [],
            columns: [
                { data: 'name' },
                { data: 'description' },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-outline-primary btn-sm" 
                                    onclick="editOperationCategory(${row.operation_category_id}, '${row.name}', '${row.description}')">
                                <i class="las la-edit"></i> Edit
                            </button>
                            <button class="btn btn-outline-danger btn-sm" 
                                    onclick="confirmCategoryDeletion(${row.operation_category_id}, '${row.name}')">
                                <i class="las la-trash-alt"></i> Delete
                            </button>
                        </div>`
                }
            ]
        });

        // Function to create an object for each operation category
        function OperationCategoryObject(operation_category_id, name, description) {
            this.operation_category_id = operation_category_id;
            this.name = name;
            this.description = description;
        }

        // Fetch data and populate table when the accordion is expanded
        document.getElementById('operationCategoryCollapse').addEventListener('shown.bs.collapse', async () => {
            console.log('Fetching operation categories...');
            const companyId = "{{ json_encode($company->company_id) }}";
            const url = `/admin/get-operation-category/${companyId}`;
            const categoriesSpinner = document.getElementById('loading-spinner');

            try {
                showElement(categoriesSpinner);
                const data = await fetchFieldInput(url);

                if (data.status === "success") {
                    const dataArray = data.operation_categories.map(
                        category => new OperationCategoryObject(category.operation_category_id, category.name, category.description)
                    );

                    // Clear and add rows without destroying the table
                    categoriesTable.clear();
                    categoriesTable.rows.add(dataArray);
                    categoriesTable.draw();
                }
            } catch (error) {
                console.error("Error fetching operation categories:", error);
                displayMessage('danger', 'An error occurred while fetching data.');
            } finally {
                hideElement(categoriesSpinner);
            }
        });

        // Edit operation category
        function editOperationCategory(operation_category_id, name, description) {
            const editCategoryCard = document.getElementById('edit-operation-category-card');
            showElement(editCategoryCard);
            scrollToElement(editCategoryCard);

            const form = document.getElementById('edit_operation_category_form');
            form.querySelector('[name="operation_category_id"]').value = operation_category_id;
            form.querySelector('[name="operation_category_name"]').value = name;
            form.querySelector('[name="operation_category_description"]').value = description;
        }

        // Confirm deletion
        function confirmCategoryDeletion(operation_category_id, name) {
            Swal.fire({
                title: `Are you sure you want to delete ${name}?`,
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/operation-category/${operation_category_id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                Swal.fire(
                                    'Deleted!',
                                    'The operation category has been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was an issue deleting the operation category.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error!',
                                'An unexpected error occurred.',
                                'error'
                            );
                        });
                }
            });
        }


        // Trigger operation category setup card
        let setupCategoryCard = document.getElementById('operation-category-card');
        document.querySelector('#setup-operation-category').addEventListener('click', () => {
            showElement(setupCategoryCard);
            scrollToElement(setupCategoryCard);
        });

        // Initialize DataTable for operations log
        let operationsLogTable = $('#tbl-operations-log').DataTable({
            paging: true,
            searching: true,
            ordering: false,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [9] } // Disable sorting on the "Action" column
            ],
            data: [], // Start with an empty data array
            columns: [
                { data: 'operation_name' },
                { data: 'operation_code' },
                { data: 'operation_type' },
                { data: 'operation_category' },
                { data: 'operation_unit_cost' },
                { data: 'calendar_year' },
                { data: 'start_date' },
                { data: 'end_date' },
                { data: 'operation_status' },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `
                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-outline-info btn-sm" onclick="viewOperationLog()">
                                <i class="las la-eye"></i> View
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="editOperationLog(${row.operation_id}, '${row.operation_name}', '${row.operation_code}', '${row.operation_type}', '${row.operation_category}', '${row.operation_unit}', '${row.expected_waste_per_operation}', '${row.expected_water_usage_per_operation}', '${row.expected_unit_produced_for_goods}', '${row.calendar_year}', '${row.start_date}', '${row.end_date}', '${row.status}')">
                                <i class="las la-edit"></i> Edit
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="confirmOperationLogDeletion(${row.operation_id}, '${row.operation_name}')">
                                <i class="las la-trash-alt"></i> Delete
                            </button>
                        </div>`;
                    }
                }
            ]
        });

        // Define a class for the operation log objects
        class OperationLog {
            constructor(
                operation_id,
                operation_name,
                operation_code,
                operation_type,
                operation_category,
                operation_unit_cost,
                calendar_year,
                start_date,
                end_date,
                operation_status
            ) {
                this.operation_id = operation_id;
                this.operation_name = operation_name;
                this.operation_code = operation_code;
                this.operation_type = operation_type;
                this.operation_category = operation_category;
                this.operation_unit_cost = operation_unit_cost;
                this.calendar_year = calendar_year;
                this.start_date = formatDate(start_date);
                this.end_date = formatDate(end_date);
                this.operation_status = operation_status;
            }
        }

        function displayMessage(type, message, timeout = 5000) {
            const alertContainer = document.getElementById('alert-container');
            const alertBox = document.createElement('div');

            alertBox.className = `alert alert-${type} alert-dismissible fade show`;
            alertBox.role = "alert";
            alertBox.innerHTML = `
                    <strong>${type.charAt(0).toUpperCase() + type.slice(1)}:</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

            alertContainer.appendChild(alertBox);

            if (timeout) {
                setTimeout(() => {
                    alertBox.classList.remove('show');
                    alertBox.classList.add('d-none');
                }, timeout);
            }
        }

        function formatOperationStatus(status) {
            const statusMap = {
                active: '<span class="badge bg-success">Active</span>',
                inactive: '<span class="badge bg-secondary">Inactive</span>',
                pending: '<span class="badge bg-warning text-dark">Pending</span>',
                cancelled: '<span class="badge bg-danger">Cancelled</span>',
            };
            return statusMap[status?.toLowerCase()] ?? '<span class="badge bg-dark">Unknown</span>';
        }


        // Utility function to fetch data
        async function fetchOperationsLog(url) {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        }

        // Utility function to format waste details
        function formatWasteDetails(wasteData) {
            if (!Array.isArray(wasteData) || wasteData.length === 0) return "No waste generated";
            return wasteData
                .map(waste => `${waste.name || "Unknown"}: ${waste.quantity || 0}`)
                .join(", ");
        }

        // Utility function to format dates in "03 April, 2025" format
        function formatDate(dateString) {
            if (!dateString) return "N/A";
            const date = new Date(dateString);
            return new Intl.DateTimeFormat("en-US", { day: "2-digit", month: "long", year: "numeric" }).format(date);
        }

        // Event listener for accordion expansion
        document.getElementById('operationsLogCollapse').addEventListener('shown.bs.collapse', async () => {
            console.log('Fetching operations log...');
            const companyId = "{{ json_encode($company->company_id) }}";
            const url = `/admin/get-operations-log/${companyId}`;
            const spinner = document.getElementById('loading-spinner');

            showElement(spinner);

            try {
                const data = await fetchOperationsLog(url);
                if (data.status === "success") {
                    const operations = data.operations.map(log => new OperationLog(
                        log.company_operation_id || 0,
                        log.operation_name || "N/A",
                        log.operation_code || "N/A",
                        log.operation_type?.name || "Unknown Type",
                        log.operation_category?.name || "Unknown Category",
                        log.operation_unit_cost || 0,
                        log.calendar_year?.name || "N/A",
                        log.start_date || null,
                        log.end_date || null,
                        formatOperationStatus(log.operation_status)
                    ));

                    operationsLogTable.clear();
                    operationsLogTable.rows.add(operations);
                    operationsLogTable.draw();
                } else {
                    displayMessage('warning', 'No operations found.');
                }
            } catch (error) {
                console.error("Error fetching operations log:", error);
                displayMessage('danger', 'An error occurred while fetching operations log.');
            } finally {
                hideElement(spinner);
            }
        });

        // Setup operation log
        const operationLogCard = document.getElementById('operation-log-card');
        document.querySelector('#btn-setup-operation-log').addEventListener('click', () => {
            showElement(operationLogCard);
            scrollToElement(operationLogCard);
        });

        // Hide operation log card on page load
        document.addEventListener('DOMContentLoaded', () => hideElement(operationLogCard));

        // View operation log
        function viewOperationLog() {
            showElement(operationLogCard);
            scrollToElement(operationLogCard);
        }

        // Edit operation log
        function editOperationLog(
            operationId, operationName, operationCode, operationType, operationCategory,
            operationUnit, expectedWaste, expectedWaterUsage, expectedUnitsProduced,
            calendarYear, startDate, endDate, status
        ) {
            showElement(operationLogCard);
            scrollToElement(operationLogCard);

            const form = document.getElementById('operations-form-update');
            form.querySelector('[name="operation_id"]').value = operationId;
            form.querySelector('[name="operation_name"]').value = operationName;
            form.querySelector('[name="operation_code"]').value = operationCode;
            form.querySelector('[name="operation_type"]').value = operationType;
            form.querySelector('[name="operation_category"]').value = operationCategory;
            form.querySelector('[name="operation_unit"]').value = operationUnit;
            form.querySelector('[name="operation_unit_price"]').value = expectedWaste;
            form.querySelector('[name="operation_unit_cost"]').value = expectedWaterUsage;
            form.querySelector('[name="operation_unit_time"]').value = expectedUnitsProduced;
            form.querySelector('[name="calendar_year"]').value = calendarYear;
            form.querySelector('[name="start_date"]').value = startDate;
            form.querySelector('[name="end_date"]').value = endDate;
            form.querySelector('[name="status"]').value = status;
        }

        // Confirm deletion
        function confirmOperationLogDeletion(operationId, operationName) {
            Swal.fire({
                title: `Are you sure you want to delete "${operationName}"?`,
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(`Deleted operation log ID: ${operationId}`);
                    displayMessage('success', 'Operation log deleted successfully.');
                    // Add your delete logic here
                }
            });
        }
    </script>
    <script>
        /**
         * Calculates the total operation unit cost for an operation log.
         * - Sums up all cost fields (labour, overhead, maintenance, etc.)
         * - Adds total material and chemical costs (based on quantity/unit and unit cost)
         * - Divides total cost by total output quantity to get unit cost
         * - Updates the relevant fields in the form
         */
        function calculateOperationUnitCost() {
            console.log("Calculating operation unit cost...");

            // Fetch cost fields
            const costs = {
                labour: parseFloat(document.getElementById('labour_cost').value) || 0,
                overhead: parseFloat(document.getElementById('overhead_cost').value) || 0,
                maintenance: parseFloat(document.getElementById('maintenance_cost').value) || 0,
                depreciation: parseFloat(document.getElementById('depreciation_cost').value) || 0,
                supervision: parseFloat(document.getElementById('supervision_cost').value) || 0,
                variable: parseFloat(document.getElementById('variable_cost').value) || 0,
                fixed: parseFloat(document.getElementById('fixed_cost').value) || 0,
            };

            // Calculate material costs
            const totalMaterialCost = Array.from(document.querySelectorAll('.material-quantity-used-container-operation-log .row'))
                .reduce((total, row, i) => {
                    const quantity = parseFloat(row.querySelector(`[name="material_used[${i}][quantity]"]`).value) || 0;
                    const unitQuantity = parseFloat(row.querySelector(`[name="material_used[${i}][unit_quantity]"]`).value) || 1;
                    const unitCost = parseFloat(row.querySelector(`[name="material_used[${i}][unit_cost]"]`).value) || 0;
                    return total + (quantity / unitQuantity) * unitCost;
                }, 0);

            // Calculate chemical costs
            const totalChemicalCost = Array.from(document.querySelectorAll('.chemical-quantity-container-operation-log .row'))
                .reduce((total, row, i) => {
                    const quantity = parseFloat(row.querySelector(`[name="chemical_used[${i}][quantity]"]`).value) || 0;
                    const unitQuantity = parseFloat(row.querySelector(`[name="chemical_used[${i}][unit_quantity]"]`).value) || 1;
                    const unitCost = parseFloat(row.querySelector(`[name="chemical_used[${i}][unit_cost]"]`).value) || 0;
                    return total + (quantity / unitQuantity) * unitCost;
                }, 0);

            // Calculate total cost
            const totalCost = Object.values(costs).reduce((sum, cost) => sum + cost, 0) + totalMaterialCost + totalChemicalCost;
            document.getElementById('total_operation_cost').value = totalCost.toFixed(2);

            // Calculate total output quantity
            const totalOutputQuantity = Array.from(document.querySelectorAll('.operation-log-product-quantity-container .row'))
                .reduce((total, row, i) => {
                    return total + (parseFloat(row.querySelector(`[name="product_produced[${i}][quantity]"]`).value) || 0);
                }, 0);

            // Calculate and update operation unit cost
            if (totalOutputQuantity > 0) {
                const operationUnitCost = totalCost / totalOutputQuantity;
                document.getElementById('operation_unit_cost').value = operationUnitCost.toFixed(2);
            } else {
                document.getElementById('operation_unit_cost').value = "N/A";
            }
        }

        /**
         * Attaches input listeners to all relevant cost and quantity fields
         * to trigger recalculation when any value changes.
         */
        function attachListeners() {
            const fields = document.querySelectorAll(
                '#labour_cost, #overhead_cost, #maintenance_cost, #depreciation_cost, #supervision_cost, #variable_cost, #fixed_cost, ' +
                '[name^="material_used"][name$="[quantity]"], [name^="material_used"][name$="[unit_cost]"], ' +
                '[name^="chemical_used"][name$="[quantity]"], [name^="chemical_used"][name$="[unit_cost]"], ' +
                '[name="expected_quantity_produced_for_goods[]"]'
            );

            fields.forEach(field => {
                field.addEventListener('input', calculateOperationUnitCost);
            });
        }

        // Initialize listeners when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            attachListeners();
            calculateOperationUnitCost(); // Run calculation on initial load in case of preset values
        });

    </script>
    <script>
        // =========================
        // Company Profile JS Logic
        // =========================

        /**
         * =========================
         * Company Profile JS Logic
         * =========================
         * This section contains event handlers, AJAX logic, and UI helpers for managing
         * company profile features such as policies, objectives, benefits, materials,
         * contacts, water/chemical/material inventory, and dynamic UI interactions.
         * All logic is scoped to the company profile component.
         */
        // --- Company Policies ---
        /**
         * Handles toggling of company policy assignment.
         * Sends AJAX request to add or remove a policy for the company.
         */
        // company policies
        async function ChangePolicy(ele, company, policy) {
            console.log(ele, company, policy);

            const uri = ele.checked 
                ? "{{ route('admin.add-company-policy') }}" 
                : "{{ route('admin.remove-company-policy') }}";

            if (!ele.checked && !confirm("Do you want to remove this policy?")) return;

            const formData = new FormData();
            formData.append('company', company);
            formData.append('policy', policy);

            try {
                const response = await fetch(uri, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    credentials: 'same-origin',
                    body: formData
                });

                const data = await response.json();
                console.log("--policy", data);

                if (data.status === 'success') {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                    }).showToast();
                } else if (data.status === 'error') {
                    Object.values(data.errors).forEach(error => {
                        Toastify({
                            text: error,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #ff0000, #ff1745)",
                            },
                        }).showToast();
                    });
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }
        // end company policies
        // Company Objectives
        async function ChangeObjective(element, company, objective) {
            console.log(element, company, objective);

            const uri = element.checked 
                ? "{{ route('admin.add-company-objective') }}" 
                : "{{ route('admin.remove-company-objective') }}";

            if (!element.checked && !confirm("Do you want to remove this objective?")) return;

            const formData = new FormData();
            formData.append('company', company);
            formData.append('objective', objective);

            try {
                const response = await fetch(uri, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    credentials: 'same-origin',
                    body: formData
                });

                const data = await response.json();
                console.log("--objective", data);

                if (data.status === 'success') {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                    }).showToast();
                } else if (data.status === 'error') {
                    Object.values(data.errors).forEach(error => {
                        Toastify({
                            text: error,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #ff0000, #ff1745)",
                            },
                        }).showToast();
                    });
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }
        // end company objectives

        // Area of Utmost Benefit
        async function ChangeUtmostBenefit(ele, company, benefit) {
            console.log(ele, company, benefit);

            const uri = ele.checked 
                ? "{{ route('admin.add-recp-project') }}" 
                : "{{ route('admin.remove-recp-project') }}";

            if (!ele.checked && !confirm("Do you want to remove this area of benefit?")) return;

            const formData = new FormData();
            formData.append('company', company);
            formData.append('areas_of_company_benefit', benefit);

            try {
                const response = await fetch(uri, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    credentials: 'same-origin',
                    body: formData
                });

                const data = await response.json();
                console.log("--benefit", data);

                if (data.status === 'success') {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                    }).showToast();
                } else if (data.status === 'error') {
                    Object.values(data.errors).forEach(error => {
                        Toastify({
                            text: error,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #ff0000, #ff1745)",
                            },
                        }).showToast();
                    });
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }
        // end area of utmost benefit

        // Human & Environmental and Health Benefit
        async function ChangeEnvironmentalBenefit(ele, company, benefit) {
            console.log(ele, company, benefit);

            const uri = ele.checked 
                ? "{{ route('admin.add-recp-environmental') }}" 
                : "{{ route('admin.remove-recp-environmental') }}";

            if (!ele.checked && !confirm("Do you want to remove this benefit?")) return;

            const formData = new FormData();
            formData.append('company', company);
            formData.append('enviromental_benefit_title', benefit);

            try {
                const response = await fetch(uri, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    credentials: 'same-origin',
                    body: formData
                });

                const data = await response.json();
                console.log("--benefit", data);

                if (data.status === 'success') {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                    }).showToast();
                } else if (data.status === 'error') {
                    Object.values(data.errors).forEach(error => {
                        Toastify({
                            text: error,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #ff0000, #ff1745)",
                            },
                        }).showToast();
                    });
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }
        // end human & environmental and health benefit

        // Good House Keeping
        async function ChangeGoodHouseKeeping(ele, company, houseKeeping) {
            console.log(ele, company, houseKeeping);

            const uri = ele.checked 
                ? "{{ route('admin.add-house-keeping') }}" 
                : "{{ route('admin.remove-house-keeping') }}";

            if (!ele.checked && !confirm("Do you want to remove House keeping?")) return;

            const formData = new FormData();
            formData.append('company', company);
            formData.append('house_keeping_title', houseKeeping);

            try {
                const response = await fetch(uri, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    credentials: 'same-origin',
                    body: formData
                });

                const data = await response.json();
                console.log("--house keeping", data);

                if (data.status === 'success') {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                    }).showToast();
                } else if (data.status === 'error') {
                    Object.values(data.errors).forEach(error => {
                        Toastify({
                            text: error,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #ff0000, #ff1745)",
                            },
                        }).showToast();
                    });
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }
        // end good house keeping

        // Waste Reduction Measures
        async function ChangeWasteReductionMeasures(ele, company, measure) {
            console.log(ele, company, measure);

            const uri = ele.checked 
                ? "{{ route('admin.add-waste-reduction-measure') }}" 
                : "{{ route('admin.remove-waste-reduction-measure') }}";

            if (!ele.checked && !confirm("Do you want to remove this waste reduction measure?")) return;

            const formData = new FormData();
            formData.append('company', company);
            formData.append('waste_reduction_measure', measure);

            try {
                const response = await fetch(uri, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    credentials: 'same-origin',
                    body: formData
                });

                const data = await response.json();
                console.log("--Waste Reduction Measure", data);

                if (data.status === 'success') {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                    }).showToast();
                } else if (data.status === 'error') {
                    Object.values(data.errors).forEach(error => {
                        Toastify({
                            text: error,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #ff0000, #ff1745)",
                            },
                        }).showToast();
                    });
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }
        // end waste reduction measures

        // Waste disposal method
        async function ChangeWasteDisposalMethod(ele, company, disposal_method) {
            console.log(ele, company, disposal_method);

            const uri = ele.checked 
                ? "{{ route('admin.add-waste-disposal-method') }}" 
                : "{{ route('admin.remove-waste-disposal-method') }}";

            if (!ele.checked && !confirm("Do you want to remove waste management method?")) return;

            const formData = new FormData();
            formData.append('company', company);
            formData.append('waste_management_method', disposal_method);

            try {
                const response = await fetch(uri, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    credentials: 'same-origin',
                    body: formData
                });

                const data = await response.json();
                console.log("--Waste Disposal Method", data);

                if (data.status === 'success') {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                    }).showToast();
                } else if (data.status === 'error') {
                    Object.values(data.errors).forEach(error => {
                        Toastify({
                            text: error,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #ff0000, #ff1745)",
                            },
                        }).showToast();
                    });
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }
        // end waste disposal method

        // Product recovery measures
        async function ChangeProductRecoveryMeasure(ele, company, measure) {
            console.log(ele, company, measure);

            const uri = ele.checked 
                ? "{{ route('admin.add-product-recovery-measure') }}" 
                : "{{ route('admin.remove-product-recovery-measure') }}";

            if (!ele.checked && !confirm("Do you want to remove product recovery measure?")) return;

            const formData = new FormData();
            formData.append('company', company);
            formData.append('product_recovery_measure', measure);

            try {
                const response = await fetch(uri, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    credentials: 'same-origin',
                    body: formData
                });

                const data = await response.json();
                console.log("--product recovery measure", data);

                if (data.status === 'success') {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)",
                        },
                    }).showToast();
                } else if (data.status === 'error') {
                    Object.values(data.errors).forEach(error => {
                        Toastify({
                            text: error,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            stopOnFocus: true,
                            style: {
                                background: "linear-gradient(to right, #ff0000, #ff1745)",
                            },
                        }).showToast();
                    });
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }
        // end product recovery measures

        // Show toast message
        function showToast(message, type) {
            const colors = {
                success: "linear-gradient(to right, #00b09b, #96c93d)",
                error: "linear-gradient(to right, #ff0000, #ff1745)"
            };

            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: colors[type] || colors.error,
                },
            }).showToast();
        }
        // end show toast message

        // Key Areas for Improvement
        document.querySelector('.add_more_key_areas').addEventListener('click', async () => {
            const keyAreaInput = document.querySelector('#key_area_for_improvent');
            const keyAreaValue = keyAreaInput.value.trim();

            if (!keyAreaValue) {
                Toastify({
                    text: "Key area cannot be empty.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #ff0000, #ff1745)",
                    },
                }).showToast();
                return;
            }

            const url = `{{ route('admin.add-improvement-key-area') }}`;
            const formData = new FormData();
            formData.append('company', '{{$company->company_id}}');
            formData.append('key_area', keyAreaValue);

            await fetch_cycle('--Add Key Area', url, 'POST', formData).then(result => {
                if (result.key_areas) {
                    const container = document.querySelector('.key-areas-container');
                    container.innerHTML = result.key_areas.map(element => `
                        <div class="row g-2 my-1">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="${element.area_title}" 
                                        placeholder="Key area for improving performance in your industry" 
                                        onblur='update_key_area("{{$company->company_id}}", ${element.improvementAreaID}, this)'>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-danger" 
                                    onclick='remove_key_area(this, ${element.improvementAreaID})' type="button">
                                    <i class="iconoir-trash"></i>
                                </button>
                            </div>
                        </div>
                    `).join('');
                }
            });
        });
        // ***** End Add key area ******//

        // ***** Update key area ******//
        async function updateKeyArea(company, keyAreaId, element) {
            const keyAreaValue = element.value.trim();

            if (!keyAreaValue) {
                showToast("Key area cannot be empty.", "error");
                return;
            }

            const url = `{{ route('admin.update-improvement-key-area') }}`;
            const formData = new FormData();
            formData.append('company', company);
            formData.append('key_area_id', keyAreaId);
            formData.append('key_area', keyAreaValue);

            await fetch_cycle('--Update Key Area', url, 'POST', formData)
                .then(result => console.log(result))
                .catch(error => console.error('Error updating key area:', error));
        }
        // ***** End Update key area ******//
        function removeKeyArea(element, keyAreaId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this area of performance improvement?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const parentElement = element.closest('.row');
                    const url = `{{ route('admin.remove-improvement-key-area') }}`;
                    const formData = new FormData();
                    formData.append('key_area_id', keyAreaId);

                    try {
                        const response = await fetch_cycle('--Remove Key Area', url, 'POST', formData);
                        if (response.status === 'success') {
                            parentElement.remove();
                            Swal.fire(
                                'Deleted!',
                                'The area of performance improvement has been deleted.',
                                'success'
                            );
                        } else {
                            console.error('Failed to remove key area:', response);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }
            });
        }
        // end Key Areas for Improvement

        // key Product Innovation
        // ***** Add ******//
        document.querySelector('.add_more_key_innovation').addEventListener('click', async () => {
            const keyProductInnovationInput = document.querySelector('#key_product_innovation');
            const keyProductInnovationValue = keyProductInnovationInput.value.trim();

            if (!keyProductInnovationValue) {
                Toastify({
                    text: "Key Product innovation cannot be empty.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #ff0000, #ff1745)",
                    },
                }).showToast();
                return;
            }

            const url = `{{ route('admin.add-product-innovation') }}`;
            const formData = new FormData();
            formData.append('company', '{{$company->company_id}}');
            formData.append('key_product_innovation', keyProductInnovationValue);

            try {
                const result = await fetch_cycle('--Add Key Product Innovation', url, 'POST', formData);
                if (result.product_innovation) {
                    const container = document.querySelector('.product-innovation-container');
                    container.innerHTML = result.product_innovation.map(element => `
                        <div class="row g-2 my-1">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="${element.innovation_area_title}" 
                                        placeholder="Key innovation that enhance your product's environmental compatibility" 
                                        onblur='update_product_innovation("{{$company->company_id}}", ${element.innovationAreaID}, this)'>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-danger" onclick='remove_product_innovation(this, ${element.innovationAreaID})' type="button">
                                    <i class="iconoir-trash"></i>
                                </button>
                            </div>
                        </div>`).join('');
                }
            } catch (error) {
                console.error("Error adding product innovation:", error);
            }
        });
        // ***** End Add key Product Innovation ******//
        // ***** Update Key Product Innovation ******//
        function updateProductInnovation(company, productInnovationId, element) {
            const innovationValue = element.value.trim();
            if (!innovationValue) {
                showToast("Key product innovation cannot be empty.", "error");
                return;
            }

            const url = `{{ route('admin.update-product-innovation') }}`;
            const formData = new FormData();
            formData.append('company', company);
            formData.append('key_product_innovation_id', productInnovationId);
            formData.append('key_product_innovation', innovationValue);

            fetchCycle('--Update Key Product Innovation', url, 'POST', formData)
                .then(result => console.log(result))
                .catch(error => console.error('Error updating product innovation:', error));
        }
        // ***** End Update Key Product Innovation ******//
        // ***** Remove Key Product Innovation ******//
        function removeProductInnovation(element, productInnovationId) {
            if (!confirm("Do you want to delete this area of performance improvement?")) return;

            const parent = element.closest('.row');
            const url = `{{ route('admin.remove-product-innovation') }}`;
            const formData = new FormData();
            formData.append('key_product_innovation_id', productInnovationId);

            fetchCycle('--Remove Product Innovation', url, 'POST', formData)
                .then(result => {
                    if (result.status === 'success') {
                        parent.remove();
                    }
                })
                .catch(error => console.error('Error removing product innovation:', error));
        }
        // End Product Innovation
        // key Hazarduous Material
        // ***** Add Hazardous Material ******//
        document.querySelector('.add_more_hazardous_material').addEventListener('click', () => {
            const hazardousMaterialInput = document.querySelector('#hazarduous_material');
            const hazardousMaterialValue = hazardousMaterialInput.value.trim();

            if (!hazardousMaterialValue) {
                Toastify({
                    text: "Hazardous material field cannot be empty.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #ff0000, #ff1745)",
                    },
                }).showToast();
                return;
            }

            const url = `{{ route('admin.add-hazarduous-material') }}`;
            const formData = new FormData();
            formData.append('company', '{{$company->company_id}}');
            formData.append('hazarduous_material', hazardousMaterialValue);

            fetch_cycle('--Add Hazardous Material', url, 'POST', formData).then(result => {
                if (result.hazarduous_materials) {
                    const container = document.querySelector('.hazarduous-material-container');
                    container.innerHTML = result.hazarduous_materials.map(element => `
                        <div class="row g-2 my-1">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="${element.material_title}" 
                                        placeholder="Key innovation that enhances your product's environmental compatibility" 
                                        onblur='update_hazarduous_material("{{$company->company_id}}", ${element.hazarduousMaterialID}, this)'>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-danger" onclick='remove_harzardous_material(this, ${element.hazarduousMaterialID})' type="button">
                                    <i class="iconoir-trash"></i>
                                </button>
                            </div>
                        </div>`).join('');
                }
            }).catch(error => {
                console.error("Error adding hazardous material:", error);
            });
        });
        // ***** End Add Hazardous Material ******//
        // ***** Update Hazardous Material ******//
        function updateHazardousMaterial(company, hazardousMaterialId, element) {
            const hazardousMaterialValue = element.value.trim();
            if (!hazardousMaterialValue) {
                showToast("Hazardous material field cannot be empty.", "error");
                return;
            }

            const url = `{{ route('admin.update-hazarduous-material') }}`;
            const formData = new FormData();
            formData.append('company', company);
            formData.append('hazarduous_material_id', hazardousMaterialId);
            formData.append('hazarduous_material', hazardousMaterialValue);

            fetch_cycle('--Update Hazardous Material', url, 'POST', formData)
                .then(result => console.log(result))
                .catch(error => console.error('Error updating hazardous material:', error));
        }
        // ***** End Update Hazardous Material ******//
        // ***** Remove Hazardous Material ******//
        function removeHazardousMaterial(element, hazardousMaterialId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this hazardous material?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const parent = element.closest('.row');
                    const url = `{{ route('admin.remove-hazarduous-material') }}`;
                    const formData = new FormData();
                    formData.append('hazarduous_material_id', hazardousMaterialId);

                    fetch(url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            parent.remove();
                            Swal.fire(
                                'Deleted!',
                                'Hazardous material removed successfully.',
                                'success'
                            );
                        } else {
                            console.error('Failed to remove hazardous material:', result);
                            Swal.fire(
                                'Error!',
                                'Failed to remove hazardous material.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error removing hazardous material:', error);
                        Swal.fire(
                            'Error!',
                            'An unexpected error occurred.',
                            'error'
                        );
                    });
                }
            });
        }
        // End Hazardous Material
        // Unit Process Management
        document.querySelector('.add_more_unit_process').addEventListener('click', () => {
            const unitProcessValue = document.querySelector('#unit_process').value.trim();
            if (!unitProcessValue) {
                showToast("Unit process field cannot be empty.", "error");
                return;
            }

            const url = `{{ route('admin.add-unit-process') }}`;
            const formData = new FormData();
            formData.append('company', '{{$company->company_id}}');
            formData.append('unit_process', unitProcessValue);

            fetch_cycle('--Add Unit Process', url, 'POST', formData)
                .then(result => {
                    if (result.unit_processes) {
                        updateUnitProcessContainer(result.unit_processes);
                    }
                })
                .catch(error => console.error('Error adding unit process:', error));
        });
        // ***** End Add Unit Process ******//
        // ***** Update Unit Process ******//
        function updateUnitProcessContainer(unitProcesses) {
            const container = document.querySelector('.unit-process-container');
            container.innerHTML = unitProcesses.map(element => `
                <div class="row g-2 my-1">
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" value="${element.unit_process_title}" 
                                placeholder="Unit process" 
                                onblur='updateUnitProcess("{{$company->company_id}}", ${element.unitProcessID}, this)'>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-danger" 
                            onclick='removeUnitProcess(this, ${element.unitProcessID})' type="button">
                            <i class="iconoir-trash"></i>
                        </button>
                    </div>
                </div>`).join('');
        }
        // ***** End Update Unit Process ******//
        // ***** Update Unit Process ******//
        function updateUnitProcess(company, unitProcessId, element) {
            const unitProcessValue = element.value.trim();
            if (!unitProcessValue) {
                showToast("Unit process field cannot be empty.", "error");
                return;
            }

            const url = `{{ route('admin.update-unit-process') }}`;
            const formData = new FormData();
            formData.append('company', company);
            formData.append('unit_process_id', unitProcessId);
            formData.append('unit_process', unitProcessValue);

            fetch_cycle('--Update Unit Process', url, 'POST', formData)
                .then(result => console.log(result))
                .catch(error => console.error('Error updating unit process:', error));
        }
        // ***** End Update Unit Process ******//
        // ***** Remove Unit Process ******//
        function removeUnitProcess(element, unitProcessId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this unit process?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const parent = element.closest('.row');
                    const url = `{{ route('admin.remove-unit-process') }}`;
                    const formData = new FormData();
                    formData.append('unit_process_id', unitProcessId);

                    fetch_cycle('--Remove Unit Process', url, 'POST', formData)
                        .then(result => {
                            if (result.status === 'success') {
                                parent.remove();
                                Swal.fire(
                                    'Deleted!',
                                    'The unit process has been deleted.',
                                    'success'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error removing unit process:', error);
                            Swal.fire(
                                'Error!',
                                'An unexpected error occurred.',
                                'error'
                            );
                        });
                }
            });
        }
        // End Unit Process Management
        // Problem Summary & Suggested Solution
        document.querySelector('.add_more_problem_solution').addEventListener('click', () => {
            const problemSummary = document.querySelector('#problem_summary').value.trim();
            const suggestedSolution = document.querySelector('#suggested_solution').value.trim();

            if (!problemSummary) {
                Toastify({
                    text: !problemSummary ? "Problem summary field cannot be empty." : "Suggested solution field cannot be empty.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: { background: "linear-gradient(to right, #ff0000, #ff1745)" },
                }).showToast();
                return;
            }
            if (problemSummary.length < 5) {
                Toastify({
                    text: "Problem summary must be at least 5 characters long.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: { background: "linear-gradient(to right, #ff0000, #ff1745)" },
                }).showToast();
                return;
            }
            const url = `{{ route('admin.add-problem-solution') }}`;
            const formData = new FormData();
            formData.append('company', '{{$company->company_id}}');
            formData.append('problem_summary', problemSummary);
            formData.append('suggested_solution', suggestedSolution);

            fetch_cycle('--Add Problem Solution', url, 'POST', formData).then(result => {
                if (result.problems_solutions) {
                    const container = document.querySelector('.problems-solutions-container');
                    container.innerHTML = result.problems_solutions.map(element => `
                        <div class="row g-2 my-1 align-items-end">
                            <div class="col-md-5">
                                <label for="">Problem Summary</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="${element.problem_title}" placeholder="Problem Summary" 
                                        onblur='updateProblemSummary("{{$company->company_id}}", ${element.problemSolutionID}, this)'>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="">Suggested Solution</label>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="${element.solution_title}" placeholder="Suggested Solution" 
                                        onblur='updateSuggestedSolution("{{$company->company_id}}", ${element.problemSolutionID}, this)'>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-outline-danger" onclick='removeProblemSolution(this, ${element.problemSolutionID})' type="button">
                                    <i class="iconoir-trash"></i>
                                </button>
                            </div>
                        </div>
                    `).join('');
                }
            });
        });
        // ***** End Add Problem Summary & Suggested Solution ******//
        // ***** Update Problem Summary ******//
        function updateProblemSummary(company, problemSolutionId, element) {
            const problemSummary = element.value.trim();
            if (!problemSummary) {
                Toastify({
                    text: "Problem summary field cannot be empty.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: { background: "linear-gradient(to right, #ff0000, #ff1745)" },
                }).showToast();
                return;
            }

            const url = `{{ route('admin.update-problem-summary') }}`;
            const formData = new FormData();
            formData.append('company', company);
            formData.append('problem_solution_id', problemSolutionId);
            formData.append('problem_summary', problemSummary);

            fetch_cycle('--Update Problem Summary', url, 'POST', formData).then(result => console.log(result));
        }
        // ***** End Update Problem Summary ******//
        // ***** Update Suggested Solution ******//
        function updateSuggestedSolution(company, problemSolutionId, element) {
            const suggestedSolution = element.value.trim();
            if (!suggestedSolution) {
                Toastify({
                    text: "Suggested solution field cannot be empty.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: { background: "linear-gradient(to right, #ff0000, #ff1745)" },
                }).showToast();
                return;
            }

            const url = `{{ route('admin.update-suggested-solution') }}`;
            const formData = new FormData();
            formData.append('company', company);
            formData.append('problem_solution_id', problemSolutionId);
            formData.append('suggested_solution', suggestedSolution);

            fetch_cycle('--Update Suggested Solution', url, 'POST', formData).then(result => console.log(result));
        }
        // ***** End Update Suggested Solution ******//
        // ***** Remove Problem Solution ******//
        function removeProblemSolution(element, problemSolutionId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete this Problem Summary with its Suggested Solution?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = `{{ route('admin.remove-problem-solution') }}`;
                    const formData = new FormData();
                    formData.append('problem_solution_id', problemSolutionId);

                    fetch_cycle('--Remove Problem Solution', url, 'POST', formData).then(result => {
                        if (result.status === 'success') {
                            element.closest('.row').remove();
                            Swal.fire(
                                'Deleted!',
                                'The Problem Summary and its Suggested Solution have been deleted.',
                                'success'
                            );
                        }
                    });
                }
            });
        }
        // ***** End Remove Problem Solution ******//
        // Material Submission
        document.querySelector('#btn-submit-material').addEventListener('click', () => {
            console.log("Material submission triggered");
            const loader = document.getElementById('loader');
            loader.style.display = 'inline-block';

            const companyID = document.querySelector('input[name="company_id"]').value.trim();
            const materialID = document.querySelector('select[name="material"]').value.trim();
            const serialNo = document.querySelector('input[name="serial_number"]').value.trim();
            const unit = document.querySelector('input[name="unit_of_measurement"]').value.trim();
            const threshold = document.querySelector('input[name="threshold"]').value.trim();

            if (!companyID || !materialID) {
                const errorMessage = !companyID ? "Company ID field cannot be empty." : "Material field cannot be empty.";
                Toastify({
                    text: errorMessage,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #ff0000, #ff1745)",
                    },
                }).showToast();
                loader.style.display = 'none';
                return;
            }

            const url = "{{ route('admin.save-company-material') }}";
            const formData = new FormData();
            formData.append('companyID', companyID);
            formData.append('material', materialID);
            formData.append('serial_number', serialNo);
            formData.append('unit_of_measurement', unit);
            formData.append('threshold', threshold);

            fetch_cycle('--Save Company Material', url, 'POST', formData).then(result => {
                console.log(result);
                loader.style.display = 'none';

                if (result.company_material) {
                    const tableBody = document.querySelector('#tbl-company-material tbody');
                    tableBody.innerHTML = result.company_material.map(material => {
                        const id = material.companyMaterialId;
                        const viewUrl = "{{ route('admin.view-material', ['material' => '__PLACEHOLDER__']) }}".replace('__PLACEHOLDER__', id);

                        return `
                            <tr>
                                <td>${material.material}</td>
                                <td>${material.serial_number || ''}</td>
                                <td>${material.unit_of_measure || ''}</td>
                                <td>
                                    <span class="badge bg-${material.company_material_status === 'active' ? 'success' : 'danger'}">
                                        ${material.company_material_status}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown d-inline-block">
                                        <a class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button">
                                            <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="${viewUrl}">Open Material</a>
                                            <a class="dropdown-item" href="#">Update Material</a>
                                            <a class="dropdown-item" href="#">Delete Material</a>
                                            <hr class="dropdown-divider">
                                            <a class="dropdown-item" href="#" onclick='triggerMaterialPrice("${id}")'>Setup Price</a>
                                            <a class="dropdown-item" href="#" onclick='triggerCheckIn("${id}", "${material.materialID}", "${material.companyID}", "${material.material}")'>Check In Item</a>
                                            <a class="dropdown-item" href="#" onclick='triggerCheckOut("${id}", "${material.materialID}", "${material.companyID}", "${material.material}")'>Check Out Item</a>
                                            <a class="dropdown-item" href="#" onclick='triggerAdjustment("${id}", "${material.materialID}", "${material.companyID}", "${material.material}")'>Make Adjustment</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;
                    }).join('');
                }
            });
        });
        // end Material Submission

        // Company Material Price
        const btnSubmitMaterialPrice = document.querySelector('#btn-submit-material-price');
        btnSubmitMaterialPrice.addEventListener('click', () => {
            console.log("Material price submission triggered");

            const loader = document.querySelector('#materialPriceModal #loader');
            loader.style.display = 'inline-block';

            const materialPriceId = document.querySelector('input[name="material_price_id"]').value.trim();
            const unit = document.querySelector('input[name="unit"]').value.trim();
            const price = document.querySelector('input[name="price"]').value.trim();
            const date = document.querySelector('input[name="date"]').value.trim();

            if (!materialPriceId || !unit || !price) {
                const errorMessage = !materialPriceId
                    ? "Material price ID field cannot be empty."
                    : !unit
                    ? "Unit field cannot be empty."
                    : "Price field cannot be empty.";

                Toastify({
                    text: errorMessage,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #ff0000, #ff1745)",
                    },
                }).showToast();

                loader.style.display = 'none';
                return;
            }

            const url = "{{ route('admin.save-company-material-price') }}";
            const formData = new FormData();
            formData.append('companyMaterialID', materialPriceId);
            formData.append('unit', unit);
            formData.append('price', price);
            formData.append('date', date);

            fetch_cycle('--Save Material Price', url, 'POST', formData).then(result => {
                console.log(result);
                loader.style.display = 'none';
            });
        });
        // End Company Material Price

        // Check-in functionality
        document.querySelector('#btn-submit-check-in').addEventListener('click', async () => {
            console.log("Check-in button clicked");

            const loader = document.querySelector('#checkInModal #loader');
            loader.style.display = 'inline-block';

            const checkInMaterialId = document.querySelector('#checkInModal input[name="checkIn_material_id"]').value.trim();
            const materialId = document.querySelector('#checkInModal input[name="material_id"]').value.trim();
            const companyId = document.querySelector('#checkInModal input[name="company_id"]').value.trim();
            const quantity = document.querySelector('#checkInModal input[name="quantity"]').value.trim();
            const date = document.querySelector('#checkInModal input[name="date"]').value.trim();
            const remark = document.querySelector('#checkInModal input[name="remark"]').value.trim();

            // Validation
            const validationMessages = [];
            if (!checkInMaterialId) validationMessages.push("Material ID field cannot be empty.");
            if (!quantity) validationMessages.push("Quantity field cannot be empty.");
            if (!date) validationMessages.push("Date field cannot be empty.");

            if (validationMessages.length > 0) {
                validationMessages.forEach(message => {
                    Toastify({
                        text: message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #ff0000, #ff1745)",
                        },
                    }).showToast();
                });
                loader.style.display = 'none';
                return;
            }

            // Prepare data and send request
            const url = "{{ route('admin.save-company-material-check-in') }}";
            const formData = new FormData();
            formData.append('checkIn_material_id', checkInMaterialId);
            formData.append('material_id', materialId);
            formData.append('company_id', companyId);
            formData.append('quantity', quantity);
            formData.append('date', date);
            formData.append('remark', remark);

            try {
                const result = await fetch_cycle('--Create Check In', url, 'POST', formData);
                console.log(result);
            } catch (error) {
                console.error("Error during check-in:", error);
            } finally {
                loader.style.display = 'none';
            }
        });
        // End check-in functionality
        // Company material checkout
        document.querySelector('#btn-submit-check-out').addEventListener('click', async () => {
            console.log("Checkout button clicked");

            const loader = document.querySelector('#checkOutModal #loader');
            loader.style.display = 'inline-block';

            const checkOutMaterialId = document.querySelector('#checkOutModal input[name="checkOut_material_id"]').value.trim();
            const materialId = document.querySelector('#checkOutModal input[name="material_id"]').value.trim();
            const companyId = document.querySelector('#checkOutModal input[name="company_id"]').value.trim();
            const quantity = document.querySelector('#checkOutModal input[name="quantity"]').value.trim();
            const date = document.querySelector('#checkOutModal input[name="date"]').value.trim();
            const remark = document.querySelector('#checkOutModal input[name="remark"]').value.trim();

            const validationMessages = [];
            if (!checkOutMaterialId) validationMessages.push("Material ID field cannot be empty.");
            if (!quantity) validationMessages.push("Quantity field cannot be empty.");
            if (!date) validationMessages.push("Date field cannot be empty.");

            if (validationMessages.length > 0) {
                validationMessages.forEach(message => {
                    Toastify({
                        text: message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #ff0000, #ff1745)",
                        },
                    }).showToast();
                });
                loader.style.display = 'none';
                return;
            }

            const url = "{{ route('admin.save-company-material-check-out') }}";
            const formData = new FormData();
            formData.append('checkOut_material_id', checkOutMaterialId);
            formData.append('material_id', materialId);
            formData.append('company_id', companyId);
            formData.append('quantity', quantity);
            formData.append('date', date);
            formData.append('remark', remark);

            try {
                const result = await fetch_cycle('--Create Check Out', url, 'POST', formData);
                console.log(result);
            } catch (error) {
                console.error("Error during checkout:", error);
            } finally {
                loader.style.display = 'none';
            }
        });
        // End checkout

        // General Settings Update
        const btnGeneralSettings = document.querySelector('#btn-general-settings');
        btnGeneralSettings.addEventListener('click', () => {
            const loader = document.querySelector('#general-settings #loader');
            loader.style.display = 'inline-block';

            const formData = new FormData(document.querySelector('#general-settings'));
            const requiredFields = [
                { name: 'company_id', message: "Company ID field cannot be empty." },
                { name: 'company_name', message: "Company name field cannot be empty." },
                { name: 'industry', message: "Industry field cannot be empty." },
                { name: 'email', message: "Company email field cannot be empty." },
                { name: 'primary_phone_number', message: "Primary phone number field cannot be empty." },
                { name: 'number_of_employees', message: "Number of employees field cannot be empty." },
                { name: 'date_of_establishment', message: "Establishment date field cannot be empty." }
            ];

            for (const field of requiredFields) {
                if (!formData.get(field.name)?.trim()) {
                    loader.style.display = 'none';
                    Toastify({
                        text: field.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #ff0000, #ff1745)",
                        },
                    }).showToast();
                    return;
                }
            }

            const url = document.querySelector('#general-settings').action;

            fetch_cycle('--Update Personal Company Details', url, 'POST', formData)
                .then(result => {
                    console.log(result, result.companies_info);
                    loader.style.display = 'none';
                });
        });
        // End General Settings Update

        // Company location update
        document.querySelector('#btn-location-settings').addEventListener('click', () => {
            const loader = document.querySelector('#location_settings #loader');
            loader.style.display = 'inline-block';

            const formData = new FormData(document.querySelector('#location_settings'));
            const requiredFields = [
                { name: 'company_id', message: "Company ID field cannot be empty." },
                { name: 'country', message: "Country field cannot be empty." },
                { name: 'state', message: "State field cannot be empty." },
                { name: 'city', message: "City field cannot be empty." },
                { name: 'address', message: "Address field cannot be empty." }
            ];

            for (const field of requiredFields) {
                if (!formData.get(field.name)?.trim()) {
                    loader.style.display = 'none';
                    Toastify({
                        text: field.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #ff0000, #ff1745)",
                        },
                    }).showToast();
                    return;
                }
            }

            fetch_cycle('--Update Company Location', document.querySelector('#location_settings').action, 'POST', formData)
                .then(result => {
                    console.log(result);
                    loader.style.display = 'none';
                });
        });

        // Company contact update
        document.querySelector('#btn_contact_settings').addEventListener('click', () => {
            const loader = document.querySelector('#contact_settings #loader');
            loader.style.display = 'inline-block';

            const formData = new FormData(document.querySelector('#contact_settings'));
            const requiredFields = [
                { name: 'company_id', message: "Company ID field cannot be empty." },
                { name: 'enviromental_operations_manager', message: "Environmental operations manager field cannot be empty." },
                { name: 'contact_person_name', message: "Contact person name field cannot be empty." },
                { name: 'contact_person_position', message: "Contact person position field cannot be empty." },
                { name: 'contact_person_phone_number', message: "Contact person phone number field cannot be empty." }
            ];

            for (const field of requiredFields) {
                if (!formData.get(field.name)?.trim()) {
                    loader.style.display = 'none';
                    Toastify({
                        text: field.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        style: {
                            background: "linear-gradient(to right, #ff0000, #ff1745)",
                        },
                    }).showToast();
                    return;
                }
            }

            fetch_cycle('--Update Contact Personnel', document.querySelector('#contact_settings').action, 'POST', formData)
                .then(result => {
                    console.log(result);
                    loader.style.display = 'none';
                });
        });
        // activate and deactivate start
        // end activate and deactivate start
        async function fetch_cycle(subject, url, method, formData) {
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    credentials: 'same-origin',
                    body: formData
                });

                const data = await response.json();

                // Handle feedback
                const toastOptions = {
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                };

                if (data.status === 'success') {
                    Toastify({
                        ...toastOptions,
                        text: data.message,
                        style: { background: "linear-gradient(to right, #00b09b, #96c93d)" },
                    }).showToast();
                    return data;
                } else if (data.status === 'error') {
                    console.error(data.errors);
                    Object.values(data.errors).forEach(error => {
                        Toastify({
                            ...toastOptions,
                            text: error,
                            style: { background: "linear-gradient(to right, #ff0000, #ff1745)" },
                        }).showToast();
                    });
                }
            } catch (error) {
                console.error('Fetch error:', error);
                Toastify({
                    text: "An unexpected error occurred.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: { background: "linear-gradient(to right, #ff0000, #ff1745)" },
                }).showToast();
            }
        }
        // End fetchCycle function

        // -----Country Code Selection
        const initializeIntlTelInput = (inputElement, hiddenInputName) => {
            const intlTelInstance = window.intlTelInput(inputElement, {
                initialCountry: "ng",
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.4/js/utils.js"
            });

            inputElement.addEventListener("blur", () => {
                const fullPhoneNumber = intlTelInstance.getNumber(); // Gets the full number in E.164 format
                console.log("Full phone number:", fullPhoneNumber);
                document.querySelector(`input[name="${hiddenInputName}"]`).value = fullPhoneNumber;
            });

            return intlTelInstance;
        };
        // Initialize intlTelInput for primary, secondary, and contact phone numbers
        const telPrimary = document.querySelector('#mobile_code_primary');
        const telSecondary = document.querySelector('#mobile_code_secondary');
        const telContact = document.querySelector('#mobile_code_contact');

        initializeIntlTelInput(telPrimary, "primary_phone_number");
        initializeIntlTelInput(telSecondary, "secondary_phone_number");
        initializeIntlTelInput(telContact, "contact_person_phone_number");
        // Country Code Selection End

        // Set selected options for industry and industry process
        const setSelectedOption = (selector, value) => {
            const element = document.querySelector(selector);
            if (element) {
                Array.from(element.options).forEach(option => {
                    if (option.value == value) {
                        option.selected = true;
                    }
                });
            }
        };

        setSelectedOption('#industry', `{{ $company->industry }}`);
        setSelectedOption('#industry-process', `{{ $company->industry_process }}`);

        // Log country options after a delay
        setTimeout(() => {
            const countriesElement = document.querySelector('.countries');
            if (countriesElement) {
                const countryValues = Array.from(countriesElement.options).map(option => option.value);
                console.log(countryValues);
            }
        }, 1000);
        // End of country options log

        // Trigger Material Price Modal
        const triggerMaterialPrice = (companyMaterialID) => {
            const materialPriceModal = document.querySelector('#materialPriceModal');
            document.querySelector('input[name="material_price_id"]').value = companyMaterialID;
            const modalInstance = new bootstrap.Modal(materialPriceModal);
            modalInstance.show();
        };

        // Trigger CheckIn
        const triggerCheckIn = (companyMaterialID, materialID, companyID, materialName) => {
            const checkInModal = document.querySelector('#checkInModal');
            const modalInputs = {
                checkInMaterialID: document.querySelector('#checkInModal input[name="checkIn_material_id"]'),
                materialID: document.querySelector('#checkInModal input[name="material_id"]'),
                companyID: document.querySelector('#checkInModal input[name="company_id"]'),
                materialName: document.querySelector('#checkInModal input[name="checkIn_material_name"]')
            };

            // Set input values
            modalInputs.checkInMaterialID.value = companyMaterialID;
            modalInputs.materialID.value = materialID;
            modalInputs.companyID.value = companyID;
            modalInputs.materialName.value = materialName;

            // Show modal
            const modalInstance = new bootstrap.Modal(checkInModal);
            modalInstance.show();
        };

        // Trigger CheckOut
        const triggerCheckOut = (companyMaterialID, materialID, companyID, materialName) => {
            const checkOutModal = document.querySelector('#checkOutModal');
            const modalInputs = {
                checkOutMaterialID: document.querySelector('#checkOutModal input[name="checkOut_material_id"]'),
                materialID: document.querySelector('#checkOutModal input[name="material_id"]'),
                companyID: document.querySelector('#checkOutModal input[name="company_id"]'),
                materialName: document.querySelector('#checkOutModal input[name="checkOut_material_name"]')
            };

            // Set input values
            modalInputs.checkOutMaterialID.value = companyMaterialID;
            modalInputs.materialID.value = materialID;
            modalInputs.companyID.value = companyID;
            modalInputs.materialName.value = materialName;

            // Show modal
            const modalInstance = new bootstrap.Modal(checkOutModal);
            modalInstance.show();
        };

        // Trigger adjustment
        const triggerAdjustment = (companyMaterialID, materialID, companyID, materialName) => {
            const adjustmentModal = document.querySelector('#adjustmentModal');
            const modalInputs = {
                adjustmentMaterialID: adjustmentModal.querySelector('input[name="adjustment_material_id"]'),
                materialID: adjustmentModal.querySelector('input[name="material_id"]'),
                companyID: adjustmentModal.querySelector('input[name="company_id"]'),
                materialName: adjustmentModal.querySelector('input[name="checkOut_material_name"]')
            };

            // Set input values
            modalInputs.adjustmentMaterialID.value = companyMaterialID;
            modalInputs.materialID.value = materialID;
            modalInputs.companyID.value = companyID;
            modalInputs.materialName.value = materialName;

            // Show modal
            const modalInstance = new bootstrap.Modal(adjustmentModal);
            modalInstance.show();
        };
        // End trigger adjustment
        // Activate and Deactivate Company
        document.querySelectorAll('.toggle-status').forEach(checkbox => {
            checkbox.addEventListener('change', async function () {
                const companyId = this.dataset.companyId;
                const status = this.checked ? 1 : 0;

                try {
                    const response = await fetch("{{ route('company.toggleStatus') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ company_id: companyId, status })
                    });

                    const data = await response.json();
                    const feedback = document.getElementById('feedback-message');
                    feedback.textContent = data.message || 'Error updating status!';
                    feedback.style.color = data.success ? 'green' : 'red';
                } catch (error) {
                    console.error('Error:', error);
                    const feedback = document.getElementById('feedback-message');
                    feedback.textContent = 'An error occurred!';
                    feedback.style.color = 'red';
                }
            });
        });
        // End activate and deactivate
        // Questionnaire
        async function toggleQuestionResult(element, companyId, questionId) {
            console.log(element, companyId, questionId);

            const isChecked = element.checked;
            const confirmationMessage = "Do you want to uncheck this?";
            const uri = isChecked 
                ? "{{ route('company.add-question') }}" 
                : "{{ route('company.remove-question') }}";

            if (!isChecked && !confirm(confirmationMessage)) return;

            const formData = new FormData();
            formData.append('company', companyId);
            formData.append('question_id', questionId);

            try {
                const result = await fetch_cycle('--Save question', uri, 'POST', formData);
                console.log(result);
            } catch (error) {
                console.error("Error processing question result:", error);
            }
        }
        // End Questionnaire
        // Water Conservation Method
        async function toggleWaterConservationMethod(element, companyId, methodId) {
            console.log(element, companyId, methodId);

            const isChecked = element.checked;
            const confirmationMessage = "Do you want to uncheck this?";
            const uri = isChecked 
                ? "{{ route('company.add-water-conservation-method') }}" 
                : "{{ route('company.remove-water-conservation-method') }}";

            if (!isChecked && !confirm(confirmationMessage)) return;

            const formData = new FormData();
            formData.append('company', companyId);
            formData.append('water_conservation_method_id', methodId);

            try {
                const result = await fetch_cycle('--Save method', uri, 'POST', formData);
                console.log(result);
            } catch (error) {
                console.error("Error processing water conservation method:", error);
            }
        }
        // End Water Conservation Method
        // Water Quality Control
        async function toggleWaterQualityControl(element, companyId, qualityControlId) {
            console.log(element, companyId, qualityControlId);

            const isChecked = element.checked;
            const confirmationMessage = "Do you want to uncheck this?";
            const uri = isChecked 
                ? "{{ route('admin.store-water-quality-logs') }}" 
                : "{{ route('admin.remove-water-quality-logs') }}";

            if (!isChecked && !confirm(confirmationMessage)) return;

            const formData = new FormData();
            formData.append('company', companyId);
            formData.append('water_quality_control_id', qualityControlId);

            try {
                const result = await fetch_cycle('--Save quality control', uri, 'POST', formData);
                console.log(result);
            } catch (error) {
                console.error("Error processing water quality control:", error);
            }
        }
        // End Water Quality Control
        // WaterSources
        async function toggleWaterSource(ele, company, value) {
            console.log(ele, company, value);

            const isChecked = ele.checked;
            const confirmationMessage = "Do you want to uncheck this?";
            const uri = isChecked 
                ? "{{ route('company.add-water-sources') }}" 
                : "{{ route('company.remove-water-sources') }}";

            if (!isChecked && !confirm(confirmationMessage)) return;

            const formData = new FormData();
            formData.append('company', company);
            formData.append('water_sources_id', value);

            try {
                const result = await fetch_cycle('--Save sources', uri, 'POST', formData);
                console.log(result);
            } catch (error) {
                console.error("Error processing water source:", error);
            }
        }
        // End WaterSources
        // Utility function to show and scroll to a card
        function showAndScrollToCard(cardSelector) {
            const card = document.querySelector(cardSelector);
            if (card) {
                card.classList.remove('d-none');
                card.scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Event listeners for various triggers
        document.querySelector('#water-checkin-trigger').addEventListener('click', (e) => {
            e.preventDefault();
            showAndScrollToCard('#water-checkin-card');
        });
        // Water usage log trigger
        document.querySelector('#water-usage-log-trigger').addEventListener('click', (e) => {
            e.preventDefault();
            showAndScrollToCard('#water-usage-logs-card');
        });
        // Water recycling log trigger
        document.querySelector('#water-recycling-log-trigger').addEventListener('click', (e) => {
            e.preventDefault();
            showAndScrollToCard('#water-recycling-logs-card');
        });
        // Water quality control log trigger
        document.querySelector('#add-quality-control-record').addEventListener('click', (e) => {
            e.preventDefault();
            showAndScrollToCard('#quality-control-log-card');
        });
        // chemical trigger
        document.querySelector('#add-chemical-button').addEventListener('click', (e) => {
            e.preventDefault();
            showAndScrollToCard('#add-chemical-form-card');
        });

        // Water quality control log trigger
        document.querySelector('#add-material-button').addEventListener('click', (e) => {
            e.preventDefault();
            showAndScrollToCard('#add-material-form-card');
        });
    </script>
    <!-- end of script -->
    <!-- water inventory -->
    <script>
        // Check-in form submission
        document.querySelector('#water-checkin-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const url = "{{ route('admin.water-stock-check-in') }}";
            const formData = new FormData(this);

            try {
                const result = await fetch_cycle('--Save Water Check-In', url, 'POST', formData);
                console.log(result);

                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-water-management tbody');
                    tableBody.innerHTML = result.water_stock_movements.map(water_stock_movement => {
                        const sourceName = water_stock_movement.company_water_sources?.water_source?.sources ?? 'N/A';
                        const calendarYear = water_stock_movement.calendar_year?.name ?? 'N/A';
                        const formattedDate = new Date(water_stock_movement.movement_date).toLocaleDateString('en-GB', {
                            day: '2-digit', month: 'short', year: 'numeric'
                        });

                        return `
                            <tr>
                                <td class="text-capitalize">
                                    ${water_stock_movement.movement_type}
                                    ${water_stock_movement.movement_type === 'in' ? '<i class="fas fa-caret-up text-success font-16"></i>' : ''}
                                    ${water_stock_movement.movement_type === 'out' ? '<i class="fas fa-caret-down text-danger font-16"></i>' : ''}
                                    ${water_stock_movement.movement_type === 'recycling' ? '<i class="fas fa-recycle text-info font-16"></i>' : ''}
                                    ${water_stock_movement.movement_type === 'usage' ? '<i class="fas fa-tint text-primary font-16"></i>' : ''}
                                </td>
                                <td>${sourceName}</td>
                                <td>${water_stock_movement.volume}</td>
                                <td>${calendarYear}</td>
                                <td>${formattedDate}</td>
                                <td>${water_stock_movement.remark ?? ''}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-outline-primary btn-sm me-2">Edit</button>
                                        <button class="btn btn-outline-danger btn-sm">Delete</button>
                                    </div>
                                </td>
                            </tr>`;
                    }).join('');
                }
            } catch (error) {
                console.error('Error during water check-in:', error);
            }
        });
        // End check-in form submission
        // Water usage log form submission
        document.querySelector('#water-usage-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.water-stock-check-out') }}";

            try {
                const result = await fetch_cycle('--Save Water Usage Log', url, 'POST', formData);
                console.log(result);

                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-water-management tbody');
                    tableBody.innerHTML = result.water_stock_movements.map(water_stock_movement => {
                        const sourceName = water_stock_movement.company_water_sources?.water_source?.sources ?? 'N/A';
                        const calendarYear = water_stock_movement.calendar_year?.name ?? 'N/A';
                        const formattedDate = new Date(water_stock_movement.movement_date).toLocaleDateString('en-GB', {
                            day: '2-digit', month: 'short', year: 'numeric'
                        });

                        return `
                            <tr>
                                <td class="text-capitalize">
                                    ${water_stock_movement.movement_type}
                                    ${water_stock_movement.movement_type === 'in' ? '<i class="fas fa-caret-up text-success font-16"></i>' : ''}
                                    ${water_stock_movement.movement_type === 'out' ? '<i class="fas fa-caret-down text-danger font-16"></i>' : ''}
                                    ${water_stock_movement.movement_type === 'recycling' ? '<i class="fas fa-recycle text-info font-16"></i>' : ''}
                                    ${water_stock_movement.movement_type === 'usage' ? '<i class="fas fa-tint text-primary font-16"></i>' : ''}
                                </td>
                                <td>${sourceName}</td>
                                <td>${water_stock_movement.volume}</td>
                                <td>${calendarYear}</td>
                                <td>${formattedDate}</td>
                                <td>${water_stock_movement.remark ?? ''}</td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-outline-primary btn-sm me-2">Edit</button>
                                        <button class="btn btn-outline-danger btn-sm">Delete</button>
                                    </div>
                                </td>
                            </tr>`;
                    }).join('');
                }
            } catch (error) {
                console.error('Error saving water usage log:', error);
            }
        });
        // End water usage log form submission
        // Water recycling log form submission
        document.querySelector('#water-recycling-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.water-stock-recycling-log') }}";

            try {
                const result = await fetch_cycle('--Save Water Recycling Log', url, 'POST', formData);
                if (result.status === 'success') {
                    updateWaterManagementTable(result.water_stock_movements);
                }
            } catch (error) {
                console.error('Error saving water recycling log:', error);
            }
        });

        // Update Water Management Table
        function updateWaterManagementTable(waterStockMovements) {
            const tableBody = document.querySelector('#tbl-water-management tbody');
            tableBody.innerHTML = waterStockMovements.map(waterStockMovement => {
                const sourceName = waterStockMovement.company_water_sources?.water_source?.sources ?? 'N/A';
                const calendarYear = waterStockMovement.calendar_year?.name ?? 'N/A';
                const formattedDate = new Date(waterStockMovement.movement_date).toLocaleDateString('en-GB', {
                    day: '2-digit', month: 'short', year: 'numeric'
                });

                return `
                    <tr>
                        <td class="text-capitalize">
                            ${waterStockMovement.movement_type}
                            ${getMovementTypeIcon(waterStockMovement.movement_type)}
                        </td>
                        <td>${sourceName}</td>
                        <td>${waterStockMovement.volume}</td>
                        <td>${calendarYear}</td>
                        <td>${formattedDate}</td>
                        <td>${waterStockMovement.remark ?? ''}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-outline-primary btn-sm me-2">Edit</button>
                                <button class="btn btn-outline-danger btn-sm">Delete</button>
                            </div>
                        </td>
                    </tr>`;
            }).join('');
        }

        // Get Movement Type Icon
        function getMovementTypeIcon(movementType) {
            const icons = {
                in: '<i class="fas fa-caret-up text-success font-16"></i>',
                out: '<i class="fas fa-caret-down text-danger font-16"></i>',
                recycling: '<i class="fas fa-recycle text-info font-16"></i>',
                usage: '<i class="fas fa-tint text-primary font-16"></i>'
            };
            return icons[movementType] || '';
        }

        // Store Water Sources
        document.querySelector('#waterSourceForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-water-source-details') }}";

            try {
                const result = await fetch_cycle('--Store Water Sources', url, 'POST', formData);
                if (result.status === 'success') {
                    updateWaterSourcesTable(result.water_sources);
                }
            } catch (error) {
                console.error('Error storing water sources:', error);
            }
        });

        // Update Water Sources Table
        function updateWaterSourcesTable(waterSources) {
            const tableBody = document.querySelector('#tbl-water-sources tbody');
            tableBody.innerHTML = waterSources.map(source => `
                <tr>
                    <td>${source.sources}</td>
                    <td>${source.description ?? ""}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-sm btn-primary me-2">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </div>
                    </td>
                </tr>`).join('');
        }
        // End water sources
    </script>
    <script>
        // Store Quality Control Logs
        document.querySelector('#qualityControlLogForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-water-quality-logs') }}";

            try {
                const result = await fetch_cycle('--Store Quality Control Log', url, 'POST', formData);
                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-quality-control-management tbody');
                    tableBody.innerHTML = result.water_quality_logs.map(quality => `
                        <tr>
                            <td>${quality.test_date}</td>
                            <td>${quality.parameter_tested}</td>
                            <td>${quality.test_results}</td>
                            <td>${quality.deviation_detected}</td>
                            <td>${quality.corrective_actions}</td>
                            <td class="text-end">
                                <div class="dropdown d-inline-block">
                                    <a class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button">
                                        <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Edit</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error storing quality control log:', error);
            }
        });
    </script>
    <!-- end water quality control logs -->
    <!-- Waste Disposal -->
    <script>
        // Store Waste Disposal
        // Handles the submission of the waste disposal form and updates the waste disposal table.
        document.querySelector('#waste-disposal-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-waste-disposal') }}";

            try {
                const result = await fetch_cycle('--Store Waste Disposal', url, 'POST', formData);
                if (result.status === 'success' && Array.isArray(result.waste_disposals)) {
                    const tableBody = document.querySelector('#tbl-waste-disposal tbody');
                    tableBody.innerHTML = result.waste_disposals.map(disposal => `
                        <tr>
                            <td>${disposal.waste_type ?? ''}</td>
                            <td>${disposal.quantity ?? ''}</td>
                            <td>${disposal.disposal_method ?? ''}</td>
                            <td>${disposal.disposal_date ?? ''}</td>
                            <td>${disposal.operation?.operation_name ?? 'N/A'}</td>
                            <td>${disposal.calendarYear?.name ?? 'N/A'}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-sm btn-primary me-2">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error storing waste disposal:', error);
            }
        });
    </script>
    <!-- end waste disposal -->

    <!--store IoT Device Management -->
    <script>
        /**
         * Handles the submission of the IoT Device Management form.
         *
         * - Prevents the default form submission behavior.
         * - Collects form data and sends it via an asynchronous POST request to the server.
         * - On successful response, updates the IoT devices table with the latest device data.
         * - Handles and logs any errors that occur during the process.
         *
         * Dependencies:
         * - Assumes the existence of a `fetch_cycle` function for making AJAX requests.
         * - Requires a form with the ID `iot-device-form` and a table with the ID `tbl-iot-devices`.
         * - Uses Laravel's route helper to generate the endpoint URL.
         */
        document.querySelector('#iot-device-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-iot-device') }}";

            try {
                const result = await fetch_cycle('--Store IoT Device', url, 'POST', formData);
                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-iot-devices tbody');
                    tableBody.innerHTML = result.iot_devices.map(device => `
                        <tr>
                            <td>${device.device_name}</td>
                            <td>${device.device_type}</td>
                            <td>${device.serial_number}</td>
                            <td>${device.status}</td>
                            <td>${device.date_added}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-sm btn-primary me-2">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error storing IoT device:', error);
            }
        });
    </script>
    <!-- end iot device managment -->
    <!-- Chemical  -->
    <script>
        /**
         * Chemical Management Script
         *
         * Handles:
         * - Submission of new company chemicals.
         * - Dynamic update of the chemical management table.
         * - Modal triggers for chemical check-in and check-out.
         * - Submission of check-in/check-out forms for chemicals.
         *
         * Features:
         * - Validates required fields before submission.
         * - Uses fetch_cycle for AJAX requests.
         * - Updates the chemical table on success.
         * - Provides utility functions for modal handling and field validation.
         */

        // Button for submitting a new chemical
        const btnSubmitChemical = document.querySelector('#btn-submit-chemical');

        // Utility: Validate required fields
        const validateFields = (fields) => {
            for (const { value, message } of fields) {
                if (!value.trim()) {
                    showToast(message);
                    return false;
                }
            }
            return true;
        };

        // Utility: Handle AJAX submission and update table
        const handleSubmit = async (url, formData, loader) => {
            loader.style.display = 'inline-block';
            try {
                const result = await fetch_cycle('--Save Chemical', url, 'POST', formData);
                loader.style.display = 'none';
                if (result.status === "success") {
                    // Update chemical management table with new data
                    const tableBody = document.querySelector('#tbl-company-chemical tbody');
                    tableBody.innerHTML = result.company_chemical.map(chemical => `
                        <tr>
                            <td>${escapeHTML(chemical.name ?? '')}</td>
                            <td>${escapeHTML(chemical.unit ?? '')}</td>
                            <td>
                                <span 
                                    class="badge bg-${chemical.chemical_status === 'active' ? 'success' : 'danger'}" 
                                    role="status" 
                                    aria-label="Chemical status: ${escapeHTML(chemical.chemical_status)}"
                                >
                                    ${escapeHTML(chemical.chemical_status)}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown d-inline-block">
                                    <a 
                                        class="dropdown-toggle arrow-none" 
                                        data-bs-toggle="dropdown" 
                                        href="#" 
                                        role="button" 
                                        aria-expanded="false"
                                    >
                                        <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a 
                                            class="dropdown-item" 
                                            href="${`/admin/view-chemical/${encodeURIComponent(chemical.company_chemical_id)}`}"
                                        >
                                            Open Chemical
                                        </a>
                                        <a class="dropdown-item" href="#">Update Chemical</a>
                                        <a class="dropdown-item" href="#">Delete Chemical</a>
                                        <hr class="dropdown-divider">
                                        <a class="dropdown-item" href="#">Setup Price</a>
                                        <a class="dropdown-item" href="#">Check In Item</a>
                                        <a class="dropdown-item" href="#">Check Out Item</a>
                                        <a class="dropdown-item" href="#">Adjustment</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error(error);
                loader.style.display = 'none';
            }
        };

        // Handle chemical submission
        btnSubmitChemical.addEventListener('click', () => {
            const loader = document.querySelector('#btn-submit-chemical #loader');
            const companyId = document.querySelector('#chemical-form input[name="company_id"]').value;
            const chemical = document.querySelector('#chemical-form select[name="chemical"]').value;
            const unitOfMeasurement = document.querySelector('#chemical-form input[name="unit_of_measurement"]').value;
            const threshold = document.querySelector('#chemical-form input[name="threshold"]').value;

            if (!validateFields([
                { value: companyId, message: "Company ID field cannot be empty." },
                { value: chemical, message: "Chemical field cannot be empty." },
                { value: unitOfMeasurement, message: "Unit of measurement field cannot be empty." }
            ])) return;

            const formData = new FormData();
            formData.append('chemical_id', chemical);
            formData.append('unit_of_measurement', unitOfMeasurement);
            formData.append('threshold', threshold);
            formData.append('company_id', companyId);

            handleSubmit("{{ route('admin.store-company-chemical') }}", formData, loader);
        });

        // Utility: Trigger modal and set fields
        const triggerModal = (modalId, fields) => {
            const modal = document.querySelector(modalId);
            fields.forEach(({ name, value }) => {
                modal.querySelector(`input[name="${name}"]`).value = value;
            });
            const loader = modal.querySelector('#loader');
            loader.style.display = 'none';
            new bootstrap.Modal(modal).show();
        };

        // Utility: Handle check-in/check-out AJAX
        const handleCheckInOut = async (url, modalId, loaderSelector) => {
            const modal = document.querySelector(modalId);
            const loader = modal.querySelector(loaderSelector);
            loader.style.display = 'inline-block';

            const formData = new FormData(modal.querySelector('form'));
            if (!validateFields([
                { value: formData.get('checkIn_chemical_id') || formData.get('checkOut_chemical_id'), message: "Chemical ID field cannot be empty." },
                { value: formData.get('quantity'), message: "Quantity field cannot be empty." },
                { value: formData.get('date'), message: "Date field cannot be empty." }
            ])) {
                loader.style.display = 'none';
                return;
            }

            try {
                const result = await fetch_cycle('--Create Check In/Out', url, 'POST', formData);
                loader.style.display = 'none';
            } catch (error) {
                console.error(error);
                loader.style.display = 'none';
            }
        };

        // Chemical check-in
        document.querySelector('#btn-submit-check-in-chemical').addEventListener('click', () => {
            handleCheckInOut("{{ route('admin.save-company-chemical-check-in') }}", '#checkInChemicalModal', '#loader');
        });

        // Chemical check-out
        document.querySelector('#btn-submit-check-out-chemical').addEventListener('click', () => {
            handleCheckInOut("{{ route('admin.save-company-chemical-check-out') }}", '#checkOutChemicalModal', '#loader');
        });

        // Modal triggers for check-in/check-out
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
    </script>
    <!-- end chemical management -->

    <!-- operations -->
    <script>
        /**
         * Operations Management Script
         *
         * Handles:
         * - Submission and update of operation types and categories.
         * - Submission of equipment types and equipment logs.
         * - Submission of operation logs and quality control records.
         * - Submission of waste items.
         *
         * Features:
         * - Updates corresponding tables on success.
         * - Uses fetch_cycle for AJAX requests.
         * - Provides utility functions for editing and confirming deletions.
         */

        // Store Operation Type
        document.querySelector('#company_operation_type_form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-operation-type') }}";

            try {
                const result = await fetch_cycle('--Store Operation Type', url, 'POST', formData);
                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-operation-types tbody');
                    tableBody.innerHTML = result.operation_types.map(type => `
                        <tr>
                            <td>${type.name}</td>
                            <td>${type.description || ""}</td>
                            <td>${type.sequence_order || ""}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="editOperationType(${type.operation_type_id}, '${type.name}', '${type.description || ""}', ${type.sequence_order || 0})">
                                        <i class="las la-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="confirmTypeDeletion(${type.operation_type_id}, '${type.name}')">
                                        <i class="las la-trash-alt"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error storing operation type:', error);
            }
        });

        // Edit Operation Type
        document.querySelector('#edit_operation_type_form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.update-operation-type') }}";

            try {
                const result = await fetch_cycle('--Update Operation Type', url, 'POST', formData);
                if (result.status === 'success') {
                    const updatedData = result.operation_types.map(
                        type => new OperationTypeObject(type.operation_type_id, type.name, type.description, type.sequence_order)
                    );

                    table.clear();
                    table.rows.add(updatedData).draw();
                }
            } catch (error) {
                console.error('Error updating operation type:', error);
            }
        });

        // Store Equipment Type
        document.querySelector('#equipment_type_form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-equipment-type') }}";

            try {
                const result = await fetch_cycle('--Store Equipment Type', url, 'POST', formData);
                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-equipment-types tbody');
                    tableBody.innerHTML = result.equipment_types.map(type => `
                        <tr>
                            <td>${type.name}</td>
                            <td>${type.description || ""}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-sm btn-primary me-2">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error storing equipment type:', error);
            }
        });

        // Store Equipment Log
        document.querySelector('#industrial-equipment-log-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-equipment-log') }}";

            try {
                const result = await fetch_cycle('--Store Equipment Log', url, 'POST', formData);
                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-equipment-logs tbody');
                    tableBody.innerHTML = result.equipment_logs.map(log => `
                        <tr>
                            <td>${log.equipment_name}</td>
                            <td>${log.equipment_type?.name || 'N/A'}</td>
                            <td>${log.equipment_capacity}</td>
                            <td>${log.status}</td>
                            <td>${log.date_added}</td>
                            <td class="text-end">
                                <div class="dropdown d-inline-block">
                                    <a class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button">
                                        <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Update</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error storing equipment log:', error);
            }
        });

        // Store Operation Category
        document.querySelector('#operation_category_form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-operation-category') }}";

            try {
                const result = await fetch_cycle('--Store Operation Category', url, 'POST', formData);
                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-operation-categories tbody');
                    tableBody.innerHTML = result.operation_categories.map(category => `
                        <tr>
                            <td>${category.name}</td>
                            <td>${category.description || ""}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="editOperationCategory(${category.operation_category_id}, '${category.name}', '${category.description || ""}')">
                                        <i class="las la-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="confirmCategoryDeletion(${category.operation_category_id}, '${category.name}')">
                                        <i class="las la-trash-alt"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error storing operation category:', error);
            }
        });

        // Edit Operation Category
        document.querySelector('#edit_operation_category_form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.update-operation-category') }}";

            try {
                const result = await fetch_cycle('--Update Operation Category', url, 'POST', formData);
                if (result.status === 'success') {
                    const updatedCategories = result.operation_categories.map(
                        category => new OperationCategoryObject(category.operation_category_id, category.name, category.description)
                    );

                    categoriesTable.clear();
                    categoriesTable.rows.add(updatedCategories).draw();
                }
            } catch (error) {
                console.error('Error updating operation category:', error);
            }
        });

        // Store Operation Log
        document.querySelector('#operations-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-operation') }}";

            try {
                const result = await fetch_cycle('--Store Operation Log', url, 'POST', formData);
                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-operations-log tbody');
                    tableBody.innerHTML = result.operation_logs.map(log => `
                        <tr>
                            <td>${log.operation_name}</td>
                            <td>${log.operation_code || ''}</td>
                            <td>${log.operation_type || ''}</td>
                            <td>${log.operation_category || ''}</td>
                            <td>${log.operation_unit || ''}</td>
                            <td>${log.expected_waste_per_operation || ''}</td>
                            <td>${log.expected_water_usage_per_operation || ''}</td>
                            <td>${log.expected_unit_produced_for_goods || ''}</td>
                            <td>${log.calendar_year_name || ''}</td>
                            <td>${log.start_date || ''}</td>
                            <td>${log.end_date || ''}</td>
                            <td>
                                <span class="badge bg-${log.status === 'active' ? 'success' : 'danger'}">
                                    ${log.status}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="dropdown d-inline-block">
                                    <a class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button">
                                        <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" onclick="triggerUpdateOperation('${log.company_operation_id}')">Update</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error storing operation log:', error);
            }
        });

        // Store Quality Control
        document.querySelector('#quality-control-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-quality-control') }}";

            try {
                const result = await fetch_cycle('--Store Quality Control', url, 'POST', formData);
                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-quality-control tbody');
                    tableBody.innerHTML = result.quality_controls_record.map(control => `
                        <tr>
                            <td>${control.quality_metric}</td>
                            <td>${control.acceptable_range}</td>
                            <td>${control.measurement_frequency}</td>
                            <td>${control.responsible_person}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-sm btn-primary me-2">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error storing quality control:', error);
            }
        });

        // Store Waste Item
        document.querySelector('#submit-waste-item').addEventListener('click', async function () {
            const form = document.querySelector('#waste-item-form');
            const formData = new FormData(form);
            const url = "{{ route('admin.store-waste') }}";

            try {
                const result = await fetch_cycle('--Store Waste Item', url, 'POST', formData);
                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-waste-items tbody');
                    tableBody.innerHTML = result.waste_items.map(item => `
                        <tr>
                            <td>${item.waste_name}</td>
                            <td>${item.waste_type}</td>
                            <td>${item.unit}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-sm btn-primary me-2">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            } catch (error) {
                console.error('Error storing waste item:', error);
            }
        });
    </script>
    <!-- operations -->

    <!-- Production Log -->
    <script>
        /**
         * Production Log Section
         * 
         * This section handles the dynamic addition of materials, chemicals, and products
         * for the production log form. It also manages the submission of the production log
         * and updates the production log table accordingly.
         * 
         * - initializeMaterialSection: Handles dynamic material rows.
         * - initializeChemicalSection: Handles dynamic chemical rows.
         * - initializeProductSection: Handles dynamic product rows.
         * - Form submission: Handles production log form submission and table update.
         */
        document.addEventListener('DOMContentLoaded', function () {
            /**
             * Initialize the "Add More Material" functionality for a section.
             * @param {string} containerSelector - Selector for the parent container where rows will be added.
             * @param {string} buttonSelector - Selector for the button to trigger adding a new row.
             */
            function initializeMaterialSection(containerSelector, buttonSelector) {
                const container = document.querySelector(containerSelector);
                const addButton = document.querySelector(buttonSelector);
                let materialIndex = 1;

                async function createMaterialRow(index) {
                    const newRow = document.createElement('div');
                    newRow.classList.add('row', 'g-2', 'align-items-end', 'mb-3');
                    newRow.innerHTML = `
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material-used-${index}">Material Needed</label>
                                <select id="material-used-${index}" class="form-select material-select" name="material_used[${index}][material_id]" required>
                                    <option value="" selected disabled>Select Material</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="material-quantity-${index}">Quantity</label>
                                <input id="material-quantity-${index}" type="number" class="form-control" name="material_used[${index}][quantity]" placeholder="Quantity" min="0" step="any" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="expected_unit" class="form-label fw-semibold">Unit</label>
                            <input type="text" class="form-control" id="expected_unit" name="material_used[${index}][unit]" placeholder="Unit">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-material-quantity">Remove</button>
                        </div>
                    `;
                    const materialSelect = newRow.querySelector(`#material-used-${index}`);
                    const companyID = "{{ json_encode($company->company_id) }}";
                    const url = `/admin/get-materials/${companyID}`;
                    try {
                        const data = await fetchFieldInput(url);
                        data.company_materials.forEach(material => {
                            if (material.material) {
                                const option = document.createElement('option');
                                option.value = material.materialID;
                                option.textContent = material.material.material;
                                materialSelect.appendChild(option);
                            }
                        });
                    } catch (error) {
                        console.error("Error fetching materials:", error);
                        alert("Failed to load materials. Please try again.");
                    }
                    return newRow;
                }

                addButton.addEventListener('click', async function () {
                    const newRow = await createMaterialRow(materialIndex);
                    container.appendChild(newRow);
                    materialIndex++;
                });

                container.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-material-quantity')) {
                        e.target.closest('.row').remove();
                    }
                });
            }

            initializeMaterialSection('.material-quantity-used-container-production-log', '.add-more-material-used-production-log');
            initializeMaterialSection('.material-quantity-used-container-annual-operation-activity', '.add-more-material-quantity-annual-operation-activity');
        });

        document.addEventListener('DOMContentLoaded', function () {
            /**
             * Initialize the "Add More Chemical" functionality for a section.
             * @param {string} containerSelector - Selector for the parent container where rows will be added.
             * @param {string} buttonSelector - Selector for the button to trigger adding a new row.
             */
            function initializeChemicalSection(containerSelector, buttonSelector) {
                const container = document.querySelector(containerSelector);
                const addButton = document.querySelector(buttonSelector);
                let chemicalIndex = 1;

                async function createChemicalRow(index) {
                    const newRow = document.createElement('div');
                    newRow.classList.add('row', 'g-2', 'align-items-end', 'mb-3');
                    newRow.innerHTML = `
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="chemical-used-${index}">Chemical Needed</label>
                                <select id="chemical-used-${index}" class="form-select chemical-select" name="chemical_used[${index}][chemical_id]" required>
                                    <option value="" selected disabled>Select Chemical</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="chemical-quantity-${index}">Quantity</label>
                                <input id="chemical-quantity-${index}" type="number" class="form-control" name="chemical_used[${index}][quantity]" placeholder="Used Quantity" min="0" step="any" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="chemical_unit" class="form-label fw-semibold">Unit</label>
                            <input type="text" class="form-control" id="chemical_unit" name="chemical_used[${index}][unit]" placeholder="Unit">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-chemical-quantity">Remove</button>
                        </div>
                    `;
                    const chemicalSelect = newRow.querySelector(`#chemical-used-${index}`);
                    const companyID = "{{ json_encode($company->company_id) }}";
                    const url = `/admin/get-chemicals/${companyID}`;
                    try {
                        const data = await fetchFieldInput(url);
                        data.company_chemicals.forEach(chemical => {
                            if (chemical.chemical) {
                                const option = document.createElement('option');
                                option.value = chemical.chemicalID;
                                option.textContent = chemical.chemical.name;
                                chemicalSelect.appendChild(option);
                            }
                        });
                    } catch (error) {
                        console.error("Error fetching chemicals:", error);
                        alert("Failed to load chemicals. Please try again.");
                    }
                    return newRow;
                }

                addButton.addEventListener('click', async function () {
                    const newRow = await createChemicalRow(chemicalIndex);
                    container.appendChild(newRow);
                    chemicalIndex++;
                });

                container.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-chemical-quantity')) {
                        e.target.closest('.row').remove();
                    }
                });
            }

            initializeChemicalSection('.chemical-quantity-container-production-log', '.add-more-chemical-used-production-log');
            initializeChemicalSection('.chemical-quantity-container-annual-operation-activity', '.add-more-chemical-quantity-annual-operation-activity');
        });

        document.addEventListener('DOMContentLoaded', function () {
            /**
             * Initialize the "Add More Product" functionality for a section.
             * @param {string} containerSelector - Selector for the parent container where rows will be added.
             * @param {string} buttonSelector - Selector for the button to trigger adding a new row.
             */
            function initializeProductSection(containerSelector, buttonSelector) {
                const container = document.querySelector(containerSelector);
                const addButton = document.querySelector(buttonSelector);
                let productIndex = 1;

                async function createProductRow(index) {
                    const newRow = document.createElement('div');
                    newRow.classList.add('row', 'g-2', 'align-items-end', 'mb-3');
                    newRow.innerHTML = `
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="produced-product-${index}">Produced Product</label>
                                <select id="produced-product-${index}" class="form-select product-select" name="product_produced[${index}][product_id]" required>
                                    <option value="" selected disabled>Select Product</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="produced-quantity-${index}">Quantity Produced</label>
                                <input id="produced-quantity-${index}" type="number" class="form-control" name="product_produced[${index}][quantity]" placeholder="Produced Quantity" min="0" step="any" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-product-quantity">Remove</button>
                        </div>
                    `;
                    const productSelect = newRow.querySelector(`#produced-product-${index}`);
                    const companyID = "{{ json_encode($company->company_id) }}";
                    const url = `/admin/get-product/${companyID}`;
                    try {
                        const data = await fetchFieldInput(url);
                        data.products.forEach(product => {
                            const option = document.createElement('option');
                            option.value = product.product_id;
                            option.textContent = product.name;
                            productSelect.appendChild(option);
                        });
                    } catch (error) {
                        console.error("Error fetching products:", error);
                    }
                    return newRow;
                }

                addButton.addEventListener('click', async function () {
                    const newRow = await createProductRow(productIndex);
                    container.appendChild(newRow);
                    productIndex++;
                });

                container.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-product-quantity')) {
                        e.target.closest('.row').remove();
                    }
                });
            }

            initializeProductSection('.operation-log-product-quantity-container', '.add-more-operation-log-product-quantity-operation');
        });

        /**
         * Waste Section for Production Log
         * 
         * This section handles the dynamic addition of waste items for the production log form.
         * It allows users to add/remove waste rows and select waste types, enter quantities, and units.
         */
        document.addEventListener('DOMContentLoaded', function () {
            /**
             * Initialize the "Add More Waste" functionality for a section.
             * @param {string} containerSelector - Selector for the parent container where rows will be added.
             * @param {string} buttonSelector - Selector for the button to trigger adding a new row.
             */
            function initializeWasteSection(containerSelector, buttonSelector) {
                const container = document.querySelector(containerSelector);
                const addButton = document.querySelector(buttonSelector);
                let wasteIndex = 1;

                async function createWasteRow(index) {
                    const newRow = document.createElement('div');
                    newRow.classList.add('row', 'g-2', 'align-items-end', 'mb-3');
                    newRow.innerHTML = `
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="waste-type-${index}">Waste Type</label>
                                <select id="waste-type-${index}" class="form-select waste-select" name="waste_generated[${index}][waste_id]" required>
                                    <option value="" selected disabled>Select Waste</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="waste-quantity-${index}">Quantity</label>
                                <input id="waste-quantity-${index}" type="number" class="form-control" name="waste_generated[${index}][quantity]" placeholder="Quantity" min="0" step="any" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="waste_unit" class="form-label fw-semibold">Unit</label>
                            <input type="text" class="form-control" id="waste_unit" name="waste_generated[${index}][unit]" placeholder="Unit">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-waste-quantity">Remove</button>
                        </div>
                    `;
                    const wasteSelect = newRow.querySelector(`#waste-type-${index}`);
                    const companyID = "{{ json_encode($company->company_id) }}";
                    const url = `/admin/get-waste/${companyID}`;
                    try {
                        const data = await fetchFieldInput(url);
                        if (data.company_wastes && Array.isArray(data.company_wastes)) {
                            data.company_wastes.forEach(waste => {
                                const option = document.createElement('option');
                                option.value = waste.company_waste_id;
                                option.textContent = waste.waste_name;
                                wasteSelect.appendChild(option);
                            });
                        }
                    } catch (error) {
                        console.error("Error fetching wastes:", error);
                        alert("Failed to load wastes. Please try again.");
                    }
                    return newRow;
                }

                addButton.addEventListener('click', async function () {
                    const newRow = await createWasteRow(wasteIndex);
                    container.appendChild(newRow);
                    wasteIndex++;
                });

                container.addEventListener('click', function (e) {
                    if (e.target.classList.contains('remove-waste-quantity')) {
                        e.target.closest('.row').remove();
                    }
                });
            }

            // Example usage for production log waste section
            initializeWasteSection('.annual-operation-activity-waste-quantity-container', '.add-more-annual-activity-waste-quantity-operation');
        });

        document.querySelector('#production-log-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-production-log') }}";

        await fetch_cycle('--Store Production Log', url, 'POST', formData).then(result => {
                if (result.status === 'success') {
                    const tableBody = document.querySelector('#tbl-production-logs tbody');
                    tableBody.innerHTML = result.production_logs.map(log => `
                        <tr>
                            <td>${log.production_title}</td>
                            <td>${log.operation?.operation_name || 'N/A'}</td>
                            <td>${log.material?.material_name || 'N/A'} (${log.quantity_used})</td>
                            <td>${log.chemical?.name || 'N/A'} (${log.chemical?.volume_used || 0} Liters)</td>
                            <td>${log.amount_of_water_used} Liters</td>
                            <td>${log.product?.name || 'N/A'} (${log.product?.quantity_produced || 0} Produced, ${log.product?.quantity_defected || 0} Defected)</td>
                            <td>${new Date(log.production_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                            <td>
                                <span class="badge bg-${log.production_status === 'completed' ? 'success' : (log.production_status === 'ongoing' ? 'primary' : 'danger')}">
                                    ${log.production_status.charAt(0).toUpperCase() + log.production_status.slice(1)}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="editProductionLog(${log.id})">Edit</button>
                                    <button class="btn btn-outline-danger btn-sm" onclick="deleteProductionLog(${log.id})">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                }
            });
        });
    </script>
    <!-- End Production Log -->

    <!-- Calendar Year Management Script -->
    <script>
        // Calendar Year Management
        // This section handles the submission and update of the company calendar year table.
        document.querySelector('#calendar_year_form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-calendar-year') }}";

            try {
                const result = await fetch_cycle('--Store Calendar Year', url, 'POST', formData);
                if (result.status === 'success') {
                    updateCalendarYearTable(result.calendar_years);
                }
            } catch (error) {
                console.error('Error storing calendar year:', error);
            }
        });

        /**
         * Updates the Calendar Year table with new data.
         * @param {Array} calendarYears - Array of calendar year objects.
         */
        function updateCalendarYearTable(calendarYears) {
            const tableBody = document.querySelector('#calendar_year_table tbody');
            tableBody.innerHTML = calendarYears.map(year => `
                <tr>
                    <td>${year.name || ''}</td>
                    <td>${year.start_date || ''}</td>
                    <td>${year.end_date || ''}</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-primary" onclick="editCalendar('${year.calendar_year_id}')">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteCalendar('${year.calendar_year_id}')">Delete</button>
                    </td>
                </tr>
            `).join('');
        }
    </script>
    <!-- End Calendar Year Management Script -->

    <!-- Product Management Script -->
    <script>
        /**
         * (No code provided in the selection.)
         *
         * Please provide the code you want documented.
         */
        // Utility function to update table content
        function updateTableContent(tableSelector, data, rowTemplate) {
            const tableBody = document.querySelector(tableSelector);
            tableBody.innerHTML = data.map(rowTemplate).join('');
        }

        // Store Product Category
        document.querySelector('#add-product-category-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-product-category') }}";

            try {
                const result = await fetch_cycle('--Store Product Category', url, 'POST', formData);
                if (result.status === 'success') {
                    updateTableContent('#tbl-product-categories tbody', result.product_categories, category => `
                        <tr>
                            <td>${category.name}</td>
                            <td>${category.description}</td>
                            <td class="text-end">
                                <div class="dropdown d-inline-block">
                                    <a class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button">
                                        <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Update</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `);
                }
            } catch (error) {
                console.error('Error storing product category:', error);
            }
        });

        // Edit Product Category
        document.querySelector('#edit-product-category-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.update-product-category') }}";

            try {
                const result = await fetch_cycle('--Update Product Category', url, 'POST', formData);
                if (result.status === 'success') {
                    updateTableContent('#tbl-product-categories tbody', result.product_categories, category => `
                        <tr>
                            <td>${category.name}</td>
                            <td>${category.description}</td>
                            <td class="text-end">
                                <div class="dropdown d-inline-block">
                                    <a class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button">
                                        <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Update</a>
                                        <a class="dropdown-item" href="#">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `);
                }
            } catch (error) {
                console.error('Error updating product category:', error);
            }
        });

        // Store Product
        document.querySelector('#add-product-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-product') }}";

            try {
                const result = await fetch_cycle('--Store Product', url, 'POST', formData);
                if (result.status === 'success') {
                    updateTableContent('#tbl-products tbody', result.products, product => `
                        <tr>
                            <td>${product.name}</td>
                            <td>${product.product_category.name}</td>
                            <td>${product.currency} ${product.price}</td>
                            <td>${product.quantity_per_unit}</td>
                            <td>${product.unit}</td>
                            <td class="text-end">
                                <button class="btn btn-primary btn-sm">Edit</button>
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    `);
                }
            } catch (error) {
                console.error('Error storing product:', error);
            }
        });
    </script>
    <!-- Product Management Script -->
     
    <!-- Annual Operation log -->
    <script>
        // Annual Operation Metadata Management
        // This section handles fetching, displaying, and managing annual operation metadata logs.
        let annualOperationMetadataTable = $('#tbl-annual-operations-metadata-log').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [5] } // Disable sorting on the "Action" column
            ],
            data: [], // Start with an empty data array
            columns: [
                { data: 'operation_name', title: 'Operation Name' },
                { data: 'calendarYear', title: 'Calendar Year' },
                { data: 'expected_operations_per_year', title: 'Expected Operations (Per Year)' },
                {
                    data: 'prepared_by',
                    title: 'Prepared By',
                    render: function(data, type, row) {
                        if (type === 'display' && data !== 'N/A') {
                            return data; // HTML content for rendering Prepared By column
                        }
                        return 'N/A'; // Fallback
                    }
                },
                { data: 'status', title: 'Operation Status' },
                {
                    data: null,
                    title: 'Actions',
                    render: function (data, type, row) {
                        return `
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-primary btn-sm" onclick="addActivityAnnualOperationLog(${row.id}, '${row.operation_name}')">
                                    <i class="fas fa-tasks"></i> Manage Activities
                                </button>
                                <button class="btn btn-success btn-sm" onclick="editAnnualOperationLog(${row.id})">
                                    <i class="fas fa-edit"></i> Edit Log
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteAnnualOperationLog(${row.id})">
                                    <i class="fas fa-trash-alt"></i> Delete Log
                                </button>
                            </div>`;
                    }
                }
            ]
        });

        document.querySelector('#annualOperationsLogCollapse').addEventListener('shown.bs.collapse', async () => {
            // Initialize DataTable for Annual Operations Metadata Log
            console.log("Annual Operations Metadata Log initialized");
            // const annualOperationMetadataTable = $('#tbl-annual-operations-metadata-log').DataTable();
            
            const companyId = "{{ json_encode($company->company_id) }}";
            console.log("Company ID:", companyId);
            
            const url = `/admin/metadata/company/${companyId}`;
            const spinner = document.getElementById('loading-spinner');

            showElement(spinner);

            try {
                const data = await fetchFieldInput(url);

                if (data.status === "success" && Array.isArray(data.annual_operation_metadata)) {
                    const logs = data.annual_operation_metadata.map(log => {
                        let preparedBy = "N/A";

                        // Attempt to parse the PreparedBy JSON string
                        try {
                            const parsedPreparedBy = JSON.parse(log.PreparedBy);
                            if (Array.isArray(parsedPreparedBy) && parsedPreparedBy.length > 0) {
                                preparedBy = parsedPreparedBy.map(prep => `
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <img src="${prep.avatar}" alt="${prep.name}" style="width: 30px; height: 30px; border-radius: 50%;" />
                                        <div>
                                            <strong>${prep.name}</strong><br />
                                            <small>${prep.email}</small>
                                        </div>
                                    </div>
                                `).join("<br />");
                            }
                        } catch (e) {
                            console.error("Error parsing PreparedBy field:", e);
                        }

                        return {
                            operation_name: log.OperationName || "N/A",
                            calendarYear: log.calendar_year?.name || "N/A",
                            expected_operations_per_year: log.annual_no_of_operation || "N/A",
                            prepared_by: preparedBy,
                            status: log.Status || "N/A",
                            id: log.annual_op_metadata_ID || "N/A"
                        };
                    });

                    // Populate the table with the fetched data
                    annualOperationMetadataTable.clear();
                    annualOperationMetadataTable.rows.add(logs).draw();

                    // Enable rendering HTML in the 'Prepared By' column
                    annualOperationMetadataTable.columns().every(function () {
                        this.render(function (data, type, row) {
                            if (type === 'display') {
                                return data;
                            }
                            return data;
                        });
                    });
                } else {
                    displayMessage('warning', 'No annual operation logs found or invalid data structure.');
                }
            } catch (error) {
                console.error("Error fetching annual operation logs:", error);
                displayMessage('danger', 'An error occurred while fetching annual operation logs. Please try again.');
            } finally {
                hideElement(spinner);
            }

        });

        // Form submission for storing annual operation log
        document.querySelector('#annual-operations-log-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            let manager = manager_4.getSelectedUserIds();
            if (manager.length === 0) {
                displayMessage('warning', 'Please select at least one manager.');
                return;
            }

            manager.forEach(id => {
                formData.append('prepared_by_ids[]', id);
            });

            const url = "{{ route('admin.store-annual-operation-metadata') }}";

            try {
                const result = await fetch_cycle('--Store Annual Operation Log', url, 'POST', formData);
                if (result.status === 'success') {
                    console.log("Annual operation log stored successfully:", result.annual_operation_metadatas);
                    updateAnnualOperationLogTable(result.annual_operation_metadatas);
                }
            } catch (error) {
                console.log('Error storing annual operation log:', error);
            }
        });
        /**
         * Updates the Annual Operation Log table with new data.
         * @param {Array} logs - Array of annual operation logs.
         */
        function updateAnnualOperationLogTable(logs) {
            if (Array.isArray(logs)) {
                // Transform logs to match DataTable columns
                const formattedLogs = logs.map(log => {
                let preparedBy = "N/A";
                try {
                    const parsedPreparedBy = JSON.parse(log.PreparedBy);
                    if (Array.isArray(parsedPreparedBy) && parsedPreparedBy.length > 0) {
                    preparedBy = parsedPreparedBy.map(prep => `
                        <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="${prep.avatar}" alt="${prep.name}" style="width: 30px; height: 30px; border-radius: 50%;" />
                        <div>
                            <strong>${prep.name}</strong><br />
                            <small>${prep.email}</small>
                        </div>
                        </div>
                    `).join("<br />");
                    }
                } catch (e) {
                    // ignore parse error, fallback to N/A
                }
                return {
                    operation_name: log.OperationName || "N/A",
                    calendarYear: log.calendar_year?.name || "N/A",
                    expected_operations_per_year: log.annual_no_of_operation || "N/A",
                    prepared_by: preparedBy,
                    status: log.Status || "N/A",
                    id: log.annual_op_metadata_ID || "N/A"
                };
                });
                annualOperationMetadataTable.clear().rows.add(formattedLogs).draw();
            }
        }

        /**
         * Adds an activity for the specified metadata ID.
         * @param {number} metadataId - The ID of the metadata to add an activity for.
         */
        function addActivityAnnualOperationLog(metadataId, metadataName) {
            console.log("Adding activity for metadata ID:", metadataId);

            const form = document.querySelector('form#annual-operations-activity-form');
            document.getElementById('annualOperationsActivityModalLabel').textContent = `Annual Operations Activities for Annual Operation Metadata: ${metadataName}`;
            if (!form) {
                console.log('Form with ID "annual-operations-activity-form" not found.');
                return;
            }

            // Reset the form and populate default options
            form.reset();

            // Set the metadata ID in the form
            const metadataInput = form.querySelector('input[name="annual_op_metadata_ID"]');
            if (metadataInput) {
                metadataInput.value = metadataId;
            } else {
                console.log('Input field "annual_op_metadata_ID" not found in the form.');
            }

            // Show the modal for adding activities
            const modalElement = document.getElementById('annualOperationsActivityModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            } else {
                console.log('Modal with ID "annualOperationsActivityModal" not found.');
            }
        }

        // annual operation activity
        let annualOperationsActivityTable = $('#tbl-annual-operations-activity').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            responsive: true,
            destroy: true,
            columnDefs: [
                { orderable: false, targets: [6] } // Disable sorting on the "Action" column
            ],
            data: [],
            columns: [
                { data: 'activity_name', title: 'Activity Name' },
                { data: 'operation_name', title: 'Operation Name' },
                { data: 'start_date', title: 'Start Date' },
                { data: 'end_date', title: 'End Date' },
                { data: 'priority', title: 'Priority' },
                { data: 'tags', title: 'Tags' },
                {
                    data: null,
                    title: 'Actions',
                    render: function (data, type, row) {
                        return `
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-primary btn-sm" onclick="editActivity(${row.id})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteActivity(${row.id})">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>`;
                    }
                }
            ]
        });
        // Annual Operations Activity Modal: Fetch and display activities for the selected annual operation metadata
        // This section listens for the modal to be shown, then fetches activities and populates the DataTable.
        // Listen for when the Annual Operations Activity Modal is shown
        document.getElementById('annualOperationsActivityModal').addEventListener('shown.bs.modal', async function () {
            const metadataId = document.querySelector('input[name="annual_op_metadata_ID"]').value;
            if (!metadataId) return;

            const url = `/admin/metadata/activities/${metadataId}`;
            const spinner = document.getElementById('loading-spinner');
            showElement(spinner);

            try {
                const data = await fetchFieldInput(url);
                if (data.status === "success" && Array.isArray(data.activities)) {
                    const activities = data.activities.map(activity => ({
                        activity_name: activity.activity_name || "N/A",
                        operation_name: activity.operation?.operation_name || "N/A",
                        start_date: activity.start_date ? new Date(activity.start_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                        end_date: activity.end_date ? new Date(activity.end_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                        priority: activity.priority || "N/A",
                        tags: activity.tags || "N/A",
                        id: activity.id || "N/A"
                    }));

                    annualOperationsActivityTable.clear().rows.add(activities).draw();
                } else {
                    displayMessage('warning', 'No activities found for this metadata.');
                    annualOperationsActivityTable.clear().draw();
                }
            } catch (error) {
                console.error("Error fetching activities:", error);
                displayMessage('danger', 'An error occurred while fetching activities. Please try again.');
                annualOperationsActivityTable.clear().draw();
            } finally {
                hideElement(spinner);
            }
        });
        // Form submission for adding activities
        // This section listens for the form submission, collects data, and sends it to the server.
        // Form submission for adding activities
        document.querySelector('#annual-operations-activity-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            console.log('====================================');
            console.log('Annual Operations Activity Form submitted');
            console.log('====================================');
            const formData = new FormData(this);
            const url = "{{ route('admin.store-activity') }}";

            // Show loading spinner
            const spinner = document.getElementById('loading-spinner');
            showElement(spinner);
            // Store the activity data
            try {
                const result = await fetch_cycle('--Store Annual Operation Activity', url, 'POST', formData);
                if (result.status === 'success') {
                    if (Array.isArray(result.activities)) {
                        const activities = result.activities.map(activity => ({
                            activity_name: activity.activity_name || "N/A",
                            operation_name: activity.operation?.operation_name || "N/A",
                            start_date: activity.start_date ? new Date(activity.start_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                            end_date: activity.end_date ? new Date(activity.end_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                            priority: activity.priority || "N/A",
                            tags: activity.tags || "N/A",
                            id: activity.id || "N/A"
                        }));
                        annualOperationsActivityTable.clear().rows.add(activities).draw();
                    }
                }
            } catch (error) {
                console.error('Error storing annual operation activity:', error);
            }
        });

    </script>
    <!-- Annual Operation log -->

    <!-- Batch tracking -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Batch Tracking Table ---
            const batchTrackingTable = $('#tbl-batch-tracking').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                destroy: true,
                columnDefs: [{ orderable: false, targets: [5] }],
                data: [],
                columns: [
                    { data: 'batch_name', title: 'Batch Name' },
                    { data: 'product_name', title: 'Product Name' },
                    { data: 'start_date', title: 'Start Date' },
                    { data: 'end_date', title: 'End Date' },
                    {
                        data: 'status',
                        title: 'Status',
                        render: function (data, type) {
                            if (type === 'display') {
                                let badgeClass = 'secondary';
                                let label = data || 'N/A';
                                if (typeof data === 'string') {
                                    switch (data.toLowerCase()) {
                                        case 'completed': badgeClass = 'success'; break;
                                        case 'pending': badgeClass = 'warning'; break;
                                        case 'rejected': badgeClass = 'dark'; break;
                                    }
                                }
                                return `<span class="badge bg-${badgeClass}">${label.charAt(0).toUpperCase() + label.slice(1)}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: null,
                        title: 'Actions',
                        render: function (data, type, row) {
                            return `
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-info btn-sm setup-production-btn">
                                        <i class="fas fa-cogs"></i> Setup Production Process
                                    </button>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </div>`;
                        }
                    }
                ]
            });

            // --- Fetch Batch Tracking Data ---
            document.getElementById('batchTrackingCollapse').addEventListener('shown.bs.collapse', async () => {
                const companyId = "{{ json_encode($company->company_id) }}";
                const url = `/admin/batch-tracking/company/${companyId}`;
                const spinner = document.getElementById('loading-spinner');
                showElement(spinner);

                try {
                    const data = await fetchFieldInput(url);
                    if (data.status === "success" && Array.isArray(data.production_batch_tracking)) {
                        const batchTrackingData = data.production_batch_tracking.map(batch => ({
                            batch_name: batch.batch_name || "N/A",
                            product_name: batch.product?.name || "N/A",
                            start_date: batch.start_date ? new Date(batch.start_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                            end_date: batch.end_date ? new Date(batch.end_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                            status: batch.status || "N/A",
                            batch_id: batch.batch_id || "N/A"
                        }));
                        batchTrackingTable.clear().rows.add(batchTrackingData).draw();
                    } else {
                        displayMessage('warning', 'No batch tracking data found or invalid data structure.');
                    }
                } catch (error) {
                    console.error("Error fetching batch tracking data:", error);
                    displayMessage('danger', 'An error occurred while fetching batch tracking data. Please try again.');
                } finally {
                    hideElement(spinner);
                }
            });

            // Make toggleBatchTrackingForm globally accessible
            window.toggleBatchTrackingForm = function () {
                const form = document.getElementById('batch-tracking-form-container');
                form.classList.toggle('d-none');
                if (!form.classList.contains('d-none')) {
                    form.scrollIntoView({ behavior: 'smooth' });
                }
            };

            // --- Add Batch Tracking ---
            document.querySelector('#batch-tracking-form').addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const url = "{{ route('admin.store-batch-tracking') }}";
                try {
                    const result = await fetch_cycle('--Store Batch Tracking', url, 'POST', formData);
                    if (result.status === 'success') {
                        const batch = result.productionBatchTracking;
                        const newRow = {
                            batch_name: batch.batch_name || "N/A",
                            product_name: batch.product?.name || "N/A",
                            start_date: batch.start_date ? new Date(batch.start_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                            end_date: batch.end_date ? new Date(batch.end_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                            status: batch.status || "N/A",
                            batch_id: batch.batch_id || "N/A"
                        };
                        batchTrackingTable.row.add(newRow).draw(false);
                    }
                } catch (error) {
                    console.error('Error storing batch tracking:', error);
                }
            });

            // --- Setup Production Process Modal ---
            $(document).on('click', '.setup-production-btn', function () {
                const rowData = batchTrackingTable.row($(this).closest('tr')).data();
                const batchId = rowData.batch_id || '';
                const productionProcessForm = document.getElementById('production-process-form');
                if (productionProcessForm) productionProcessForm.reset();
                document.getElementById('batch_id').value = batchId;
                showProductionProcessModal(batchId);
            });

            // --- Show/Hide Production Process Form/Table ---
            function showProductionProcessTable() {
                
                document.getElementById('production-process-form-container').classList.add('d-none');
                document.getElementById('production-process-table-container').classList.remove('d-none');
            }
            function showProductionProcessForm() {
                document.getElementById('production-process-form-container').classList.remove('d-none');
                document.getElementById('production-process-table-container').classList.add('d-none');
            }
            $('#productionProcessModal').on('show.bs.modal', showProductionProcessTable);
            document.getElementById('show-production-process-form')?.addEventListener('click', showProductionProcessForm);
            document.getElementById('cancel-production-process-form')?.addEventListener('click', showProductionProcessTable);
            document.getElementById('close-production-process-form')?.addEventListener('click', showProductionProcessTable);

            // --- Production Process Table ---
            const productionProcessTable = $('#tbl-production-process').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                destroy: true,
                columnDefs: [{ orderable: false, targets: [5] }],
                data: [],
                columns: [
                    { data: 'workflow_name', title: 'Workflow' },
                    { data: 'start_date', title: 'Start Date' },
                    { data: 'end_date', title: 'End Date' },
                    { data: 'remarks', title: 'Remarks' },
                    {
                        data: 'status',
                        title: 'Status',
                        render: (data, type) => {
                            if (type === 'display') {
                                let badgeClass = 'secondary';
                                let label = data || 'N/A';
                                switch ((data || '').toLowerCase()) {
                                    case 'completed': badgeClass = 'success'; break;
                                    case 'pending': badgeClass = 'warning'; break;
                                    case 'rejected': badgeClass = 'dark'; break;
                                }
                                return `<span class="badge bg-${badgeClass}">${label.charAt(0).toUpperCase() + label.slice(1)}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: null,
                        title: 'Actions',
                        render: (data, type, row) => `
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="editProductionProcess(${row.process_id})">Edit</button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteProductionProcess(${row.process_id})">Delete</button>
                            </div>
                        `
                    }
                ]
            });

            // --- Fetch Production Process Table for Batch ---
            window.showProductionProcessModal = function (batchId) {
                const modalElement = document.getElementById('productionProcessModal');
                if (!modalElement) return;
                (async function populateProductionProcessTable() {
                    if (!batchId) return;
                    const url = `/admin/get-production-process/${batchId}`;
                    try {
                        const data = await fetchFieldInput(url);
                        if (data.status === "success" && Array.isArray(data.production_processes)) {
                            const processes = data.production_processes.map(proc => ({
                                workflow_name: (proc.workflow && proc.workflow.workflow_name) ? proc.workflow.workflow_name : 'N/A',
                                start_date: proc.start_time ? (() => { const d = new Date(proc.start_time); return isNaN(d) ? 'N/A' : `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`; })() : 'N/A',
                                end_date: proc.end_time ? (() => { const d = new Date(proc.end_time); return isNaN(d) ? 'N/A' : `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`; })() : 'N/A',
                                remarks: proc.remarks || '',
                                status: proc.status || 'N/A',
                                process_id: proc.process_id || ''
                            }));
                            productionProcessTable.clear().rows.add(processes).draw();
                        } else {
                            productionProcessTable.clear().draw();
                        }
                    } catch (error) {
                        console.error("Error fetching production process data:", error);
                        productionProcessTable.clear().draw();
                    } finally {
                        hideElement(document.getElementById('loading-spinner'));
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    }
                })();
            };

            // --- Production Process Form Submission ---
            document.getElementById('production-process-form').addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const url = "{{ route('admin.store-production-process') }}";
                try {
                    const result = await fetch_cycle('--Store Production Process', url, 'POST', formData);
                    if (result.status === 'success' && Array.isArray(result.production_processes)) {
                        const formatted = result.production_processes.map(proc => ({
                            workflow_name: proc.workflow?.workflow_name || 'N/A',
                            start_date: proc.start_time ? new Date(proc.start_time).toLocaleDateString('en-GB') : 'N/A',
                            end_date: proc.end_time ? new Date(proc.end_time).toLocaleDateString('en-GB') : 'N/A',
                            remarks: proc.remarks || '',
                            status: proc.status || 'N/A',
                            process_id: proc.process_id || ''
                        }));
                        productionProcessTable.clear().rows.add(formatted).draw();
                        // Hide form and show table
                        showProductionProcessTable();
                        // Optionally reset the form
                        this.reset();
                    }
                } catch (error) {
                    console.error('Error storing production process:', error);
                }
            });

            // Edit Production Process Modal Handler
            window.editProductionProcess = async function(id) {
                // Try to get the process data from the DataTable row
                const row = $(`button[onclick="editProductionProcess(${id})"]`).closest('tr');
                let process = productionProcessTable.row(row).data();

                // If not found, fetch from backend
                if (!process) {
                    try {
                        const response = await fetch(`/admin/production-process/${id}`);
                        if (response.ok) {
                            process = await response.json();
                        } else {
                            console.error('Failed to fetch production process:', response.statusText);
                            return;
                        }
                    } catch (error) {
                        console.error('Failed to fetch production process:', error);
                        return;
                    }
                }
                if (!process) return;

            

                // Populate form fields
                document.getElementById('edit_process_id').value = process.process_id || process.id || "";
                document.getElementById('edit_process_start_date').value = formatDateForInput(process.start_time || process.start_date);
                document.getElementById('edit_process_end_date').value = formatDateForInput(process.end_time || process.end_date);
                document.getElementById('edit_process_status').value = process.status || "";
                document.getElementById('edit_remarks').value = process.remarks || "";

                // Populate workflow select
                // Populate workflow select with the workflow directly from the DataTable row first, then fetch all others
                const workflowSelect = document.getElementById('edit_workflow');
                workflowSelect.innerHTML = '';

                // Get workflow from the current row (prefer direct row data)
                let currentWorkflowId = process.workflow_id || (process.workflow && (process.workflow.id || process.workflow.workflow_id)) || '';
                let currentWorkflowName = process.workflow_name || (process.workflow && (process.workflow.name || process.workflow.workflow_name)) || 'Select Workflow';

                // Add the workflow from the row as the first (selected) option
                if (currentWorkflowId) {
                    workflowSelect.appendChild(new Option(currentWorkflowName, currentWorkflowId, true, true));
                }

                // Fetch all workflows for the company and add the rest (avoid duplicate)
                try {
                    const companyId = "{{ json_encode($company->company_id) }}";
                    const url = `/admin/get-workflows/${companyId}`;
                    const data = await fetchFieldInput(url);

                    if (data.status === "success" && Array.isArray(data.workflows)) {
                    data.workflows.forEach(wf => {
                        const wfId = wf.workflow_id || wf.id;
                        // Avoid duplicate of the already-selected workflow
                        if (wfId != currentWorkflowId) {
                        workflowSelect.appendChild(new Option(wf.workflow_name || wf.name || '', wfId));
                        }
                    });
                    }
                } catch (error) {
                    // fallback: just show the current workflow
                    if (currentWorkflowId) {
                    workflowSelect.innerHTML = `<option value="${currentWorkflowId}" selected>${currentWorkflowName}</option>`;
                    }
                }

                // Show the modal
                new bootstrap.Modal(document.getElementById('editProductionProcessModal')).show();

                // Helper function to format date as YYYY-MM-DD for input fields
                function formatDateForInput(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    if (isNaN(date)) return '';
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }
            };

            // --- Update Production Process ---
            const editForm = document.querySelector('#edit-production-process-form');
                if (editForm) {
                    editForm.addEventListener('submit', async function (e) {
                        e.preventDefault();

                        const formData = new FormData(editForm);
                        const processId = formData.get('process_id');

                        if (!processId) {
                            console.error('No process_id provided for update.');
                            return;
                        }

                        try {
                            const url = `/admin/production-process/${processId}`;
                            const result = await fetch_cycle('--Update Production Process', url, 'POST', formData);

                            if (result.status === 'success' && Array.isArray(result.production_processes)) {
                                const formatted = result.production_processes.map(proc => ({
                                    workflow_name: proc.workflow?.workflow_name || 'N/A',
                                    start_date: proc.start_time ? new Date(proc.start_time).toLocaleDateString('en-GB') : 'N/A',
                                    end_date: proc.end_time ? new Date(proc.end_time).toLocaleDateString('en-GB') : 'N/A',
                                    remarks: proc.remarks || '',
                                    status: proc.status || 'N/A',
                                    process_id: proc.process_id || ''
                                }));

                                productionProcessTable.clear().rows.add(formatted).draw();

                                // Hide modal
                                const modal = bootstrap.Modal.getInstance(document.getElementById('editProductionProcessModal'));
                                if (modal) modal.hide();

                                // Optionally reset the form
                                editForm.reset();
                            } else {
                                console.error('Update failed:', result.errors || result.message);
                            }
                        } catch (error) {
                            console.error('Error updating production process:', error);
                        }
                    });
                }

            // --- Delete Production Process ---
            window.deleteProductionProcess = function (processId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete this production process?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        const url = `/admin/production-process/${processId}`;
                        try {
                            const response = await fetch(url, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            });
                            const data = await response.json();
                            if (data.status === 'success') {
                                productionProcessTable.row($(`button[onclick="deleteProductionProcess(${processId})"]`).parents('tr')).remove().draw();
                                Swal.fire('Deleted!', 'Production process has been deleted.', 'success');
                            } else {
                                Swal.fire('Error!', data.message || 'Failed to delete production process.', 'error');
                            }
                        } catch (error) {
                            Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                        }
                    }
                });
            };
        });
    </script>
    <!-- end store production process -->
    <!-- End Batch tracking -->
    <script>
        /**
         * Toggle the visibility of the annual operation activity form card.
         * Called when addActivityAnnualOperationLog is invoked.
         */
        function addActivityAnnualOperationLogForm() {
            // Show the activity form card
            const formCard = document.getElementById('annual-operation-activity-form-card');
            if (formCard) {
                formCard.classList.remove('d-none');
                formCard.scrollIntoView({ behavior: 'smooth' });
            }
            // Reset the form fields
            const form = document.getElementById('annual-operations-activity-form');
            if (form) {
                form.reset();
            }
        }
    </script>
    <script>
        class TaskTaggingSystem {
            constructor({ containerId, apiUrl }) {
                this.container = document.getElementById(containerId);
                this.apiUrl = apiUrl;
                this.tags = [];
                this.init();
            }

            // Initialize the tagging system
            init() {
                // Build the tag input UI
                this.container.innerHTML = `
                    <div class="tag-inline-container" style="display: flex; align-items: center; flex-wrap: wrap; padding: 0 10px;">
                        <div class="tags-display" style="display: flex; flex-wrap: wrap;"></div>
                        <input type="text" class="form-control border-0 shadow-none" autocomplete="off" placeholder="Add a task tag..." style="flex: 1; min-width: 120px;" />
                    </div>
                    <div class="task-suggestions"></div>
                `;

                this.tagsContainer = this.container.querySelector(".tags-display");
                this.inputField = this.container.querySelector("input[type='text']");
                this.suggestionsDiv = this.container.querySelector(".task-suggestions");

                this.bindEvents();
                this.renderTags();
            }


            // Fetch suggestions from API
            async fetchSuggestions(query) {
                try {
                    const response = await fetch(`${this.apiUrl}?query=${encodeURIComponent(query)}&type=task`);
                    if (!response.ok) throw new Error("Failed to fetch task suggestions");
                    return await response.json();
                } catch (error) {
                    console.error("Error fetching task suggestions:", error);
                    return [];
                }
            }

            // Add a task tag
            addTag(idOrName, name = null) {
                // If called with (id, name) from suggestion, use id as tag object
                let tagObj;
                if (typeof idOrName === "object" && idOrName !== null) {
                    tagObj = idOrName;
                } else if (name !== null) {
                    tagObj = { id: idOrName, name };
                } else {
                    // Called from free input, treat as plain string
                    tagObj = { id: null, name: idOrName };
                }
                // Prevent duplicates by name (case-insensitive)
                if (
                    tagObj.name &&
                    !this.tags.some(t => t.name.toLowerCase() === tagObj.name.toLowerCase())
                ) {
                    this.tags.push(tagObj);
                    this.renderTags();
                }
                this.inputField.value = "";
                this.suggestionsDiv.innerHTML = "";
            }

            // Remove a task tag
            removeTag(tag) {
                const index = this.tags.indexOf(tag);
                if (index > -1) {
                    this.tags.splice(index, 1);
                    this.renderTags();
                }
            }

            // Render task tags
            renderTags() {
                this.tagsContainer.innerHTML = "";
                this.tags.forEach(tag => {
                    const tagElement = document.createElement("div");
                    tagElement.className = "task-tag";
                    tagElement.innerHTML = `
                        ${tag.name}
                        <span title="Remove tag">&times;</span>
                    `;
                    tagElement.querySelector("span").onclick = () => this.removeTag(tag);
                    this.tagsContainer.appendChild(tagElement);
                });
            }

            // Show task suggestions
            async showSuggestions(input) {
                const suggestions = await this.fetchSuggestions(input);
                // Only show suggestions not already tagged (by id or name)
                const filteredSuggestions = suggestions.filter(suggestion => !this.tags.includes(suggestion.name));
                this.suggestionsDiv.innerHTML = "";
                filteredSuggestions.forEach(suggestion => {
                    const suggestionElement = document.createElement("div");
                    suggestionElement.className = "task-suggestion";
                    suggestionElement.textContent = suggestion.name;
                    suggestionElement.addEventListener('click', () => this.addTag(suggestion.tagID, suggestion.name));
                    this.suggestionsDiv.appendChild(suggestionElement);
                });
            }

            // Bind events
            bindEvents() {
                this.inputField.addEventListener("input", () => {
                    const input = this.inputField.value.trim();
                    if (input) {
                        this.showSuggestions(input);
                    } else {
                        this.suggestionsDiv.innerHTML = "";
                    }
                });

                this.inputField.addEventListener("keydown", (event) => {
                    if (event.key === "Enter" || event.key === ",") {
                        event.preventDefault();
                        this.addTag(this.inputField.value.trim());
                    }
                });
            }
            // Extract tag IDs for submission
            getTagIds() {
                // Return an array of tag IDs (excluding null/undefined)
                console.log(this.tags);
                
                return this.tags
                    .map(tag => tag.id)
                    .filter(id => typeof id !== "undefined" && id !== null && id !== "");
            }
        }

        // Usage example
        const taskTaggingSystem2 = new TaskTaggingSystem({
            containerId: "tagging-system-2",
            apiUrl: "{{ url('/admin/search-tag') }}"
        });
    </script>
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
    <!-- Department -->
    <script>
        /**
         * Handles the submission of the Department form.
         * - Prevents default form submission.
         * - Collects form data and selected manager IDs.
         * - Sends data to the server using fetch_cycle for AJAX POST.
         * - Handles success and error responses.
         */
        /**
         * Department Management Script
         * Handles the submission of the Department form and updates the department table.
         * - Prevents default form submission.
         * - Collects form data and selected manager IDs.
         * - Sends data to the server using fetch_cycle for AJAX POST.
         * - Handles success and error responses.
         */

        /**
         * Department Management Script
         * Handles the submission of the Department form and updates the department table.
         * - Prevents default form submission.
         * - Collects form data and selected manager IDs.
         * - Sends data to the server using fetch_cycle for AJAX POST.
         * - Handles success and error responses.
         */

        let departmentsTable = $('#tbl-departments').DataTable({
            paging: true,
            searching: true,
            ordering: false,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [2] } // Disable sorting on the "Action" column
            ],
            data: [],
            columns: [
                { data: 'name', title: 'Name' },
                {
                    data: 'managers',
                    title: 'Managers',
                    render: function(data, type, row) {
                        if (Array.isArray(data) && data.length > 0) {
                            return `
                                <div class="d-flex flex-row flex-wrap gap-2">
                                    ${data.map(mgr => `
                                        <div class="card shadow-sm mb-0" style="display:inline-block; min-width:220px; max-width:320px;">
                                            <div class="card-body p-2">
                                                <div class="d-flex align-items-center">
                                                    <img src="${mgr.profilePic || 'https://via.placeholder.com/32'}" alt="${mgr.name ?? mgr.full_name ?? 'N/A'}" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;">
                                                    <div>
                                                        <div class="fw-bold">${mgr.name ?? mgr.full_name ?? 'N/A'}</div>
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
                { data: 'status', title: 'Status' },
                {
                    data: null,
                    title: 'Action',
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="editDepartment('${row.id}')">
                                    <i class="las la-edit"></i> Edit
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteDepartment('${row.id}')">
                                    <i class="las la-trash-alt"></i> Delete
                                </button>
                            </div>
                        `;
                    },
                    className: 'text-end'
                }
            ]
        });
        // Fetch and display departments when the accordion is expanded
        document.getElementById('departmentCollapse').addEventListener('shown.bs.collapse', async () => {
            console.log('====================================');
            console.log('Fetching departments for company:', "{{ json_encode($company->company_id) }}");
            console.log('====================================');
            const companyId = "{{ json_encode($company->company_id) }}";
            const url = `/admin/get-departments/${companyId}`;
            const spinner = document.getElementById('loading-spinner');
            showElement(spinner);

            try {
                const data = await fetchFieldInput(url);
                if (data.status === "success" && Array.isArray(data.departments)) {
                    const departments = data.departments.map(department => ({
                        name: department.DepartmentName || "N/A",
                        managers: department.managers || [],
                        id: department.DepartmentID || "N/A"
                    }));
                    departmentsTable.clear().rows.add(departments).draw();
                } else {
                    displayMessage('warning', 'No departments found or invalid data structure.');
                    departmentsTable.clear().draw();
                }
            } catch (error) {
                console.error("Error fetching departments:", error);
                displayMessage('danger', 'An error occurred while fetching departments.');
            } finally {
                hideElement(spinner);
            }
        });

        // Bootstrap validation shim
        Array.from(document.querySelectorAll('.needs-validation')).forEach(form => {
            form.addEventListener('submit', ev => {
                if (!form.checkValidity()) {
                    ev.preventDefault();
                    ev.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Handle department form submission
        document.addEventListener('DOMContentLoaded', function () {
            const departmentForm = document.getElementById('department-form');
            console.log(departmentForm);
            
            if (departmentForm) {
                departmentForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    console.log('submission working');
                    return
                    
                    const formData = new FormData(departmentForm);
                    console.log('====================================');
                    console.log(manager_1.getSelectedUserIds());
                    console.log('====================================');
                    let manager = manager_1.getSelectedUserIds();
                    // Append manager IDs to the form data
                    manager.forEach(id => {
                        formData.append('manager_ids[]', id);
                    });
                    // Append the company ID to the form data
                    const url = "{{ route('admin.store-company-department') }}";

                    try {
                        const result = await fetch_cycle('--Store Department', url, 'POST', formData);
                        if (result.status === 'success') {
                            // Optionally update UI or show a success message
                            console.log("Department stored successfully:", result.department);
                        } else {
                            // Handle validation errors
                            console.error("Error storing department:", result.message);
                        }
                    } catch (error) {
                        console.error('Error storing department:', error);
                    }
                });
            }
        });
    </script>
    <!-- End Department -->
   <!-- Employee -->
    <script>
        // Handle employee form submission
        document.getElementById('employee-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = "{{ route('admin.store-company-employee') }}";

            try {
                // Ensure the route supports POST; if not, use GET and append params to URL
                // If the route only supports GET, use the following pattern:
                // const params = new URLSearchParams(formData).toString();
                // const response = await fetch(url + '?' + params, { method: 'GET' });
                // Otherwise, use POST as below if the route supports it:
                const result = await fetch_cycle('--Store Employee', url, 'POST', formData);
                if (result.status === 'success' && result.employee) {
                    const emp = result.employee;
                    employeesTable.row.add({
                        name: emp.name ?? "N/A",
                        email: emp.email ?? "N/A",
                        role: emp.role ?? "N/A",
                        department: emp.department?.DepartmentName ?? "N/A",
                        id: emp.id ?? "N/A"
                    }).draw(false);
                } else {
                    displayMessage('danger', result.message || 'Failed to add employee.');
                }
            } catch (error) {
                console.error('Error storing employee:', error);
                displayMessage('danger', 'An error occurred while adding employee.');
            }
        });

    </script>
   <!-- Employee -->
    <!-- Company Workflow Management Script -->
    <script>
    /**
     * Company Workflow Management Script
     * Handles:
     * - Fetching and displaying company workflows in a DataTable.
     * - Adding, editing, and deleting workflows.
     * - Uses fetch_cycle for AJAX requests.
     */

    // Initialize DataTable for Company Workflow
    const workflowTable = $('#tbl-workflow-management').DataTable({
        paging: true,
        searching: true,
        ordering: false,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [4] }
        ],
        data: [],
        columns: [
            { data: 'workflow_name', title: 'Workflow Name' },
            { data: 'description', title: 'Description' },
            {
                data: 'creator',
                title: 'Created By',
                render: data => data
                    ? `<div class="d-flex align-items-center">
                            <img src="${data.profile_picture}" alt="Profile" class="rounded-circle me-2" width="30" height="30">
                            <div>
                                <strong>${data.first_name ?? ''} ${data.last_name ?? ''} ${data.other_name ?? ''}</strong>
                                <div>${data.email}</div>
                            </div>
                       </div>`
                    : 'N/A'
            },
            {
                data: 'status',
                title: 'Status',
                render: data => `<span class="${data === 'active' ? 'text-success' : 'text-danger'} fw-bold">${data}</span>`
            },
            {
                data: null,
                title: 'Action',
                className: 'text-end',
                render: (data, type, row) => `
                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-secondary btn-sm" onclick="manageWorkflowStages('${row.workflow_id}')">
                            <i class="las la-layer-group"></i> Stages
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="editWorkflowStep('${row.workflow_id}')">
                            <i class="las la-edit"></i> Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteWorkflowStep('${row.workflow_id}')">
                            <i class="las la-trash-alt"></i> Delete
                        </button>
                    </div>
                `
            }
        ]
    });

    // Load workflows into the table
    function loadWorkflows(data) {
        workflowTable.clear().rows.add(data).draw();
    }

    // Fetch and display workflows when the accordion is expanded
    document.getElementById('workflowManagementCollapse').addEventListener('shown.bs.collapse', async () => {
        const companyId = "{{ json_encode($company->company_id) }}";
        const url = `/admin/get-workflows/${companyId}`;
        const spinner = document.getElementById('loading-spinner');
        showElement(spinner);

        try {
            const data = await fetchFieldInput(url);
            if (data.status === "success" && Array.isArray(data.workflows)) {
                const workflows = data.workflows.map(wf => ({
                    workflow_name: wf.workflow_name || "N/A",
                    description: wf.description || "",
                    creator: wf.creator
                        ? {
                            first_name: wf.creator.first_name || "N/A",
                            last_name: wf.creator.last_name || "N/A",
                            other_name: wf.creator.other_name || "",
                            profile_picture: wf.creator.profile_photo_path || 'https://via.placeholder.com/30',
                            email: wf.creator.email || "N/A"
                        }
                        : null,
                    status: wf.status || "N/A",
                    workflow_id: wf.workflow_id || "N/A"
                }));
                loadWorkflows(workflows);
            } else {
                displayMessage('warning', 'No workflows found or invalid data structure.');
                workflowTable.clear().draw();
            }
        } catch (error) {
            console.error("Error fetching workflows:", error);
            displayMessage('danger', 'An error occurred while fetching workflows.');
        } finally {
            hideElement(spinner);
        }
    });

    // Handle workflow form submission
    document.addEventListener('DOMContentLoaded', function () {
        const workflowForm = document.getElementById('workflow-management-form');
        if (workflowForm) {
            workflowForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(workflowForm);
                const url = "{{ route('admin.store-company-workflow') }}";

                try {
                    const result = await fetch_cycle('--Store Workflow', url, 'POST', formData);
                    if (result.status === 'success' && Array.isArray(result.workflows)) {
                        const workflows = result.workflows.map(wf => ({
                            workflow_name: wf.workflow_name || "N/A",
                            description: wf.description || "",
                            creator: wf.creator
                                ? {
                                    first_name: wf.creator.first_name || "N/A",
                                    last_name: wf.creator.last_name || "N/A",
                                    other_name: wf.creator.other_name || "",
                                    profile_picture: wf.creator.profile_photo_path || 'https://via.placeholder.com/30',
                                    email: wf.creator.email || "N/A"
                                }
                                : null,
                            status: wf.status || "N/A",
                            workflow_id: wf.workflow_id || "N/A"
                        }));
                        loadWorkflows(workflows);
                    }
                } catch (error) {
                    console.error('Error storing workflow:', error);
                }
            });
        }
    });

    // Edit workflow step
    window.editWorkflowStep = function (id) {
        const row = $(`button[onclick="editWorkflowStep('${id}')"]`).closest('tr');
        const workflow = workflowTable.row(row).data();
        if (!workflow) {
            Toastify({
                text: 'Workflow data not found.',
                duration: 4000,
                style: { background: "linear-gradient(to right, #ff0000, #ff1745)" }
            }).showToast();
            return;
        }
        document.getElementById('workflow_edit_id').value = id;
        document.getElementById('workflow_edit_name').value = workflow.workflow_name || '';
        document.getElementById('workflow_edit_description').value = workflow.description || '';
        const statusSelect = document.getElementById('workflow_edit_status');
        if (statusSelect) {
            Array.from(statusSelect.options).forEach(option => {
                option.selected = (option.value === (workflow.status || ''));
            });
        }
        const modal = new bootstrap.Modal(document.getElementById('workflowEditModal'));
        modal.show();
    };

    document.getElementById('workflow-edit-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const url = "{{ route('admin.update-company-workflow') }}";
        try {
            const result = await fetch_cycle('--Update Workflow', url, 'POST', formData);
            if (result.status === 'success' && Array.isArray(result.workflows)) {
                const workflows = result.workflows.map(wf => ({
                    workflow_name: wf.workflow_name || "N/A",
                    description: wf.description || "",
                    creator: wf.creator
                        ? {
                            first_name: wf.creator.first_name || "N/A",
                            last_name: wf.creator.last_name || "N/A",
                            other_name: wf.creator.other_name || "",
                            profile_picture: wf.creator.profile_photo_path || 'https://via.placeholder.com/30',
                            email: wf.creator.email || "N/A"
                        }
                        : null,
                    status: wf.status || "N/A",
                    workflow_id: wf.workflow_id || "N/A"
                }));
                loadWorkflows(workflows);
                const modal = bootstrap.Modal.getInstance(document.getElementById('workflowEditModal'));
                if (modal) modal.hide();
            }
        } catch (error) {
            console.error('Error updating workflow:', error);
        }
    });

    // Delete workflow step
    window.deleteWorkflowStep = function(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this workflow?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const url = `/admin/workflow/${id}`;
                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        workflowTable.row($(`button[onclick="deleteWorkflowStep('${id}')"]`).parents('tr')).remove().draw();
                        Swal.fire('Deleted!', 'Workflow step has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete workflow step.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                }
            }
        });
    };

    </script>
    <!-- End Company Workflow Management Script -->
    <!-- Stage Management Script -->
    <script>
    /**
     * Stage Management Script
     * Handles:
     * - Fetching and displaying stages for a batch in a DataTable.
     * - Adding, editing, and deleting stages.
     * - Submitting the stage management form and updating the stage table.
     * - Uses fetch_cycle for AJAX requests.
     */

    // Initialize DataTable for Stage Management
    // Initialize DataTable for Stage Management
    const stageTable = $('#tbl-stage-management').DataTable({
        paging: true,
        searching: true,
        ordering: false,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [4] } // Action column
        ],
        data: [],
        columns: [
            { data: 'stage_name', title: 'Stage Name' },
            { data: 'description', title: 'Description' },
            { data: 'status', title: 'Status' },
            { data: 'sequence_order', title: 'Sequence Order' },
            {
                data: null,
                title: 'Action',
                className: 'text-end',
                render: (data, type, row) => `
                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-secondary btn-sm" onclick="manageStageTasks('${row.stage_id}')">
                            <i class="las la-tasks"></i> Tasks
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="editStage('${row.stage_id}')">
                            <i class="las la-edit"></i> Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteStage('${row.stage_id}')">
                            <i class="las la-trash-alt"></i> Delete
                        </button>
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="las la-ellipsis-h"></i> More
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#" onclick="viewStageDetails('${row.stage_id}')">
                                        <i class="las la-eye"></i> View Details
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="duplicateStage('${row.stage_id}')">
                                        <i class="las la-copy"></i> Duplicate
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="archiveStage('${row.stage_id}')">
                                        <i class="las la-archive"></i> Archive
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="setStageDuration('${row.stage_id}', '${row.stage_name}', '${row.estimated_time || ''}')">
                                        <i class="las la-clock"></i> Estimated Time
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="inputMaterial('${row.stage_id}')">
                                        <i class="las la-cube"></i> Input Material
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="outputMaterial('${row.stage_id}')">
                                        <i class="las la-box"></i> Output Material
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                `
            }
        ]
    });

    window.manageWorkflowStages = function(workflowId) {
        // clear the form fields
        document.getElementById('stage-management-form').reset();
        // Set the batch_id (or workflow_id) in the hidden input for the stage form
        document.getElementById('stage_workflow_id').value = workflowId;

        const workflow = workflowTable.row($(`button[onclick="manageWorkflowStages('${workflowId}')"]`).parents('tr')).data();
        if (workflow && document.getElementById('stage-workflow-title')) {
            document.getElementById('stage-workflow-title').textContent = workflow.workflow_name || '';
        }

        // Fetch and display stages for the selected workflow
        (async () => {
            const url = `/admin/get-company-stages/${workflowId}`;
            try {
                const data = await fetchFieldInput(url);
                if (data.status === "success" && Array.isArray(data.stages)) {
                    const stages = data.stages.map(stage => ({
                        stage_name: stage.name || "N/A",
                        description: stage.description || "",
                        status: stage.status || "N/A",
                        sequence_order: stage.sequence || "N/A",
                        stage_id: stage.stage_id || stage.id || "N/A"
                    }));
                    stageTable.clear().rows.add(stages).draw();
                    // Clear the form for new entry
                    document.getElementById('stage-management-form').reset();
                } else {
                    stageTable.clear().draw();
                }
            } catch (error) {
                console.error("Error fetching stages:", error);
                stageTable.clear().draw();
            }
        })();
        
        // Show the stage management modal
        const modal = new bootstrap.Modal(document.getElementById('stageManagementModal'));
        modal.show();
    };


    // Handle stage form submission
    document.addEventListener('DOMContentLoaded', function () {
        const stageForm = document.getElementById('stage-management-form');
        if (stageForm) {
            stageForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(stageForm);
                const url = "{{ route('admin.store-company-stage') }}";

                try {
                    const result = await fetch_cycle('--Store Stage', url, 'POST', formData);
                    if (result.status === 'success' && Array.isArray(result.stages)) {
                        const stages = result.stages.map(stage => ({
                            stage_name: stage.name || "N/A",
                            description: stage.description || "",
                            status: stage.status || "N/A",
                            sequence_order: stage.sequence || "N/A",
                            stage_id: stage.stage_id || stage.id || "N/A"
                        }));
                        stageTable.clear().rows.add(stages).draw();
                        stageForm.reset();
                    }
                } catch (error) {
                    console.error('Error storing stage:', error);
                }
            });
        }
    });

    // View stage details
    window.viewStageDetails = function(id) {
        // Get stage data 
        let stage = stageTable.row($(`button[onclick="viewStageDetails('${id}')"]`).parents('tr')).data();
        if (!stage) {
            try {
                const response = await fetch_cycle('--Fetch Stage Details', `/admin/get-stage/${id}`, 'GET');
                if (response && response.status === 'success' && response.stage) {
                    stage = {
                        stage_name: response.stage.name || "N/A",
                        description: response.stage.description || "",
                        status: response.stage.status || "N/A",
                        sequence_order: response.stage.sequence || "N/A",
                        stage_id: response.stage.stage_id || response.stage.id || "N/A"
                    };
                }
            } catch (error) {
                console.error('Failed to fetch stage details:', error);
                stage = {};
            }
        }
        // Populate the modal with stage details
        document.getElementById('stage_details_name').textContent = stage.stage_name || 'N/A';
        document.getElementById('stage_details_description').textContent = stage.description || 'N/A';
        document.getElementById('stage_details_status').textContent = stage.status || 'N/A';
        document.getElementById('stage_details_sequence_order').textContent = stage.sequence_order || 'N/A';
        document.getElementById('stage_details_id').textContent = stage.stage_id || 'N/A';

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('viewStageDetailsModal'));
        modal.show();
    };

    // Estimated Time Management
    window.setStageDuration = function(id, stage_name, estimated_time="") {
        // Get stage data from DataTable row
        
        // Populate modal fields
        document.getElementById('estimated_time_stage_id').value = id || "";
        document.getElementById('estimated_time_stage_name').value = stage_name  || "";
        document.getElementById('estimated_time').value = estimated_time || "";

        // Show modal
        new bootstrap.Modal(document.getElementById('setStageDurationModal')).show();
    };

    // submit estimated duration
    const estimatedTimeForm = document.getElementById('estimated-time-stage-form');
    if (estimatedTimeForm) {
        estimatedTimeForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(estimatedTimeForm);
            const url = "{{ route('admin.update-estimated-time') }}";

            try {
                const result = await fetch_cycle('--Store Estimated Time', url, 'POST', formData);
                if (result.status === 'success') {
                    // Handle success (e.g., refresh stage data)
                    console.log('Estimated time stored successfully');
                }
            } catch (error) {
                console.error('Error storing estimated time:', error);
            }
        });
    }


    // Edit stage
    window.editStage = async function(id) {
        // Optionally fetch the latest stage data from the server
        let stage = stageTable.row($(`button[onclick="editStage('${id}')"]`).parents('tr')).data();

        // If not found in DataTable, fetch from backend
        if (!stage) {
            try {
                const response = await fetch(`/admin/get-stage/${id}`);
                if (response.ok) {
                    stage = await response.json();
                }
            } catch (error) {
                console.error('Failed to fetch stage:', error);
                return;
            }
        }
        // Populate the form fields with stage data
        console.log("Editing stage:", stage);

        if (stage) {
            document.getElementById('edit_stage_id').value = stage.stage_id || stage.id || "";
            document.getElementById('edit_stage_name').value = stage.stage_name || stage.name || "";
            document.getElementById('edit_stage_status').value = stage.status || "";
            document.getElementById('edit_stage_sequence_order').value = stage.sequence_order || stage.sequence || "";
            document.getElementById('edit_stage_description').value = stage.description || "";
            // Show the modal for editing stage
            const modal = new bootstrap.Modal(document.getElementById('editStageModal'));
            modal.show();
        }
    };

    document.addEventListener('DOMContentLoaded', function () {
        const editStageForm = document.getElementById('edit-stage-form');
        if (editStageForm) {
            editStageForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(editStageForm);
                const url = "{{ route('admin.update-company-stage') }}";
                try {
                    const result = await fetch_cycle('--Update Stage', url, 'POST', formData);
                    if (result.status === 'success' && Array.isArray(result.stages)) {
                        const stages = result.stages.map(stage => ({
                            stage_name: stage.name || "N/A",
                            description: stage.description || "",
                            status: stage.status || "N/A",
                            sequence_order: stage.sequence || "N/A",
                            stage_id: stage.stage_id || stage.id || "N/A"
                        }));
                        stageTable.clear().rows.add(stages).draw();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editStageModal'));
                        if (modal) modal.hide();
                    }
                } catch (error) {
                    console.error('Error updating stage:', error);
                }
            });
        }
    });

    // Delete stage
    window.deleteStage = function(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this stage?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const url = `/admin/company-stages/${id}`;
                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        stageTable.row($(`button[onclick="deleteStage('${id}')"]`).parents('tr')).remove().draw();
                        Swal.fire('Deleted!', 'Stage has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete stage.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                }
            }
        });
    };
    </script>
    <!-- End Stage Management Script -->
     <!-- Task Management -->
    <script>
    /**
     * Task Management Script
     * Handles:
     * - Fetching and displaying tasks for a stage in a DataTable.
     * - Adding, editing, and deleting tasks.
     * - Submitting the task management form and updating the task table.
     * - Uses fetch_cycle for AJAX requests.
     */

    // Initialize DataTable for Task Management
    const taskTable = $('#tbl-task-management').DataTable({
        paging: true,
        searching: true,
        ordering: false,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [6] } // Action column
        ],
        data: [],
        columns: [
            { data: 'title', title: 'Title' },
            {
                data: 'supervisor',
                title: 'Supervisor',
                render: function(data, type, row) {
                    // If supervisor is an array of objects, display as cards
                    if (Array.isArray(row.supervisors) && row.supervisors.length > 0) {
                        return row.supervisors.map(sup => `
                            <div class="card shadow-sm mb-1" style="display:inline-block; min-width:220px; max-width:320px;">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center">
                                        <img src="${sup.profilePic || 'https://via.placeholder.com/32'}" alt="${sup.name ?? sup.full_name ?? 'N/A'}" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;">
                                        <div>
                                            <div class="fw-bold">${sup.name ?? sup.full_name ?? 'N/A'}</div>
                                            <div class="small text-muted">${sup.email ?? ''}</div>
                                            <div class="small text-secondary">${sup.jobTitle ?? ''}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                    }
                    // Fallback: display as plain text
                    return data || '<span class="text-muted">None</span>';
                }
            },
            { data: 'due_date', title: 'Due Date' },
            { data: 'priority', title: 'Priority' },
            { data: 'status', title: 'Status' },
            { data: 'tags', title: 'Tags' },
            {
                data: null,
                title: 'Action',
                className: 'text-end',
                render: (data, type, row) => `
                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-outline-light btn-sm" onclick="viewTask('${row.task_id}')">
                            <i class="las la-eye"></i> View Task
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="scheduleTask('${row.task_id}')">
                            <i class="las la-calendar-plus"></i> Schedule Management
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="assignTask('${row.task_id}')">
                            <i class="las la-user-plus"></i> Assigned Employee
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="editTask('${row.task_id}')">
                            <i class="las la-edit"></i> Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteTask('${row.task_id}')">
                            <i class="las la-trash-alt"></i> Delete
                        </button>
                    </div>
                `
            }
        ]
    });
    // Show Task Management Modal for a stage
    window.manageStageTasks = function(stageId) {
        console.log("Manage Stage Tasks for Stage ID:", stageId);
        document.getElementById('task-management-form').reset();
        document.getElementById('stage_workflow_id').value = ""; // Clear workflow id if present
        document.getElementById('task-management-form').querySelector('input[name="stage_id"]').value = stageId;

        // Fetch and display tasks for the selected stage
        (async () => {
            const url = `/admin/get-stage-tasks/${stageId}`;
            try {
                const data = await fetchFieldInput(url);
                if (data.status === "success" && Array.isArray(data.tasks)) {
                    const tasks = data.tasks.map(task => ({
                        title: task.title || task.task_name || "N/A",
                        supervisor: Array.isArray(task.supervisors) && task.supervisors.length > 0
                            ? task.supervisors.map(sup => `
                                <div class="card shadow-sm mb-1" style="display:inline-block; min-width:220px; max-width:320px;">
                                    <div class="card-body p-2">
                                        <div class="d-flex align-items-center">
                                            <img src="${sup.ProfilePicture || 'https://via.placeholder.com/32'}" alt="${sup.name ?? sup.full_name ?? 'N/A'}" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;">
                                            <div>
                                                <div class="fw-bold">${sup.name ?? sup.full_name ?? [sup.FirstName, sup.LastName].filter(Boolean).join(" ") ?? 'N/A'}</div>
                                                <div class="small text-muted">${sup.Email ?? ''}</div>
                                                <div class="small text-secondary">${sup.EmployeeNumber ?? ''}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')
                            : '<span class="text-muted">None</span>',
                        due_date: task.due_date ? new Date(task.due_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                        priority: task.priority || "N/A",
                        status: task.status || "N/A",
                        tags: Array.isArray(task.tags)
                            ? task.tags.map(tag => tag.name || tag).join(", ")
                            : (task.tags || ""),
                        task_id: task.stage_task_id || task.id || "N/A"
                    }));
                    taskTable.clear().rows.add(tasks).draw();
                    document.getElementById('task-management-form').reset();
                } else {
                    taskTable.clear().draw();
                }
            } catch (error) {
                console.error("Error fetching tasks:", error);
                taskTable.clear().draw();
            }
        })();

        // Show the task management modal
        const modal = new bootstrap.Modal(document.getElementById('taskManagementModal'));
        modal.show();
    };



    // Handle task form submission
    document.addEventListener('DOMContentLoaded', function () {
    const taskForm = document.getElementById('task-management-form');

    if (!taskForm) return;

    taskForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(taskForm);

        // Append selected supervisor IDs
        const supervisorIds = manager_5.getSelectedUserIds();
        supervisorIds.forEach(id => formData.append('supervisor_ids[]', id));

        // Append selected tag IDs
        const tagIds = taskTaggingSystem2.getTagIds();
        tagIds.forEach(id => formData.append('task_tag_ids[]', id));

        const url = "{{ route('admin.store-company-stage-task') }}";

        try {
            const result = await fetch_cycle('--Store Task', url, 'POST', formData);

            if (result.status === 'success' && result.data) {
                const task = result.data;

                // Prepare the row data for DataTable
                const formattedTask = {
                    title: task.task_name || "N/A",
                    supervisor: '', // Will be rendered by DataTable using `supervisors`
                    supervisors: Array.isArray(task.supervisors) ? task.supervisors.map(sup => ({
                        profilePic: sup.ProfilePicture || '',
                        name: sup.name || sup.full_name || [sup.FirstName, sup.LastName].filter(Boolean).join(' ') || 'N/A',
                        email: sup.Email || '',
                        jobTitle: sup.EmployeeNumber || ''
                    })) : [],
                    due_date: task.due_date
                        ? new Date(task.due_date).toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        })
                        : 'N/A',
                    priority: task.priority || 'N/A',
                    status: task.status || 'N/A',
                    tags: Array.isArray(task.tags)
                        ? task.tags.map(tag => tag.name || tag).join(', ')
                        : (task.tags || ''),
                    task_id: task.stage_task_id || task.id || 'N/A'
                };

                // Check if this task already exists in the table
                const rowIndex = taskTable.rows().indexes().filter(i => {
                    return taskTable.row(i).data().task_id === formattedTask.task_id;
                });

                let updatedRow;
                if (rowIndex.length > 0) {
                    // Update existing row
                    taskTable.row(rowIndex[0]).data(formattedTask).draw(false);
                    updatedRow = taskTable.row(rowIndex[0]).nodes().to$();
                } else {
                    // Add as new row
                    taskTable.row.add(formattedTask).draw(false);
                    updatedRow = taskTable.row(':last').nodes().to$();
                }

                // Highlight the updated or added row
                updatedRow.addClass('table-success');
                setTimeout(() => updatedRow.removeClass('table-success'), 2000);

                // Reset the form
                taskForm.reset();
            }
        } catch (error) {
            console.error('Error submitting task:', error);
        }
    });
});

    // Task Scheduling Management
    // Initialize DataTable for Task Scheduling
    const taskSchedulingTable = $('#tbl-task-scheduling').DataTable({
        paging: true,
        searching: true,
        ordering: false,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [6] } // Disable sorting on the Action column
        ],
        data: [],
        columns: [
            {
                data: 'is_recurrence',
                title: 'Recurrence',
                render: (data) => data ? data : 'N/A'
            },
            {
                data: 'assignee',
                title: 'Assignee',
                render: (data, type, row) => {
                    if (row.employee && row.employee.name) {
                        return row.employee.name;
                    }
                    return data ? data : '<span class="text-muted">None</span>';
                }
            },
            {
                data: 'start_date',
                title: 'Start Date',
                render: (data) => data ? new Date(data).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A'
            },
            {
                data: 'end_date',
                title: 'End Date',
                render: (data) => data ? new Date(data).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A'
            },
            {
                data: 'frequency',
                title: 'Frequency',
                render: (data) => data ? data : '<span class="text-muted">None</span>'
            },
            {
                data: 'status',
                title: 'Status',
                render: (data) => {
                    let badgeClass = 'secondary';
                    let label = data || 'N/A';
                    if (typeof data === 'string') {
                        switch (data.toLowerCase()) {
                            case 'completed': badgeClass = 'success'; break;
                            case 'pending': badgeClass = 'warning'; break;
                            case 'rejected': badgeClass = 'dark'; break;
                        }
                    }
                    return `<span class="badge bg-${badgeClass}">${label.charAt(0).toUpperCase() + label.slice(1)}</span>`;
                }
            },
            {
                data: null,
                title: 'Action',
                className: 'text-end',
                render: (data, type, row) => `
                    <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-secondary btn-sm setup-task-metrics">
                        <i class="las la-chart-bar"></i> Metrics
                    </button>
                    </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="editTaskSchedule('${row.schedule_id}')">
                            <i class="las la-edit"></i> Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteTaskSchedule('${row.schedule_id}')">
                            <i class="las la-trash-alt"></i> Delete
                        </button>
                    </div>
                `
            }
        ]
    });

    // Show Task Scheduling Modal for a task
    window.scheduleTask = function(taskId) {
        document.getElementById('task-scheduling-form').reset();
        document.getElementById('task_id').value = taskId;

        const row = taskTable.row($(`button[onclick="scheduleTask('${taskId}')"]`).parents('tr')).data();
        console.log('Task Title:', row ? row.title || row.task_name || 'N/A' : 'N/A');
        document.getElementById('schedule_task_title').value = row ? row.title || row.task_name || 'N/A' : 'N/A';
        // Fetch and display schedules for the selected task
        (async () => {
            const url = `/admin/get-task-schedules/${taskId}`;
            // const url = ``;
            try {
                const data = await fetchFieldInput(url);
                if (data.status === "success" && Array.isArray(data.task_schedules)) {
                    const schedules = data.task_schedules.map(schedule => ({
                        employee: schedule.employee?.name || "N/A",
                        start_date: schedule.start_time ? new Date(schedule.start_time).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                        end_date: schedule.end_time ? new Date(schedule.end_time).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                        status: (schedule.status && schedule.status.toLowerCase() === "in_progress") 
                            ? "In Progress" 
                            : (schedule.status ? schedule.status.charAt(0).toUpperCase() + schedule.status.slice(1) : "N/A"),
                        is_recurrence: schedule.is_recurrence=== true
                            ? '<span class="text-success"><i class="las la-check-circle"></i> Yes</span>'
                            : '<span class="text-danger"><i class="las la-times-circle"></i> No</span>',
                        frequency: schedule.recurrenceRule && schedule.recurrenceRule.frequency ? schedule.recurrenceRule.frequency : "N/A",
                        schedule_id: schedule.task_schedule_id || schedule.id || "N/A"
                    }));
                    taskSchedulingTable.clear().rows.add(schedules).draw();
                } else {
                    taskSchedulingTable.clear().draw();
                }
            } catch (error) {
                console.error("Error fetching task schedules:", error);
                taskSchedulingTable.clear().draw();
            }
        })();

        // Show the task scheduling modal
        const modal = new bootstrap.Modal(document.getElementById('taskSchedulingModal'));
        modal.show();
    };

    
    // Show/hide recurrence rule container based on is_recurrence radio
    document.addEventListener('DOMContentLoaded', function () {
        const recurrenceRadios = document.querySelectorAll('input[name="is_recurrence"]');
        const recurrenceRuleContainer = document.getElementById('recurrence-rule-container');
        if (recurrenceRadios.length && recurrenceRuleContainer) {
            function toggleRecurrenceRule() {
                const checked = Array.from(recurrenceRadios).find(r => r.checked);
                if (checked && checked.value === "1") {
                    recurrenceRuleContainer.style.display = '';
                } else {
                    recurrenceRuleContainer.style.display = 'none';
                }
            }
            recurrenceRadios.forEach(radio => {
                radio.addEventListener('change', toggleRecurrenceRule);
            });
            // Initial state
            toggleRecurrenceRule();
        }
    });
    
    // Fetch recurrence rule when a recurrence_rule_id is selected
    document.addEventListener('DOMContentLoaded', function () {
        const recurrenceRuleSelect = document.getElementById('recurrence_rule_id');
        console.log("Recurrence Rule Select Element:", recurrenceRuleSelect);
        
        if (recurrenceRuleSelect) {
            recurrenceRuleSelect.addEventListener('focus', async function () {
                console.log("Recurrence Rule Select Focused: ", recurrenceRuleSelect);
                
                // Populate recurrence rule select options dynamically
                try {
                    const companyId = "{{ json_encode($company->company_id) }}";
                    const url = `/admin/get-recurrence-rules/${companyId}`;
                    const data = await fetchFieldInput(url);
                    if (data.status === "success" && Array.isArray(data.recurrence_rules)) {
                        // Clear existing options
                        recurrenceRuleSelect.innerHTML = '';
                        // Add default option
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Select recurrence rule';
                        defaultOption.disabled = true;
                        defaultOption.selected = true;
                        recurrenceRuleSelect.appendChild(defaultOption);
                        // Add options from data
                        data.recurrence_rules.forEach(rule => {
                            const option = document.createElement('option');
                            option.value = rule.id || rule.recurrence_rule_id;
                            option.textContent = rule.name || rule.title || rule.frequency || 'Rule';
                            recurrenceRuleSelect.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error("Error fetching recurrence rules:", error);
                }
            });

            recurrenceRuleSelect.addEventListener('change', async function () {
                const ruleId = this.value;
                if (!ruleId) return;
                try {
                    const url = `/admin/get-recurrence-rule/${ruleId}`;
                    const data = await fetchFieldInput(url);
                    if (data.status === "success" && data.recurrence_rule) {
                        // Example: populate a description field or display rule details
                        const descField = document.getElementById('recurrence_rule_description');
                        if (descField) {
                            descField.textContent = data.recurrence_rule.description || '';
                        }
                        // You can populate other fields as needed
                    }
                } catch (error) {
                    console.error("Error fetching recurrence rule:", error);
                }
            });
        }
    });

    // Handle task scheduling form submission
    document.addEventListener('DOMContentLoaded', function () {
        const schedulingForm = document.getElementById('task-scheduling-form');
        if (schedulingForm) {
            schedulingForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(schedulingForm);
                const url = "{{ route('admin.store-company-stage-task-schedule') }}";

                try {
                    const result = await fetch_cycle('--Store Task Schedule', url, 'POST', formData);
                    if (result.status === 'success' && Array.isArray(result.schedules)) {
                        const schedules = result.schedules.map(schedule => ({
                            employee: schedule.employee?.name || "N/A",
                            start_date: schedule.start_date ? new Date(schedule.start_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                            end_date: schedule.end_date ? new Date(schedule.end_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                            status: schedule.status || "N/A",
                            remarks: schedule.remarks || "",
                            schedule_id: schedule.schedule_id || schedule.id || "N/A"
                        }));
                        taskSchedulingTable.clear().rows.add(schedules).draw();
                        schedulingForm.reset();
                    }
                } catch (error) {
                    console.error('Error storing task schedule:', error);
                }
            });
        }
    });

    
    // Edit task schedule
    window.editTaskSchedule = async function(id) {
        let schedule = taskSchedulingTable.row($(`button[onclick="editTaskSchedule('${id}')"]`).parents('tr')).data();

        if (!schedule) {
            try {
                const response = await fetch(`/admin/get-task-schedule/${id}`);
                if (response.ok) {
                    schedule = await response.json();
                }
            } catch (error) {
                console.error('Failed to fetch task schedule:', error);
                return;
            }
        }

        if (schedule) {
            document.getElementById('schedule_id').value = schedule.schedule_id || schedule.id || "";
            document.getElementById('employee_id').value = schedule.employee_id || "";
            document.getElementById('start_date').value = schedule.start_date || "";
            document.getElementById('end_date').value = schedule.end_date || "";
            document.getElementById('status').value = schedule.status || "";
            document.getElementById('remarks').value = schedule.remarks || "";
            // Show the modal for editing schedule
            const modal = new bootstrap.Modal(document.getElementById('taskSchedulingModal'));
            modal.show();
        }
    };

    // Delete task schedule
    window.deleteTaskSchedule = function(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this schedule?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const url = `/admin/task-schedules/${id}`;
                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        taskSchedulingTable.row($(`button[onclick="deleteTaskSchedule('${id}')"]`).parents('tr')).remove().draw();
                        Swal.fire('Deleted!', 'Schedule has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete schedule.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                }
            }
        });
    };

    // assign task
    window.assignTask = function(taskId) {
        console.log("Assign Task for Task ID:", taskId);
        document.getElementById('assign-employee-task-form').reset();
        document.getElementById('assign_task_id').value = taskId;

        // Fetch and display assigned employees for the selected task
        (async () => {
            const url = `/admin/get-task-assignees/${taskId}`;
            try {
                const data = await fetchFieldInput(url);
                if (data.status === "success" && Array.isArray(data.assignees)) {
                    const assignees = data.assignees.map(assignee => ({
                        employee_id: assignee.employee_id || assignee.id || "N/A",
                        name: assignee.name || assignee.full_name || "N/A",
                        email: assignee.email || "N/A",
                        job_title: assignee.job_title || "N/A"
                    }));
                    // Populate the assign task table or form as needed
                    // For example, you can use a DataTable or a simple list
                } else {
                    // Handle no assignees case
                }
            } catch (error) {
                console.error("Error fetching task assignees:", error);
            }
        })();

        // Show the assign task modal
        const modal = new bootstrap.Modal(document.getElementById('assignEmployeeToTaskModal'));
        modal.show();
    };
    // Handle assign employee form submission
    document.addEventListener('DOMContentLoaded', function () {
        const assignEmployeeForm = document.getElementById('assign-employee-task-form');
        if (assignEmployeeForm) {
            assignEmployeeForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(assignEmployeeForm);
                // Append selected supervisor IDs
                const employeeIds = manager_6.getSelectedUserIds();
                employeeIds.forEach(id => formData.append('employee_ids[]', id));
                const url = "{{ route('admin.store-task-employee') }}";
                try {
                    const result = await fetch_cycle('--Assign Employee to Task', url, 'POST', formData);
                    if (result.status === 'success') {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('assignEmployeeToTaskModal'));
                        if (modal) modal.hide();
                    }
                } catch (error) {
                    console.error('Error assigning employee to task:', error);
                }
            });
        }
    });

    // Edit task
    window.editTask = async function(id) {
        let task = taskTable.row($(`button[onclick="editTask('${id}')"]`).parents('tr')).data();

        if (!task) {
            try {
                const response = await fetch(`/admin/get-stage-tasks/${id}`);
                if (response.ok) {
                    task = await response.json();
                }
            } catch (error) {
                console.error('Failed to fetch task:', error);
                return;
            }
        }
        console.log("Editing task:", task);
        if (task) {
            document.getElementById('edit_task_id').value = task.task_id || task.id || "";
            document.getElementById('edit_task_title').value = task.title || task.task_name || "";
            document.getElementById('edit_task_description').value = task.description || "";
            document.getElementById('edit_task_status').value = task.status || "";
            document.getElementById('edit_task_priority').value = task.priority || "";
            document.getElementById('edit_task_due_date').value = task.due_date ? new Date(task.due_date).toISOString().split('T')[0] : "";
            const modal = new bootstrap.Modal(document.getElementById('editStageTaskModal'));
            modal.show();
            
        }
    };

    // Handle task edit form submission
    document.addEventListener('DOMContentLoaded', function () {
        const editTaskForm = document.getElementById('edit-stage-task-form');
        if (editTaskForm) {
            editTaskForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(editTaskForm);
                const url = "{{ route('admin.update-company-stage-task') }}";
                try {
                    const result = await fetch_cycle('--Update Task', url, 'POST', formData);
                    if (result.status === 'success' && Array.isArray(result.tasks)) {
                        const tasks = result.tasks.map(task => ({
                            title: task.title || "N/A",
                            supervisor: task.supervisor || "N/A",
                            due_date: task.due_date ? new Date(task.due_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : "N/A",
                            priority: task.priority || "N/A",
                            status: task.status || "N/A",
                            tags: Array.isArray(task.tags) ? task.tags.map(tag => tag.name || tag).join(", ") : (task.tags || ""),
                            task_id: task.task_id || task.id || "N/A"
                        }));
                        taskTable.clear().rows.add(tasks).draw();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('taskManagementModal'));
                        if (modal) modal.hide();
                    }
                } catch (error) {
                    console.error('Error updating task:', error);
                }
            });
        }
    });

    // Delete task
    window.deleteTask = function(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this task?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const url = `/admin/stage-tasks/${id}`;
                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        taskTable.row($(`button[onclick="deleteTask('${id}')"]`).parents('tr')).remove().draw();
                        Swal.fire('Deleted!', 'Task has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete task.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                }
            }
        });
    };
    // Show task metrics modal
    $(document).on('click', '.setup-task-metrics', async function() {
        const rowData = taskSchedulingTable.row($(this).closest('tr')).data();
        const scheduleId = rowData.task_schedule_id || rowData.schedule_id || rowData.id || '';
        document.querySelector('#task-metrics-form input[name="task_schedule_id"]').value = scheduleId;
        const taskMetricsModal = new bootstrap.Modal(document.getElementById('taskMetricsModal'));
        taskMetricsModal.show();
    });
   // Initialize the DataTable
const taskMetricsHistoryTable = $('#tbl-task-metrics').DataTable({
    paging: true,
    searching: true,
    ordering: false,
    responsive: true,
    data: [],
    columns: [
        { data: 'expected_chemical_quantity' },           // Chemical Quantity
        { data: 'expected_material_quantity' },           // Material Quantity
        { data: 'expected_water_quantity' },              // Water Quantity (L)
        {
            data: null,                                   // Action buttons
            orderable: false,
            className: 'text-center',                        // Align right
            render: (data, type, row) => `
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="editTaskMetrics('${row.task_metrics_id}')">
                        <i class="las la-edit"></i> Edit
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteTaskMetrics('${row.task_metrics_id}')">
                        <i class="las la-trash-alt"></i> Delete
                    </button>
                </div>
            `
        }
    ]
});

// Fetch and reload task metrics
async function reloadTaskMetrics(scheduleId) {
    if (!scheduleId) return;

    const url = `/admin/get-task-schedule-metrics/${scheduleId}`;
    try {
        const response = await fetch_cycle('--Reload Task Metrics', url, 'GET');
        if (response.status === "success" && Array.isArray(response.task_schedule_metrics)) {
            const metrics = response.task_schedule_metrics.map(metric => ({
                expected_chemical_quantity: metric.expected_chemical_quantity || "N/A",
                expected_material_quantity: metric.expected_material_quantity || "N/A",
                expected_water_quantity: metric.expected_water_quantity || "N/A",
                task_metrics_id: metric.task_schedule_metric_id || metric.id || "N/A"
            }));
            taskMetricsHistoryTable.clear().rows.add(metrics).draw();
        } else {
            taskMetricsHistoryTable.clear().draw();
        }
    } catch (error) {
        console.error("Error reloading task metrics:", error);
        taskMetricsHistoryTable.clear().draw();
    }
}

// Handle form submission
document.getElementById('task-metrics-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const scheduleId = formData.get('task_schedule_id');
    const url = "{{ route('admin.store-task-metrics') }}";

    try {
        const result = await fetch_cycle('--Store Task Metrics', url, 'POST', formData);
        if (result.status === 'success') {
            await reloadTaskMetrics(scheduleId);
            this.reset();
        } else {
            console.warn('Task metric store failed:', result);
        }
    } catch (error) {
        console.error('Error storing task metrics:', error);
    }
});

// Fetch when modal opens
$('#taskMetricsModal').on('shown.bs.modal', async function () {
    const scheduleId = document.querySelector('#task-metrics-form input[name="task_schedule_id"]').value;
    await reloadTaskMetrics(scheduleId);
});


    // Edit task metrics
    window.editTaskMetrics = async function(id) {
        let metric;
        try {
            const response = await fetch(`/admin/get-task-metrics-by-id/${id}`);
            if (response.ok) {
                metric = await response.json();
            }
        } catch (error) {
            console.error('Failed to fetch task metrics:', error);
            return;
        }
        if (metric) {
            document.querySelector('#task-metrics-form input[name="expected_chemical_quantity"]').value = metric.expected_chemical_quantity || "";
            document.querySelector('#task-metrics-form input[name="expected_material_quantity"]').value = metric.expected_material_quantity || "";
            document.querySelector('#task-metrics-form input[name="expected_water_quantity"]').value = metric.expected_water_quantity || "";
            document.querySelector('#task-metrics-form input[name="task_metrics_id"]').value = metric.task_metrics_id || metric.id || "";
        }
    };

    // Delete task metrics
    window.deleteTaskMetrics = function(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to delete this task metrics entry?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const url = `/admin/task-metrics/${id}`;
                try {
                    const response = await fetch(url, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        taskMetricsHistoryTable.row($(`button[onclick="deleteTaskMetrics('${id}')"]`).parents('tr')).remove().draw();
                        Swal.fire('Deleted!', 'Task metrics entry has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', data.message || 'Failed to delete task metrics.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                }
            }
        });
    };

            // Employee Assignment Management
            // Initialize DataTable for Employee Assignment Management
            const employeeAssignmentTable = $('#tbl-employee-assignment-management').DataTable({
                paging: true,
                searching: true,
                ordering: false,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: [4] } // Action column
                ],
                data: [],
                columns: [
                    { data: 'employee_name', title: 'Employee Name' },
                    { data: 'email', title: 'Email' },
                    { data: 'job_title', title: 'Job Title' },
                    { data: 'assignment_status', title: 'Status' },
                    {
                        data: null,
                        title: 'Action',
                        className: 'text-end',
                        render: (data, type, row) => `
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-outline-danger btn-sm" onclick="removeEmployeeAssignment('${row.assignment_id}')">
                                    <i class="las la-trash-alt"></i> Remove
                                </button>
                            </div>
                        `
                    }
                ]
            });
            
            // Show Employee Assignment Management Modal for a task
            window.viewEmployeeAssignmentManagement = function(taskId) {
                // Fetch and display assigned employees for the selected task
                (async () => {
                    const url = `/admin/get-task-assignees/${taskId}`;
                    try {
                        const data = await fetchFieldInput(url);
                        if (data.status === "success" && Array.isArray(data.assignees)) {
                            const assignees = data.assignees.map(assignee => ({
                                employee_name: assignee.name || assignee.full_name || "N/A",
                                email: assignee.email || "N/A",
                                job_title: assignee.job_title || "N/A",
                                assignment_status: assignee.status || "N/A",
                                assignment_id: assignee.assignment_id || assignee.id || "N/A"
                            }));
                            employeeAssignmentTable.clear().rows.add(assignees).draw();
                        } else {
                            employeeAssignmentTable.clear().draw();
                        }
                    } catch (error) {
                        console.error("Error fetching task assignees:", error);
                        employeeAssignmentTable.clear().draw();
                    }
                })();

                // Show the employee assignment management modal
                const modal = new bootstrap.Modal(document.getElementById('employeeAssignmentManagementModal'));
                modal.show();
            };

            // Remove employee assignment from task
            
    </script>
    <!-- End Task Management -->
    <!-- Recp form submission -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const recpForm = document.getElementById('recp-form');
        if (recpForm) {
            recpForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const formData = new FormData(recpForm);
                const url = "{{ route('admin.store-recp-status') }}";

                try {
                    const result = await fetch_cycle('--Store Recp', url, 'POST', formData);
                    if (result.status === 'success') {
                        Swal.fire({
                            title: 'Success',
                            text: 'Recp has been successfully submitted.',
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: result.message || 'Failed to submit Recp.',
                            icon: 'error'
                        });
                    }
                } catch (error) {
                    console.error('Error submitting Recp:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'An unexpected error occurred.',
                        icon: 'error'
                    });
                }
            });
        }
    });
    </script>
    <!-- Recp form submission -->
    @endsection

