=== JetFormBuilder MarkupMarkdown Integration ===
Contributors: yourname
Tags: jetformbuilder, markdown, editor, forms, crocoblock
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Replaces JetFormBuilder WYSIWYG fields with MarkupMarkdown editor for enhanced markdown editing experience.

== Description ==

JetFormBuilder MarkupMarkdown Integration seamlessly integrates JetFormBuilder forms with the MarkupMarkdown plugin, providing users with a modern markdown editing experience instead of the traditional TinyMCE editor.

**Key Features:**

* **WYSIWYG Replacement**: Automatically converts JetFormBuilder WYSIWYG fields to Markdown editors
* **Custom Field Type**: Adds a dedicated Markdown field type to JetFormBuilder
* **EasyMDE Integration**: Uses the powerful EasyMDE editor with live preview
* **Dynamic Loading**: Handles conditional blocks and dynamic form loading
* **Form Compatibility**: Maintains all JetFormBuilder validation and submission functionality
* **Admin Settings**: Easy configuration through WordPress admin panel
* **Responsive Design**: Works on desktop and mobile devices

**How It Works:**

1. **Automatic Conversion**: When enabled, all JetFormBuilder WYSIWYG fields are converted to Markdown editors
2. **Custom Field**: Adds a dedicated Markdown field type for new forms
3. **Frontend Integration**: Detects pages with JetFormBuilder forms and loads MarkupMarkdown assets
4. **Dynamic Handling**: Properly handles conditional blocks and dynamic form loading

**Requirements:**

* JetFormBuilder plugin (active)
* Markup Markdown plugin (active)
* WordPress 5.0+
* PHP 7.4+

== Installation ==

1. Download the plugin zip file
2. Go to WordPress Admin → Plugins → Add New → Upload Plugin
3. Upload the zip file and activate the plugin
4. Go to Settings → JFB MarkupMarkdown to configure the plugin

== Frequently Asked Questions ==

= Do I need both JetFormBuilder and MarkupMarkdown plugins? =

Yes, both plugins must be installed and activated for this integration to work.

= Will this affect my existing forms? =

Only if you enable the "Replace WYSIWYG Fields" option. When enabled, existing WYSIWYG fields will use the Markdown editor instead of TinyMCE.

= Can I still use regular text fields? =

Yes, this plugin only affects WYSIWYG fields. Regular text fields, textareas, and other field types remain unchanged.

= Does this work with conditional fields? =

Yes, the plugin properly handles conditional blocks and dynamic form loading.

= Can I disable the integration? =

Yes, you can disable the integration through the plugin settings without deactivating the plugin.

== Screenshots ==

1. Admin settings page showing configuration options
2. Markdown editor in JetFormBuilder form
3. Live preview of markdown content
4. Form with converted WYSIWYG field

== Changelog ==

= 1.0.0 =
* Initial release
* WYSIWYG field conversion
* Custom Markdown field type
* Admin settings page
* Frontend integration
* Dynamic form handling

== Upgrade Notice ==

= 1.0.0 =
Initial release of JetFormBuilder MarkupMarkdown Integration.
