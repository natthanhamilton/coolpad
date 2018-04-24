/**
* Handles showing and hiding fields conditionally
*/
jQuery( document ).ready( function( $ ) {

	// Show/hide elements as necessary when a conditional field is changed
	$( '#envira-albums-settings input:not([type=hidden]), #envira-albums-settings select, #envira-gallery-settings input:not([type=hidden]), #envira-gallery-settings select' ).conditions( 
		[

			{	// Download Button Elements Dependant on Theme
				conditions: [
					{
						element: '[name="_envira_gallery[lightbox_theme]"], [name="_eg_album_data[config][lightbox_theme]"]',
						type: 'value',
						operator: 'array',
						condition: [ 'base', 'captioned', 'polaroid', 'showcase', 'sleek', 'subtle' ]
					},
					{
						element: '[name="_envira_gallery[download_lightbox]"], [name="_eg_album_data[config][download_lightbox]"]',
						type: 'checked',
						operator: 'is'
					}
				],
				actions: {
					if: {
						element: '#envira-config-downloads-lightbox-position-box',
						action: 'show'
					},
					else: {
						element: '#envira-config-downloads-lightbox-position-box',
						action: 'hide'
					}
				}
			},
			{	// Download Button Elements Independant of Theme
				conditions: {
					element: '[name="_envira_gallery[download_lightbox]"], [name="_eg_album_data[config][download_lightbox]"]',
					type: 'checked',
					operator: 'is'
				},
				actions: {
					if: {
						element: '#envira-config-downloads-lightbox-force-box',
						action: 'show'
					},
					else: {
						element: '#envira-config-downloads-lightbox-force-box',
						action: 'hide'
					}
				}
			},
			{	// Download all options
				conditions: {
					element: '[name="_envira_gallery[download_all]"], [name="_eg_album_data[config][download_all]"]',
					type: 'checked',
					operator: 'is'
				},
				actions: {
					if: {
						element: '#envira-config-downloads-all-custom-name, #envira-config-downloads-all-position-box, #envira-config-downloads-all-label-box',
						action: 'show'
					},
					else: {
						element: '#envira-config-downloads-all-custom-name, #envira-config-downloads-all-position-box, #envira-config-downloads-all-label-box',
						action: 'hide'
					}
				}
			},
			{	// Download button options
				conditions: {
					element: '[name="_envira_gallery[download]"], [name="_eg_album_data[config][download]"]',
					type: 'checked',
					operator: 'is'
				},
				actions: {
					if: {
						element: '#envira-config-downloads-position-box, #envira-config-downloads-force-box, #envira-config-downloads-password-box, #envira-config-downloads-invalid-password-box',
						action: 'show'
					},
					else: {
						element: '#envira-config-downloads-position-box, #envira-config-downloads-force-box, #envira-config-downloads-password-box, #envira-config-downloads-invalid-password-box',
						action: 'hide'
					}
				}
			},
			/* {	// Download Button Elements Dependant on Theme
				conditions: [
					{
						element: '[name="_envira_gallery[download]"], [name="_eg_album_data[config][download]"]',
						type: 'checked',
						operator: 'is'
					},
					{
						element: '[name="_envira_gallery[download_all]"], [name="_eg_album_data[config][download_all]"]',
						type: 'checked',
						operator: 'is'
					}
				],
				actions: {
					if: {
						element: '#envira-config-downloads-image-size',
						action: 'show'
					},
					else: {
						element: '#envira-config-downloads-image-size',
						action: 'hide'
					}
				}
			}, */

		]
	);

} );