<?php
// ==========================================================
// Plugins :: zeimoto :: Branding :: ColorField
// ==========================================================
//
// Exposes the site's configured color palettes to the Panel.
//
// ==========================================================

declare(strict_types=1);

namespace Zeimoto\Branding;

use Kirby\Form\Field\ColorField as KirbyColorField;
use Kirby\Reflection\Attributes\Prop;

final class ColorField extends KirbyColorField
{
  #[Prop(computed: true)]
  protected array $palettes = [];

  public function palettes(): array
  {
    $palettes = [];

    foreach ($this->kirby()->site()->palettes()->toStructure() as $palette) {
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

    return $palettes ?: [[
      "label" => "Default Palette (B&W)",
      "swatches" => [["name" => "Black", "value" => "#000000"], ["name" => "White", "value" => "#ffffff"]],
    ]];
  }
}
