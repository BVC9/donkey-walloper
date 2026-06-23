<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VB_Ajax {

	private static $instance = null;

	public static function instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'wp_ajax_vb_save_layout', array( $this, 'save_layout' ) );
		add_action( 'wp_ajax_vb_save_block', array( $this, 'save_block' ) );
		add_action( 'wp_ajax_vb_delete_block', array( $this, 'delete_block' ) );
		add_action( 'wp_ajax_nopriv_vb_submit_form', array( $this, 'submit_form' ) );
		add_action( 'wp_ajax_vb_submit_form', array( $this, 'submit_form' ) );
	}

	public function save_layout() {
		check_ajax_referer( 'vb_builder_nonce', 'nonce' );

		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$layout  = isset( $_POST['layout'] ) ? wp_unslash( $_POST['layout'] ) : '';

		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( array( 'message' => 'Permission denied.' ) );
		}

		$decoded = json_decode( $layout, true );
		if ( ! is_array( $decoded ) ) {
			wp_send_json_error( array( 'message' => 'Invalid layout data.' ) );
		}
		if ( isset( $decoded['rows'] ) && ! is_array( $decoded['rows'] ) ) {
			wp_send_json_error( array( 'message' => 'Invalid row data.' ) );
		}

		update_post_meta( $post_id, '_vb_layout', wp_json_encode( $decoded ) );
		update_post_meta( $post_id, '_vb_enabled', '1' );

		wp_send_json_success( array( 'message' => 'Saved.' ) );
	}

	public function save_block() {
		check_ajax_referer( 'vb_builder_nonce', 'nonce' );
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => 'Permission denied.' ) );
		}
		$name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$block = isset( $_POST['block'] ) ? json_decode( wp_unslash( $_POST['block'] ), true ) : null;
		if ( ! $name || ! is_array( $block ) ) {
			wp_send_json_error( array( 'message' => 'Invalid reusable block.' ) );
		}
		$blocks = get_option( 'vb_reusable_blocks', array() );
		if ( ! is_array( $blocks ) ) {
			$blocks = array();
		}
		$id = 'block_' . time() . '_' . wp_rand( 1000, 9999 );
		$blocks[ $id ] = array(
			'id'      => $id,
			'name'    => $name,
			'created' => current_time( 'mysql' ),
			'block'   => $block,
		);
		update_option( 'vb_reusable_blocks', $blocks, false );
		wp_send_json_success( array( 'message' => 'Block saved.', 'blocks' => array_values( $blocks ) ) );
	}

	public function delete_block() {
		check_ajax_referer( 'vb_builder_nonce', 'nonce' );
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => 'Permission denied.' ) );
		}
		$id     = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
		$blocks = get_option( 'vb_reusable_blocks', array() );
		if ( is_array( $blocks ) && isset( $blocks[ $id ] ) ) {
			unset( $blocks[ $id ] );
			update_option( 'vb_reusable_blocks', $blocks, false );
		}
		wp_send_json_success( array( 'message' => 'Block deleted.', 'blocks' => array_values( is_array( $blocks ) ? $blocks : array() ) ) );
	}

	public function submit_form() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'vb_frontend_nonce' ) ) {
			wp_send_json_error( array( 'message' => 'Security check failed.' ) );
		}
		if ( ! empty( $_POST['website'] ) ) {
			wp_send_json_error( array( 'message' => 'Spam blocked.' ) );
		}
		$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
		$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
		$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
		$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
		if ( ! $name || ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => 'Please enter your name and a valid email.' ) );
		}
		$submissions = get_option( 'vb_form_submissions', array() );
		if ( ! is_array( $submissions ) ) {
			$submissions = array();
		}
		$entry = array(
			'date'    => current_time( 'mysql' ),
			'name'    => $name,
			'email'   => $email,
			'phone'   => $phone,
			'message' => $message,
			'page'    => esc_url_raw( wp_get_referer() ),
		);
		array_unshift( $submissions, $entry );
		$submissions = array_slice( $submissions, 0, 250 );
		update_option( 'vb_form_submissions', $submissions, false );
		wp_mail( get_option( 'admin_email' ), 'New Visual Builder form submission', "Name: $name\nEmail: $email\nPhone: $phone\n\n$message" );
		wp_send_json_success( array( 'message' => 'Thanks. Your message has been sent.' ) );
	}
}
