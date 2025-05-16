<?php

/**
 * Payment page template.
 * Step 1.
 *
 * @see Page Template: Payment -> Step 1
 *
 * @package WordPress
 * @subpackage inheart
 */

// Принудительно устанавливаем, что шаг 1 всегда скрыт при загрузке страницы
$class = 'hidden';
$title	= get_field( 'title_payment_step_1' );
$desc	= get_field( 'description_payment_step_1' );
$button_text = get_field( 'button_text_payment_step_1' ) ?: 'Перейти до оплати';
?>

<section id="new-memory-step-1" class="new-memory-step new-memory-step-1 direction-column<?php echo esc_attr( $class ) ?>" style="display: none;">
	<div class="container direction-column align-start">

		<?php
		if( $title ){
			?>
			<div class="new-memory-step-title">
				<?php echo $title ?>
			</div>
			<?php
		}

		if( $desc ){
			?>
			<div class="new-memory-step-desc">
				<?php echo $desc ?>
			</div>
			<?php
		}
		?>

		<form class="form-white payment-delivery-form custom-form" onsubmit="return false;" id="payment-step-1-form" data-button-text="<?php echo esc_attr( $button_text ) ?>">
			<fieldset class="flex flex-wrap">

				<p class="legend">
					<?php _e( 'Отримати у відділенні Нової пошти', 'inheart' ) ?>
					<a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
						<g clip-path="url(#clip0_1070_53792)">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M24.0485 8.76098C24.1392 8.73244 24.2587 8.78951 24.3781 8.94646C24.3781 8.94646 24.3781 8.94645 29.7759 14.2064C30.0912 14.5203 30.0912 14.9959 29.7759 15.229C29.7759 15.229 29.7759 15.229 24.3781 20.5698C24.2587 20.7267 24.1392 20.7648 24.0485 20.7172C23.9577 20.6697 23.9004 20.5317 23.9004 20.332V9.1034C23.9004 8.90841 23.9577 8.78951 24.0485 8.76098Z" fill="#ED1C24"/>
							<path fill-rule="evenodd" clip-rule="evenodd" d="M14.815 -0.00390625H15.1972L15.565 0.148282C15.565 0.148282 15.565 0.148282 21.1204 5.64606C21.3593 5.95995 21.2781 6.19774 20.8816 6.19774C20.8816 6.19774 20.8816 6.19774 18.5792 6.19774C18.1827 6.19774 17.8674 6.51163 17.8674 6.90637C17.8674 6.90637 17.8674 6.90637 17.8674 10.9869C17.8674 11.3816 17.5474 11.6955 17.0697 11.6955C17.0697 11.6955 17.0697 11.6955 13.0237 11.6955C12.6272 11.6955 12.3072 11.3816 12.3072 10.9869C12.3072 10.9869 12.3072 10.9869 12.3072 6.90637C12.3072 6.51163 11.9919 6.19774 11.5907 6.19774H9.13058C8.73411 6.19774 8.6529 5.95995 8.89174 5.64606C8.89174 5.64606 8.89174 5.64606 14.452 0.148282L14.815 -0.00390625Z" fill="#ED1C24"/>
							<path fill-rule="evenodd" clip-rule="evenodd" d="M6.11196 8.64416C6.21227 8.69171 6.27437 8.82963 6.27437 9.02938V20.491C6.27437 20.6908 6.21227 20.8097 6.11196 20.8477C6.01642 20.8858 5.87789 20.8477 5.71548 20.7288C5.71548 20.7288 5.71548 20.7288 0.236453 15.231C-0.0788177 14.998 -0.0788177 14.5224 0.236453 14.2085C0.236453 14.2085 0.236454 14.2085 5.71548 8.79159C5.87789 8.63464 6.01642 8.5966 6.11196 8.64416Z" fill="#ED1C24"/>
							<path fill-rule="evenodd" clip-rule="evenodd" d="M13.0235 17.6641C13.0235 17.6641 13.0235 17.6641 17.0695 17.6641C17.5472 17.6641 17.8672 17.9779 17.8672 18.3727C17.8672 18.3727 17.8672 18.3727 17.8672 22.691C17.8672 23.1618 18.1825 23.4757 18.579 23.4757H20.7238C21.1203 23.4757 21.2779 23.7088 20.9626 23.9466C20.9626 23.9466 20.9626 23.9466 15.5648 29.3635C15.4024 29.5204 15.2065 29.6013 15.0059 29.6013C14.8101 29.6013 14.6094 29.5204 14.4518 29.3635C14.4518 29.3635 14.4518 29.3635 9.05398 23.9466C8.73393 23.7088 8.89157 23.4757 9.28804 23.4757C9.28804 23.4757 9.28804 23.4757 11.5905 23.4757C11.9917 23.4757 12.307 23.1618 12.307 22.691C12.307 22.691 12.307 22.691 12.307 18.3727C12.307 17.9779 12.627 17.6641 13.0235 17.6641Z" fill="#ED1C24"/>
						</g>
						<defs>
							<clipPath id="clip0_1070_53792">
								<rect width="30" height="29.6053" fill="white"/>
							</clipPath>
						</defs>
						</svg></a>
				</p>

				<?php
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'city',
					'label'			=> __( 'Місто', 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Ваше місто', 'inheart' ),
					'value'			=> '',
					'required'		=> 1,
					'extra_attrs'   => 'oninput="console.log(\'City input\');" autocomplete="off"'
				] );
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'branch',
					'label'			=> __( 'Відділення', 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Номер відділення', 'inheart' ),
					'value'			=> '',
					'required'		=> 1,
					'extra_attrs'   => 'oninput="console.log(\'Branch input\');" autocomplete="off" disabled'
				] );
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'firstname',
					'label'			=> __( "Ваше ім'я", 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( "Ваше ім'я", 'inheart' ),
					'value'			=> '',
					'autocomplete'	=> 'given-name',
					'required'		=> 1,
					'extra_attrs'   => 'oninput="console.log(\'First name input\');"'
				] );
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'lastname',
					'label'			=> __( 'Ваше прізвище', 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Ваше прізвище', 'inheart' ),
					'value'			=> '',
					'autocomplete'	=> 'family-name',
					'required'		=> 1,
					'extra_attrs'   => 'oninput="console.log(\'Last name input\');"'
				] );
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'middlename',
					'label'			=> __( 'По батькові', 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Ваше по батькові', 'inheart' ),
					'value'			=> '',
					'autocomplete'	=> 'additional-name',
					'required'		=> 1,
					'extra_attrs'   => 'oninput="console.log(\'Middle name input\');"'
				] );
			
				?>

				<div class="payment-submit-container full flex justify-center">
					<button type="submit" class="btn lg primary payment-submit-btn" id="payment-step-1-submit" disabled>
						<?php echo esc_html( $button_text ) ?>
					</button>
				</div>
				
				<!-- <div id="payment-step-1-validation-status" style="color: #f00; margin-top: 15px; text-align: center; width: 100%;">
					Заповніть всі обов'язкові поля
				</div> -->
			</fieldset>
		</form>
		
		<!-- Inline script for step 1 validation -->
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				// Define AJAX URL
				const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
				
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
			});
		</script>
		
		<!-- Function to force step-1 visibility if needed -->
		<script>
			// Function to check and force step-1 visibility if it should be shown
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
		</script>
	</div>
</section><!-- #new-memory-step-1 -->

