const plugin = require('tailwindcss/plugin')

module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: [
    
  ],
  theme: {
    extend: {
      color: {
        gray: {
          bf: '#bfbfbf',
        },
      },
      width: {
        '210px': '210px',
        '3/10': '30%'
      },
      height: {
        '300px': '300px',
        '350px': '350px',
        '210px': '210px',
        '65px': '65px',
        '546px': '546px',
        '450px': '450px',
        '591px': '591px'
      },
      inset: {
        '-20px': '-20px',
        '-30px': '-30px',
        '20px': '20px',
        '30px': '30px',
        '90px': '90px',
        '100px': '100px'
      },
      flex: {
        '2': '2 2 0%'
      },
      margin: {
        '0.1': '0.1rem'
      },
      borderWidth: {
        DEFAULT: '1px',
        '20': '20px',
        '30': '30px'
      },
    },
    cursor: {
      'zoom-in': 'zoom-in',
      'pointer': 'pointer'
    }
  },
  variants: {
    objectPosition: ['responsive', 'hover', 'focus', 'group-hover'],
    textColor: ['responsive', 'hover', 'focus', 'group-hover']
  },
  plugins: [
    plugin(function({ addUtilities }) {
      const newUtilities = {
        '.bg-rgba': {
          border: '1px solid rgba(0,0,0,0.2)',
        },
      }
      addUtilities(newUtilities)
    })
  ],
}
