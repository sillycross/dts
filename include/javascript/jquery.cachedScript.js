jQuery.cachedScript = function( url, options ) {
	// Allow user to set any option except for dataType, cache, and url
	options = jQuery.extend( options || {}, {
		dataType: "script",
		cache: true,
		url: url,
		headers: 
		{
			'Cache-Control': 'max-age=86400000' 
		}
	});
 
	// Use $.ajax() since it is more flexible than $.getScript
	// Return the jqXHR object so we can chain callbacks
	return jQuery.ajax( options );
}; 
