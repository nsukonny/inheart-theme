<?php

/**
 * Payment page template.
 * Step 0.
 *
 * @see Page Template: Payment -> Step 0
 *
 * @package WordPress
 * @subpackage inheart
 */
$title			= get_field( 'title_payment_step_0' );
$description	= get_field( 'description_payment_step_0' );
$description_sec = get_field( 'description_payment_step_0_sec' );
$acception_text = get_field( 'acception_text_payment_step_0' );
$email	= get_field( 'email_payment_step_0' );
$phone	= get_field( 'phone_payment_step_0' );
$button_text	= get_field( 'button_text_payment_step_0' ) ?: 'Замовити QR-код';
$class			= isset( $args['hidden'] ) && $args['hidden'] ? '' : ' active';
?>

<section id="new-memory-step-0" class="new-memory-step new-memory-step-0 direction-column<?php echo esc_attr( $class ) ?>">
	<div class="container direction-column">
		
		<form class="form-white payment-info-form custom-form" id="payment-step-0-form" onsubmit="return false;">
			<fieldset class="flex flex-wrap">

				<div class="title">
				<?php echo $title ?>
				</div>

				<div class="description">
					<?php echo $description ?></br>
					<?php echo $description_sec ?>
					
				</div>

				<?php
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'email',
					'label'			=> __( $email, 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( 'Ваш Email', 'inheart' ),
					'value'			=> '',
					'autocomplete'	=> 'email',
					'required'		=> 1,
					'type'          => 'email',
					'extra_attrs'   => 'oninput="console.log(\'Native email input event\');"'
				] );
				
				get_template_part( 'components/inputs/default', null, [
					'name'			=> 'phone',
					'label'			=> __( $phone, 'inheart' ),
					'label_class'	=> 'full',
					'placeholder'	=> __( '+380-(00)-000-0000', 'inheart' ),
					'value'			=> '',
					'autocomplete'	=> 'tel',
					'required'		=> 1,
					'type'          => 'text',
					'extra_attrs'   => 'oninput="console.log(\'Native phone input event\');"'
				] );
				?>

				<div class="payment-terms-agreement full">
					<?php
					get_template_part( 'components/inputs/checkbox', null, [
						'name'			=> 'terms_agreement',
						'label'			=> __( $acception_text, 'inheart' ),
						'label_class'	=> 'label-checkbox-custom',
						'required'		=> 1,
						'extra_attrs'   => 'onchange="console.log(\'Native checkbox change event\');"'
					] );
					?>
				</div>

				<div class="payment-submit-container full flex justify-center">
					<button type="submit" class="btn lg primary payment-submit-btn" id="payment-step-0-submit" 
					        onclick="document.getElementById('payment-step-0-validation-status').innerText='Форма отправляется...'; console.log('Submit button clicked');">
						<?php echo esc_html( $button_text ) ?>
					</button>
				</div>
				
				<!-- <div id="payment-step-0-validation-status" style="color: #f00; margin-top: 15px; text-align: center; width: 100%;">
					Ожидание заполнения формы
				</div> -->
				
				<!-- <div style="margin-top: 20px; text-align: center; width: 100%;">
					<button type="button" class="btn sm secondary" 
					        onclick="document.querySelector('#payment-step-0-form input[name=\'email\']').value='test@example.com'; 
					                 document.querySelector('#payment-step-0-form input[name=\'phone\']').value='+380123456789'; 
					                 document.querySelector('#payment-step-0-form input[name=\'terms_agreement\']').checked=true;
					                 document.getElementById('payment-step-0-validation-status').innerText='Тестовые данные заполнены';
					                 console.log('Test data filled');
					                 
					                 // Trigger events for handlers
					                 var inputEvent = new Event('input', { bubbles: true });
					                 var changeEvent = new Event('change', { bubbles: true });
					                 document.querySelector('#payment-step-0-form input[name=\'email\']').dispatchEvent(inputEvent);
					                 document.querySelector('#payment-step-0-form input[name=\'phone\']').dispatchEvent(inputEvent);
					                 document.querySelector('#payment-step-0-form input[name=\'terms_agreement\']').dispatchEvent(changeEvent);
					                 
					                 // Enable button
					                 document.getElementById('payment-step-0-submit').removeAttribute('disabled');
					                 
					                 return false;">
						Заполнить тестовыми данными
					</button>
					
					<button type="button" class="btn sm primary" style="margin-left: 10px;" 
					        onclick="if (!document.getElementById('payment-step-0-submit').disabled) {
					                     console.log('Simulating submit click...');
					                     // Simulate form submission
					                     var submitEvent = new Event('submit', { bubbles: true, cancelable: true });
					                     document.getElementById('payment-step-0-form').dispatchEvent(submitEvent);
					                 } else {
					                     document.getElementById('payment-step-0-validation-status').innerText='Сначала заполните все поля';
					                     console.log('Form not ready for submission');
					                 }
					                 return false;">
						Отправить форму
					</button>
				</div> -->
			</fieldset>
		</form>
		
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				console.log('DOMContentLoaded in step-0 template');
				
				var form = document.getElementById('payment-step-0-form');
				var emailInput = document.querySelector('#payment-step-0-form input[name="email"]');
				var phoneInput = document.querySelector('#payment-step-0-form input[name="phone"]');
				var termsCheckbox = document.querySelector('#payment-step-0-form input[name="terms_agreement"]');
				var submitButton = document.querySelector('#payment-step-0-form .payment-submit-btn');
				var validationStatus = document.getElementById('payment-step-0-validation-status');
				
				if (!form || !emailInput || !phoneInput || !termsCheckbox || !submitButton) {
					console.error('Form elements not found in inline script');
					if (validationStatus) validationStatus.innerText = 'Ошибка: элементы формы не найдены';
					return;
				}
				
				// Prevent form submission on button click
				submitButton.addEventListener('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					console.log('Submit button clicked, preventing default');
					
					// Validate form
					var isEmailValid = emailInput.value.includes('@') && emailInput.value.includes('.');
					var isPhoneValid = phoneInput.value.startsWith('+') && phoneInput.value.length >= 10;
					var isTermsAccepted = termsCheckbox.checked;
					
					if (isEmailValid && isPhoneValid && isTermsAccepted) {
						// Save form data
						var formData = {
							email: emailInput.value,
							phone: phoneInput.value,
							termsAccepted: termsCheckbox.checked,
							completed: true
						};
						localStorage.setItem('ih-payment-step-0', JSON.stringify(formData));
						
						// Update step number in header
						var currentStepEl = document.querySelector('.payment-current-step');
						if (currentStepEl) {
							currentStepEl.textContent = '2';
						}
						
						// Hide step 0
						var step0 = document.getElementById('new-memory-step-0');
						if (step0) {
							step0.classList.remove('active', 'step-visible');
							step0.classList.add('hidden', 'step-hidden');
							step0.style.display = 'none';
							step0.style.visibility = 'hidden';
							step0.style.opacity = '0';
						}
						
						// Show step 1
						var step1 = document.getElementById('new-memory-step-1');
						if (step1) {
							step1.classList.remove('hidden', 'step-hidden');
							step1.classList.add('active', 'step-visible');
							step1.style.display = 'flex';
							step1.style.visibility = 'visible';
							step1.style.opacity = '1';
						}
						
						// Update URL without page reload
						if (history.pushState) {
							var newUrl = window.location.pathname + '?step=1';
							window.history.pushState({step: 1}, '', newUrl);
						}
						
						return false;
					}
				});
				
				// Also prevent form submission on form submit event
				form.addEventListener('submit', function(e) {
					e.preventDefault();
					e.stopPropagation();
					console.log('Form submit event prevented');
					return false;
				});
				
				function validateFormInline() {
					console.log('Inline validation running');
					
					var isEmailValid = emailInput.value.includes('@') && emailInput.value.includes('.');
					
					var isPhoneValid = phoneInput.value.startsWith('+') && phoneInput.value.length >= 10;
					
					var isTermsAccepted = termsCheckbox.checked;
					
					if (isEmailValid && isPhoneValid && isTermsAccepted) {
						submitButton.removeAttribute('disabled');
						validationStatus.innerText = 'Форма готова к отправке';
						validationStatus.style.color = 'green';
					} else {
						submitButton.setAttribute('disabled', 'true');
						
						var reasons = [];
						if (!isEmailValid && emailInput.value) reasons.push('Email неверный');
						if (!isPhoneValid && phoneInput.value) reasons.push('Телефон неверный');
						if (!isTermsAccepted) reasons.push('Примите условия');
						
						validationStatus.innerText = reasons.length ? 'Ошибки: ' + reasons.join(', ') : 'Заполните форму';
						validationStatus.style.color = 'red';
					}
					
					console.log('Inline validation complete:', {
						email: emailInput.value,
						isEmailValid,
						phone: phoneInput.value,
						isPhoneValid,
						termsAccepted: isTermsAccepted,
						buttonDisabled: submitButton.hasAttribute('disabled')
					});
				}
				
				emailInput.addEventListener('input', validateFormInline);
				phoneInput.addEventListener('input', validateFormInline);
				termsCheckbox.addEventListener('change', validateFormInline);
				
				validateFormInline();
				
				// Function to switch to step 1
				function switchToStep1() {
					console.log('Switching to step 1...');
					
					// Save form data to localStorage
					var formData = {
						email: emailInput.value,
						phone: phoneInput.value,
						termsAccepted: termsCheckbox.checked,
						completed: true
					};
					localStorage.setItem('ih-payment-step-0', JSON.stringify(formData));
					console.log('Form data saved to localStorage');
					
					// Update step number in header
					var currentStepEl = document.querySelector('.payment-current-step');
					if (currentStepEl) {
						currentStepEl.textContent = '2';
						console.log('Updated step number in header to 2');
					}
					
					// Hide current step with stronger CSS rules
					var step0 = document.getElementById('new-memory-step-0');
					if (step0) {
						// Use special classes for complete guarantee
						step0.classList.remove('active', 'step-visible');
						step0.classList.add('hidden', 'step-hidden');
						
						// Apply inline styles for additional reliability
						step0.style.display = 'none';
						step0.style.visibility = 'hidden';
						step0.style.opacity = '0';
						step0.style.position = 'absolute';
						step0.style.pointerEvents = 'none';
						step0.style.zIndex = '-1';
						console.log('Step 0 hidden with stronger styles');
					} else {
						console.error('Step 0 element not found');
					}
					
					// Show next step with stronger CSS rules
					var step1 = document.getElementById('new-memory-step-1');
					if (step1) {
						// Remove all styles that might prevent display
						step1.removeAttribute('style');
						
						// Use special classes for complete guarantee
						step1.classList.remove('hidden', 'step-hidden');
						step1.classList.add('active', 'step-visible');
						
						// Force display the block
						step1.style.display = 'flex';
						step1.style.visibility = 'visible';
						step1.style.opacity = '1';
						step1.style.position = 'relative';
						step1.style.pointerEvents = 'auto';
						step1.style.zIndex = '1';
						console.log('Step 1 displayed with stronger styles');
						
						// Check if step is actually visible after our changes
						setTimeout(function() {
							var computedStyle = getComputedStyle(step1);
							console.log('Step 1 computed style:', {
								display: computedStyle.display,
								visibility: computedStyle.visibility,
								opacity: computedStyle.opacity,
								position: computedStyle.position
							});
							
							// If still not visible, try again
							if (computedStyle.display === 'none' || computedStyle.visibility === 'hidden') {
								console.warn('Step 1 still not visible, forcing display with dynamic style element');
								// Add styles directly to head for complete certainty
								var styleEl = document.createElement('style');
								styleEl.textContent = '#new-memory-step-1 { display: flex !important; visibility: visible !important; opacity: 1 !important; position: relative !important; z-index: 10 !important; }';
								styleEl.textContent += '#new-memory-step-0 { display: none !important; visibility: hidden !important; opacity: 0 !important; position: absolute !important; }';
								document.head.appendChild(styleEl);
								
								// As a last resort, if CSS fails, try reinserting the element
								var parent = step1.parentNode;
								var next = step1.nextSibling;
								parent.removeChild(step1);
								setTimeout(function() {
									if (next) {
										parent.insertBefore(step1, next);
									} else {
										parent.appendChild(step1);
									}
									step1.classList.add('step-visible');
									step1.style.display = 'flex';
									console.log('Re-inserted step 1 into DOM');
								}, 50);
							}
						}, 100);
					} else {
						console.error('Step 1 element not found');
					}
					
					// Show footer for back button functionality
					var footer = document.querySelector('.new-memory-footer');
					if (footer) {
						footer.classList.remove('hidden');
						footer.style.display = 'block';
						footer.style.visibility = 'visible';
						footer.style.opacity = '1';
						console.log('Footer displayed');
					} else {
						console.error('Footer element not found');
					}
					
					// Enable "Back" button
					var prevButton = document.querySelector('.new-memory-prev-step');
					if (prevButton) {
						prevButton.classList.remove('hidden');
						prevButton.setAttribute('data-prev', '0');
						prevButton.style.display = 'inline-flex';
						prevButton.style.visibility = 'visible';
						console.log('Prev button enabled');
					} else {
						console.error('Prev button not found');
					}
					
					validationStatus.innerText = 'Переход на шаг 2 успешно выполнен';
					validationStatus.style.color = 'green';
					
					console.log('Successfully switched to step 1');
				}
				
				// Handle form submission
				form.addEventListener('submit', function(e) {
					console.log('Form submit event in inline script');
					
					// Prevent form submission and page reload
					e.preventDefault();
					
					// Check form validity
					var isEmailValid = emailInput.value.includes('@') && emailInput.value.includes('.');
					var isPhoneValid = phoneInput.value.startsWith('+') && phoneInput.value.length >= 10;
					var isTermsAccepted = termsCheckbox.checked;
					
					if (isEmailValid && isPhoneValid && isTermsAccepted) {
						// If form is valid, switch to step 1
						validationStatus.innerText = 'Обработка данных...';
						validationStatus.style.color = 'blue';
						submitButton.setAttribute('disabled', 'true');
						
						// Save data with "completed" flag
						var formData = {
							email: emailInput.value,
							phone: phoneInput.value,
							termsAccepted: termsCheckbox.checked,
							completed: true  // Mark step as completed
						};
						localStorage.setItem('ih-payment-step-0', JSON.stringify(formData));
						
						// Add small delay to simulate submission
						setTimeout(function() {
							// Use main switching function
							switchToStep1();
							
							// Additionally call global forceShowStep1 function if available
							if (typeof window.forceShowStep1 === 'function') {
								console.log('Calling global forceShowStep1 function');
								window.forceShowStep1();
							}
							
							// Add parameter to URL to save state
							var newUrl = window.location.pathname + '?step=1';
							// Use history.replaceState to avoid creating new browser history entry
							try {
								window.history.replaceState({step: 1}, 'Шаг 2', newUrl);
								console.log('URL updated to indicate step 1');
							} catch (e) {
								console.error('Error updating URL:', e);
							}
						}, 500);
					} else {
						// If form is invalid, show error message
						validationStatus.innerText = 'Пожалуйста, заполните все поля корректно';
						validationStatus.style.color = 'red';
					}
				});
				
				console.log('Inline script setup complete');
			});
		</script>
	</div>
</section>

