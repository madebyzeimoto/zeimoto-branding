<?php
// ==========================================================
// Helpers :: Flatten Blueprint Translations
// ==========================================================
//
// Converts nested Panel blueprint translations to Kirby keys.
//
// ==========================================================

declare(strict_types=1);

return function (array $items, string $prefix = ""): array {
  $flatten = function (
    array $items,
    string $prefix = "",
  ) use (&$flatten): array {
    $result = [];

    foreach ($items as $key => $value) {
      $path = $prefix === ""
        ? (string) $key
        : $prefix . "." . $key;

      if (is_array($value)) {
        $result = [
          ...$result,
          ...$flatten($value, $path),
        ];
      } else {
        $result[$path] = $value;
      }
    }

    return $result;
  };

  return $flatten($items, $prefix);
};
