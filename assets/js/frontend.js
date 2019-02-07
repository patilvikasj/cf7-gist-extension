jQuery( function( $ ) {
	
	document.addEventListener( 'wpcf7submit', function( event ) {

		var form_data = cf7_gist_vars.form_data;
		var form_id   = event.detail.contactFormId;

	    if ( 'undefined' !== typeof form_data[form_id] && '1' == form_data[form_id] ) {

	    	if( 'undefined' !== typeof event.detail ) {

	    		var input_data = event.detail.inputs;
	    		var email = '';
	    		var name = '';

	    		$.each( input_data, function( index, value ) {
					
					switch ( value.name ) {
						case 'your-name':
							name = value.value;
							break;
						case 'your-email': 
							email = value.value;
							break;
						default:
							// statements_def
							break;
					}
				});

	    		if( 'undefined' !== typeof convertfox ) {

					convertfox.identify({
						email: email,
						name: name
					});
				}
	    	}
	    }
	}, false );
});