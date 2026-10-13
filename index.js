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
      template: `
        <k-field
          :help="help"
          :label="label"
          :name="name"
          :required="required"
          class="k-zeimoto-color-field"
        >
          <div class="k-zeimoto-color-palettes">
            <section
              v-for="palette in resolvedPalettes"
              :key="palette.label"
              class="k-zeimoto-color-palette"
            >
              <h3 class="k-zeimoto-color-palette-label">{{ palette.label }}</h3>
              <ul class="k-zeimoto-color-swatches">
                <li v-for="swatch in palette.swatches" :key="swatch.value">
                  <button
                    :aria-pressed="isSelected(swatch.value)"
                    :class="{ 'is-selected': isSelected(swatch.value) }"
                    :disabled="disabled"
                    :title="swatch.name + ' (' + swatch.value + ')'"
                    class="k-zeimoto-color-swatch"
                    type="button"
                    @click="select(swatch.value)"
                  >
                    <span
                      class="k-zeimoto-color-chip"
                      :style="{ background: swatch.value }"
                    ></span>
                    <span class="k-zeimoto-color-name">{{ swatch.name }}</span>
                  </button>
                </li>
              </ul>
            </section>
          </div>
        </k-field>
      `,
    },
  },
})
