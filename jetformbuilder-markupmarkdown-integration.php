<?php
/**
 * Plugin Name: JetFormBuilder MarkupMarkdown Integration
 * Plugin URI: https://github.com/muneebgawri/jetformbuilder-markupmarkdown-integration
 * Description: Replaces JetFormBuilder WYSIWYG fields with MarkupMarkdown editor for enhanced markdown editing experience.
 * Version: 1.0.1
 * Author: Muneeb Gawri
 * Author URI: https://muneebgawri.com
 * Text Domain: jetformbuilder-markupmarkdown-integration
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Network: false
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('JFB_MMD_INTEGRATION_VERSION', '1.0.0');
define('JFB_MMD_INTEGRATION_FILE', __FILE__);
define('JFB_MMD_INTEGRATION_PATH', plugin_dir_path(__FILE__));
define('JFB_MMD_INTEGRATION_URL', plugin_dir_url(__FILE__));
define('JFB_MMD_INTEGRATION_BASENAME', plugin_basename(__FILE__));

/**
 * Main plugin class
 */
class JetFormBuilder_MarkupMarkdown_Integration {
    
    /**
     * Plugin instance
     */
    private static $instance = null;
    
    /**
     * Plugin version
     */
    private $version = JFB_MMD_INTEGRATION_VERSION;
    
    /**
     * Get plugin instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Check if both required plugins are active
        if (!$this->is_jetformbuilder_active() || !$this->is_markupmarkdown_active()) {
            add_action('admin_notices', array($this, 'missing_plugins_notice'));
            return;
        }
        
        // Load text domain
        load_plugin_textdomain(
            'jetformbuilder-markupmarkdown-integration',
            false,
            dirname(JFB_MMD_INTEGRATION_BASENAME) . '/languages'
        );
        
        // Setup hooks
        $this->setup_hooks();
        
        // Initialize admin
        if (is_admin()) {
            $this->init_admin();
        }
    }
    
    /**
     * Check if JetFormBuilder is active
     */
    private function is_jetformbuilder_active() {
        return class_exists('Jet_Form_Builder\Plugin') || 
               function_exists('jet_form_builder');
    }
    
    /**
     * Check if MarkupMarkdown is active
     */
    private function is_markupmarkdown_active() {
        return function_exists('mmd') || class_exists('Markup_Markdown');
    }
    
    /**
     * Show notice if required plugins are missing
     */
    public function missing_plugins_notice() {
        $missing_plugins = array();
        
        if (!$this->is_jetformbuilder_active()) {
            $missing_plugins[] = 'JetFormBuilder';
        }
        
        if (!$this->is_markupmarkdown_active()) {
            $missing_plugins[] = 'Markup Markdown';
        }
        
        if (!empty($missing_plugins)) {
            $plugin_names = implode(' and ', $missing_plugins);
            ?>
            <div class="notice notice-error">
                <p>
                    <strong><?php esc_html_e('JetFormBuilder MarkupMarkdown Integration', 'jetformbuilder-markupmarkdown-integration'); ?></strong>
                    <?php 
                    printf(
                        esc_html__(' requires %s to be installed and activated.', 'jetformbuilder-markupmarkdown-integration'),
                        $plugin_names
                    );
                    ?>
                </p>
            </div>
            <?php
        }
    }
    
    /**
     * Setup plugin hooks
     */
    private function setup_hooks() {
        // Enable MarkupMarkdown on frontend for JetFormBuilder forms
        add_filter('mmd_frontend_enabled', array($this, 'enable_frontend_markdown'));
        
        // Replace JetFormBuilder WYSIWYG field configuration
        add_filter('jet-form-builder/fields/wysiwyg-field/config', array($this, 'modify_wysiwyg_config'));
        
        // Enqueue assets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Add custom field type for markdown
        add_action('jet-form-builder/blocks/items', array($this, 'add_markdown_field_type'));
        
        // Handle form submission
        add_action('jet-form-builder/form-handler/after-save', array($this, 'process_markdown_content'), 10, 2);
        
        // Add settings page
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }
    
    /**
     * Enable MarkupMarkdown on frontend for JetFormBuilder forms
     */
    public function enable_frontend_markdown($enabled) {
        // Enable on pages with JetFormBuilder forms
        if (is_singular()) {
            global $post;
            if (isset($post->post_content) && 
                (strpos($post->post_content, 'jet-forms/') !== false || 
                 strpos($post->post_content, 'jet-form-builder') !== false ||
                 strpos($post->post_content, 'jet-forms/wysiwyg-field') !== false)) {
                return true;
            }
        }
        
        // Also enable on pages with our custom markdown fields
        if (is_singular()) {
            global $post;
            if (isset($post->post_content) && 
                strpos($post->post_content, 'jet-forms/markdown-field') !== false) {
                return true;
            }
        }
        
        return $enabled;
    }
    
    /**
     * Modify JetFormBuilder WYSIWYG field configuration
     */
    public function modify_wysiwyg_config($config) {
        // Get plugin settings
        $replace_wysiwyg = get_option('jfb_mmd_replace_wysiwyg', true);
        
        if (!$replace_wysiwyg) {
            return $config;
        }
        
        // Debug logging
        if (isset($_GET['jfb_mmd_debug']) && $_GET['jfb_mmd_debug'] == '1') {
            error_log('JFB MMD: Modifying WYSIWYG config. Original: ' . print_r($config, true));
        }
        
        // Convert WYSIWYG to textarea for markdown support
        // Use minimal TinyMCE config to ensure textarea is rendered
        $config['tinymce'] = array(
            'toolbar1' => '',
            'toolbar2' => '',
            'toolbar3' => '',
            'toolbar4' => '',
            'plugins' => '',
            'menubar' => false,
            'statusbar' => false,
            'resize' => false,
            'setup' => 'function(ed) { ed.hide(); }'
        );
        $config['quicktags'] = false;
        $config['media_buttons'] = false;
        $config['textarea_rows'] = isset($config['textarea_rows']) ? $config['textarea_rows'] : 15;
        
        // Debug logging
        if (isset($_GET['jfb_mmd_debug']) && $_GET['jfb_mmd_debug'] == '1') {
            error_log('JFB MMD: Modified WYSIWYG config: ' . print_r($config, true));
        }
        
        return $config;
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        if (!$this->should_load_frontend_assets()) {
            return;
        }
        
        // Enable frontend markdown
        add_filter('mmd_frontend_enabled', '__return_true');
        
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
            
            // Also enqueue EasyMDE assets
            wp_enqueue_style(
                'easymde-css',
                $mmd_plugin_url . 'assets/easy-markdown-editor/dist/easymde.min.css',
                array(),
                '2.19.1011'
            );
            
            // Add initialization script
            wp_add_inline_script('markup-markdown-builder', $this->get_initialization_script());
            
            // Add debug info
            if (isset($_GET['jfb_mmd_debug']) && $_GET['jfb_mmd_debug'] == '1') {
                wp_add_inline_script('markup-markdown-builder', '
                    console.log("JetFormBuilder MarkupMarkdown Integration Debug Mode");
                    console.log("MarkupMarkdown URL:", "' . $mmd_plugin_url . '");
                    console.log("Builder script loaded:", typeof window.MarkupMarkdown !== "undefined");
                ');
            }
        } else {
            // Fallback: try to load from our plugin directory
            wp_enqueue_script(
                'jfb-mmd-frontend',
                JFB_MMD_INTEGRATION_URL . 'assets/js/frontend.js',
                array('jquery'),
                $this->version,
                true
            );
            
            wp_enqueue_style(
                'jfb-mmd-frontend',
                JFB_MMD_INTEGRATION_URL . 'assets/css/frontend.css',
                array(),
                $this->version
            );
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'jetformbuilder-markupmarkdown') === false) {
            return;
        }
        
        wp_enqueue_style(
            'jfb-mmd-admin',
            JFB_MMD_INTEGRATION_URL . 'assets/css/admin.css',
            array(),
            $this->version
        );
        
        wp_enqueue_script(
            'jfb-mmd-admin',
            JFB_MMD_INTEGRATION_URL . 'assets/js/admin.js',
            array('jquery'),
            $this->version,
            true
        );
    }
    
    /**
     * Check if frontend assets should be loaded
     */
    private function should_load_frontend_assets() {
        if (is_admin()) {
            return false;
        }
        
        if (is_singular()) {
            global $post;
            return isset($post->post_content) && 
                   (strpos($post->post_content, 'jet-forms/') !== false || 
                    strpos($post->post_content, 'jet-form-builder') !== false);
        }
        
        return false;
    }
    
    /**
     * Get MarkupMarkdown plugin URL
     */
    private function get_markupmarkdown_url() {
        if (function_exists('mmd')) {
            return mmd()->plugin_uri;
        }
        
        // Fallback: try to find the plugin
        $plugins = get_plugins();
        foreach ($plugins as $plugin_file => $plugin_data) {
            if (strpos($plugin_file, 'markup-markdown') !== false) {
                return plugin_dir_url(WP_PLUGIN_DIR . '/' . $plugin_file);
            }
        }
        
        return false;
    }
    
    /**
     * Get initialization script
     */
    private function get_initialization_script() {
        return "
        jQuery(document).ready(function($) {
            // Initialize MarkupMarkdown on JetFormBuilder textarea fields
            function initMarkupMarkdown() {
                console.log('Attempting to initialize MarkupMarkdown...');
                console.log('MarkupMarkdown available:', typeof window.MarkupMarkdown !== 'undefined');
                console.log('WYSIWYG fields found:', $('.jet-form-builder__field.wysiwyg-field').length);
                
                // Target WYSIWYG fields that were converted to textarea
                $('.jet-form-builder__field.wysiwyg-field').each(function() {
                    var \$field = $(this);
                    var \$textarea = \$field.find('textarea');
                    
                    console.log('Processing field:', \$textarea.attr('name'), 'Initialized:', \$field.hasClass('markdown-initialized'));
                    
                    if (\$textarea.length && !\$field.hasClass('markdown-initialized')) {
                        \$field.addClass('markdown-initialized');
                        
                        // Initialize MarkupMarkdown
                        if (typeof window.MarkupMarkdown !== 'undefined') {
                            try {
                                var editor = new window.MarkupMarkdown(\$textarea[0]);
                                console.log('MarkupMarkdown initialized on field:', \$textarea.attr('name'), editor);
                            } catch (error) {
                                console.warn('Failed to initialize MarkupMarkdown:', error);
                            }
                        } else {
                            console.warn('MarkupMarkdown not available - trying alternative approach');
                            // Try to initialize EasyMDE directly if available
                            if (typeof window.EasyMDE !== 'undefined') {
                                try {
                                    var easyMDE = new window.EasyMDE({
                                        element: \$textarea[0],
                                        spellChecker: false,
                                        toolbar: ['bold', 'italic', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link', 'image', '|', 'preview', 'side-by-side', 'fullscreen']
                                    });
                                    console.log('EasyMDE initialized on field:', \$textarea.attr('name'));
                                } catch (error) {
                                    console.warn('Failed to initialize EasyMDE:', error);
                                }
                            }
                        }
                    }
                });
                
                // Also initialize custom markdown fields
                $('.jet-form-builder__field.markdown-field').each(function() {
                    var \$field = $(this);
                    var \$textarea = \$field.find('textarea');
                    
                    if (\$textarea.length && !\$field.hasClass('markdown-initialized')) {
                        \$field.addClass('markdown-initialized');
                        
                        // Initialize MarkupMarkdown
                        if (typeof window.MarkupMarkdown !== 'undefined') {
                            try {
                                var editor = new window.MarkupMarkdown(\$textarea[0]);
                                console.log('MarkupMarkdown initialized on custom field:', \$textarea.attr('name'), editor);
                            } catch (error) {
                                console.warn('Failed to initialize MarkupMarkdown:', error);
                            }
                        }
                    }
                });
            }
            
            // Wait for MarkupMarkdown to load
            function waitForMarkupMarkdown() {
                if (typeof window.MarkupMarkdown !== 'undefined') {
                    initMarkupMarkdown();
                } else {
                    console.log('Waiting for MarkupMarkdown to load...');
                    setTimeout(waitForMarkupMarkdown, 100);
                }
            }
            
            // Start initialization
            setTimeout(waitForMarkupMarkdown, 500);
            
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
            
            // Debug mode
            if (window.location.search.indexOf('jfb_mmd_debug=1') !== -1) {
                console.log('JetFormBuilder MarkupMarkdown Integration Debug Mode');
                console.log('jQuery version:', $.fn.jquery);
                console.log('MarkupMarkdown available:', typeof window.MarkupMarkdown !== 'undefined');
                console.log('EasyMDE available:', typeof window.EasyMDE !== 'undefined');
                console.log('WYSIWYG fields found:', $('.jet-form-builder__field.wysiwyg-field').length);
                console.log('All textarea elements:', $('textarea').length);
            }
        });
        ";
    }
    
    /**
     * Add custom markdown field type
     */
    public function add_markdown_field_type($block_types) {
        $block_types[] = new JetFormBuilder_Markdown_Field_Type();
        return $block_types;
    }
    
    /**
     * Process markdown content on form submission
     */
    public function process_markdown_content($form_data, $post_id) {
        // Content is already saved as markdown, no conversion needed
        // MarkupMarkdown will render it as HTML on the frontend
    }
    
    /**
     * Initialize admin functionality
     */
    private function init_admin() {
        // Admin-specific initialization
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('JetFormBuilder MarkupMarkdown', 'jetformbuilder-markupmarkdown-integration'),
            __('JFB MarkupMarkdown', 'jetformbuilder-markupmarkdown-integration'),
            'manage_options',
            'jetformbuilder-markupmarkdown-integration',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('jfb_mmd_settings', 'jfb_mmd_replace_wysiwyg');
        register_setting('jfb_mmd_settings', 'jfb_mmd_enable_custom_field');
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        include JFB_MMD_INTEGRATION_PATH . 'templates/admin-page.php';
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Set default options
        add_option('jfb_mmd_replace_wysiwyg', true);
        add_option('jfb_mmd_enable_custom_field', true);
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Get plugin version
     */
    public function get_version() {
        return $this->version;
    }
}

/**
 * Custom Markdown Field Type
 */
class JetFormBuilder_Markdown_Field_Type {
    
    public function get_name() {
        return 'markdown-field';
    }
    
    public function get_title() {
        return __('Markdown Editor', 'jetformbuilder-markupmarkdown-integration');
    }
    
    public function get_template() {
        return JFB_MMD_INTEGRATION_PATH . 'templates/markdown-field.php';
    }
    
    public function get_assets() {
        return array(
            'css' => array(),
            'js' => array()
        );
    }
}

// Initialize the plugin
JetFormBuilder_MarkupMarkdown_Integration::get_instance();
