<?php
/**
 * FAQ section.
 *
 * @package GetOnTrack
 */

$faqs = got_get_faqs();
?>

<section class="got-section got-section--faq" id="faq">
	<div class="got-container">
		<div class="got-split got-split--faq">
			<div class="got-split__content">
				<p class="got-eyebrow"><?php esc_html_e( 'FAQ', 'getontrack' ); ?></p>
				<h2 class="got-section-header__title"><?php esc_html_e( 'Common Questions About Peptide Therapy', 'getontrack' ); ?></h2>
				<p><?php esc_html_e( 'Have more questions? Our team is here to help you understand if peptide therapy is right for you.', 'getontrack' ); ?></p>
				<a class="got-btn got-btn--outline" href="#consultation"><?php esc_html_e( 'Ask a Question', 'getontrack' ); ?></a>
			</div>
			<div class="got-faq" data-faq>
				<?php foreach ( $faqs as $index => $faq ) : ?>
					<details class="got-faq__item" <?php echo $index === 0 ? 'open' : ''; ?>>
						<summary class="got-faq__question"><?php echo esc_html( $faq['q'] ); ?></summary>
						<div class="got-faq__answer">
							<p><?php echo esc_html( $faq['a'] ); ?></p>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
