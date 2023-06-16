import '../common/forms'
import { submitAuthForm } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	submitAuthForm( '#form-register', 'ih_ajax_register' )
} )