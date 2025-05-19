document.addEventListener('DOMContentLoaded', function() {
    // Define AJAX URL
    const ajaxUrl = window.ajaxUrlPayment;
    
    var form = document.getElementById('payment-step-1-form');
    var inputs = document.querySelectorAll('#payment-step-1-form input[required]');
    var submitButton = document.getElementById('payment-step-1-submit');
    var validationStatus = document.getElementById('payment-step-1-validation-status');
    
    // Get city and branch fields
    var cityInput = document.querySelector('#payment-step-1-form input[name="city"]');
    var branchInput = document.querySelector('#payment-step-1-form input[name="branch"]');
    
    // Create containers for dropdowns
    var cityContainer = cityInput.parentElement;
    var branchContainer = branchInput.parentElement;
    
    // Add class for positioning
    cityContainer.classList.add('input-container');
    branchContainer.classList.add('input-container');
    
    // Variables to store selected values
    var selectedCityRef = null;
    var selectedWarehouseRef = null;
    var selectedCityMainDescription = null;
    
    // Function to load warehouses
    async function loadWarehouses(cityRef, mainDescription, searchString = "") {
        const warehouses = await window.novaPoshtaAPI.getWarehouses(cityRef, mainDescription, searchString);
        // Show dropdown only if branch field is focused
        if (document.activeElement === branchInput) {
            window.createNovaPoshtaDropdown(branchContainer, warehouses, (warehouse) => {
                branchInput.value = warehouse.name;
                selectedWarehouseRef = warehouse.ref;
                // Form validation will be triggered after blur in createNovaPoshtaDropdown
            });
        }
    }
    
    // City input handler
    let cityTimeout;
    cityInput.addEventListener('input', function(e) {
        clearTimeout(cityTimeout);
        const value = e.target.value.trim();
        
        if (value.length < 2) {
            return;
        }
        
        cityTimeout = setTimeout(async () => {
            const cities = await window.novaPoshtaAPI.getCities(value);
            window.createNovaPoshtaDropdown(cityContainer, cities, (city) => {
                cityInput.value = city.name;
                selectedCityRef = city.ref;
                selectedCityMainDescription = city.mainDescription;
                
                // Enable branch field
                branchInput.disabled = false;
                branchInput.value = '';
                selectedWarehouseRef = null;
                
                // Load warehouses for selected city, but don't show the list
                loadWarehouses(city.ref, city.mainDescription);
            });
        }, 300);
    });

    // City field focus handler
    cityInput.addEventListener('focus', function() {
        // Hide branch dropdown when city field is focused
        const existingDropdown = branchContainer.querySelector('.np-dropdown');
        if (existingDropdown) {
            existingDropdown.remove();
        }
    });
    
    // Branch input handler
    let warehouseTimeout;
    branchInput.addEventListener('input', function(e) {
        if (!selectedCityRef || !selectedCityMainDescription) return;
        
        clearTimeout(warehouseTimeout);
        const value = e.target.value.trim();
        
        if (value.length < 1) {
            return;
        }
        
        warehouseTimeout = setTimeout(async () => {
            loadWarehouses(selectedCityRef, selectedCityMainDescription, value);
        }, 300);
    });

    // Branch field focus handler
    branchInput.addEventListener('focus', function() {
        if (selectedCityRef && selectedCityMainDescription) {
            // Show warehouses list when branch field is focused
            loadWarehouses(selectedCityRef, selectedCityMainDescription);
        }
    });

    // Branch field blur handler
    branchInput.addEventListener('blur', function() {
        // Small delay to ensure blur happens after dropdown selection
        setTimeout(() => {
            // Close dropdown
            const existingDropdown = branchContainer.querySelector('.np-dropdown');
            if (existingDropdown) {
                existingDropdown.remove();
            }
            // Check form validation
            validateForm();
        }, 200);
    });
    
    // Form validation function
    function validateForm() {
        var isValid = true;
        var emptyFields = [];
        
        // Check all required fields
        inputs.forEach(function(input) {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('error');
                emptyFields.push(input.name);
            } else {
                input.classList.remove('error');
            }
        });
        
        // Update submit button state
        if (isValid) {
            submitButton.removeAttribute('disabled');
            if (validationStatus) {
                validationStatus.innerText = 'Форма готова до відправки';
                validationStatus.style.color = 'green';
            }
        } else {
            submitButton.setAttribute('disabled', 'true');
            if (validationStatus) {
                validationStatus.innerText = 'Заповніть всі обов\'язкові поля';
                validationStatus.style.color = 'red';
            }
        }
        
        return isValid;
    }
    
    // Add handlers for form fields
    inputs.forEach(function(input) {
        input.addEventListener('input', validateForm);
        input.addEventListener('change', validateForm);
        input.addEventListener('blur', validateForm);
    });
    
    // Form submission handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            // Get data from step 0
            const step0Data = JSON.parse(localStorage.getItem('ih-payment-step-0') || '{}');
            
            // Collect form data
            const formData = new FormData();
            formData.append('action', 'ih_ajax_create_payment_order');
            formData.append('email', step0Data.email || '');
            formData.append('phone', step0Data.phone || '');
            formData.append('city', cityInput.value);
            formData.append('warehouse', branchInput.value);
            formData.append('name', document.querySelector('input[name="firstname"]').value);
            formData.append('surname', document.querySelector('input[name="lastname"]').value);
            formData.append('lastname', document.querySelector('input[name="middlename"]').value);
            formData.append('qr-count-qty','1');

            // Send request
            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear all form data from localStorage
                    localStorage.removeItem('ih-payment-step-0');
                    localStorage.removeItem('ih-payment-step-1');
                    
                    // Clear all fields on step-1
                    inputs.forEach(function(input) {
                        input.value = '';
                    });
                    
                    // Clear all fields on step-0
                    const step0Inputs = document.querySelectorAll('#new-memory-step-0 input');
                    step0Inputs.forEach(function(input) {
                        input.value = '';
                    });
                    
                    // Redirect to payment page
                    if (data.data.pageUrl) {
                        window.location.href = data.data.pageUrl;
                    } else {
                        alert('Помилка: не отримано URL для оплати');
                    }
                } else {
                    alert(data.data.msg || 'Помилка при створенні замовлення');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Помилка при створенні замовлення');
            });
        }
    });
    
    // Initial form validation
    validateForm();

    function ensureStepOneVisible() {
        // If URL has step=1 parameter, force show step 1
        var urlParams = new URLSearchParams(window.location.search);
        var forceShowStep1 = urlParams.get('step') === '1';
        
        // Check localStorage for step 0 data and if we should be on step 1
        var step0Data = localStorage.getItem('ih-payment-step-0');
        var shouldBeOnStep1 = step0Data && JSON.parse(step0Data).completed;
        
        if (forceShowStep1 || shouldBeOnStep1) {
            var step0 = document.getElementById('new-memory-step-0');
            var step1 = document.getElementById('new-memory-step-1');
            var footer = document.querySelector('.new-memory-footer');
            var prevButton = document.querySelector('.new-memory-prev-step');
            
            if (step0) {
                step0.classList.remove('active', 'step-visible');
                step0.classList.add('hidden', 'step-hidden');
                step0.style.display = 'none';
                step0.style.visibility = 'hidden';
                step0.style.opacity = '0';
            }
            
            if (step1) {
                step1.classList.remove('hidden', 'step-hidden');
                step1.classList.add('active', 'step-visible');
                step1.style.removeProperty('display');
                step1.style.removeProperty('visibility');
                step1.style.removeProperty('opacity');
                step1.style.display = 'flex';
                step1.style.visibility = 'visible';
                step1.style.opacity = '1';
                step1.style.zIndex = '1';
            }
            
            if (footer) {
                footer.classList.remove('hidden');
                footer.style.display = 'block';
                footer.style.visibility = 'visible';
            }
            
            if (prevButton) {
                prevButton.classList.remove('hidden');
                prevButton.setAttribute('data-prev', '0');
                prevButton.style.display = 'inline-flex';
                prevButton.style.visibility = 'visible';
            }
            
            // Add CSS with !important for reliability
            var styleEl = document.createElement('style');
            styleEl.textContent = '#new-memory-step-1 { display: flex !important; visibility: visible !important; opacity: 1 !important; }';
            styleEl.textContent += '#new-memory-step-0 { display: none !important; visibility: hidden !important; opacity: 0 !important; }';
            document.head.appendChild(styleEl);
        }
    }
    
    // Run check after DOM load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(ensureStepOneVisible, 500);
    });
    
    // Add global function to force step-1 visibility
    window.forceShowStep1 = function() {
        ensureStepOneVisible();
    };
});