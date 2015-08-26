<?php

	/* Admin bar */
	function linkpro_admin_bar(){
		global $linkpro_admin;
	?>
			<div class="linkpro-admin-head">
				<div class="linkpro-admin-left">
					<a href="<?php echo admin_url('admin.php'); ?>?page=linkpro"></a>
					<span class="linkpro-admin-version"><?php echo $linkpro_admin->version; ?></span>
					
				</div>
				
				<div class="clear"></div>
			</div>
	<?php
	}