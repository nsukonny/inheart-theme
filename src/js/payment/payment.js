import { defineGlobalStepsItems, nextStep, prevStep } from './common'
import { initStep0Form } from './step-0'
import { initStep1Form } from './step-1'

// Add module loading check
console.log('Payment module loaded, imported functions:', {
    defineGlobalStepsItems: typeof defineGlobalStepsItems,
    nextStep: typeof nextStep,
    prevStep: typeof prevStep,
    initStep0Form: typeof initStep0Form,
    initStep1Form: typeof initStep1Form
});

/**
 * Force hide all steps except step-0
 */
function forceShowOnlyStep0() {
    console.log('Forcing display of step 0 only...');
    
    // Find all steps
    const steps = document.querySelectorAll('.new-memory-step');
    
    // First hide all steps
    steps.forEach(step => {
        step.classList.remove('active');
        step.classList.add('hidden');
        step.style.display = 'none';
    });
    
    // Show only step 0
    const step0 = document.getElementById('new-memory-step-0');
    if (step0) {
        step0.classList.remove('hidden');
        step0.classList.add('active');
        step0.style.display = 'flex';
    }
    
    // Hide footer
    const footer = document.querySelector('.new-memory-footer');
    if (footer) {
        footer.classList.add('hidden');
        footer.style.display = 'none';
    }
    
    console.log('Step 0 is now the only visible step');
}

/**
 * Initialize all payment page components
 */
const initPaymentPage = () => {
    console.log('Payment page initialization...');
    
    // First hide the loader
    const loader = document.querySelector('.popup-loader');
    if (loader) {
        loader.style.display = 'none';
        console.log('Loader hidden');
    } else {
        console.warn('Loader element not found');
    }

    // Guaranteed display of step 0 only
    forceShowOnlyStep0();

    // Initialize global elements for step navigation
    console.log('Initializing global steps elements...');
    if (defineGlobalStepsItems()) {
        console.log('Global steps elements initialized successfully');
        // Add handlers to navigation buttons
        nextStep();
        prevStep();
        console.log('Step navigation buttons handlers added');
    } else {
        console.error('Failed to initialize global steps elements');
    }

    // Initialize forms with explicit output of results
    console.log('Initializing step 0 form...');
    initStep0Form();
    console.log('Step 0 form initialization complete');
    
    console.log('Initializing step 1 form...');
    initStep1Form();
    console.log('Step 1 form initialization complete');
    
    // For reliability, check display again after a second
    setTimeout(() => {
        console.log('Running delayed force show only step 0...');
        forceShowOnlyStep0();
    }, 1000);
    
    console.log('Payment page initialized successfully');
};

// Check if page is loading correctly
console.log('Page readiness state:', document.readyState);

// Use load event instead of DOMContentLoaded for complete page load
window.addEventListener('load', () => {
    console.log('Window load event triggered');
    initPaymentPage();
});

// Add backup call on DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoaded event triggered');
    // Hide loader even if main initialization is delayed
    const loader = document.querySelector('.popup-loader');
    if (loader) {
        loader.style.display = 'none';
        console.log('Loader hidden on DOMContentLoaded');
    } else {
        console.warn('Loader element not found on DOMContentLoaded');
    }
    
    // Immediately hide all steps except the first one
    forceShowOnlyStep0();
});

// Check in case page is already loaded when script is connected
if (document.readyState === 'complete') {
    console.log('Document already complete, initializing page now');
    initPaymentPage();
}

export default initPaymentPage; 