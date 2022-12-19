const pick = require('lodash/pick')
const twColors = require('tailwindcss/colors')

const colors = {
    primary: {
        50: "#f5f3ff",
        100: "#ede9fe",
        200: "#ddd6fe",
        300: "#c4b5fd",
        400: "#a78bfa",
        500: "#8b5cf6",
        600: "#7c3aed",
        700: "#6d28d9",
        800: "#5b21b6",
        900: "#4c1d95"
    },
    ...twColors
}

const colorToConvert = pick(colors, ['primary'])

function generateRootCSSVariables() {
  return Object.fromEntries(
    Object.entries(colorToConvert)
      .map(([key, value]) => {
        if (typeof value === 'string') {
          return [[`--colors-${key}`, toRGBString(value)]]
        }

        return Object.entries(value).map(([shade, color]) => {
          return [`--colors-${key}-${shade}`, toRGBString(color)]
        })
      })
      .flat(1)
  )
}

function generateTailwindColors() {
  return Object.fromEntries(
    Object.entries(colorToConvert).map(([key, value]) => {
      if (typeof value === 'string') {
        return [`${key}`, value]
      }

      return [
        key,
        Object.fromEntries(
          Object.entries(value).map(([shade]) => {
            return [`${shade}`, `rgba(var(--colors-${key}-${shade}))`]
          })
        ),
      ]
    })
  )
}

const toRGBString = hexCode => {
    if (hexCode.startsWith('#')) {
        let hex = hexCode.replace('#', '')

        if (hex.length === 3) {
            hex = `${hex[0]}${hex[0]}${hex[1]}${hex[1]}${hex[2]}${hex[2]}`
        }

        const r = parseInt(hex.substring(0, 2), 16)
        const g = parseInt(hex.substring(2, 4), 16)
        const b = parseInt(hex.substring(4, 6), 16)

        return `${r}, ${g}, ${b}`
    }

    return hexCode
}

module.exports = {
  generateRootCSSVariables,
  generateTailwindColors,
}
