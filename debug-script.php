/**
 * Debug script for JetFormBuilder MarkupMarkdown Integration
 * Add this to your theme's functions.php or as a separate plugin for testing
 */

// Add debug parameter to enable console logging
add_action('wp_footer', function() {
    if (isset($_GET['jfb_mmd_debug']) && $_GET['jfb_mmd_debug'] == '1') {
        ?>
        <script>
        console.log('=== JetFormBuilder MarkupMarkdown Debug ===');
        console.log('Page URL:', window.location.href);
        console.log('jQuery loaded:', typeof jQuery !== 'undefined');
        console.log('MarkupMarkdown available:', typeof window.MarkupMarkdown !== 'undefined');
        console.log('EasyMDE available:', typeof window.EasyMDE !== 'undefined');
        
        jQuery(document).ready(function($) {
            console.log('jQuery version:', $.fn.jquery);
            console.log('JetFormBuilder forms found:', $('.jet-form-builder').length);
            console.log('WYSIWYG fields found:', $('.jet-form-builder__field.wysiwyg-field').length);
            console.log('All textarea elements:', $('textarea').length);
            
            // Check if our plugin is loaded
            if (typeof window.JFB_MMD !== 'undefined') {
                console.log('JFB_MMD utility available:', window.JFB_MMD);
            } else {
                console.log('JFB_MMD utility NOT available');
            }
            
            // Try to manually initialize MarkupMarkdown
            setTimeout(function() {
                console.log('=== Manual Initialization Test ===');
                $('.jet-form-builder__field.wysiwyg-field').each(function() {
                    var $field = $(this);
                    var $textarea = $field.find('textarea');
                    
                    if ($textarea.length) {
                        console.log('Found textarea:', $textarea.attr('name'), $textarea.attr('id'));
                        
                        if (typeof window.MarkupMarkdown !== 'undefined') {
                            try {
                                var editor = new window.MarkupMarkdown($textarea[0]);
                                console.log('SUCCESS: MarkupMarkdown initialized on', $textarea.attr('name'));
                            } catch (error) {
                                console.error('ERROR: Failed to initialize MarkupMarkdown:', error);
                            }
                        } else {
                            console.log('MarkupMarkdown not available, trying EasyMDE...');
                            if (typeof window.EasyMDE !== 'undefined') {
                                try {
                                    var easyMDE = new window.EasyMDE({
                                        element: $textarea[0],
                                        spellChecker: false,
                                        toolbar: ['bold', 'italic', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link', 'image', '|', 'preview', 'side-by-side', 'fullscreen']
                                    });
                                    console.log('SUCCESS: EasyMDE initialized on', $textarea.attr('name'));
                                } catch (error) {
                                    console.error('ERROR: Failed to initialize EasyMDE:', error);
                                }
                            }
                        }
                    }
                });
            }, 1000);
        });
        </script>
        <?php
    }
});
