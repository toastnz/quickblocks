# SilverStripe QuickBlocks 

Simple content blocks system. Nothing fancy, easy to implement.

Add the following to your `config.yml`:

```yaml
Page:
  extensions:
    - QuickBlocksExtension
```

Use `Page` or other class that extends `SiteTree`.

In your `Layout/Page.ss` template, add the following:

```silverstripe
<% loop $ContentBlocks %>
    $ForTemplate
<% end_loop %>
```

## Configuration

Add / remove available block classes

```yaml
QuickBlocksExtension:
  available_blocks:
    - TextBlock
```

## Todo:

* Namespacing
* Template global providers
* Zoning
* SilverStripe 4