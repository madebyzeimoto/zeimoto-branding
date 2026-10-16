<?php
// ==========================================================
// Tests :: Branding Contracts
// ==========================================================
//
// Exercises the plugin public behavior with disposable Kirby fixtures.
//
// ==========================================================

declare(strict_types=1);

namespace Zeimoto\Branding\Tests;

use Kirby\Cms\File;
use Kirby\Form\Field;

final class ContractTest extends PluginTestCase
{
  public function testUnconfiguredMarksAreAbsentAndPaletteIsUsable(): void
  {
    $app = $this->app();
    self::assertNull(zeimotoMark("missing"));
    $field = new Field("zeimoto-color", ["name" => "color", "model" => $app->site()]);
    self::assertNotEmpty($field->palettes());
  }

  public function testConfiguredPaletteAndRawAlt(): void
  {
    $app = $this->app(site: ["content" => ["palettes" => "- label: Brand\n  swatches:\n    - name: Accent\n      value: '#123456'"]]);
    $field = new Field("zeimoto-color", ["name" => "color", "model" => $app->site()]);
    self::assertSame("#123456", $field->palettes()[0]["swatches"][0]["value"]);
    $file = new File(["filename" => "mark.svg", "parent" => $app->site(), "content" => ["alt" => "A & B"]]);
    self::assertSame("A & B", zeimotoMarkAlt($file));
  }
}
