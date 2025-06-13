import { isStepFilled, disallowNextStep, allowNextStep } from './common'
import { ihAjaxRequest, showNotification } from '../common/global'

/**
 * Theme selection.
 */
export const selectTheme = () => {
	const
		themes       = document.querySelectorAll( '.new-memory-theme' ),
		activeTheme  = document.querySelector( '.new-memory-theme.active' ),
		footer       = document.querySelector( '.new-memory-footer' )
	let initialTheme = activeTheme ? activeTheme.dataset.value : undefined

	if( ! themes.length ) return

	// Hide footer on step-0
	if (footer) {
		footer.style.display = 'none'
	}

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
								// Скрываем текущий шаг
								document.querySelector('.new-memory-step.active').classList.remove('active');
								// Показываем следующий шаг
								document.querySelector('#new-memory-step-1').classList.add('active');
								// Показываем кнопку "Назад"
								document.querySelector('.new-memory-prev-step').classList.remove('hidden');
								document.querySelector('.new-memory-prev-step').setAttribute('data-prev', '0');
								// Показываем футер
								if (footer) {
									footer.style.display = 'block'
								}
								// Проверяем заполненность следующего шага
								isStepFilled(1);
								break;

							case false:
								showNotification(res.data.msg, 'error');
								break;
						}
					}
				});
			} else {
				// Сохраняем данные шага
				const formData = new FormData();
				formData.append('action', 'ih_ajax_save_data_step_0');
				formData.append('stepData', localStorage.getItem('ih-step-0') || '');

				ihAjaxRequest(formData).then(res => {
					if (res && res.success) {
						// Скрываем текущий шаг
						document.querySelector('.new-memory-step.active').classList.remove('active');
						// Показываем следующий шаг
						document.querySelector('#new-memory-step-1').classList.add('active');
						// Показываем кнопку "Назад"
						document.querySelector('.new-memory-prev-step').classList.remove('hidden');
						document.querySelector('.new-memory-prev-step').setAttribute('data-prev', '0');
						// Показываем футер
						if (footer) {
							footer.style.display = 'block'
						}
						// Проверяем заполненность следующего шага
						isStepFilled(1);
					} else if (res) {
						showNotification(res.data.msg, 'error');
					}
				});
			}
		});
	});
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