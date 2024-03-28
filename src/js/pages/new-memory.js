// Common functions for all steps.
import {
	defineGlobalStepsItems,
	instagramPopupEvents,
	isStepFilled,
	nextStep,
	prevStep,
	saveProgress
} from '../new-memory/common'

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
	videoLinkInput,
	uploadVideoLink
} from '../new-memory/step-4'
import { addCoordsFormValidation, legendTipClick } from '../new-memory/step-5'
import { step2MilitaryFormValidation } from '../new-memory/step-2-military'
import { addReward } from '../new-memory/step-3-military'
import { TRANSITION_DURATION } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	hideLoader()
	saveProgress()

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

	// Step 2 Military
	step2MilitaryFormValidation()

	// Step 3 Military
	addReward()

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

	// Last screen (military).
	instagramPopupEvents()
} )

const hideLoader = () => {
	const loader = document.querySelector( '.popup-loader' )

	if( ! loader ) return

	loader.classList.add( 'opacity-0' )
	setTimeout( () => loader.remove(), TRANSITION_DURATION )
}
