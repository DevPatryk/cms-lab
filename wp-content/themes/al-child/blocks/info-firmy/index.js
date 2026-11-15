// blocks/info-firmy/index.js – bez npm, korzysta z globalnych obiektów wp.*
( function ( blocks, element, blockEditor, components, i18n, ServerSideRender ) {
	var el = element.createElement;
	var __ = i18n.__;

	blocks.registerBlockType( 'al-child/info-firmy', {
		edit: function ( props ) {
			return el(
				element.Fragment,
				null,
				// Panel boczny: przełącznik „Pokaż telefon"
				el(
					blockEditor.InspectorControls,
					null,
					el(
						components.PanelBody,
						{ title: __( 'Ustawienia', 'al-child' ) },
						el( components.ToggleControl, {
							label: __( 'Pokaż telefon', 'al-child' ),
							checked: props.attributes.pokazTelefon,
							onChange: function ( wartosc ) {
								props.setAttributes( { pokazTelefon: wartosc } );
							},
						} )
					)
				),
				// Podgląd w edytorze generuje PHP (render.php)
				el(
					'div',
					blockEditor.useBlockProps(),
					el( ServerSideRender, {
						block: 'al-child/info-firmy',
						attributes: props.attributes,
					} )
				)
			);
		},
		save: function () {
			return null; // blok dynamiczny – HTML powstaje na serwerze
		},
	} );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.i18n,
	window.wp.serverSideRender
);
