/**
 * JetFormBuilder MarkupMarkdown Integration - Admin JavaScript
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Admin page functionality
    if ($('.jfb-mmd-admin-content').length) {
        initAdminPage();
    }
    
    function initAdminPage() {
        // Handle form validation
        $('form').on('submit', function(e) {
            var isValid = true;
            var errorMessage = '';
            
            // Add any custom validation here
            
            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
                return false;
            }
        });
        
        // Add tooltips or help text functionality
        $('.jfb-mmd-widget a[href^="http"]').attr('target', '_blank');
        
        // Handle settings changes
        $('input[type="checkbox"]').on('change', function() {
            var $this = $(this);
            var settingName = $this.attr('name');
            
            // You can add real-time preview or other functionality here
            console.log('Setting changed:', settingName, $this.is(':checked'));
        });
    }
    
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
                return new window.MarkupMarkdown(element);
            }
            return null;
        },
        
        // Re-initialize all markdown fields
        reinitializeFields: function() {
            var self = this;
            $('.jet-form-builder__field.wysiwyg-field, .jet-form-builder__field.markdown-field').each(function() {
                var $field = $(this);
                var $textarea = $field.find('textarea');
                
                if ($textarea.length && !$field.hasClass('markdown-initialized')) {
                    $field.addClass('markdown-initialized');
                    self.initMarkdownEditor($textarea[0]);
                }
            });
        }
    };
    
    // Expose utility functions globally
    window.JFB_MMD = window.JetFormBuilderMarkupMarkdown;
});
