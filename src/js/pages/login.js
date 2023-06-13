import '../common/forms'
import { submitAuthForm } from '../common/global'

document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	submitAuthForm( '#form-login', 'ih_ajax_login' )
} )