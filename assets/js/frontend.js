/**
 * JetFormBuilder MarkupMarkdown Integration - Frontend JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize MarkupMarkdown on JetFormBuilder fields
    function initMarkupMarkdown() {
        // Initialize WYSIWYG fields converted to markdown
        $('.jet-form-builder__field.wysiwyg-field').each(function() {
            var $field = $(this);
            var $textarea = $field.find('textarea');
            
            if ($textarea.length && !$field.hasClass('markdown-initialized')) {
                $field.addClass('markdown-initialized');
                
                // Initialize MarkupMarkdown
                if (typeof window.MarkupMarkdown !== 'undefined') {
                    try {
                        new window.MarkupMarkdown($textarea[0]);
                    } catch (error) {
                        console.warn('Failed to initialize MarkupMarkdown:', error);
                    }
                }
            }
        });
        
        // Initialize custom markdown fields
        $('.jet-form-builder__field.markdown-field').each(function() {
            var $field = $(this);
            var $textarea = $field.find('textarea');
            
            if ($textarea.length && !$field.hasClass('markdown-initialized')) {
                $field.addClass('markdown-initialized');
                
                // Initialize MarkupMarkdown
                if (typeof window.MarkupMarkdown !== 'undefined') {
                    try {
                        new window.MarkupMarkdown($textarea[0]);
                    } catch (error) {
                        console.warn('Failed to initialize MarkupMarkdown:', error);
                    }
                }
            }
        });
    }
    
    // Initialize on page load
    initMarkupMarkdown();
    
    // Re-initialize when forms are dynamically loaded
    $(document).on('jet-form-builder/conditional-block/block-toggle-hidden-dom', function(e) {
        setTimeout(initMarkupMarkdown, 100);
    });
    
    // Re-initialize when forms are submitted and reset
    $(document).on('jet-form-builder/form-submit/after', function() {
        setTimeout(initMarkupMarkdown, 100);
    });
    
    // Re-initialize when new fields are added dynamically
    $(document).on('jet-form-builder/repeater/add-item', function() {
        setTimeout(initMarkupMarkdown, 100);
    });
    
    // Re-initialize when fields are removed
    $(document).on('jet-form-builder/repeater/remove-item', function() {
        setTimeout(initMarkupMarkdown, 100);
    });
    
    // Handle form validation with markdown fields
    $(document).on('jet-form-builder/form-validation/before', function(e) {
        // Ensure all markdown content is saved before validation
        if (typeof window.tinyMCE !== 'undefined') {
            window.tinyMCE.triggerSave();
        }
        
        // Trigger save on all MarkupMarkdown instances
        $('.jet-form-builder__field.markdown-initialized').each(function() {
            var $field = $(this);
            var $textarea = $field.find('textarea');
            
            if ($textarea.length) {
                // Trigger change event to ensure form validation picks up the content
                $textarea.trigger('change');
            }
        });
    });
    
    // Handle form submission
    $(document).on('jet-form-builder/form-submit/before', function(e) {
        // Ensure all markdown content is saved before submission
        if (typeof window.tinyMCE !== 'undefined') {
            window.tinyMCE.triggerSave();
        }
        
        // Trigger save on all MarkupMarkdown instances
        $('.jet-form-builder__field.markdown-initialized').each(function() {
            var $field = $(this);
            var $textarea = $field.find('textarea');
            
            if ($textarea.length) {
                // Trigger change event to ensure form submission picks up the content
                $textarea.trigger('change');
            }
        });
    });
    
    // Utility functions
    window.JetFormBuilderMarkupMarkdown = {
        version: '1.0.0',
        
        // Check if MarkupMarkdown is available
        isMarkupMarkdownAvailable: function() {
            return typeof window.MarkupMarkdown !== 'undefined';
        },
        
        // Initialize markdown editor on a specific element
        initMarkdownEditor: function(element) {
            if (this.isMarkupMarkdownAvailable()) {
                try {
                    return new window.MarkupMarkdown(element);
                } catch (error) {
                    console.warn('Failed to initialize MarkupMarkdown:', error);
                    return null;
                }
            }
            return null;
        },
        
        // Re-initialize all markdown fields
        reinitializeFields: function() {
            initMarkupMarkdown();
        },
        
        // Get all markdown fields
        getMarkdownFields: function() {
            return $('.jet-form-builder__field.wysiwyg-field, .jet-form-builder__field.markdown-field');
        },
        
        // Get initialized markdown fields
        getInitializedFields: function() {
            return $('.jet-form-builder__field.markdown-initialized');
        }
    };
    
    // Expose utility functions globally
    window.JFB_MMD = window.JetFormBuilderMarkupMarkdown;
    
    // Debug mode
    if (window.location.search.indexOf('jfb_mmd_debug=1') !== -1) {
        console.log('JetFormBuilder MarkupMarkdown Integration loaded');
        console.log('MarkupMarkdown available:', window.JFB_MMD.isMarkupMarkdownAvailable());
        console.log('Markdown fields found:', window.JFB_MMD.getMarkdownFields().length);
        console.log('Initialized fields:', window.JFB_MMD.getInitializedFields().length);
    }
});
