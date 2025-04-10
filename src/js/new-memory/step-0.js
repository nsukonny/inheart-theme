import { isStepFilled } from './common'
import { ihAjaxRequest, showNotification } from '../common/global'

// Declare global event for interface updates when theme changes
const themeChangeEvent = new CustomEvent('themeChanged', {
	bubbles: true,
	detail: { theme: null, military: false }
});

/**
 * Theme selection.
 */
export const selectTheme = () => {
	const
		themes       = document.querySelectorAll( '.new-memory-theme' ),
		activeTheme  = document.querySelector( '.new-memory-theme.active' )
	let initialTheme = activeTheme ? activeTheme.dataset.value : undefined

	if( ! themes.length ) return

	themes.forEach( theme => {
		theme.addEventListener( 'click', () => {
			const alreadySelectedTheme = document.querySelector( '.new-memory-theme.active' )

			if( alreadySelectedTheme ) alreadySelectedTheme.classList.remove( 'active' )

			theme.classList.add( 'active' )
			const themeValue = theme.dataset.value;
			localStorage.setItem( 'ih-step-0', JSON.stringify( { theme: themeValue } ) )

			// Check if switching between military and regular themes
			const isMilitaryChange = (initialTheme === 'military' && themeValue !== 'military') || 
                                    (initialTheme !== 'military' && themeValue === 'military');

			if (isMilitaryChange) {
				// Send data to server
				const formData = new FormData();
				formData.append('action', `ih_ajax_save_data_step_0`);
				formData.append('stepData', localStorage.getItem(`ih-step-0`) || '');

				ihAjaxRequest(formData).then(res => {
					if (res) {
						switch (res.success) {
							case true:
								// Instead of reloading the page, apply changes dynamically
								applyThemeChange(themeValue);
								initialTheme = themeValue; // Update initialTheme for future comparisons
								
								// Update language for military theme
								if (themeValue === 'military') {
									const stepData1 = localStorage.getItem('ih-step-1') ?
										JSON.parse(localStorage.getItem('ih-step-1')) : { lang: 'uk' };
									
									stepData1.lang = 'uk';
									localStorage.setItem('ih-step-1', JSON.stringify(stepData1));
								}
								
								// Trigger theme change event
								themeChangeEvent.detail.theme = themeValue;
								themeChangeEvent.detail.military = (themeValue === 'military');
								document.dispatchEvent(themeChangeEvent);
								
								isStepFilled();
								break;

							case false:
								showNotification(res.data.msg, 'error');
								break;
						}
					}
				});
			} else {
				// Trigger theme change event
				themeChangeEvent.detail.theme = themeValue;
				themeChangeEvent.detail.military = (themeValue === 'military');
				document.dispatchEvent(themeChangeEvent);
				
				isStepFilled();
			}
		});
	});
}

/**
 * Apply theme changes without page reload
 * 
 * @param {string} theme - Theme name
 */
function applyThemeChange(theme) {
	// Apply theme classes
	if (theme === 'military') {
		document.body.classList.add('memory-page-theme-military');
		
		// If language switcher exists, make Ukrainian active
		const langSwitcher = document.querySelector('.new-memory-lang[data-lang="uk"]');
		if (langSwitcher) {
			document.querySelector('.new-memory-lang.active')?.classList.remove('active');
			langSwitcher.classList.add('active');
		}
	} else {
		document.body.classList.remove('memory-page-theme-military');
	}
	
	// Show notification about successful theme change
	showNotification('Тему успішно змінено');
}

/**
 * Check if step is done.
 *
 * @returns {boolean}
 */
export const checkStep0 = () => {
	const activeTheme = document.querySelector( '.new-memory-theme.active' )

	if( ! activeTheme ) return false

	const theme = activeTheme.dataset.value

	localStorage.setItem( 'ih-step-0', JSON.stringify( { theme } ) )

	if( theme === 'military' ){
		const stepData1 = localStorage.getItem( 'ih-step-1' ) ?
			JSON.parse( localStorage.getItem( 'ih-step-1' ) ) : { lang: 'uk' }

		stepData1.lang = 'uk'
		localStorage.setItem( 'ih-step-1', JSON.stringify( stepData1 ) )
		document.body.classList.add( 'memory-page-theme-military' )
	}else{
		document.body.classList.remove( 'memory-page-theme-military' )
	}

	return true
}