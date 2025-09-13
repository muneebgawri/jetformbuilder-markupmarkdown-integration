# JetFormBuilder MarkupMarkdown Integration

## Installation Instructions

### Method 1: WordPress Admin (Recommended)

1. **Download the Plugin**
   - Download the `jetformbuilder-markupmarkdown-integration.zip` file
   - Do not extract the zip file

2. **Upload to WordPress**
   - Go to your WordPress Admin Dashboard
   - Navigate to **Plugins → Add New**
   - Click **Upload Plugin**
   - Choose the zip file and click **Install Now**
   - Click **Activate Plugin**

3. **Configure Settings**
   - Go to **Settings → JFB MarkupMarkdown**
   - Enable the desired options:
     - ✅ Replace WYSIWYG Fields
     - ✅ Enable Custom Markdown Field
   - Click **Save Changes**

### Method 2: FTP Upload

1. **Extract the Plugin**
   - Extract the zip file to your computer
   - You should see a folder named `jetformbuilder-markupmarkdown-integration`

2. **Upload via FTP**
   - Connect to your website via FTP
   - Navigate to `/wp-content/plugins/`
   - Upload the `jetformbuilder-markupmarkdown-integration` folder

3. **Activate Plugin**
   - Go to WordPress Admin → Plugins
   - Find "JetFormBuilder MarkupMarkdown Integration"
   - Click **Activate**

4. **Configure Settings**
   - Go to **Settings → JFB MarkupMarkdown**
   - Enable the desired options and save

## Requirements Check

Before installation, ensure you have:

- ✅ **JetFormBuilder Plugin** - Must be installed and activated
- ✅ **Markup Markdown Plugin** - Must be installed and activated
- ✅ **WordPress 5.0+** - Check in Dashboard → Updates
- ✅ **PHP 7.4+** - Check in Dashboard → Tools → Site Health

## Post-Installation

### 1. Verify Installation

After activation, you should see:
- A new menu item: **Settings → JFB MarkupMarkdown**
- No error messages in the admin area

### 2. Test the Integration

1. **Create a Test Form**
   - Go to JetFormBuilder → Forms
   - Create a new form or edit existing
   - Add a WYSIWYG field or Markdown field
   - Save the form

2. **View on Frontend**
   - Publish the form on a page
   - Visit the page
   - Verify the field shows MarkupMarkdown editor instead of TinyMCE

### 3. Configure Settings

Navigate to **Settings → JFB MarkupMarkdown** and configure:

- **Replace WYSIWYG Fields**: Enable to convert existing WYSIWYG fields
- **Enable Custom Markdown Field**: Enable to add Markdown field type

## Troubleshooting

### Plugin Not Activating

**Error**: "Plugin could not be activated because it triggered a fatal error"

**Solution**: 
- Check that both JetFormBuilder and MarkupMarkdown are active
- Ensure PHP version is 7.4 or higher
- Check WordPress error logs for specific error details

### Markdown Editor Not Loading

**Issue**: WYSIWYG fields still show TinyMCE instead of Markdown editor

**Solutions**:
1. Verify both required plugins are active
2. Check plugin settings are enabled
3. Clear any caching plugins
4. Check browser console for JavaScript errors

### Form Submission Issues

**Issue**: Forms with Markdown fields don't submit properly

**Solutions**:
1. Ensure MarkupMarkdown plugin is properly configured
2. Check that post types support MarkupMarkdown
3. Verify form validation settings

### Debug Mode

Enable debug mode by adding `?jfb_mmd_debug=1` to your URL to see console logs.

## Uninstallation

### Complete Removal

1. **Deactivate Plugin**
   - Go to Plugins → Installed Plugins
   - Find "JetFormBuilder MarkupMarkdown Integration"
   - Click **Deactivate**

2. **Delete Plugin**
   - Click **Delete** under the plugin name
   - Confirm deletion

3. **Clean Up (Optional)**
   - Plugin settings will be removed automatically
   - No database cleanup needed

### Partial Disable

To disable without removing:
1. Go to **Settings → JFB MarkupMarkdown**
2. Uncheck all options
3. Save changes
4. Deactivate plugin (but don't delete)

## Support

If you encounter issues:

1. **Check Requirements**: Ensure all requirements are met
2. **Enable Debug Mode**: Add `?jfb_mmd_debug=1` to URL
3. **Check Console**: Look for JavaScript errors in browser console
4. **Plugin Conflicts**: Temporarily deactivate other plugins to test
5. **Contact Support**: Visit the plugin repository for support

## Additional Resources

- [JetFormBuilder Documentation](https://jetformbuilder.com/)
- [MarkupMarkdown Plugin](https://wordpress.org/plugins/markup-markdown/)
- [EasyMDE Editor](https://github.com/Ionaru/easy-markdown-editor)
