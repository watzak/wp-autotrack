<?php

class Wp_Autotrack {

	public $mainfilepath; //__FILE__

	function __construct($mainfilepath) {

		$this->$mainfilepath = $mainfilepath;
		// echo $this->$mainfilepath;


		// add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		add_action( 'admin_menu', array( $this, 'menu_menu' ));
		add_action( 'admin_init', array( $this, 'update_autotracking' ));
		add_filter( 'plugin_action_links_' . plugin_basename($this->$mainfilepath), array( $this, 'wp_autotrack_action_link' ));
		add_action( 'wp_footer',  array( $this, 'autotrack_output') );
	}


	function wp_autotrack_action_link($links) {
		$links[] = '<a href="admin.php?page=autotrack">Settings</a>';
  	return $links;
	}

	function settings_menu() {
		add_options_page(
			'Page Title',
			'WP Autotrack Google Analytics',
			'manage_options',
			'autotrack',
			array(
				$this,
				'settings_page'
			)
		);
	}

	function menu_menu() {

	  $page_title = 'WP Autotrack Google Analytics';
	  $menu_title = 'WP Autotrack Google Analytics';
	  $capability = 'manage_options';
	  $menu_slug  = 'autotrack';
	  $function   = 'settings_page';
	  $icon_url   = 'dashicons-analytics';
	  $position   = 80;

	  add_menu_page( $page_title,
	                 $menu_title,
	                 $capability,
	                 $menu_slug,
									 array(
						 				$this,
						 				'settings_page'
						 			),
	                 $icon_url,
	                 $position );

	}



	function settings_page() {
		$eventtracker = get_option( 'eventtracker' );
		$outboundFormTracker = get_option( 'outboundFormTracker' );
		$outboundLinkTracker = get_option( 'outboundLinkTracker' );
		$socialWidgetTracker = get_option( 'socialWidgetTracker' );
		?>
	  <h1>WP Autotrack Google Analytics</h1>
	  <form method="post" action="options.php">
	    <?php settings_fields( 'autotrack-settings' ); ?>
	    <?php do_settings_sections( 'autotrack-settings' ); ?>
	    <table class="form-table">
				<tr valign="top">
	      <td style="font-weight:bold;">Google Analytics Tracking ID</td>
	      <td><input type="text" name="gaid" value="<?php echo get_option( 'gaid' ); ?>" placeholder="UA-########-#"/></td>
	      <td>The tracking ID is a string like UA-000000-01 => <a href="https://support.google.com/analytics/answer/1032385?hl=en" target="_blank">Tracking ID</a></td>
	      </tr>
				<tr valign="top">
	      <td style="font-weight:bold;">EventTracker</td>
	      <td><input type="checkbox" name="eventtracker" value="1" <?php checked( 1,  $eventtracker); ?> /></td>
	      <td>Enables default declarative event tracking, via HTML attributes in the markup. => <a href="https://github.com/googleanalytics/autotrack/blob/master/docs/plugins/event-tracker.md" target="_blank">Event Tracker</a></td>
	      </tr>
				<tr valign="top">
	      <td style="font-weight:bold;">OutboundFormTracker</td>
	      <td><input type="checkbox" name="outboundFormTracker" value="1" <?php checked( 1,  $outboundFormTracker); ?> /></td>
	      <td>Automatically tracks default form submits to external domains. => <a href="https://github.com/googleanalytics/autotrack/blob/master/docs/plugins/outbound-form-tracker.md" target="_blank">Outbound Form Tracker</a></td>
	      </tr>
				<tr valign="top">
	      <td style="font-weight:bold;">OutboundLinkTracker</td>
	      <td><input type="checkbox" name="outboundLinkTracker" value="1" <?php checked( 1,  $outboundLinkTracker); ?> /></td>
	      <td>Automatically tracks default link clicks to external domains. => <a href="https://github.com/googleanalytics/autotrack/blob/master/docs/plugins/outbound-link-tracker.md" target="_blank">Outbound Link Tracker</a></td>
	      </tr>
				<tr valign="top">
	      <td style="font-weight:bold;">SocialWidgetTracker</td>
	      <td><input type="checkbox" name="socialWidgetTracker" value="1" <?php checked( 1,  $socialWidgetTracker); ?> /></td>
	      <td>Automatically tracks default link clicks to external domains. => <a href="https://github.com/googleanalytics/autotrack/blob/master/docs/plugins/social-widget-tracker.md" target="_blank">Social Widget Tracker</a></td>
	      </tr>

	    </table>
	    <?php submit_button(); ?>
	  </form>
		<?php

	}

	function update_autotracking() {
		register_setting( 'autotrack-settings', 'gaid' );
		register_setting( 'autotrack-settings', 'eventtracker' );
		register_setting( 'autotrack-settings', 'outboundFormTracker' );
		register_setting( 'autotrack-settings', 'outboundLinkTracker' );
		register_setting( 'autotrack-settings', 'socialWidgetTracker' );
	}

	function autotrack_output() {
		$gaid = get_option('gaid', null);
		$eventtracker = get_option('eventtracker', null);
		$outboundFormTracker = get_option('outboundFormTracker', null);
		$outboundLinkTracker = get_option('outboundLinkTracker', null);
		$socialWidgetTracker = get_option('socialWidgetTracker', null);
		if ($gaid !== '') {
		?>
		<script>
		window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
		ga('create', '<?= get_option('gaid') ?>', 'auto');

		<?php if ($eventtracker === '1') { ?>
		ga('require', 'eventTracker');
		<?php } ?>
		<?php if ($outboundFormTracker === '1') { ?>
		ga('require', 'outboundFormTracker');
		<?php } ?>
		<?php if ($outboundLinkTracker === '1') { ?>
		ga('require', 'outboundLinkTracker');
		<?php } ?>
		<?php if ($socialWidgetTracker === '1') { ?>
		ga('require', 'socialWidgetTracker');
		<?php } ?>

		ga('send', 'pageview');

		</script>
		<script async src='https://www.google-analytics.com/analytics.js'></script>
		<script async src='<?=plugins_url()?>/wp-autotrack/js/autotrack.js'></script>

		<?php } ?>
	<?php
  }

}

?>
