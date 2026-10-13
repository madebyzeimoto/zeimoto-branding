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
    // Extends the core color field (hex validation, value handling) and
    // exposes the site's palettes/swatches to the Vue component (index.js).
    "zeimoto-color" => [
      "extends" => "color",
      "computed" => [
        // Always sourced from the site's Branding tab, never the blueprint.
        "palettes" => function (): array {
          $palettes = [];

          foreach (kirby()->site()->palettes()->toStructure() as $palette) {
            $swatches = [];

            foreach ($palette->swatches()->toStructure() as $swatch) {
              $hex = $swatch->value()->value();
              if (!empty($hex)) {
                $swatches[] = [
                  "name" => $swatch->name()->value(),
                  "value" => $hex,
                ];
              }
            }

            if (!empty($swatches)) {
              $palettes[] = [
                "label" => $palette->label()->value(),
                "swatches" => $swatches,
              ];
            }
          }

          if (empty($palettes)) {
            $palettes[] = [
              "label" => "Default Palette (B&W)",
              "swatches" => [["name" => "White", "value" => "#ffffff"], ["name" => "Black", "value" => "#000000"]],
            ];
          }

          return $palettes;
        },
      ],
    ],
  ],
  "blueprints" => [
    "tabs/branding/site" => __DIR__ . "/blueprints/tabs/branding/site.yml",
  ],
]);
