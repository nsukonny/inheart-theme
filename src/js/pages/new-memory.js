// Common functions for all steps.
import { defineGlobalStepsItems, isStepFilled, nextStep, prevStep } from '../new-memory/common'

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
import {
	uploadMediaPhotos,
	uploadMediaVideo,
	uploadCustomPoster,
	selectScreenshot,
	saveVideoPoster,
	setDefaultDeletePhoto,
	setDefaultDeleteVideo,
	externalLinksFieldsInput, checkStep4, externalLinkDelete
} from '../new-memory/step-4'
import { addCoordsFormValidation, legendTipClick } from '../new-memory/step-5'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	// Exit if something is missing.
	if( ! defineGlobalStepsItems() ) return

	// Step 0.
	isStepFilled()
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
	setDefaultDeletePhoto()
	setDefaultDeleteVideo()
	uploadMediaPhotos()
	uploadMediaVideo()
	uploadCustomPoster()
	selectScreenshot()
	saveVideoPoster()
	externalLinksFieldsInput()
	externalLinkDelete()

	// Step 5
	addCoordsFormValidation()
	legendTipClick()
} )