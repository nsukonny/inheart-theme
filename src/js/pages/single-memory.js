window.addEventListener( 'scroll', () => {
	const
		topContent		= document.querySelector( '.single-memory-top-inner' ),
		contentBottom	= topContent.getBoundingClientRect().bottom

	if( contentBottom < -100 ){
		if( ! document.body.classList.contains( 'light' ) ) document.body.classList.add( 'light' )
	}else{
		if( document.body.classList.contains( 'light' ) ) document.body.classList.remove( 'light' )
	}
} )