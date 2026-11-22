<?php
// ==========================================================
// Plugins :: zeimoto :: Branding
// ==========================================================
//
// Owns site logo marks, color palettes, their management tab,
// and the palette-backed color field in the Kirby Panel.
//
// ==========================================================

declare(strict_types=1);

use Kirby\Cms\File;
use Kirby\Toolkit\Str;
use Zeimoto\Branding\ColorField;

load([
  ColorField::class => "src/ColorField.php",
], __DIR__);

if (function_exists("zeimotoMark") === false) {
  function zeimotoMark(string $slug): File|null
  {
    $slug = Str::slug($slug);

    foreach (kirby()->site()->marks()->toStructure() as $mark) {
      if ($mark->slug()->value() === $slug) {
        return $mark->file()->toFile();
      }
    }

    return null;
  }
}

if (function_exists("zeimotoMarkAlt") === false) {
  function zeimotoMarkAlt(File $mark): string
  {
    if ($mark->alt()->isNotEmpty()) {
      return $mark->alt()->value();
    }

    foreach (kirby()->site()->marks()->toStructure() as $markEntry) {
      $markFile = $markEntry->file()->toFile();

      if ($markFile !== null && $markFile->is($mark)) {
        return $markEntry->name()->value();
      }
    }

    return "";
  }
}

Kirby::plugin("zeimoto/branding", [
  "fields" => [
    "zeimoto-color" => ColorField::class,
  ],
  "blueprints" => [
    "tabs/branding/site" => __DIR__ . "/blueprints/tabs/branding/site.yml",
  ],
]);
