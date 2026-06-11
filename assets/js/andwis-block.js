/* global window, document */
( function () {
	'use strict';

	var DESKTOP_QUERY = '(min-width: 1400px)';

	function init( root ) {
		var triggers = Array.prototype.slice.call(
			root.querySelectorAll( '.andwis-item__trigger' )
		);
		if ( ! triggers.length ) {
			return;
		}

		var mql = window.matchMedia( DESKTOP_QUERY );

		function panelFor( trigger ) {
			return document.getElementById(
				trigger.getAttribute( 'aria-controls' )
			);
		}

		function close( trigger ) {
			var panel = panelFor( trigger );
			trigger.setAttribute( 'aria-expanded', 'false' );
			if ( panel ) {
				panel.hidden = true;
			}
		}

		function closeAll() {
			triggers.forEach( close );
			root.classList.remove( 'has-active' );
		}

		function open( trigger ) {
			closeAll();
			var panel = panelFor( trigger );
			trigger.setAttribute( 'aria-expanded', 'true' );
			if ( panel ) {
				panel.hidden = false;
			}
			root.classList.add( 'has-active' );
		}

		function toggle( trigger ) {
			var isOpen = trigger.getAttribute( 'aria-expanded' ) === 'true';

			if ( mql.matches ) {
				// Desktop tabs: selecting a tab shows it (stays open).
				if ( ! isOpen ) {
					open( trigger );
				}
			} else {
				// Mobile single-open accordion: toggle.
				if ( isOpen ) {
					close( trigger );
					root.classList.remove( 'has-active' );
				} else {
					open( trigger );
				}
			}
		}

		triggers.forEach( function ( trigger, index ) {
			trigger.addEventListener( 'click', function () {
				toggle( trigger );
			} );

			trigger.addEventListener( 'keydown', function ( event ) {
				var next;

				switch ( event.key ) {
					case 'ArrowDown':
					case 'ArrowRight':
						next = triggers[ index + 1 ] || triggers[ 0 ];
						break;
					case 'ArrowUp':
					case 'ArrowLeft':
						next = triggers[ index - 1 ] || triggers[ triggers.length - 1 ];
						break;
					case 'Home':
						next = triggers[ 0 ];
						break;
					case 'End':
						next = triggers[ triggers.length - 1 ];
						break;
					default:
						return;
				}

				event.preventDefault();
				next.focus();
				// On desktop the tablist pattern activates on focus.
				if ( mql.matches ) {
					open( next );
				}
			} );
		} );

		// Re-open the first item when crossing the breakpoint, so the desktop
		// tab view always has a selection and the mobile accordion matches.
		function onBreakpointChange() {
			open( triggers[ 0 ] );
		}

		if ( typeof mql.addEventListener === 'function' ) {
			mql.addEventListener( 'change', onBreakpointChange );
		} else if ( typeof mql.addListener === 'function' ) {
			mql.addListener( onBreakpointChange );
		}
	}

	function boot() {
		var blocks = document.querySelectorAll( '.andwis-items[data-andwis]' );
		Array.prototype.forEach.call( blocks, init );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}
} )();
