import html2canvas from 'html2canvas'
import {
	checkAjaxWorkingStatus, getTargetElement, hideElement,
	ihAjaxRequest,
	setAjaxWorkingStatus, setTargetElement, showElement,
	showNotification
} from '../common/global'
import { checkStep0 } from './step-0'
import { checkStep1 } from './step-1'

let footer,
	progressBar,
	prevStepBtn,
	nextStepBtn,
	nextStepIdGlobal,
	prevStepIdGlobal;

/**
 * Initialize global elements for step management
 */
export const defineGlobalStepsItems = () => {
	footer 		= document.querySelector('.new-memory-footer');
	progressBar	= document.querySelector('.new-memory-progress-bar');
	prevStepBtn	= document.querySelector('.new-memory-prev-step');
	nextStepBtn	= document.querySelector('.new-memory-next-step');

	return !(!footer || !progressBar || !prevStepBtn || !nextStepBtn);
}

/**
 * Allow transition to next step
 * @param {number} nextStepId - ID of the next step
 */
export const allowNextStep = (nextStepId = 1) => {
	nextStepBtn.removeAttribute('disabled');
	nextStepBtn.dataset.next = `${nextStepId}`;
}

/**
 * Disallow transition to next step
 */
export const disallowNextStep = () => {
	nextStepBtn.setAttribute('disabled', 'true');
	nextStepBtn.dataset.next = '';
}

/**
 * Update progress bar
 * @param {number} partId - ID of the progress bar part
 * @param {number} percentage - Completion percentage
 */
export const applyProgress = (partId = 1, percentage = 100) => {
	const part = progressBar.querySelector(`[data-part="${parseInt(partId)}"]`);

	if (!part) return;

	part.querySelector('.new-memory-progress-inner').style.width = `${percentage}%`;
}

/**
 * Check if current step is filled
 * @param {number|string} stepId - ID of the current step
 */
export const isStepFilled = (stepId = 0) => {
	let cb,	// Callback function for each step, returns true if step is ready, otherwise - false.
		stepForProgress	= 0,
		percentage		= 100,
		falsePercentage	= 0;

	switch (stepId) {
		case 1:
			cb = checkStep1;
			nextStepIdGlobal = 2;
			prevStepIdGlobal = 1;
			stepForProgress = 1;
			break;

		default:
			cb = checkStep0;
			nextStepIdGlobal = 1;
			prevStepIdGlobal = 0;
	}

	if (cb()) {
		applyProgress(stepForProgress, percentage);
		allowNextStep(nextStepIdGlobal);
		
		// If this is step 0 and its form is being submitted, move to step 1
		if (stepId === 0) {
			showNextStepSection();
		}
	} else {
		applyProgress(stepForProgress, falsePercentage);
		disallowNextStep();
	}
}

/**
 * Transition to next step
 */
export const nextStep = () => {
	nextStepBtn.addEventListener('click', () => {
		if (nextStepBtn.disabled) return;

		// If this is not the last step and button is not disabled, proceed to next step
		showNextStepSection();
	});
}

/**
 * Transition to previous step
 */
export const prevStep = () => {
	prevStepBtn.addEventListener('click', () => {
		if (prevStepBtn.classList.contains('hidden')) return;

		const prevStepIdUpd = prevStepBtn.dataset.prev;
		
		if (!prevStepIdUpd && prevStepIdUpd !== '0') return;
        
		// Hide current step (step-1)
		const currentStep = document.querySelector('.new-memory-step.active');
		if (currentStep) {
			currentStep.classList.remove('active');
			currentStep.classList.add('hidden');
			currentStep.style.display = 'none';
		}
		
		// Show step 0
		const step0 = document.getElementById('new-memory-step-0');
		if (step0) {
			step0.classList.remove('hidden');
			step0.classList.add('active');
			step0.style.display = 'flex';
		}
		
		// Hide "Back" button and footer as we returned to the first step
		prevStepBtn.classList.add('hidden');
		
		// Hide footer if this is the first step
		if (prevStepIdUpd === '0') {
			footer.classList.add('hidden');
			footer.style.display = 'none';
		}
		
		console.log('Successfully returned to step 0');
	});
}

/**
 * Display next step
 */
const showNextStepSection = () => {
	const currentStep = document.querySelector('.new-memory-step.active');
	
	if (!currentStep) return;
	
	currentStep.classList.remove('active');
	currentStep.style.display = 'none';
	
	const nextStep = document.querySelector(`#new-memory-step-${nextStepIdGlobal}`);
	
	if (!nextStep) return;
	
	nextStep.classList.add('active');
	nextStep.style.display = 'flex';
	
	prevStepBtn.classList.remove('hidden');
	prevStepBtn.setAttribute('data-prev', prevStepIdGlobal);
}