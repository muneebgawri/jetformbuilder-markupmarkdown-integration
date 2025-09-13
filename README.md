# JetFormBuilder MarkupMarkdown Integration

A WordPress plugin that integrates JetFormBuilder with MarkupMarkdown, replacing TinyMCE WYSIWYG fields with a powerful markdown editor.

## Description

This plugin seamlessly integrates JetFormBuilder forms with the MarkupMarkdown plugin, providing users with a modern markdown editing experience instead of the traditional TinyMCE editor. It converts WYSIWYG fields to markdown editors and adds a dedicated Markdown field type to JetFormBuilder.

## Features

- **WYSIWYG Replacement**: Automatically converts JetFormBuilder WYSIWYG fields to Markdown editors
- **Custom Field Type**: Adds a dedicated Markdown field type to JetFormBuilder
- **EasyMDE Integration**: Uses the powerful EasyMDE editor with live preview
- **Dynamic Loading**: Handles conditional blocks and dynamic form loading
- **Form Compatibility**: Maintains all JetFormBuilder validation and submission functionality
- **Admin Settings**: Easy configuration through WordPress admin panel
- **Responsive Design**: Works on desktop and mobile devices

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- JetFormBuilder plugin (active)
- Markup Markdown plugin (active)

## Installation

1. Download the plugin zip file
2. Go to WordPress Admin → Plugins → Add New → Upload Plugin
3. Upload the zip file and activate the plugin
4. Go to Settings → JFB MarkupMarkdown to configure the plugin

## Configuration

### Admin Settings

Navigate to **Settings → JFB MarkupMarkdown** to configure the plugin:

- **Replace WYSIWYG Fields**: When enabled, all JetFormBuilder WYSIWYG fields will be converted to Markdown editors
- **Enable Custom Markdown Field**: When enabled, a dedicated Markdown field type will be available in JetFormBuilder

### Usage

1. **Automatic Conversion**: If "Replace WYSIWYG Fields" is enabled, existing WYSIWYG fields will automatically use the Markdown editor
2. **Custom Field**: If "Enable Custom Markdown Field" is enabled, you can add dedicated Markdown fields to your forms
3. **Form Building**: Create or edit JetFormBuilder forms as usual - the Markdown editor will be used automatically

## How It Works

### WYSIWYG Field Conversion

When enabled, the plugin:
1. Intercepts JetFormBuilder WYSIWYG field configuration
2. Disables TinyMCE and QuickTags
3. Converts the field to a textarea
4. Initializes MarkupMarkdown editor on the textarea

### Custom Markdown Field

The plugin adds a new field type called "Markdown Editor" that:
1. Provides a dedicated textarea for markdown content
2. Automatically initializes MarkupMarkdown editor
3. Includes proper form validation and submission handling

### Frontend Integration

The plugin:
1. Detects pages with JetFormBuilder forms
2. Enables MarkupMarkdown frontend support
3. Loads necessary assets (CSS/JS)
4. Initializes editors on form fields
5. Handles dynamic form loading and conditional blocks

## Technical Details

### Hooks and Filters

- `mmd_frontend_enabled`: Enables MarkupMarkdown on frontend for JetFormBuilder forms
- `jet-form-builder/fields/wysiwyg-field/config`: Modifies WYSIWYG field configuration
- `jet-form-builder/blocks/items`: Adds custom Markdown field type
- `jet-form-builder/form-handler/after-save`: Handles form submission

### JavaScript Events

The plugin listens for these JetFormBuilder events:
- `jet-form-builder/conditional-block/block-toggle-hidden-dom`
- `jet-form-builder/form-submit/after`
- `jet-form-builder/repeater/add-item`
- `jet-form-builder/repeater/remove-item`

### File Structure

```
jetformbuilder-markupmarkdown-integration/
├── jetformbuilder-markupmarkdown-integration.php (Main plugin file)
├── templates/
│   ├── markdown-field.php (Custom field template)
│   └── admin-page.php (Admin settings page)
├── assets/
│   ├── css/
│   │   ├── admin.css (Admin styles)
│   │   └── frontend.css (Frontend styles)
│   └── js/
│       ├── admin.js (Admin JavaScript)
│       └── frontend.js (Frontend JavaScript)
└── languages/ (Translation files)
```

## Troubleshooting

### Common Issues

1. **Markdown editor not loading**: Ensure both JetFormBuilder and MarkupMarkdown plugins are active
2. **Form submission issues**: Check that the plugin settings are properly configured
3. **Styling issues**: Verify that the plugin CSS is loading correctly

### Debug Mode

Add `?jfb_mmd_debug=1` to your URL to enable debug logging in the browser console.

### Support

For support and bug reports, please visit the plugin repository on GitHub.

## Changelog

### Version 1.0.0
- Initial release
- WYSIWYG field conversion
- Custom Markdown field type
- Admin settings page
- Frontend integration
- Dynamic form handling

## License

This plugin is licensed under the GPL v2 or later.

## Credits

- Built for JetFormBuilder by Crocoblock
- Integrates with MarkupMarkdown plugin
- Uses EasyMDE editor for markdown editing
