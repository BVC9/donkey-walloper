<?php
/**
 * Plugin Name: Visual Builder
 * Plugin URI: https://example.com
 * Description: A visual drag-and-drop page builder for WordPress, inspired by Divi. Build pages with rows, columns and modules using a live front-end preview editor.
 * Version: 1.3.0
 * Author: Your Name
 * Text Domain: visual-builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VB_VERSION', '1.3.0' );
define( 'VB_PATH', plugin_dir_path( __FILE__ ) );
define( 'VB_URL', plugin_dir_url( __FILE__ ) );

require_once VB_PATH . 'includes/class-vb-modules.php';
require_once VB_PATH . 'includes/class-vb-renderer.php';
require_once VB_PATH . 'includes/class-vb-builder.php';
require_once VB_PATH . 'includes/class-vb-ajax.php';

/**
 * Core plugin bootstrap.
 */
final class Visual_Builder {

	private static $instance = null;

	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'register_post_meta' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_filter( 'the_content', array( $this, 'maybe_render_builder_content' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_builder_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'admin_post_vb_launch_builder', array( $this, 'redirect_to_builder' ) );
		add_action( 'admin_menu', array( $this, 'register_submissions_page' ) );

		VB_Builder::instance();
		VB_Ajax::instance();
	}


	public function register_submissions_page() {
		add_management_page(
			'Visual Builder Submissions',
			'VB Form Submissions',
			'manage_options',
			'vb-form-submissions',
			array( $this, 'render_submissions_page' )
		);
	}

	public function render_submissions_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$submissions = get_option( 'vb_form_submissions', array() );
		if ( ! is_array( $submissions ) ) {
			$submissions = array();
		}
		?>
		<div class="wrap">
			<h1>Visual Builder Form Submissions</h1>
			<p>Recent submissions from Visual Builder forms. The newest submissions appear first.</p>
			<table class="widefat striped">
				<thead><tr><th>Date</th><th>Name</th><th>Email</th><th>Phone</th><th>Message</th><th>Page</th></tr></thead>
				<tbody>
				<?php if ( empty( $submissions ) ) : ?>
					<tr><td colspan="6">No submissions yet.</td></tr>
				<?php else : ?>
					<?php foreach ( $submissions as $entry ) : ?>
						<tr>
							<td><?php echo esc_html( $entry['date'] ?? '' ); ?></td>
							<td><?php echo esc_html( $entry['name'] ?? '' ); ?></td>
							<td><a href="mailto:<?php echo esc_attr( $entry['email'] ?? '' ); ?>"><?php echo esc_html( $entry['email'] ?? '' ); ?></a></td>
							<td><?php echo esc_html( $entry['phone'] ?? '' ); ?></td>
							<td><?php echo nl2br( esc_html( $entry['message'] ?? '' ) ); ?></td>
							<td><?php if ( ! empty( $entry['page'] ) ) : ?><a href="<?php echo esc_url( $entry['page'] ); ?>" target="_blank" rel="noopener">View</a><?php endif; ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public function register_post_meta() {
		register_post_meta(
			'',
			'_vb_layout',
			array(
				'type'         => 'string',
				'single'       => true,
				'show_in_rest' => false,
			)
		);
		register_post_meta(
			'',
			'_vb_enabled',
			array(
				'type'         => 'boolean',
				'single'       => true,
				'show_in_rest' => false,
			)
		);
	}

	/**
	 * Meta box shown on Page/Post edit screen with a button to launch the
	 * full screen visual builder.
	 */
	public function add_meta_box() {
		$post_types = array( 'post', 'page' );
		foreach ( $post_types as $pt ) {
			add_meta_box(
				'vb_builder_box',
				'Visual Builder',
				array( $this, 'render_meta_box' ),
				$pt,
				'normal',
				'high'
			);
		}
	}

	public function render_meta_box( $post ) {
		$enabled    = get_post_meta( $post->ID, '_vb_enabled', true );
		$builder_url = admin_url( 'admin.php?page=vb-builder&post=' . $post->ID );
		?>
		<div class="vb-metabox">
			<?php wp_nonce_field( 'vb_save_meta_box', 'vb_meta_box_nonce' ); ?>
			<label>
				<input type="checkbox" name="vb_enabled" value="1" <?php checked( $enabled, '1' ); ?> />
				Use Visual Builder for this page
			</label>
			<p>
				<a href="<?php echo esc_url( $builder_url ); ?>" class="button button-primary button-hero">
					Launch Visual Builder
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * If a page has builder content, render it instead of normal content.
	 */
	public function maybe_render_builder_content( $content ) {
		global $post;
		if ( ! $post ) {
			return $content;
		}
		$enabled = get_post_meta( $post->ID, '_vb_enabled', true );
		$layout  = get_post_meta( $post->ID, '_vb_layout', true );

		if ( $enabled && $layout ) {
			$data = json_decode( $layout, true );
			if ( is_array( $data ) ) {
				return VB_Renderer::render_layout( $data );
			}
		}
		return $content;
	}

	public function enqueue_frontend_assets() {
		global $post;
		if ( ! $post ) {
			return;
		}
		$enabled = get_post_meta( $post->ID, '_vb_enabled', true );
		if ( $enabled ) {
			wp_enqueue_style( 'vb-frontend', VB_URL . 'assets/css/frontend.css', array(), VB_VERSION );
			wp_enqueue_script( 'vb-frontend', VB_URL . 'assets/js/frontend.js', array(), VB_VERSION, true );
			wp_localize_script( 'vb-frontend', 'VB_FRONTEND', array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'vb_frontend_nonce' ),
			) );
		}
	}

	public function maybe_enqueue_builder_assets( $hook ) {
		// Handled inside VB_Builder for the dedicated admin page.
	}

	public function redirect_to_builder() {
		// Reserved for future use (e.g. nonce-protected launch links).
	}
}

add_action( 'plugins_loaded', array( 'Visual_Builder', 'instance' ) );

/**
 * Save the "enabled" checkbox from the meta box.
 */
add_action( 'save_post', function( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	$nonce = isset( $_POST['vb_meta_box_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['vb_meta_box_nonce'] ) ) : '';
	if ( ! $nonce || ! wp_verify_nonce( $nonce, 'vb_save_meta_box' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['vb_enabled'] ) && '1' === wp_unslash( $_POST['vb_enabled'] ) ) {
		update_post_meta( $post_id, '_vb_enabled', '1' );
		return;
	}

	delete_post_meta( $post_id, '_vb_enabled' );
} );

/**
 * Activation: nothing to set up yet, but reserved for future migrations.
 */
register_activation_hook( __FILE__, function() {
	// Placeholder for future DB setup (templates table, etc).
} );
