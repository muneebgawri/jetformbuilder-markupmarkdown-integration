<?php
/**
 * Admin page template
 */

if (!defined('WPINC')) {
    die;
}

// Handle form submission
if (isset($_POST['submit'])) {
    check_admin_referer('jfb_mmd_settings');
    
    update_option('jfb_mmd_replace_wysiwyg', isset($_POST['jfb_mmd_replace_wysiwyg']));
    update_option('jfb_mmd_enable_custom_field', isset($_POST['jfb_mmd_enable_custom_field']));
    
    echo '<div class="notice notice-success"><p>' . esc_html__('Settings saved successfully!', 'jetformbuilder-markupmarkdown-integration') . '</p></div>';
}

// Get current settings
$replace_wysiwyg = get_option('jfb_mmd_replace_wysiwyg', true);
$enable_custom_field = get_option('jfb_mmd_enable_custom_field', true);
?>

<div class="wrap">
    <h1><?php esc_html_e('JetFormBuilder MarkupMarkdown Integration', 'jetformbuilder-markupmarkdown-integration'); ?></h1>
    
    <div class="jfb-mmd-admin-content">
        <div class="jfb-mmd-main-content">
            <form method="post" action="">
                <?php wp_nonce_field('jfb_mmd_settings'); ?>
                
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="jfb_mmd_replace_wysiwyg">
                                    <?php esc_html_e('Replace WYSIWYG Fields', 'jetformbuilder-markupmarkdown-integration'); ?>
                                </label>
                            </th>
                            <td>
                                <fieldset>
                                    <label for="jfb_mmd_replace_wysiwyg">
                                        <input 
                                            type="checkbox" 
                                            id="jfb_mmd_replace_wysiwyg" 
                                            name="jfb_mmd_replace_wysiwyg" 
                                            value="1" 
                                            <?php checked($replace_wysiwyg); ?>
                                        />
                                        <?php esc_html_e('Replace existing WYSIWYG fields with Markdown editor', 'jetformbuilder-markupmarkdown-integration'); ?>
                                    </label>
                                    <p class="description">
                                        <?php esc_html_e('When enabled, all JetFormBuilder WYSIWYG fields will be converted to Markdown editors.', 'jetformbuilder-markupmarkdown-integration'); ?>
                                    </p>
                                </fieldset>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row">
                                <label for="jfb_mmd_enable_custom_field">
                                    <?php esc_html_e('Enable Custom Markdown Field', 'jetformbuilder-markupmarkdown-integration'); ?>
                                </label>
                            </th>
                            <td>
                                <fieldset>
                                    <label for="jfb_mmd_enable_custom_field">
                                        <input 
                                            type="checkbox" 
                                            id="jfb_mmd_enable_custom_field" 
                                            name="jfb_mmd_enable_custom_field" 
                                            value="1" 
                                            <?php checked($enable_custom_field); ?>
                                        />
                                        <?php esc_html_e('Add custom Markdown field type to JetFormBuilder', 'jetformbuilder-markupmarkdown-integration'); ?>
                                    </label>
                                    <p class="description">
                                        <?php esc_html_e('When enabled, a dedicated Markdown field will be available in the JetFormBuilder field types.', 'jetformbuilder-markupmarkdown-integration'); ?>
                                    </p>
                                </fieldset>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        
        <div class="jfb-mmd-sidebar">
            <div class="jfb-mmd-widget">
                <h3><?php esc_html_e('Plugin Information', 'jetformbuilder-markupmarkdown-integration'); ?></h3>
                <p>
                    <strong><?php esc_html_e('Version:', 'jetformbuilder-markupmarkdown-integration'); ?></strong> 
                    <?php echo esc_html(JFB_MMD_INTEGRATION_VERSION); ?>
                </p>
                <p>
                    <strong><?php esc_html_e('Author:', 'jetformbuilder-markupmarkdown-integration'); ?></strong> 
                    <?php esc_html_e('Your Name', 'jetformbuilder-markupmarkdown-integration'); ?>
                </p>
            </div>
            
            <div class="jfb-mmd-widget">
                <h3><?php esc_html_e('Requirements', 'jetformbuilder-markupmarkdown-integration'); ?></h3>
                <ul>
                    <li><?php esc_html_e('JetFormBuilder Plugin', 'jetformbuilder-markupmarkdown-integration'); ?></li>
                    <li><?php esc_html_e('Markup Markdown Plugin', 'jetformbuilder-markupmarkdown-integration'); ?></li>
                    <li><?php esc_html_e('WordPress 5.0+', 'jetformbuilder-markupmarkdown-integration'); ?></li>
                    <li><?php esc_html_e('PHP 7.4+', 'jetformbuilder-markupmarkdown-integration'); ?></li>
                </ul>
            </div>
            
            <div class="jfb-mmd-widget">
                <h3><?php esc_html_e('How to Use', 'jetformbuilder-markupmarkdown-integration'); ?></h3>
                <ol>
                    <li><?php esc_html_e('Enable the options above', 'jetformbuilder-markupmarkdown-integration'); ?></li>
                    <li><?php esc_html_e('Create or edit a JetFormBuilder form', 'jetformbuilder-markupmarkdown-integration'); ?></li>
                    <li><?php esc_html_e('Add WYSIWYG or Markdown fields to your form', 'jetformbuilder-markupmarkdown-integration'); ?></li>
                    <li><?php esc_html_e('The fields will automatically use MarkupMarkdown editor', 'jetformbuilder-markupmarkdown-integration'); ?></li>
                </ol>
            </div>
            
            <div class="jfb-mmd-widget">
                <h3><?php esc_html_e('Support', 'jetformbuilder-markupmarkdown-integration'); ?></h3>
                <p>
                    <?php esc_html_e('For support and documentation, please visit:', 'jetformbuilder-markupmarkdown-integration'); ?>
                    <br>
                    <a href="https://github.com/your-username/jetformbuilder-markupmarkdown-integration" target="_blank">
                        <?php esc_html_e('Plugin Repository', 'jetformbuilder-markupmarkdown-integration'); ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
