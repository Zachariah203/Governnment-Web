<script>
    // Fetch Cycle Function
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
</script>