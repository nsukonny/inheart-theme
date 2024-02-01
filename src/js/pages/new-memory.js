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
	externalLinksFieldsInput,
	externalLinkDelete,
	externalLinkAdd,
	videoLinkInput, uploadVideoLink
} from '../new-memory/step-4'
import { addCoordsFormValidation, legendTipClick } from '../new-memory/step-5'
import { step2MilitaryFormValidation } from '../new-memory/step-2-military'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	// Exit if something is missing.
	if( ! defineGlobalStepsItems() ) return

	const initialStep = parseInt( document.querySelector( '.main.new-memory' ).dataset.initialStep )

	// Step 0.
	isStepFilled( initialStep )
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

	// Step 2 Military
	step2MilitaryFormValidation()

	// Step 3
	checkEpitaphContentLength()

	// Step 4
	setDefaultDeletePhoto()
	setDefaultDeleteVideo()
	uploadMediaPhotos()

	uploadMediaVideo()
	videoLinkInput()
	uploadVideoLink()
	uploadCustomPoster()
	selectScreenshot()
	saveVideoPoster()

	externalLinksFieldsInput()
	externalLinkAdd()
	externalLinkDelete()

	// Step 5
	addCoordsFormValidation()
	legendTipClick()
} )