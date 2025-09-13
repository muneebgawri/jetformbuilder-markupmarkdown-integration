# Troubleshooting Guide - JetFormBuilder MarkupMarkdown Integration

## Issue: WYSIWYG Field Shows as Plain Textarea

### Problem Description
The WYSIWYG field is being converted to a textarea (which is correct), but the MarkupMarkdown editor is not being initialized, so it appears as a plain textarea without any editor functionality.

### Debugging Steps

#### 1. Enable Debug Mode
Add `?jfb_mmd_debug=1` to your form URL to enable console logging:
```
https://your-site.com/your-form-page/?jfb_mmd_debug=1
```

#### 2. Check Browser Console
Open browser developer tools (F12) and check the Console tab for error messages. Look for:
- "JetFormBuilder MarkupMarkdown Integration Debug Mode"
- "MarkupMarkdown available: true/false"
- "WYSIWYG fields found: X"
- Any error messages

#### 3. Verify Plugin Requirements
Ensure both plugins are active:
- JetFormBuilder plugin
- Markup Markdown plugin

#### 4. Check Plugin Settings
Go to **Settings → JFB MarkupMarkdown** and verify:
- ✅ "Replace WYSIWYG Fields" is enabled
- ✅ "Enable Custom Markdown Field" is enabled (optional)

#### 5. Check Asset Loading
In browser developer tools, go to **Network** tab and reload the page. Look for:
- `builder.min.js` from MarkupMarkdown plugin
- `plugin_options.min.css` from MarkupMarkdown plugin
- `easymde.min.css` from MarkupMarkdown plugin

### Common Issues and Solutions

#### Issue 1: MarkupMarkdown Assets Not Loading
**Symptoms:** Console shows "MarkupMarkdown not available"

**Solutions:**
1. Verify MarkupMarkdown plugin is active
2. Check if MarkupMarkdown plugin files exist in `/wp-content/plugins/markup-markdown/`
3. Clear any caching plugins
4. Check for JavaScript errors preventing asset loading

#### Issue 2: Wrong Field Selector
**Symptoms:** Console shows "WYSIWYG fields found: 0"

**Solutions:**
1. Check if the field has the correct CSS classes
2. Verify the field is actually a WYSIWYG field that was converted
3. Check if the field is inside a conditional block that's hidden

#### Issue 3: Timing Issues
**Symptoms:** Fields found but editor not initializing

**Solutions:**
1. The plugin now waits for MarkupMarkdown to load before initializing
2. Check if there are JavaScript conflicts
3. Try increasing the timeout in the plugin code

#### Issue 4: EasyMDE Fallback Not Working
**Symptoms:** MarkupMarkdown not available, EasyMDE also not working

**Solutions:**
1. Check if EasyMDE assets are loading
2. Verify no JavaScript errors in console
3. Try manual initialization (see debug script below)

### Manual Testing

#### Add Debug Script
Add this to your theme's `functions.php` for detailed debugging:

```php
// Add debug parameter to enable console logging
add_action('wp_footer', function() {
    if (isset($_GET['jfb_mmd_debug']) && $_GET['jfb_mmd_debug'] == '1') {
        ?>
        <script>
        console.log('=== JetFormBuilder MarkupMarkdown Debug ===');
        console.log('MarkupMarkdown available:', typeof window.MarkupMarkdown !== 'undefined');
        console.log('EasyMDE available:', typeof window.EasyMDE !== 'undefined');
        
        jQuery(document).ready(function($) {
            console.log('WYSIWYG fields found:', $('.jet-form-builder__field.wysiwyg-field').length);
            
            // Manual initialization test
            setTimeout(function() {
                $('.jet-form-builder__field.wysiwyg-field').each(function() {
                    var $textarea = $(this).find('textarea');
                    if ($textarea.length && typeof window.MarkupMarkdown !== 'undefined') {
                        try {
                            new window.MarkupMarkdown($textarea[0]);
                            console.log('SUCCESS: Manual initialization worked');
                        } catch (error) {
                            console.error('ERROR:', error);
                        }
                    }
                });
            }, 1000);
        });
        </script>
        <?php
    }
});
```

#### Manual Initialization Test
If the plugin isn't working, you can test manual initialization by adding this to your page:

```javascript
jQuery(document).ready(function($) {
    setTimeout(function() {
        $('.jet-form-builder__field.wysiwyg-field textarea').each(function() {
            if (typeof window.MarkupMarkdown !== 'undefined') {
                new window.MarkupMarkdown(this);
            }
        });
    }, 1000);
});
```

### Plugin File Updates

The plugin has been updated with the following improvements:

1. **Better Field Detection**: Now targets `.textarea-field` instead of `.wysiwyg-field`
2. **Enhanced Debugging**: More detailed console logging
3. **Fallback Support**: Tries EasyMDE if MarkupMarkdown fails
4. **Timing Fixes**: Waits for MarkupMarkdown to load before initializing
5. **Asset Loading**: Better handling of MarkupMarkdown assets

### Testing Checklist

- [ ] Both JetFormBuilder and MarkupMarkdown plugins are active
- [ ] Plugin settings are enabled
- [ ] Debug mode shows correct information in console
- [ ] MarkupMarkdown assets are loading (check Network tab)
- [ ] No JavaScript errors in console
- [ ] Field has correct CSS classes
- [ ] Manual initialization works (if automatic doesn't)

### Support

If the issue persists after following these steps:

1. **Check Console Logs**: Enable debug mode and share console output
2. **Check Network Tab**: Verify assets are loading
3. **Test Manual Initialization**: Try the manual JavaScript approach
4. **Plugin Conflicts**: Temporarily deactivate other plugins to test
5. **Theme Issues**: Test with default WordPress theme

### Quick Fix

If you need a quick solution, you can manually initialize the editor by adding this JavaScript to your page:

```javascript
jQuery(document).ready(function($) {
    function initMarkdownEditors() {
        $('.jet-form-builder__field.wysiwyg-field textarea').each(function() {
            var $textarea = $(this);
            if (!$textarea.closest('.jet-form-builder__field').hasClass('markdown-initialized')) {
                $textarea.closest('.jet-form-builder__field').addClass('markdown-initialized');
                
                if (typeof window.MarkupMarkdown !== 'undefined') {
                    new window.MarkupMarkdown(this);
                } else if (typeof window.EasyMDE !== 'undefined') {
                    new window.EasyMDE({
                        element: this,
                        spellChecker: false,
                        toolbar: ['bold', 'italic', 'heading', '|', 'quote', 'unordered-list', 'ordered-list', '|', 'link', 'image', '|', 'preview', 'side-by-side', 'fullscreen']
                    });
                }
            }
        });
    }
    
    setTimeout(initMarkdownEditors, 1000);
});
```
