
		$(function() {
			$('.crop_me').jWindowCrop({
				targetWidth: 320,
				targetHeight: 320,
				zoomSteps:20,
				loadingText: 'Loading...',
				smartControls: false,
				onChange: function(result) {
					$('#x1').val(result.cropX);
					$('#y1').val(result.cropY);
					$('#w').val(result.cropW);
					$('#h').val(result.cropH);
				}
			});
		});		

			$(document).ready(function () { 
			$('#save_thumb').click(function() {
				var x1 = $('#x1').val();
				var y1 = $('#y1').val();
				var w = $('#w').val();
				var h = $('#h').val();
				if(x1=="" || y1=="" || w=="" || h==""){
					alert("You must make a selection first");
					return false;
				}else{					
					/*$.ajax({
					type: 'post',
					url: '<?php echo $formAction;?>',
					dataType: 'json',
					data: {x1:x1,y1:y1,w:w,h:h},
					success: function(msg){		
					}
					});*/
					$('#facybox').hide();
					$('#facybox_overlay').fadeOut(200, function(){
					  $("#facybox_overlay").removeClass("facybox_overlayBG").
						addClass("facybox_hide").
						remove();
					});
					
					return true;
				}
			});
		     
		}); 
