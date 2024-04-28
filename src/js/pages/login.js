import '../common/forms'
import { submitAuthForm } from '../common/global'
import { checkFormStatus } from './register'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	closeMemoryTip()
	submitAuthForm( '#form-login', 'ih_ajax_login' )
	checkFormStatus( document.querySelector( '#form-login' ) )
} )

const closeMemoryTip = () => {
	const tip = document.querySelector( '.memory-tip' )

	if( ! tip ) return

	tip.addEventListener( 'click', () => tip.remove() )
}