<?php
/**
 * Simple test script to debug the issue
 * Add this to your theme's functions.php temporarily
 */

// Test if our plugin is working
add_action('wp_footer', function() {
    if (isset($_GET['jfb_mmd_test']) && $_GET['jfb_mmd_test'] == '1') {
        ?>
        <script>
        console.log('=== JFB MMD Test ===');
        console.log('jQuery loaded:', typeof jQuery !== 'undefined');
        console.log('MarkupMarkdown available:', typeof window.MarkupMarkdown !== 'undefined');
        console.log('EasyMDE available:', typeof window.EasyMDE !== 'undefined');
        
        jQuery(document).ready(function($) {
            console.log('jQuery version:', $.fn.jquery);
            console.log('JetFormBuilder forms found:', $('.jet-form-builder').length);
            console.log('WYSIWYG fields found:', $('.jet-form-builder__field.wysiwyg-field').length);
            console.log('All textarea elements:', $('textarea').length);
            console.log('TinyMCE containers found:', $('.mce-tinymce').length);
            console.log('wp-editor-tools found:', $('.wp-editor-tools').length);
            
            // Check if MarkupMarkdown is interfering
            if (typeof window.MarkupMarkdown !== 'undefined') {
                console.log('MarkupMarkdown is loaded and available');
            }
            
            // Try manual initialization
            setTimeout(function() {
                console.log('=== Manual Test ===');
                $('.jet-form-builder__field.wysiwyg-field').each(function() {
                    var $field = $(this);
                    var $textarea = $field.find('textarea');
                    var $tinymce = $field.find('.mce-tinymce');
                    
                    console.log('Field found:', $field.length);
                    console.log('Textarea found:', $textarea.length);
                    console.log('TinyMCE found:', $tinymce.length);
                    
                    if ($textarea.length) {
                        console.log('Textarea name:', $textarea.attr('name'));
                        console.log('Textarea id:', $textarea.attr('id'));
                        console.log('Textarea visible:', $textarea.is(':visible'));
                    }
                });
            }, 2000);
        });
        </script>
        <?php
    }
});
