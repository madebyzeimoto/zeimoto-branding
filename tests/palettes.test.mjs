// ==========================================================
// Tests :: Brand Palettes
// ==========================================================
//
// Exercises public JavaScript behavior without a live project.
//
// ==========================================================

import { test } from "node:test"
import assert from "node:assert/strict"
import { readFileSync } from "node:fs"
import vm from "node:vm"

let field
vm.runInNewContext(readFileSync(new URL("../index.js", import.meta.url), "utf8"), {
  panel: { plugin: (_name, config) => { field = config.fields["zeimoto-color"] } },
})

test("a saved custom color remains selectable after a palette changes", () => {
  const context = { value: "#abcdef", palettes: [{ label: "Brand", swatches: [{ name: "Accent", value: "#123456" }] }], ...field.methods }
  const resolved = field.computed.resolvedPalettes.call(context)
  assert.equal(resolved.length, 2)
  assert.equal(resolved[1].swatches[0].value, "#abcdef")
  assert.equal(context.palettes.length, 1)
})

test("an empty current color does not create a custom palette", () => {
  const palettes = [{ label: "Brand", swatches: [] }]
  assert.equal(field.computed.resolvedPalettes.call({ value: "", palettes }), palettes)
})
