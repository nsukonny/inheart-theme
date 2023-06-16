import '../common/forms'
import { submitAuthForm } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	submitAuthForm( '#form-lostpass', 'ih_ajax_lost_password' )
	submitAuthForm( '#form-new-pass', 'ih_ajax_new_password' )
} )