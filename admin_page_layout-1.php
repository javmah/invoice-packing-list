<div class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1><?php esc_attr_e( 'Heading', 'WpAdminStyle' ); ?></h1>
	<div id="poststuff">

		<div id="post-body" class="metabox-holder columns-1">

			<!-- main content -->
			<div id="post-body-content">
				<!-- Tab Nav Starts  -->

				<!-- <h2 class="nav-tab-wrapper">
					<a href="#" id='tab1' v-on:click="isActive = !isActive"  v-bind:class="{ active: isActive } class="nav-tab">Tab #1</a>
					<a href="#" id='tab1' class="nav-tab nav-tab-active">Tab #2</a>
					<a href="#" id='tab1' class="nav-tab">Tab #3</a>
				</h2> -->

				 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
				 <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
				 <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/cupertino/jquery-ui.css">

				<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.min.js"></script>

				<div id="app">
				  <p>{{ message }}</p>
				  <button v-on:click="reverseMessage">Reverse Message</button>
				</div> -->


				<!-- <script type="text/javascript">
					var app5 = new Vue({
					  el: '#app',
					  data: {
					    message: 'Hello Vue.js!',
					    id :'',
					  },
					  methods: {
					    reverseMessage: function () {
					      this.message = this.message.split('').reverse().join('')
					    }
					  }
					})
				</script> -->


				<!-- Starts  -->
				<!-- 
				<div id="tabs">
				  <ul>
				    <li><a href="#tabs-1">Genaral Settings</a></li>
				    <li><a href="#tabs-2">Invoice </a></li>
				    <li><a href="#tabs-3"> Paking Slip </a></li>
				  </ul>
				  <div id="tabs-1">

				    <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.

				    </p>

				  </div>
				  <div id="tabs-2">

				    <p>
				    Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.
					</p>

				  </div>
				  <div id="tabs-3">

				    <p>
				    Mauris eleifend est et turpis. Duis id erat. Suspendisse potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel enim commodo pellentesque. Praesent eu risus hendrerit ligula tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec, luctus a, lacus.
					</p>

				    <p>
				    Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.
					</p>

				  </div>
				</div>
				-->

				<?php
				// create custom plugin settings menu
				add_action('admin_menu', 'my_cool_plugin_create_menu');

				function my_cool_plugin_create_menu() {

					//create new top-level menu
					add_menu_page('My Cool Plugin Settings', 'Cool Settings', 'administrator', __FILE__, 'my_cool_plugin_settings_page' , plugins_url('/images/icon.png', __FILE__) );

					//call register settings function
					add_action( 'admin_init', array($this, 'register_my_cool_plugin_settings' ) );
				}


				function register_my_cool_plugin_settings() {
					//register our settings
					register_setting( 'my-cool-plugin-settings-group', 'new_option_name' );
					register_setting( 'my-cool-plugin-settings-group', 'some_other_option' );
					register_setting( 'my-cool-plugin-settings-group', 'option_etc' );
				}

				function my_cool_plugin_settings_page() {
				?>
				<div class="wrap">
				<h1>Your Plugin Name</h1>

				<form method="post" action="options.php">
				    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
				    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
				    <table class="form-table">
				        <tr valign="top">
				        <th scope="row">New Option Name</th>
				        <td><input type="text" name="new_option_name" value="<?php echo esc_attr( get_option('new_option_name') ); ?>" /></td>
				        </tr>
				         
				        <tr valign="top">
				        <th scope="row">Some Other Option</th>
				        <td><input type="text" name="some_other_option" value="<?php echo esc_attr( get_option('some_other_option') ); ?>" /></td>
				        </tr>
				        
				        <tr valign="top">
				        <th scope="row">Options, Etc.</th>
				        <td><input type="text" name="option_etc" value="<?php echo esc_attr( get_option('option_etc') ); ?>" /></td>
				        </tr>
				    </table>
				    
				    <?php submit_button(); ?>

				</form>
				</div>
				<?php } ?>

				<!-- Ends  -->

				<script type="text/javascript">
					jQuery("document").ready(function() {
					    jQuery( "#tabs" ).tabs();
					});
				</script>

				
				
				<!-- Tab Nav Ends  -->
			</div>
			<!-- post-body-content -->
		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->

</div> <!-- .wrap -->
