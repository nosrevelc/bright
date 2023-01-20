jQuery(document).ready(function($) {
	
	$( '#form-submit' ).click(function(e) {

		var error = false;
		if ( $('#contactSubject').val() == '' ){
			$('#sub-error-msg').text( 'Subject empty' );
			error = true;
		} else {
			$('#sub-error-msg').text( '' );
		}

		if ( $('#commentsText').val() == '' ){
			$('#com-error-msg').text( 'Message empty' );
			error = true;
		} else {
			$('#com-error-msg').text( '' );
		}

		if ( error ){
			return false;
		}
	});





});
