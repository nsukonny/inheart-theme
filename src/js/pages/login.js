import '../common/forms'
import { submitAuthForm } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	closeMemoryTip()
	submitAuthForm( '#form-login', 'ih_ajax_login' )
} )

const closeMemoryTip = () => {
	const tip = document.querySelector( '.memory-tip' )

	if( ! tip ) return

	tip.addEventListener( 'click', () => tip.remove() )
}