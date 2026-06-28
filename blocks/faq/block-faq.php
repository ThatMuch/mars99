<?php

/**
 * Block FAQ Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'faq-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'faq-block';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}

$faq_items = get_field('faq_items');

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<?php if ($is_preview) : ?>
		<?php foreach ($faq_items as $index => $item) : ?>
			<div class="preview-faq-item">
				<h3 class="preview-faq-question"><?php echo esc_html($item['question']); ?></h3>
				<p class="preview-faq-answer"><?php echo wp_kses_post($item['answer']); ?></p>
			</div>
		<?php endforeach; ?>
	<?php elseif (!empty($faq_items)) : ?>
		<div class="faq-accordion">
			<?php foreach ($faq_items as $index => $item) : ?>
				<div class="faq-item">
					<button class="faq-question" aria-expanded="false" aria-controls="faq-answer-<?php echo esc_attr($id . '-' . $index); ?>">
						<span class="faq-question-text"><?php echo esc_html($item['question']); ?></span>
						<span class="faq-icon"><i class="fa fa-plus"></i></span>
					</button>
					<div id="faq-answer-<?php echo esc_attr($id . '-' . $index); ?>" class="faq-answer" aria-hidden="true">
						<div class="faq-answer-inner no-animation">
							<?php echo wp_kses_post($item['answer']); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
