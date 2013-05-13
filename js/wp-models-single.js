jQuery(document).ready( function(jQuery) {
	wp_models_init_colorbox();
	wp_models_init_player();

	wp_models_init_toggle();
});

function togglePanel() {
    var jQuerythis = jQuery(this);
    jQuerythis.parent().prev().slideToggle('slow');
    jQuerythis.hide().siblings().show();
}

function wp_models_init_colorbox()
{
	jQuery('a.wp-models-gallery').colorbox({
		rel: 'group1',
		maxWidth:'95%',
		maxHeight:'95%'
	});
}

function wp_models_init_player()
{
	jQuery('.wp-models-model-vid').flowplayer();
}

function wp_models_init_toggle()
{
	// The height of the content block when it's not expanded
	var adjustheight = '3em';
	// The "more" link text
	var moreText = "More";
	// The "less" link text
	var lessText = "Less";
	
	// Sets the .wp-models-model-content div to the specified height and hides any content that overflows
	jQuery(".wp-models-toggle-container .wp-models-model-content").css('height', adjustheight).css('overflow', 'hidden');
	
	// The section added to the bottom of the "wp-models-toggle-container" div
	jQuery(".wp-models-toggle-container").append('<p><a href="#" class="wp-models-toggle"></a></p>');
	
	// Set the "More" text
	jQuery("a.wp-models-toggle").text(moreText);
	
	jQuery(".wp-models-toggle").toggle(function() {
			jQuery(this).parents("div:first").find(".wp-models-model-content").css('height', 'auto').css('overflow', 'visible');
			// Hide the [...] when expanded
			jQuery(this).parents("div:first").find("p.continued").css('display', 'none');
			jQuery(this).text(lessText);
		}, function() {
			jQuery(this).parents("div:first").find(".wp-models-model-content").css('height', adjustheight).css('overflow', 'hidden');
			jQuery(this).parents("div:first").find("p.continued").css('display', 'block');
			jQuery(this).text(moreText);
	});
}