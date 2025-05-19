<?php

/**
 * Template name: Payment
 *
 * @package WordPress
 * @subpackage inheart
 */

// Include our custom header
get_template_part( 'template-parts/payment/header');

// Add global ajaxUrl variable
?>
<script>
    window.ajaxUrlPayment = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php

wp_enqueue_style( 'payment-styles', THEME_URI . '/static/css/pages/payment.min.css', [], THEME_VERSION );
wp_enqueue_script( 'nova-poshta', THEME_URI . '/static/js/nova-poshta/nova-poshta.min.js', [], THEME_VERSION, true );
wp_enqueue_script( 'payment-script', THEME_URI . '/static/js/payment/payment.min.js', [], THEME_VERSION, true );
// Always start with step 0
$start_with_step_0 = true;
?>

<!-- Guaranteed loader hiding via inline script -->
<script>
    
    window.addEventListener('load', function() {
        document.querySelector('.popup-loader').style.display = 'none';
    });
    
    // Backup timeout in case something goes wrong
    setTimeout(function() {
        var loader = document.querySelector('.popup-loader');
        if (loader) {
            loader.style.display = 'none';
        }
    }, 3000);
    
    // Hide step 1 and show only step 0
    window.addEventListener('DOMContentLoaded', function() {
        
        var step1 = document.getElementById('new-memory-step-1');
        if (step1) {
            step1.style.display = 'none';
            step1.classList.remove('active');
            step1.classList.add('hidden');
        }
        
        var step0 = document.getElementById('new-memory-step-0');
        if (step0) {
            step0.style.display = 'flex';
            step0.classList.add('active');
            step0.classList.remove('hidden');
        }
        
        // Add check for form field event initialization
        setTimeout(function() {
            var emailInput = document.querySelector('#payment-step-0-form input[name="email"]');
            var phoneInput = document.querySelector('#payment-step-0-form input[name="phone"]');
            var termsCheckbox = document.querySelector('#payment-step-0-form input[name="terms_agreement"]');
        }, 1000);
    });
</script>

<main class="main new-memory flex direction-column" data-initial-step="0">
	<div class="popup popup-loader" style="background-color: #fff">
		<div class="tmp-loader"></div>
	</div>

	<?php
	// Always start with step 0, so it's active
	get_template_part( 'template-parts/payment/step-0/step', '0', ['hidden' => false] );
	// Step-1 is always hidden on load
	get_template_part( 'template-parts/payment/step-1/step', '1', ['active' => false] );
	?>
</main>

<!-- Direct script connection without WordPress for testing -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add direct check for form handlers
        setTimeout(function checkFormElements() {
            var form = document.getElementById('payment-step-0-form');
            var emailInput = document.querySelector('#payment-step-0-form input[name="email"]');
            var phoneInput = document.querySelector('#payment-step-0-form input[name="phone"]');
            var termsCheckbox = document.querySelector('#payment-step-0-form input[name="terms_agreement"]');
            
            if (form && emailInput && phoneInput && termsCheckbox) {
            } else {
                setTimeout(checkFormElements, 1000);
            }
        }, 1500);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Always start with step 0 on any page load
        // Clear all saved data
        localStorage.removeItem('ih-payment-step-0');
        
        // Hide step 1
        var step1 = document.getElementById('new-memory-step-1');
        if (step1) {
            step1.classList.remove('active', 'step-visible');
            step1.classList.add('hidden', 'step-hidden');
            step1.style.display = 'none';
            step1.style.visibility = 'hidden';
            step1.style.opacity = '0';
        }
        
        // Show step 0
        var step0 = document.getElementById('new-memory-step-0');
        if (step0) {
            step0.classList.remove('hidden', 'step-hidden');
            step0.classList.add('active', 'step-visible');
            step0.style.display = 'flex';
            step0.style.visibility = 'visible';
            step0.style.opacity = '1';
        }
        
        // Update step number in header
        var currentStepEl = document.querySelector('.payment-current-step');
        if (currentStepEl) {
            currentStepEl.textContent = '1';
        }
        
        // Clear URL parameter
        if (history.pushState) {
            var newUrl = window.location.pathname;
            window.history.replaceState({step: 0}, '', newUrl);
        }
        
        // Find back button in header
        var backButton = document.querySelector('.new-memory-prev-step');
        
        if (backButton) {
            backButton.addEventListener('click', function() {
                // Determine current step by element visibility
                var step0 = document.getElementById('new-memory-step-0');
                var step1 = document.getElementById('new-memory-step-1');
                
                // If step 1 is visible, we're on step 1
                if (step1 && step1.classList.contains('active')) {
                    
                    // Hide step 1
                    step1.classList.remove('active', 'step-visible');
                    step1.classList.add('hidden', 'step-hidden');
                    step1.style.display = 'none';
                    step1.style.visibility = 'hidden';
                    step1.style.opacity = '0';
                    
                    // Show step 0
                    if (step0) {
                        step0.classList.remove('hidden', 'step-hidden');
                        step0.classList.add('active', 'step-visible');
                        step0.style.display = 'flex';
                        step0.style.visibility = 'visible';
                        step0.style.opacity = '1';
                    }
                    
                    // Update step number in header
                    var currentStepEl = document.querySelector('.payment-current-step');
                    if (currentStepEl) {
                        currentStepEl.textContent = '1';
                    }
                    
                    // Clear saved data
                    localStorage.removeItem('ih-payment-step-0');
                    
                    // Update URL without reloading page
                    if (history.pushState) {
                        var newUrl = window.location.pathname;
                        window.history.pushState({step: 0}, '', newUrl);
                    }
                } else {
                    window.location.href = 'https://app.inheart.memorial';
                }
            });
        }
    });
</script>

<?php
get_template_part( 'template-parts/payment/footer');
?>

