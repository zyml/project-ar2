	</div><!-- #main -->

	<?php ar2_before_footer() ?>
  <div class="clearfix"></div>
	<footer id="footer" class="clearfix" role="contentinfo">
	
		<div class="footer-sidebar-container clearfix">
			<?php 
				$footer_sidebars = 4;
			
				for ($i = 1; $i < $footer_sidebars + 1; $i++) : 
			?>
				<div id="footer-sidebar-<?php echo $i ?>" class="footer-sidebar clearfix xoxo">
					<?php if ( !dynamic_sidebar( 'footer-sidebar-' . $i ) ) : ?>
					<?php endif; ?>
				</div>
			<?php endfor; ?>
		</div>
		
		<nav class="footer-meta">
			<ul id="menu-top-menus-1" class="menu clearfix">
				<?php
				wp_nav_menu( array( 
					'sort_column'	=> 'menu_order', 
					'menu_class' 	=> 'menu clearfix', 
					'theme_location'=> 'footer-nav',
					'container'		=> false,
					'fallback_cb'	=> 'ar2_footer_nav_fallback_cb',
					'depth'			=> 1,
					'items_wrap'	=> '%3$s'
				) );
				?>
				<li class="menu-item"><a href="#header"><strong><?php _e( 'Back to Top', 'ar2' ) ?></strong></a></li>
			</ul>
		
			<?php echo html_entity_decode( ar2_get_theme_option( 'footer_message' ) ); ?>		
		</nav><!-- .footer-meta -->
		
	</footer>

</div><!-- #wrapper -->

<?php wp_footer() ?>
<!-- Baidu Button BEGIN -->
<script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=2763062" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
</script>
<!-- Baidu Button END -->


<script type="text/javascript" src="http://tajs.qq.com/stats?sId=19392753" charset="UTF-8"></script>
</body>
</html>
   
