    <script>
        // RECP section

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
        async function update_key_area(company, keyAreaId, element) {
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
        function remove_key_area(element, keyAreaId) {
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
        function update_product_innovation(company, productInnovationId, element) {
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

            fetch_cycle('--Update Key Product Innovation', url, 'POST', formData)
                .then(result => console.log(result))
                .catch(error => console.error('Error updating product innovation:', error));
        }
        // ***** End Update Key Product Innovation ******//
        // ***** Remove Key Product Innovation ******//
        function remove_product_innovation(element, productInnovationId) {
            if (!confirm("Do you want to delete this area of performance improvement?")) return;

            const parent = element.closest('.row');
            const url = `{{ route('admin.remove-product-innovation') }}`;
            const formData = new FormData();
            formData.append('key_product_innovation_id', productInnovationId);

            fetch_cycle('--Remove Product Innovation', url, 'POST', formData)
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
                                        onblur='update_hazardous_material("{{$company->company_id}}", ${element.hazarduousMaterialID}, this)'>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-danger" onclick='remove_hazardous_material(this, ${element.hazarduousMaterialID})' type="button">
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
        function update_hazardous_material(company, hazardousMaterialId, element) {
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
        function remove_hazardous_material(element, hazardousMaterialId) {
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
    </script>