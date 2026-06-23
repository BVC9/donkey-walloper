<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders a saved JSON layout (rows > columns > modules) into front-end HTML.
 * Inline styles are used per-element so the builder stays self-contained
 * without relying on a separate compiled stylesheet per page.
 */
class VB_Renderer {

	public static function render_layout( $layout ) {
		if ( empty( $layout ) || ! is_array( $layout ) ) {
			return '';
		}

		$globals = isset( $layout['globals'] ) && is_array( $layout['globals'] ) ? $layout['globals'] : array();
		$rows    = isset( $layout['rows'] ) && is_array( $layout['rows'] ) ? $layout['rows'] : $layout;
		$style   = self::global_style( $globals );
		$html = '<div class="vb-page" style="' . $style . '">';
		foreach ( $rows as $row ) {
			$html .= self::render_row( $row );
		}
		$html .= '</div>';

		return $html;
	}


	protected static function global_style( $globals ) {
		$defaults = array(
			'primary_color' => '#7c3aed',
			'secondary_color' => '#111827',
			'font_family' => 'inherit',
			'content_width' => '1200',
			'button_radius' => '4',
		);
		$g = wp_parse_args( $globals, $defaults );
		return sprintf(
			'--vb-primary:%s;--vb-secondary:%s;--vb-font-family:%s;--vb-content-width:%spx;--vb-button-radius:%spx;',
			esc_attr( $g['primary_color'] ),
			esc_attr( $g['secondary_color'] ),
			esc_attr( $g['font_family'] ),
			esc_attr( $g['content_width'] ),
			esc_attr( $g['button_radius'] )
		);
	}

	protected static function render_row( $row ) {
		$columns     = isset( $row['columns'] ) ? $row['columns'] : array();
		$bg          = isset( $row['settings']['bg_color'] ) ? $row['settings']['bg_color'] : '';
		$padding     = isset( $row['settings']['padding'] ) ? $row['settings']['padding'] : '40px 20px';
		$style       = sprintf( 'padding:%s;%s', esc_attr( $padding ), $bg ? 'background-color:' . esc_attr( $bg ) . ';' : '' );

		$html  = '<div class="vb-row" style="' . $style . '">';
		$html .= '<div class="vb-row-inner">';
		foreach ( $columns as $column ) {
			$html .= self::render_column( $column, count( $columns ) );
		}
		$html .= '</div></div>';

		return $html;
	}

	protected static function responsive_var_style( $settings, $base_key, $css_var, $unit = 'px' ) {
		$desktop = isset( $settings[ $base_key ] ) && $settings[ $base_key ] !== '' ? $settings[ $base_key ] : '';
		$tablet  = isset( $settings[ $base_key . '_tablet' ] ) && $settings[ $base_key . '_tablet' ] !== '' ? $settings[ $base_key . '_tablet' ] : $desktop;
		$mobile  = isset( $settings[ $base_key . '_mobile' ] ) && $settings[ $base_key . '_mobile' ] !== '' ? $settings[ $base_key . '_mobile' ] : $tablet;
		if ( $desktop === '' ) {
			return '';
		}
		return sprintf( '--%1$s:%2$s%5$s;--%1$s-tablet:%3$s%5$s;--%1$s-mobile:%4$s%5$s;', esc_attr( $css_var ), esc_attr( $desktop ), esc_attr( $tablet ), esc_attr( $mobile ), esc_attr( $unit ) );
	}

	protected static function render_column( $column, $total_columns ) {
		$width    = $total_columns > 0 ? round( 100 / $total_columns, 4 ) : 100;
		$modules  = isset( $column['modules'] ) ? $column['modules'] : array();

		$html  = '<div class="vb-col" style="width:' . esc_attr( $width ) . '%;">';
		foreach ( $modules as $module ) {
			$html .= self::render_module( $module );
		}
		$html .= '</div>';

		return $html;
	}

	protected static function wrap_module_visibility( $html, $settings ) {
		$visibility = isset( $settings['visibility'] ) ? sanitize_html_class( $settings['visibility'] ) : 'all';
		if ( empty( $html ) ) {
			return '';
		}
		$animation = isset( $settings['animation'] ) ? sanitize_html_class( $settings['animation'] ) : 'none';
		return '<div class="vb-visibility-' . esc_attr( $visibility ) . ' vb-anim-' . esc_attr( $animation ) . '">' . $html . '</div>';
	}

	protected static function render_video_embed( $url ) {
		$embed = wp_oembed_get( $url );
		if ( $embed ) {
			return $embed;
		}

		if ( preg_match( '/\.(mp4|webm|ogg)(?:[?#].*)?$/i', $url, $matches ) ) {
			$mime_types = array(
				'mp4'  => 'video/mp4',
				'webm' => 'video/webm',
				'ogg'  => 'video/ogg',
			);
			$extension  = strtolower( $matches[1] );
			$mime_type  = isset( $mime_types[ $extension ] ) ? $mime_types[ $extension ] : 'video/mp4';

			return sprintf(
				'<video class="vb-video-player" controls preload="metadata"><source src="%s" type="%s" /></video>',
				esc_url( $url ),
				esc_attr( $mime_type )
			);
		}

		return '<a href="' . esc_url( $url ) . '">' . esc_html( $url ) . '</a>';
	}

	protected static function render_module( $module ) {
		$type     = isset( $module['type'] ) ? $module['type'] : '';
		$settings = isset( $module['settings'] ) ? $module['settings'] : array();
		$html = '';

		switch ( $type ) {
			case 'text':
				$html = sprintf('<div class="vb-module vb-text" style="%scolor:%s;text-align:%s;">%s</div>', self::responsive_var_style( $settings, 'font_size', 'vb-font-size' ), esc_attr( $settings['text_color'] ?? '#333' ), esc_attr( $settings['align'] ?? 'left' ), wp_kses_post( $settings['content'] ?? '' ) );
				break;
			case 'heading':
				$tag = in_array( $settings['tag'] ?? 'h2', array( 'h1', 'h2', 'h3', 'h4' ), true ) ? $settings['tag'] : 'h2';
				$html = sprintf('<%1$s class="vb-module vb-heading" style="%2$scolor:%3$s;text-align:%4$s;">%5$s</%1$s>', esc_attr( $tag ), self::responsive_var_style( $settings, 'font_size', 'vb-font-size' ), esc_attr( $settings['text_color'] ?? '#1a1a1a' ), esc_attr( $settings['align'] ?? 'left' ), esc_html( $settings['content'] ?? '' ) );
				break;
			case 'button':
				$html = sprintf('<div class="vb-module vb-button-wrap" style="text-align:%s;"><a class="vb-button" href="%s" style="background-color:%s;color:%s;">%s</a></div>', esc_attr( $settings['align'] ?? 'left' ), esc_url( $settings['url'] ?? '#' ), esc_attr( $settings['bg_color'] ?? 'var(--vb-primary)' ), esc_attr( $settings['text_color'] ?? '#fff' ), esc_html( $settings['text'] ?? 'Click Here' ) );
				break;
			case 'image':
				if ( ! empty( $settings['src'] ) ) $html = sprintf('<div class="vb-module vb-image"><img src="%s" alt="%s" style="width:%s%%;height:auto;" /></div>', esc_url( $settings['src'] ), esc_attr( $settings['alt'] ?? '' ), esc_attr( $settings['width'] ?? '100' ) );
				break;
			case 'video':
				if ( ! empty( $settings['url'] ) ) $html = sprintf('<div class="vb-module vb-video"><div class="vb-video-embed">%s</div></div>', self::render_video_embed( $settings['url'] ) );
				break;
			case 'spacer':
				$html = sprintf('<div class="vb-module vb-spacer" style="height:%spx;"></div>', esc_attr( $settings['height'] ?? '40' ) );
				break;
			case 'gallery':
				$images = isset( $settings['images'] ) && is_array( $settings['images'] ) ? $settings['images'] : array(); $columns = max( 1, intval( $settings['columns'] ?? 3 ) ); $col_w = round( 100 / $columns, 4 );
				$html = '<div class="vb-module vb-gallery">'; foreach ( $images as $img ) { $html .= sprintf('<div class="vb-gallery-item" style="width:%s%%;"><img src="%s" alt="" /></div>', esc_attr( $col_w ), esc_url( $img ) ); } $html .= '</div>';
				break;
			case 'form':
				$show_phone = ( $settings['show_phone'] ?? 'yes' ) === 'yes';
				$html  = '<form class="vb-module vb-form" style="background-color:' . esc_attr( $settings['bg_color'] ?? '#f8fafc' ) . ';" method="post">';
				$html .= '<input type="text" name="website" value="" class="vb-hp" tabindex="-1" autocomplete="off" aria-hidden="true" />';
				$html .= '<h3>' . esc_html( $settings['title'] ?? 'Get in touch' ) . '</h3><input name="name" type="text" placeholder="Name" aria-label="Name" required /><input name="email" type="email" placeholder="Email" aria-label="Email" required />';
				if ( $show_phone ) { $html .= '<input name="phone" type="tel" placeholder="Phone" aria-label="Phone" />'; }
				$html .= '<textarea name="message" placeholder="Message" aria-label="Message"></textarea><button type="submit">' . esc_html( $settings['button_text'] ?? 'Send Message' ) . '</button><div class="vb-form-status" role="status" aria-live="polite" aria-atomic="true"></div></form>';
				break;
			case 'tabs':
				$tabs_id = 'vb-tabs-' . wp_rand( 10000, 99999 );
				$html    = '<div class="vb-module vb-tabs" data-vb-tabs="' . esc_attr( $tabs_id ) . '"><div class="vb-tabs-nav" role="tablist">';
				for ( $i = 1; $i <= 3; $i++ ) {
					$is_active = 1 === $i;
					$tab_id    = $tabs_id . '-tab-' . $i;
					$panel_id  = $tabs_id . '-panel-' . $i;
					$html     .= '<button type="button" id="' . esc_attr( $tab_id ) . '" class="' . ( $is_active ? 'active' : '' ) . '" data-vb-tab="' . esc_attr( $i ) . '" role="tab" aria-selected="' . ( $is_active ? 'true' : 'false' ) . '" aria-controls="' . esc_attr( $panel_id ) . '" tabindex="' . ( $is_active ? '0' : '-1' ) . '">' . esc_html( $settings[ 'tab_' . $i . '_title' ] ?? ( 'Tab ' . $i ) ) . '</button>';
				}
				$html .= '</div>';
				for ( $i = 1; $i <= 3; $i++ ) {
					$is_active = 1 === $i;
					$tab_id    = $tabs_id . '-tab-' . $i;
					$panel_id  = $tabs_id . '-panel-' . $i;
					$html     .= '<div id="' . esc_attr( $panel_id ) . '" class="vb-tab-panel ' . ( $is_active ? 'active' : '' ) . '" data-vb-panel="' . esc_attr( $i ) . '" role="tabpanel" aria-labelledby="' . esc_attr( $tab_id ) . '"' . ( $is_active ? '' : ' hidden' ) . '>' . wp_kses_post( $settings[ 'tab_' . $i . '_content' ] ?? '' ) . '</div>';
				}
				$html .= '</div>';
				break;
			case 'accordion':
			case 'faq_schema':
				$schema = array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array() );
				$html = '<div class="vb-module vb-accordion ' . ( 'faq_schema' === $type ? 'vb-faq-schema' : '' ) . '">';
				for ( $i = 1; $i <= 3; $i++ ) { $q = $settings[ 'item_' . $i . '_title' ] ?? ( 'Item ' . $i ); $a = $settings[ 'item_' . $i . '_content' ] ?? ''; $html .= '<details ' . ( 1 === $i ? 'open' : '' ) . '><summary>' . esc_html( $q ) . '</summary><div>' . wp_kses_post( $a ) . '</div></details>'; if ( 'faq_schema' === $type ) { $schema['mainEntity'][] = array( '@type' => 'Question', 'name' => wp_strip_all_tags( $q ), 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => wp_strip_all_tags( $a ) ) ); } }
				$html .= '</div>'; if ( 'faq_schema' === $type ) { $html .= '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>'; }
				break;
			case 'slider':
				$images = isset( $settings['images'] ) && is_array( $settings['images'] ) ? $settings['images'] : array(); $style = self::responsive_var_style( $settings, 'height', 'vb-slider-height' ); $html = '<div class="vb-module vb-slider" style="' . $style . '" aria-roledescription="carousel">';
				if ( empty( $images ) ) { $html .= '<div class="vb-slide active" aria-hidden="false"><span>No slider images selected.</span></div>'; } else { foreach ( $images as $index => $img ) { $html .= '<div class="vb-slide ' . ( 0 === $index ? 'active' : '' ) . '" aria-hidden="' . ( 0 === $index ? 'false' : 'true' ) . '" style="background-image:url(' . esc_url( $img ) . ');"></div>'; } }
				if ( ! empty( $settings['caption'] ) ) { $html .= '<div class="vb-slider-caption">' . esc_html( $settings['caption'] ) . '</div>'; } $html .= '</div>';
				break;
			case 'countdown':
				$target = trim( ( $settings['target_date'] ?? '' ) . ' ' . ( $settings['target_time'] ?? '23:59' ) );
				$html = '<div class="vb-module vb-countdown" data-vb-countdown="' . esc_attr( $target ) . '" style="background:' . esc_attr( $settings['bg_color'] ?? '#111827' ) . ';color:' . esc_attr( $settings['text_color'] ?? '#fff' ) . ';"><span class="vb-countdown-label">' . esc_html( $settings['label'] ?? 'Offer ends in' ) . '</span><div class="vb-countdown-units"><b data-unit="days">00</b><b data-unit="hours">00</b><b data-unit="minutes">00</b><b data-unit="seconds">00</b></div><small>Days&nbsp;&nbsp;Hours&nbsp;&nbsp;Minutes&nbsp;&nbsp;Seconds</small></div>';
				break;
			case 'pricing':
				$features = array_filter( array_map( 'trim', explode( "\n", $settings['features'] ?? '' ) ) );
				$html = '<div class="vb-module vb-pricing" style="--vb-pricing-accent:' . esc_attr( $settings['accent_color'] ?? '#7c3aed' ) . ';"><h3>' . esc_html( $settings['plan_name'] ?? 'Starter' ) . '</h3><div class="vb-price"><strong>' . esc_html( $settings['price'] ?? '£49' ) . '</strong><span>' . esc_html( $settings['period'] ?? '/month' ) . '</span></div><ul>'; foreach ( $features as $f ) { $html .= '<li>' . esc_html( $f ) . '</li>'; } $html .= '</ul><a class="vb-button" href="' . esc_url( $settings['button_url'] ?? '#' ) . '">' . esc_html( $settings['button_text'] ?? 'Choose Plan' ) . '</a></div>';
				break;
			case 'testimonial':
				$stars = str_repeat( '★', max( 1, min( 5, intval( $settings['stars'] ?? 5 ) ) ) );
				$html = '<div class="vb-module vb-testimonial">'; if ( ! empty( $settings['image'] ) ) { $html .= '<img src="' . esc_url( $settings['image'] ) . '" alt="" />'; } $html .= '<div><div class="vb-stars">' . esc_html( $stars ) . '</div><blockquote>' . wp_kses_post( $settings['quote'] ?? '' ) . '</blockquote><strong>' . esc_html( $settings['name'] ?? '' ) . '</strong><span>' . esc_html( $settings['role'] ?? '' ) . '</span></div></div>';
				break;

			case 'popup':
				$trigger = in_array( $settings['trigger'] ?? 'timed', array( 'button', 'timed', 'exit_intent' ), true ) ? $settings['trigger'] : 'timed';
				$delay = max( 0, intval( $settings['delay'] ?? 5 ) );
				$popup_id = 'vb-popup-' . wp_rand( 10000, 99999 );
				$title_id = $popup_id . '-title';
				$html  = '<div class="vb-module vb-popup-module" data-vb-popup-trigger="' . esc_attr( $trigger ) . '" data-vb-popup-delay="' . esc_attr( $delay ) . '" data-vb-popup-id="' . esc_attr( $popup_id ) . '">';
				if ( 'button' === $trigger ) { $html .= '<button type="button" class="vb-button vb-popup-open" aria-haspopup="dialog" aria-controls="' . esc_attr( $popup_id ) . '">' . esc_html( $settings['button_label'] ?? 'Open Offer' ) . '</button>'; }
				$html .= '<div class="vb-popup-overlay" id="' . esc_attr( $popup_id ) . '" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="' . esc_attr( $title_id ) . '"><div class="vb-popup-box" style="background:' . esc_attr( $settings['bg_color'] ?? '#fff' ) . ';"><button type="button" class="vb-popup-close" aria-label="Close popup">×</button><h3 id="' . esc_attr( $title_id ) . '">' . esc_html( $settings['title'] ?? 'Special Offer' ) . '</h3><div>' . wp_kses_post( $settings['content'] ?? '' ) . '</div><a class="vb-button" href="' . esc_url( $settings['cta_url'] ?? '#' ) . '">' . esc_html( $settings['cta_text'] ?? 'Learn More' ) . '</a></div></div></div>';
				break;
		}
		return self::wrap_module_visibility( $html, $settings );
	}
}
