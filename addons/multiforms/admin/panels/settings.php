<?php if (linkpro_mu_get_option('multi_forms')) : ?>

<form action="" method="post">

<h3><?php _e('Default Registration Form to Use','linkpro'); ?></h3>
<table class="form-table">

	<tr valign="top">
		<th scope="row"><label for="multi_forms_default"><?php _e('Choose your default form','linkpro'); ?></label></th>
		<td>
			<select name="multi_forms_default" id="multi_forms_default" class="chosen-select" style="width:300px">
				<?php foreach( linkpro_mu_get_option('multi_forms') as $key => $arr ) { ?>
				<option value="<?php echo $key; ?>" <?php selected($key, linkpro_mu_get_option('multi_forms_default')); ?>><?php echo $key; ?></option>
				<?php } ?>
			</select>
		</td>
	</tr>

</table>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','linkpro'); ?>"  />
	<input type="submit" name="reset-options" id="reset-options" class="button" value="<?php _e('Reset Options','linkpro'); ?>"  />
</p>

</form>
<?php endif; ?>

<div class="upadmin-highlight">

<h4><?php _e('Setup Multiple Register Forms','linkpro'); ?></h4>

<p><?php _e('This feature helps you setup multiple or seperate registration forms. For example, If you want to have two seperate forms or more e.g. Customer, Seller, etc. This tool helps you create conditional forms, just follow the simple steps.','linkpro'); ?></p>

<h4><?php _e('Quick Setup Guide','linkpro'); ?></h4>

<ol>
	<li><?php printf(__('First, setup all form fields including the multiple forms fields at the field customizer (Registration Fields) <a href="%s">here</a>','linkpro'), admin_url() . 'admin.php?page=linkpro'); ?></li>
	<li><?php _e('Enter a unique name below for a seperate registration form below to start.','linkpro'); ?></li>
	<li><?php _e('Once entered a unique name (used to tell the plugin which fields/form group to display), you will be able to check all the fields that are available only in that specific registration form. Save and go!','linkpro'); ?></li>
</ol>

<h4><?php _e('Enter a unique name to start','linkpro'); ?></h4>
<input type="text" name="linkpro_mu_name" id="linkpro_mu_name" value="" placeholder="<?php _e('example: customer_form','linkpro'); ?>" class="regular-text upadmin-standard" />

<div id="results-wrap"></div>

<p>
	<a href="#" class="linkpro_mu_start button button-primary" data-create="<?php _e('Done! Create another?','linkpro'); ?>" data-ready="<?php _e('Ready','linkpro'); ?>"><?php _e('Start','linkpro'); ?></a>
	<img src="<?php echo linkpro_url . 'admin/images/loading.gif'; ?>" alt="" class="upadmin-load-inline" />
</p>

</div>