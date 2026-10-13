# zeimoto Branding

`zeimoto/branding` owns the site's visual-identity data: logo marks and color
palettes. It supplies the site Branding tab, the `zeimoto-color` Panel field, and
two global helpers for resolving marks. It does not style the public frontend.

## Installation

Requires PHP 8.5 and Kirby 5.6.1 or later within Kirby 5.
The Composer package is `zeimoto/branding` and installs into
`site/plugins/zeimoto-branding`. Release versions come from Git tags.

Once published on Packagist:

```sh
composer require zeimoto/branding
```

For manual installation, copy this plugin into `site/plugins/zeimoto-branding`.
See the integration requirements below before using its public surfaces.
The plugin is distributed under the MIT license; see [LICENSE](LICENSE).

## Branding tab

The plugin registers the blueprint `tabs/branding/site`. Mount it in the site
blueprint:

```yaml
tabs:
  branding: tabs/branding/site
```

The tab stores two structures on the site: `palettes` (a label and its named
hex `swatches`) and `marks` (a name, a slug, and one file).

## Helpers

```php
$mark = zeimotoMark("wordmark");
$alt  = $mark ? zeimotoMarkAlt($mark) : null;
```

- `zeimotoMark(string $slug): File|null` returns the file of the mark with that
  slug, or `null` when the slug is unknown or has no file.
- `zeimotoMarkAlt(File $mark): string` returns the file's `alt` text, falling
  back to the matching mark's name, then to an empty string.

Both return raw values; escape at output. Check for `null` before emitting markup.

## Color field

```yaml
type: zeimoto-color
label: Theme Light Mode
```

The field extends Kirby's `color` field and offers the site's palettes as
swatches. Palettes always come from the Branding tab, never from the blueprint.
When none are defined it offers black and white.

## Integration

There are no plugin options and no build step; Kirby loads `index.js` and
`index.css` in the Panel.

The Marks file field uses the host's `files/image` upload blueprint. Supply that
blueprint (and any definitions it extends) to control upload validation and file
metadata. The kit provides it at `site/blueprints/files/image.yml`.

## Development environment

Open `kirby.code-workspace` while this plugin is installed under the zeimoto Kirby
Kit's `site/plugins/` directory. The workspace uses the kit's Composer and npm
dependencies, shared formatting rules, and Kirby classes for autocomplete. IDE
stubs are included; local editor settings, caches, private notes, and `.env` are
ignored. The formatter launcher currently requires Herd's PHP 8.5 on macOS.

Install Composer and npm dependencies at the kit root. From that root, check this
plugin with:

```sh
node tools/check-style.mjs --plugin site/plugins/zeimoto-branding
```
