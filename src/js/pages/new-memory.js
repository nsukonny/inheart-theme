// Common functions for all steps.
import { defineGlobalStepsItems, nextStep, prevStep } from '../new-memory/common'

// Steps one-by-one.
import { selectTheme } from '../new-memory/step-0'
import { addMainFormValidation, selectLanguage, uploadMainPhoto } from '../new-memory/step-1'
import {
	addSection,
	dragOrderSections,
	removeContentSection,
	removeSidebarAddedSection,
	sectionsContentInput,
	setActiveSectionContent
} from '../new-memory/step-2'
import { checkEpitaphContentLength } from '../new-memory/step-3'
import { uploadMediaPhotos, uploadMediaVideo } from '../new-memory/step-4'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	// Exit if something is missing.
	if( ! defineGlobalStepsItems() ) return

	// Step 0.
	selectTheme()
	nextStep()

	// Step 1.
	selectLanguage()
	uploadMainPhoto()
	addMainFormValidation()
	prevStep()

	// Step 2.
	addSection()
	removeSidebarAddedSection()
	removeContentSection()
	setActiveSectionContent()
	dragOrderSections()
	sectionsContentInput()

	// Step 3
	checkEpitaphContentLength()

	// Step 4
	uploadMediaPhotos()
	uploadMediaVideo()
} )