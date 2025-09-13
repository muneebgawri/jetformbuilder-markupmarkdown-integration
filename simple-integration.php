<?php
/**
 * Simple JetFormBuilder MarkupMarkdown Integration
 * Add this to your theme's functions.php or as a separate plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Simple_JFB_MarkupMarkdown_Integration {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_filter('mmd_frontend_enabled', '__return_true');
    }
    
    public function enqueue_scripts() {
        // Only load on pages with JetFormBuilder forms
        if (!$this->has_jetformbuilder_form()) {
            return;
        }
        
        // Get MarkupMarkdown plugin URL
        $mmd_plugin_url = $this->get_markupmarkdown_url();
        
        if ($mmd_plugin_url) {
            // Enqueue MarkupMarkdown assets
            wp_enqueue_script(
                'markup-markdown-builder',
                $mmd_plugin_url . 'assets/markup-markdown/js/builder.min.js',
                array('jquery'),
                '3.20.10',
                true
            );
            
            wp_enqueue_style(
                'markup-markdown-builder',
                $mmd_plugin_url . 'assets/markup-markdown/css/plugin_options.min.css',
                array(),
                '3.20.10'
            );
            
            wp_enqueue_style(
                'easymde-css',
                $mmd_plugin_url . 'assets/easy-markdown-editor/dist/easymde.min.css',
                array(),
                '2.19.1011'
            );
        }
        
        // Enqueue our simple integration script
        wp_enqueue_script(
            'jfb-mmd-simple',
            plugin_dir_url(__FILE__) . 'simple-integration.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
    
    private function has_jetformbuilder_form() {
        global $post;
        
        if (!$post) {
            return false;
        }
        
        // Check if page contains JetFormBuilder forms
        return has_shortcode($post->post_content, 'jet_form_builder') ||
               strpos($post->post_content, 'jet-form-builder') !== false ||
               strpos($post->post_content, 'jet_engine_booking_form') !== false;
    }
    
    private function get_markupmarkdown_url() {
        if (function_exists('mmd')) {
            return plugin_dir_url(mmd()->file) . 'assets/';
        }
        
        // Fallback: try to find MarkupMarkdown plugin
        $plugins = get_plugins();
        foreach ($plugins as $plugin_file => $plugin_data) {
            if (strpos($plugin_file, 'markup-markdown') !== false) {
                return plugin_dir_url(WP_PLUGIN_DIR . '/' . $plugin_file) . 'assets/';
            }
        }
        
        return false;
    }
}

// Initialize the integration
new Simple_JFB_MarkupMarkdown_Integration();
