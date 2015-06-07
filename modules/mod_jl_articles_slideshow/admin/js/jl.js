jQuery.noConflict();
window.addEvent("domready",function(){
	$$("#jform_params_asset-lbl").getParent().destroy();
	jQuery('.jl_color').ColorPicker({
		color: '#0000ff',
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onSubmit: function(hsb, hex, rgb, el) {
			jQuery(el).val("#"+hex);
			//jQuery(el).css('background',jQuery(el).val())
			jQuery(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			jQuery(this).ColorPickerSetColor(this.value);
		}
	})
	.bind('keyup', function(){
		jQuery(this).ColorPickerSetColor(this.value);
	});
	
	// add ajax menu
    
    var widthoptions = jQuery('#jform_params_jl_options_chzn').outerWidth();
    var jl_options = jQuery.trim(jQuery('#jform_params_jl_options').val());
    //jQuery('li[id^="jform_params_jl_options"]').click(function(){
    jQuery('#jform_params_jl_options').change(function(event){
                    var jl_options = jQuery.trim(jQuery('#jform_params_jl_options').val());
                    //load ajax here.
                    loadAjax(jl_options,widthoptions,'-1');
    });
    if( jl_options!='content' ){
            //load ajax here.
            loadAjax(jl_options,widthoptions,'0');
    }
	
})
function loadAjax(jl_options,widthoptions,setmodid){
	jQuery.ajax({
		  url: baseurl+"modules/mod_jl_articles_slideshow/admin/formfield/options/"+jl_options+".php",
		  type: "POST",
		  data: { moduleid: setmodid=='-1'?setmodid:moduleid},
		  error: function ( jqXHR, textStatus, errorThrown ) {
			 alert('Error loading Ajax');
		  }
	}).done(function( html ) {
		 jQuery("#jform_params_catid_chzn").remove();
		 if( jl_options!='content' ){
			jQuery("#jform_params_catid").attr('multiple','multiple');
			jQuery("#jform_params_catid").attr('name','jform[params][catid][]');
		 }else{
			 jQuery("#jform_params_catid").removeAttr('multiple');
			 jQuery("#jform_params_catid").attr('name','jform[params][catid]');
		 }
		 jQuery("#jform_params_catid").html(html);
		 jQuery("#jform_params_catid").removeClass('chzn-done');
		 jQuery("select#jform_params_catid").chosen();
		 jQuery("#jform_params_catid_chzn").css('width',widthoptions);
	});
}