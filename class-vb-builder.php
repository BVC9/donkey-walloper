<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and renders the full-screen visual builder admin page.
 * This page hides the default WP admin chrome and shows a live,
 * edit-in-place canvas with a Divi-style dark settings sidebar.
 */
class VB_Builder {

	private static $instance = null;

	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_filter( 'admin_body_class', array( $this, 'maybe_add_body_class' ) );
		add_action( 'in_admin_header', array( $this, 'maybe_hide_admin_chrome' ) );
	}

	public function register_page() {
		add_submenu_page(
			null, // hidden from menu, only reachable via the "Launch Visual Builder" button
			'Visual Builder',
			'Visual Builder',
			'edit_posts',
			'vb-builder',
			array( $this, 'render_page' )
		);
	}

	private function is_builder_screen() {
		return isset( $_GET['page'] ) && $_GET['page'] === 'vb-builder';
	}

	public function maybe_add_body_class( $classes ) {
		if ( $this->is_builder_screen() ) {
			$classes .= ' vb-fullscreen-builder';
		}
		return $classes;
	}

	/**
	 * Hide the WP admin bar/menu/footer so the builder feels full-screen,
	 * similar to how Divi/Elementor take over the whole browser tab.
	 */
	public function maybe_hide_admin_chrome() {
		if ( ! $this->is_builder_screen() ) {
			return;
		}
		echo '<style>
			#wpadminbar, #adminmenumain, #wpfooter, .notice, #screen-meta-links, #screen-meta { display: none !important; }
			html.wp-toolbar { padding-top: 0 !important; }
			#wpcontent, #wpbody-content { margin-left: 0 !important; padding: 0 !important; }
			#wpbody-content .wrap { margin: 0 !important; }
		</style>';
	}

	public function enqueue_assets( $hook ) {
		if ( ! $this->is_builder_screen() ) {
			return;
		}

		wp_enqueue_style( 'vb-builder-css', VB_URL . 'assets/css/builder.css', array(), VB_VERSION );
		wp_enqueue_style( 'vb-frontend-css', VB_URL . 'assets/css/frontend.css', array(), VB_VERSION );

		wp_enqueue_media(); // WP media library for image/gallery fields

		wp_enqueue_script( 'sortablejs', 'https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js', array(), '1.15.2', true );
		wp_enqueue_script( 'vb-builder-js', VB_URL . 'assets/js/builder.js', array( 'jquery', 'sortablejs' ), VB_VERSION, true );

		$post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : 0;
		$layout  = $post_id ? get_post_meta( $post_id, '_vb_layout', true ) : '';
		$layout  = $layout ? json_decode( $layout, true ) : array();
		if ( is_array( $layout ) && ! isset( $layout['rows'] ) ) {
			$layout = array( 'globals' => array(), 'rows' => $layout );
		}

		wp_localize_script( 'vb-builder-js', 'VB_DATA', array(
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'vb_builder_nonce' ),
			'postId'   => $post_id,
			'layout'   => $layout,
			'modules'  => VB_Modules::get_modules(),
			'previewUrl' => $post_id ? get_permalink( $post_id ) : '',
			'editUrl'    => $post_id ? get_edit_post_link( $post_id, '' ) : admin_url( 'edit.php' ),
			'blocks'     => array_values( (array) get_option( 'vb_reusable_blocks', array() ) ),
		) );
	}

	public function render_page() {
		$post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : 0;
		$title   = $post_id ? get_the_title( $post_id ) : 'Untitled';
		?>
		<div id="vb-app" class="vb-app">

			<div class="vb-topbar">
				<div class="vb-topbar-left">
					<span class="vb-logo">Visual Builder</span>
					<span class="vb-page-title"><?php echo esc_html( $title ); ?></span>
				</div>
				<div class="vb-topbar-center">
					<button class="vb-tb-btn" id="vb-global-btn">Global Styles</button>
					<button class="vb-tb-btn" id="vb-template-btn">Templates</button>
					<button class="vb-tb-btn" id="vb-blocks-btn">Saved Blocks</button>
					<button class="vb-tb-btn" id="vb-export-btn">Export</button>
					<button class="vb-tb-btn" id="vb-import-btn">Import</button>
					<button class="vb-tb-btn" id="vb-client-mode-btn">Client Mode</button>
					<button class="vb-tb-btn" id="vb-add-row">+ Add Row</button>
					<button class="vb-tb-btn" id="vb-undo-btn" title="Undo">Undo</button>
					<button class="vb-tb-btn" id="vb-redo-btn" title="Redo">Redo</button>
					<span class="vb-device-buttons" id="vb-device-buttons">
						<button class="vb-tb-btn vb-device-btn active" data-device="desktop" title="Desktop preview">Desktop</button>
						<button class="vb-tb-btn vb-device-btn" data-device="tablet" title="Tablet preview">Tablet</button>
						<button class="vb-tb-btn vb-device-btn" data-device="mobile" title="Mobile preview">Mobile</button>
					</span>
				</div>
				<div class="vb-topbar-right">
					<span id="vb-save-status" class="vb-save-status"></span>
					<button class="vb-tb-btn vb-tb-btn-secondary" id="vb-exit-btn">Exit</button>
					<button class="vb-tb-btn vb-tb-btn-primary" id="vb-save-btn">Save</button>
				</div>
			</div>

			<div class="vb-canvas-wrap">
				<div id="vb-canvas" class="vb-canvas vb-device-desktop">
					<!-- Rows injected by builder.js -->
					<div class="vb-empty-state" id="vb-empty-state">
						<p>This page is empty.</p>
						<button class="vb-tb-btn vb-tb-btn-primary" id="vb-add-row-empty">+ Add Your First Row</button>
					</div>
				</div>
			</div>


			<!-- Global style modal -->
			<div class="vb-modal" id="vb-global-modal">
				<div class="vb-modal-inner">
					<h3>Global Design System</h3>
					<p class="vb-modal-help">Set reusable brand values for this page.</p>
					<div class="vb-global-grid">
						<label>Primary Color <input type="color" id="vb-global-primary" /></label>
						<label>Secondary Color <input type="color" id="vb-global-secondary" /></label>
						<label>Font Family <input type="text" id="vb-global-font" placeholder="inherit, Arial, Georgia" /></label>
						<label>Content Width (px) <input type="number" id="vb-global-width" /></label>
						<label>Button Radius (px) <input type="number" id="vb-global-radius" /></label>
					</div>
					<button class="vb-tb-btn vb-tb-btn-primary" id="vb-global-apply">Apply</button>
					<button class="vb-modal-close" id="vb-global-modal-close">Cancel</button>
				</div>
			</div>


			<!-- Template library modal -->
			<div class="vb-modal" id="vb-template-modal">
				<div class="vb-modal-inner">
					<h3>Template / Layout Library</h3>
					<p class="vb-modal-help">Insert a ready-made starting layout, then edit modules and responsive values.</p>
					<div class="vb-template-grid" id="vb-template-grid"><!-- injected --></div>
					<button class="vb-modal-close" id="vb-template-modal-close">Cancel</button>
				</div>
			</div>


			<!-- Saved reusable blocks modal -->
			<div class="vb-modal" id="vb-blocks-modal">
				<div class="vb-modal-inner">
					<h3>Saved Reusable Blocks</h3>
					<p class="vb-modal-help">Insert saved rows/sections into this page.</p>
					<div class="vb-template-grid" id="vb-blocks-grid"></div>
					<button class="vb-modal-close" id="vb-blocks-modal-close">Close</button>
				</div>
			</div>

			<!-- Import / export modal -->
			<div class="vb-modal" id="vb-io-modal">
				<div class="vb-modal-inner vb-modal-wide">
					<h3 id="vb-io-title">Layout JSON</h3>
					<textarea id="vb-io-text" class="vb-io-text" placeholder="Paste exported JSON here"></textarea>
					<button class="vb-tb-btn vb-tb-btn-primary" id="vb-io-apply">Apply Import</button>
					<button class="vb-modal-close" id="vb-io-modal-close">Close</button>
				</div>
			</div>

			<!-- Row layout picker modal -->
			<div class="vb-modal" id="vb-row-modal">
				<div class="vb-modal-inner">
					<h3>Select a Row Layout</h3>
					<div class="vb-layout-grid">
						<button class="vb-layout-option" data-cols="1">
							<span class="vb-layout-preview"><i style="width:100%"></i></span>1 Column
						</button>
						<button class="vb-layout-option" data-cols="2">
							<span class="vb-layout-preview"><i style="width:48%"></i><i style="width:48%"></i></span>2 Columns
						</button>
						<button class="vb-layout-option" data-cols="3">
							<span class="vb-layout-preview"><i style="width:31%"></i><i style="width:31%"></i><i style="width:31%"></i></span>3 Columns
						</button>
						<button class="vb-layout-option" data-cols="4">
							<span class="vb-layout-preview"><i style="width:23%"></i><i style="width:23%"></i><i style="width:23%"></i><i style="width:23%"></i></span>4 Columns
						</button>
					</div>
					<button class="vb-modal-close" id="vb-row-modal-close">Cancel</button>
				</div>
			</div>

			<!-- Module picker modal -->
			<div class="vb-modal" id="vb-module-modal">
				<div class="vb-modal-inner">
					<h3>Select a Module</h3>
					<div class="vb-module-grid" id="vb-module-grid"><!-- injected --></div>
					<button class="vb-modal-close" id="vb-module-modal-close">Cancel</button>
				</div>
			</div>

			<!-- Right-hand settings sidebar (Divi-style dark panel) -->
			<div class="vb-sidebar" id="vb-sidebar">
				<div class="vb-sidebar-header">
					<span id="vb-sidebar-title">Module Settings</span>
					<button id="vb-sidebar-close">&times;</button>
				</div>
				<div class="vb-sidebar-tabs">
					<button class="vb-tab active" data-tab="content">Content</button>
					<button class="vb-tab" data-tab="design">Design</button>
				</div>
				<div class="vb-sidebar-body" id="vb-sidebar-body"><!-- injected fields --></div>
				<div class="vb-sidebar-footer">
					<button id="vb-delete-element" class="vb-tb-btn vb-tb-btn-danger">Delete</button>
				</div>
			</div>

		</div>
		<?php
	}
}
