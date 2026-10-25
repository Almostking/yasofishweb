( function () {
	'use strict';

	function initAnnouncement( bar ) {
		var message = bar.querySelector( '[data-yf-announcement-message]' );
		var toggle = bar.querySelector( '[data-yf-announcement-toggle]' );
		var notices;
		var index = 0;
		var paused = false;
		var timer;

		if ( ! message || ! toggle ) {
			return;
		}

		try {
			notices = JSON.parse( bar.getAttribute( 'data-yf-announcements' ) || '[]' );
		} catch ( error ) {
			notices = [];
		}

		if ( ! Array.isArray( notices ) || notices.length < 2 ) {
			 toggle.hidden = true;
			return;
		}

		function schedule() {
			window.clearTimeout( timer );
			if ( paused ) {
				return;
			}
			timer = window.setTimeout( function () {
				if ( ! document.hidden && ! bar.matches( ':hover' ) && ! bar.contains( document.activeElement ) ) {
					index = ( index + 1 ) % notices.length;
					message.classList.add( 'is-changing' );
					window.setTimeout( function () {
						message.textContent = notices[ index ];
						message.classList.remove( 'is-changing' );
					}, 180 );
				}
				schedule();
			}, 5200 );
		}

		toggle.addEventListener( 'click', function () {
			paused = ! paused;
			toggle.textContent = paused ? '▶' : 'Ⅱ';
			toggle.setAttribute( 'aria-label', paused ? 'Reanudar avisos' : 'Pausar avisos' );
			toggle.title = paused ? 'Reanudar avisos' : 'Pausar avisos';
			schedule();
		} );
		bar.addEventListener( 'mouseleave', schedule );
		bar.addEventListener( 'focusout', schedule );
		document.addEventListener( 'visibilitychange', schedule );
		schedule();
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		Array.prototype.forEach.call( document.querySelectorAll( '[data-yf-announcements]' ), initAnnouncement );
	} );
}() );
