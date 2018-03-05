# SilverStripe QuickBlocks 

Simple content blocks system. Nothing fancy, easy to implement.

Add the following to your `config.yml`:

```yaml
Page:
  extensions:
    - Toast\QuickBlocksExtension
```

Optionally:

```yaml
PageController:
  extensions:
    - Toast\QuickBlocksControllerExtension
```

Use `Page` or other class that extends `SiteTree`.

In your `Layout/Page.ss` template, add the following:

```silverstripe
<% loop $ContentBlocks %>
    $ForTemplate
<% end_loop %>
```

## Configuration

### Add / remove available block classes

```yaml
QuickBlocksExtension:
  available_blocks:
    - Toast\TextBlock
```

### Create a custom block

Extend `QuickBlock` to create a new block type.

```php
<?php
 
class MyBlock extends Toast\QuickBlock
{
    private static $singular_name = 'My Block';
    private static $plural_name = 'My Blocks';
    private static $icon = 'mysite/images/blocks/custom.png';
    
    private static $db = [
        'Content' => 'HTMLText'
    ];

}
```

`/themes/default/templates/Toast/QuickBlocks/MyBlock.ss`:

```silverstripe
<%-- Your block template here --%>

<h2>$Title</h2>
$Content
```

## Todo:

* Namespacing
* Template global providers
* Zoning
* SilverStripe 4