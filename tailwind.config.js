module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: [
    
  ],
  theme: {
    extend: {
      width: {
        '210px': '210px',
      },
      height: {
        '210px': '210px',
      },
      inset: {
        '-20px': '-20px',
        '20px': '20px'
      }
    },
  },
  variants: {
    objectPosition: ['responsive', 'hover', 'focus', 'group-hover'],
    textColor: ['responsive', 'hover', 'focus', 'group-hover']
  },
  plugins: [],
}
