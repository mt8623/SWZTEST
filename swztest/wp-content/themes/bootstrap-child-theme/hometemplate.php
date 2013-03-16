<?php
/*
Template Name: SolidWize Homepage
*/
?>

<?php get_header(); ?>
			
			<div id="content" class="clearfix row-fluid">
			
				<div id="main" class="clearfix homepage" role="main">

					
						<h1>Online SolidWorks Training. Available 24/7.</h1>
						<img class="imgblock" src="<?php echo get_stylesheet_directory_uri(); ?>/images/solidworksscreen.png">
						<a class="btn btn-info btn-large homebutton" href="/pricing">Sign Up for Free</a>
						<p class="homebuttonextra">or take a look at <a href="/training">some of our tutorials.</a></p>
						<a href="#videomodal" data-toggle="modal">
						<img class="playbutton" src="<?php echo get_stylesheet_directory_uri(); ?>/images/play-button.png">
						</a>
						
					    <!-- <fieldset class="offers">
					                       <legend align="center">Membership includes these courses and more:</legend>
					                       <ul class="courseicons">
					                           <li><span class="parts courseicon"></span><p>Part Modeling</p></li>
					                           <li><span class="drawing courseicon"></span><p>Drawing</p></li>
					                           <li><span class="assembly courseicon"></span><p>Assembly</p></li>
					                           <li><span class="sheetmetal courseicon"></span><p>Sheet Metal</p></li>
					                           <li><span class="surfacing courseicon"></span><p>Surfacing</p></li>
					                           <li><span class="photoview courseicon"></span><p>Photoview 360</p></li>
					                           <li><span class="cswp courseicon"></span><p>CSWP</p></li>
					                           <li><span class="weldments courseicon"></span><p>Weldments</p></li>
					                       </ul>
					                   </fieldset> -->
					
								   	<div class="featureset">
								   		<div class="featureset1">
								   		<img src="http://solidwize.com/solidwize/wp-content/themes/Karma-Child-Theme/images/books.png">
								   <h2>Access to All Courses</h2>
								   			<p>Get instant access to hundreds of tutorials and training files. New Courses added weekly.</p>
								   		</div>
								   		<div class="featureset1">
								   					<img src="http://solidwize.com/solidwize/wp-content/themes/Karma-Child-Theme/images/slide.png">

								   			<h2>Online Office Hours</h2>
								   			<p>Join our member-only office hours and get your specific questions answered in live weekly webinars.</p>
								   		</div>
								   		<div class="featureset1">
								   		<img src="http://solidwize.com/solidwize/wp-content/themes/Karma-Child-Theme/images/cert.png">

								   			<h2>Get Certified</h2>
								   			<p>With lots of exclusive content and practice exams catering to the CSWP. You'll be a certified professional in no time.</p>
								   		</div>
								   		<div class="featureset2">
								   					<img src="http://solidwize.com/solidwize/wp-content/themes/Karma-Child-Theme/images/hand.png">
								   			<h2>Insane Customer Service</h2>
								   			<p>Phone, email, chat, you name it. We are here to make sure you are learning. We even create courses based on customer requests.</p>
								   		</div>
			
					
	<div id="videomodal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
<div class="modal-body"></div>	</div>		
	

				</div> <!-- end #main -->
<script>
jQuery(document).ready(function() {
jQuery('#videomodal').on('show', function () {
  jQuery('div.modal-body').html('<iframe src="http://player.vimeo.com/video/58140022?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1" width="700" height="394" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');  
});

jQuery('#videomodal').on('hide', function () {
  jQuery('div.modal-body').html('&nbsp;');  
});

});
</script>    
    
			</div> <!-- end #content -->

<?php get_footer(); ?>