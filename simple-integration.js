/**
 * Simple JavaScript solution for JetFormBuilder MarkupMarkdown Integration
 * Add this script to your page or theme
 */

jQuery(document).ready(function($) {
    'use strict';
    
    console.log('JFB MMD Simple Integration loaded');
    
    // Function to initialize MarkupMarkdown on any textarea
    function initMarkupMarkdownOnTextarea(textarea) {
        if (!textarea || $(textarea).hasClass('markdown-initialized')) {
            return;
        }
        
        $(textarea).addClass('markdown-initialized');
        
        // Try MarkupMarkdown first
        if (typeof window.MarkupMarkdown !== 'undefined') {
            try {
                var editor = new window.MarkupMarkdown(textarea);
                console.log('MarkupMarkdown initialized on:', textarea.name || textarea.id);
                return editor;
            } catch (error) {
                console.warn('MarkupMarkdown failed:', error);
            }
        }
        
        // Fallback to EasyMDE
        if (typeof window.EasyMDE !== 'undefined') {
            try {
                var easyMDE = new window.EasyMDE({
                    element: textarea,
                    spellChecker: false,
                    toolbar: ['bold', 'italic', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link', 'image', '|', 'preview', 'side-by-side', 'fullscreen']
                });
                console.log('EasyMDE initialized on:', textarea.name || textarea.id);
                return easyMDE;
            } catch (error) {
                console.warn('EasyMDE failed:', error);
            }
        }
        
        console.warn('No markdown editor available');
        return null;
    }
    
    // Function to find and initialize textareas
    function findAndInitTextareas() {
        console.log('Looking for textareas...');
        
        // Look for any textarea that might be a WYSIWYG field
        $('textarea').each(function() {
            var $textarea = $(this);
            var $field = $textarea.closest('.jet-form-builder__field');
            
            // Skip if already initialized
            if ($textarea.hasClass('markdown-initialized')) {
                return;
            }
            
            // Check if this looks like a WYSIWYG field
            if ($field.hasClass('wysiwyg-field') || 
                $textarea.attr('name') && $textarea.attr('name').includes('content') ||
                $textarea.attr('id') && $textarea.attr('id').includes('editor')) {
                
                console.log('Found potential WYSIWYG field:', $textarea.attr('name'), $textarea.attr('id'));
                
                // Hide any TinyMCE containers
                $field.find('.mce-tinymce').hide();
                $field.find('.wp-editor-tools').hide();
                
                // Show the textarea
                $textarea.show();
                
                // Initialize markdown editor
                initMarkupMarkdownOnTextarea(this);
            }
        });
    }
    
    // Initialize immediately
    setTimeout(findAndInitTextareas, 1000);
    
    // Re-initialize when forms are dynamically loaded
    $(document).on('jet-form-builder/conditional-block/block-toggle-hidden-dom', function() {
        setTimeout(findAndInitTextareas, 100);
    });
    
    // Re-initialize when forms are submitted and reset
    $(document).on('jet-form-builder/form-submit/after', function() {
        setTimeout(findAndInitTextareas, 100);
    });
    
    // Re-initialize when new fields are added dynamically
    $(document).on('jet-form-builder/repeater/add-item', function() {
        setTimeout(findAndInitTextareas, 100);
    });
    
    // Debug mode
    if (window.location.search.indexOf('jfb_mmd_debug=1') !== -1) {
        console.log('Debug mode enabled');
        setInterval(function() {
            console.log('Textareas found:', $('textarea').length);
            console.log('WYSIWYG fields found:', $('.jet-form-builder__field.wysiwyg-field').length);
            console.log('Markdown initialized:', $('.markdown-initialized').length);
        }, 5000);
    }
});
