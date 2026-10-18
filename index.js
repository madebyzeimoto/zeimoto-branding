// ==========================================================
// Plugins :: zeimoto :: Branding
// ==========================================================
//
// Registers the palette-backed color picker with the Kirby Panel.
//
// ==========================================================

panel.plugin("zeimoto/branding", {
  fields: {
    "zeimoto-color": {
      props: {
        disabled: Boolean,
        help: String,
        label: String,
        name: String,
        required: Boolean,
        value: String,
        palettes: {
          type: Array,
          default: () => [],
        },
      },
      computed: {
        // The branding palettes, plus a "Custom" group holding the current
        // value when it was set manually but no longer matches any swatch in
        // Site Branding (e.g. the swatch was removed after being picked).
        // Without this the saved color would be invisible/unselectable here.
        resolvedPalettes() {
          if (!this.value) {
            return this.palettes
          }

          const known = this.palettes.some((palette) =>
            palette.swatches.some((swatch) => this.isSelected(swatch.value))
          )

          if (known) {
            return this.palettes
          }

          return [
            ...this.palettes,
            {
              label: "Custom",
              swatches: [{ name: this.value, value: this.value }],
            },
          ]
        },
      },
      methods: {
        isSelected(hex) {
          return hex.toLowerCase() === this.value?.toLowerCase()
        },
        select(hex) {
          if (this.disabled) {
            return
          }
          // Click a selected swatch again to clear the value.
          this.$emit("input", this.isSelected(hex) ? "" : hex)
        },
      },
      render(h) {
        return h("k-field", {
          class: "k-zeimoto-color-field",
          props: { help: this.help, label: this.label, name: this.name, required: this.required },
        }, [
          h("div", { class: "k-zeimoto-color-palettes" }, this.resolvedPalettes.map((palette) =>
            h("section", { key: palette.label, class: "k-zeimoto-color-palette" }, [
              h("h3", { class: "k-zeimoto-color-palette-label" }, palette.label),
              h("ul", { class: "k-zeimoto-color-swatches" }, palette.swatches.map((swatch) =>
                h("li", { key: swatch.value }, [
                  h("button", {
                    class: ["k-zeimoto-color-swatch", { "is-selected": this.isSelected(swatch.value) }],
                    attrs: {
                      "aria-pressed": this.isSelected(swatch.value),
                      disabled: this.disabled,
                      title: `${swatch.name} (${swatch.value})`,
                      type: "button",
                    },
                    on: { click: () => this.select(swatch.value) },
                  }, [
                    h("span", { class: "k-zeimoto-color-chip", style: { background: swatch.value } }),
                    h("span", { class: "k-zeimoto-color-name" }, swatch.name),
                  ]),
                ])
              )),
            ])
          )),
        ])
      },
    },
  },
})
