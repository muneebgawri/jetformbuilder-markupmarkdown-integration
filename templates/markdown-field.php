<?php
/**
 * Markdown field template for JetFormBuilder
 */

use Jet_Form_Builder\Blocks\Render\Base;

if (!defined('WPINC')) {
    die;
}

if (empty($args['default'])) {
    $args['default'] = '';
}

$field_id = 'markdown_' . $this->block_type->get_field_id($args['name']);
$field_name = $this->block_type->get_field_name($args['name']);
$rows = isset($args['rows']) ? $args['rows'] : 15;
$placeholder = isset($args['placeholder']) ? $args['placeholder'] : __('Write your content in Markdown...', 'jetformbuilder-markupmarkdown-integration');

$this->add_attribute('class', 'jet-form-builder__field markdown-field');
$this->add_attribute('class', $args['class_name']);
$this->add_attribute('data-required', $this->block_type->get_required_val());
$this->add_attribute('data-jfb-sync');
?>

<div class="jet-form-builder__field-wrap">
    <div <?php $this->render_attributes_string(); ?>>
        <textarea 
            id="<?php echo esc_attr($field_id); ?>" 
            name="<?php echo esc_attr($field_name); ?>" 
            rows="<?php echo esc_attr($rows); ?>" 
            class="markdown-textarea"
            placeholder="<?php echo esc_attr($placeholder); ?>"
            <?php if ($this->block_type->get_required_val()) : ?>
                required
            <?php endif; ?>
        ><?php echo esc_textarea($args['default']); ?></textarea>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    var $textarea = $('#<?php echo esc_js($field_id); ?>');
    var $field = $textarea.closest('.jet-form-builder__field');
    
    if (!$field.hasClass('markdown-initialized')) {
        $field.addClass('markdown-initialized');
        
        // Initialize MarkupMarkdown
        if (typeof window.MarkupMarkdown !== 'undefined') {
            new window.MarkupMarkdown($textarea[0]);
        }
    }
});
</script>
