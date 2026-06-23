<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registry of available builder modules.
 * Each module defines: label, icon, default settings, and field schema
 * used to generate the right-hand settings panel in the builder UI.
 */
class VB_Modules {

	public static function get_modules() {
		return array(
			'text' => array(
				'label'    => 'Text',
				'icon'     => 'dashicons-text',
				'defaults' => array(
					'content'    => 'Double-click to edit this text.',
					'text_color' => '#333333',
					'font_size'  => '16',
					'font_size_tablet' => '',
					'font_size_mobile' => '',
					'align'      => 'left',
				),
				'fields'   => array(
					'content'    => array( 'type' => 'textarea', 'label' => 'Content' ),
					'text_color' => array( 'type' => 'color', 'label' => 'Text Color' ),
					'font_size'  => array( 'type' => 'number', 'label' => 'Desktop Font Size (px)' ),
					'font_size_tablet' => array( 'type' => 'number', 'label' => 'Tablet Font Size (px)' ),
					'font_size_mobile' => array( 'type' => 'number', 'label' => 'Mobile Font Size (px)' ),
					'align'      => array( 'type' => 'select', 'label' => 'Alignment', 'options' => array( 'left', 'center', 'right' ) ),
				),
			),
			'heading' => array(
				'label'    => 'Heading',
				'icon'     => 'dashicons-heading',
				'defaults' => array(
					'content'    => 'Your Heading Here',
					'tag'        => 'h2',
					'text_color' => '#1a1a1a',
					'font_size'  => '36',
					'font_size_tablet' => '30',
					'font_size_mobile' => '24',
					'align'      => 'left',
				),
				'fields'   => array(
					'content'    => array( 'type' => 'text', 'label' => 'Heading Text' ),
					'tag'        => array( 'type' => 'select', 'label' => 'Tag', 'options' => array( 'h1', 'h2', 'h3', 'h4' ) ),
					'text_color' => array( 'type' => 'color', 'label' => 'Text Color' ),
					'font_size'  => array( 'type' => 'number', 'label' => 'Desktop Font Size (px)' ),
					'font_size_tablet' => array( 'type' => 'number', 'label' => 'Tablet Font Size (px)' ),
					'font_size_mobile' => array( 'type' => 'number', 'label' => 'Mobile Font Size (px)' ),
					'align'      => array( 'type' => 'select', 'label' => 'Alignment', 'options' => array( 'left', 'center', 'right' ) ),
				),
			),
			'button' => array(
				'label'    => 'Button',
				'icon'     => 'dashicons-button',
				'defaults' => array(
					'text'     => 'Click Here',
					'url'      => '#',
					'bg_color' => '#7c3aed',
					'text_color' => '#ffffff',
					'align'    => 'left',
				),
				'fields'   => array(
					'text'       => array( 'type' => 'text', 'label' => 'Button Text' ),
					'url'        => array( 'type' => 'text', 'label' => 'Link URL' ),
					'bg_color'   => array( 'type' => 'color', 'label' => 'Background Color' ),
					'text_color' => array( 'type' => 'color', 'label' => 'Text Color' ),
					'align'      => array( 'type' => 'select', 'label' => 'Alignment', 'options' => array( 'left', 'center', 'right' ) ),
				),
			),
			'image' => array(
				'label'    => 'Image',
				'icon'     => 'dashicons-format-image',
				'defaults' => array(
					'src'   => '',
					'alt'   => '',
					'width' => '100',
				),
				'fields'   => array(
					'src'   => array( 'type' => 'image', 'label' => 'Image' ),
					'alt'   => array( 'type' => 'text', 'label' => 'Alt Text' ),
					'width' => array( 'type' => 'number', 'label' => 'Width (%)' ),
				),
			),
			'video' => array(
				'label'    => 'Video',
				'icon'     => 'dashicons-video-alt3',
				'defaults' => array(
					'url' => '',
				),
				'fields'   => array(
					'url' => array( 'type' => 'text', 'label' => 'Video URL (YouTube/Vimeo/MP4)' ),
				),
			),
			'spacer' => array(
				'label'    => 'Spacer',
				'icon'     => 'dashicons-minus',
				'defaults' => array(
					'height' => '40',
				),
				'fields'   => array(
					'height' => array( 'type' => 'number', 'label' => 'Height (px)' ),
				),
			),
			'gallery' => array(
				'label'    => 'Gallery',
				'icon'     => 'dashicons-images-alt2',
				'defaults' => array(
					'images'  => array(),
					'columns' => '3',
				),
				'fields'   => array(
					'images'  => array( 'type' => 'gallery', 'label' => 'Images' ),
					'columns' => array( 'type' => 'number', 'label' => 'Columns' ),
				),
			),

			'form' => array(
				'label'    => 'Contact Form',
				'icon'     => 'dashicons-email-alt',
				'defaults' => array(
					'title' => 'Get in touch',
					'button_text' => 'Send Message',
					'show_phone' => 'yes',
					'bg_color' => '#f8fafc',
				),
				'fields'   => array(
					'title' => array( 'type' => 'text', 'label' => 'Form Title' ),
					'button_text' => array( 'type' => 'text', 'label' => 'Button Text' ),
					'show_phone' => array( 'type' => 'select', 'label' => 'Show Phone Field', 'options' => array( 'yes', 'no' ) ),
					'bg_color' => array( 'type' => 'color', 'label' => 'Background Color' ),
				),
			),
			'tabs' => array(
				'label'    => 'Tabs',
				'icon'     => 'dashicons-index-card',
				'defaults' => array(
					'tab_1_title' => 'Tab One',
					'tab_1_content' => 'Add your first tab content here.',
					'tab_2_title' => 'Tab Two',
					'tab_2_content' => 'Add your second tab content here.',
					'tab_3_title' => 'Tab Three',
					'tab_3_content' => 'Add your third tab content here.',
				),
				'fields'   => array(
					'tab_1_title' => array( 'type' => 'text', 'label' => 'Tab 1 Title' ),
					'tab_1_content' => array( 'type' => 'textarea', 'label' => 'Tab 1 Content' ),
					'tab_2_title' => array( 'type' => 'text', 'label' => 'Tab 2 Title' ),
					'tab_2_content' => array( 'type' => 'textarea', 'label' => 'Tab 2 Content' ),
					'tab_3_title' => array( 'type' => 'text', 'label' => 'Tab 3 Title' ),
					'tab_3_content' => array( 'type' => 'textarea', 'label' => 'Tab 3 Content' ),
				),
			),
			'accordion' => array(
				'label'    => 'Accordion',
				'icon'     => 'dashicons-editor-justify',
				'defaults' => array(
					'item_1_title' => 'Question One',
					'item_1_content' => 'Answer content goes here.',
					'item_2_title' => 'Question Two',
					'item_2_content' => 'Answer content goes here.',
					'item_3_title' => 'Question Three',
					'item_3_content' => 'Answer content goes here.',
				),
				'fields'   => array(
					'item_1_title' => array( 'type' => 'text', 'label' => 'Item 1 Title' ),
					'item_1_content' => array( 'type' => 'textarea', 'label' => 'Item 1 Content' ),
					'item_2_title' => array( 'type' => 'text', 'label' => 'Item 2 Title' ),
					'item_2_content' => array( 'type' => 'textarea', 'label' => 'Item 2 Content' ),
					'item_3_title' => array( 'type' => 'text', 'label' => 'Item 3 Title' ),
					'item_3_content' => array( 'type' => 'textarea', 'label' => 'Item 3 Content' ),
				),
			),
			'slider' => array(
				'label'    => 'Slider',
				'icon'     => 'dashicons-images-alt',
				'defaults' => array(
					'images' => array(),
					'height' => '360',
					'caption' => 'Add a short caption here.',
				),
				'fields'   => array(
					'images' => array( 'type' => 'gallery', 'label' => 'Slider Images' ),
					'height' => array( 'type' => 'number', 'label' => 'Desktop Height (px)' ),
					'height_tablet' => array( 'type' => 'number', 'label' => 'Tablet Height (px)' ),
					'height_mobile' => array( 'type' => 'number', 'label' => 'Mobile Height (px)' ),
					'caption' => array( 'type' => 'text', 'label' => 'Caption' ),
				),
			),

			'countdown' => array(
				'label'    => 'Countdown Timer',
				'icon'     => 'dashicons-clock',
				'defaults' => array(
					'target_date' => date( 'Y-m-d', strtotime( '+14 days' ) ),
					'target_time' => '23:59',
					'label' => 'Offer ends in',
					'bg_color' => '#111827',
					'text_color' => '#ffffff',
				),
				'fields'   => array(
					'target_date' => array( 'type' => 'text', 'label' => 'Target Date (YYYY-MM-DD)' ),
					'target_time' => array( 'type' => 'text', 'label' => 'Target Time (HH:MM)' ),
					'label' => array( 'type' => 'text', 'label' => 'Label' ),
					'bg_color' => array( 'type' => 'color', 'label' => 'Background Color' ),
					'text_color' => array( 'type' => 'color', 'label' => 'Text Color' ),
				),
			),
			'pricing' => array(
				'label'    => 'Pricing Table',
				'icon'     => 'dashicons-money-alt',
				'defaults' => array(
					'plan_name' => 'Starter',
					'price' => '£49',
					'period' => '/month',
					'features' => "Feature one\nFeature two\nFeature three",
					'button_text' => 'Choose Plan',
					'button_url' => '#',
					'accent_color' => '#7c3aed',
				),
				'fields'   => array(
					'plan_name' => array( 'type' => 'text', 'label' => 'Plan Name' ),
					'price' => array( 'type' => 'text', 'label' => 'Price' ),
					'period' => array( 'type' => 'text', 'label' => 'Period' ),
					'features' => array( 'type' => 'textarea', 'label' => 'Features (one per line)' ),
					'button_text' => array( 'type' => 'text', 'label' => 'Button Text' ),
					'button_url' => array( 'type' => 'text', 'label' => 'Button URL' ),
					'accent_color' => array( 'type' => 'color', 'label' => 'Accent Color' ),
				),
			),
			'testimonial' => array(
				'label'    => 'Testimonial',
				'icon'     => 'dashicons-format-quote',
				'defaults' => array(
					'quote' => 'This made the whole process faster and easier.',
					'name' => 'Happy Customer',
					'role' => 'Business Owner',
					'image' => '',
					'stars' => '5',
				),
				'fields'   => array(
					'quote' => array( 'type' => 'textarea', 'label' => 'Quote' ),
					'name' => array( 'type' => 'text', 'label' => 'Name' ),
					'role' => array( 'type' => 'text', 'label' => 'Role / Company' ),
					'image' => array( 'type' => 'image', 'label' => 'Image' ),
					'stars' => array( 'type' => 'select', 'label' => 'Star Rating', 'options' => array( '5', '4', '3', '2', '1' ) ),
				),
			),
			'faq_schema' => array(
				'label'    => 'FAQ Schema',
				'icon'     => 'dashicons-editor-help',
				'defaults' => array(
					'item_1_title' => 'What is included?',
					'item_1_content' => 'Add your answer here.',
					'item_2_title' => 'How quickly can I start?',
					'item_2_content' => 'Add your answer here.',
					'item_3_title' => 'Is this mobile friendly?',
					'item_3_content' => 'Yes, this section is designed to work on mobile devices.',
				),
				'fields'   => array(
					'item_1_title' => array( 'type' => 'text', 'label' => 'Question 1' ),
					'item_1_content' => array( 'type' => 'textarea', 'label' => 'Answer 1' ),
					'item_2_title' => array( 'type' => 'text', 'label' => 'Question 2' ),
					'item_2_content' => array( 'type' => 'textarea', 'label' => 'Answer 2' ),
					'item_3_title' => array( 'type' => 'text', 'label' => 'Question 3' ),
					'item_3_content' => array( 'type' => 'textarea', 'label' => 'Answer 3' ),
				),
			),


			'popup' => array(
				'label'    => 'Popup CTA',
				'icon'     => 'dashicons-welcome-view-site',
				'defaults' => array(
					'trigger' => 'timed',
					'delay' => '5',
					'button_label' => 'Open Offer',
					'title' => 'Special Offer',
					'content' => 'Add your popup message here.',
					'cta_text' => 'Learn More',
					'cta_url' => '#',
					'bg_color' => '#ffffff',
				),
				'fields'   => array(
					'trigger' => array( 'type' => 'select', 'label' => 'Trigger', 'options' => array( 'button', 'timed', 'exit_intent' ) ),
					'delay' => array( 'type' => 'number', 'label' => 'Timed Delay (seconds)' ),
					'button_label' => array( 'type' => 'text', 'label' => 'Button Label' ),
					'title' => array( 'type' => 'text', 'label' => 'Popup Title' ),
					'content' => array( 'type' => 'textarea', 'label' => 'Popup Content' ),
					'cta_text' => array( 'type' => 'text', 'label' => 'CTA Text' ),
					'cta_url' => array( 'type' => 'text', 'label' => 'CTA URL' ),
					'bg_color' => array( 'type' => 'color', 'label' => 'Popup Background' ),
				),
			),

		);
	}

	public static function get_module( $key ) {
		$modules = self::get_modules();
		return isset( $modules[ $key ] ) ? $modules[ $key ] : null;
	}
}
