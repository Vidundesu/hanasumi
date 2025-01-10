/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./dist/*.{html,js}"],
  theme: {
    extend: {
      colors:{
        pink:'#ea8abd',
        brightPink:'#d42583',
        lightPink:'#f7cfe4',
      },
      height: {
        'dvh' : '100dvh',
        'svh' : '100svh',
        '2dvh': '200dvh',
        'half' : '60dvh',
        '30vh':'30vh',
        '150dvh' : '150dvh',
        '75dvh':'75dvh',
      },
      fontFamily: {
        quicksand: ['Quicksand', 'sans-serif'],
        yeseva: ['Yeseva One', 'sans'],
        luxurious: ['Luxurious Script', 'cursive'],
        kaisei: ['Kaisei Decol', 'sans'],
        poppins: ['Poppins', 'sans'],
        lora: ['Lora', 'sans'],
      },
      screens: {
        'max-md': { 'max': '1000px' },
        'max-sm': { 'max': '600px'},
      },
    },
  },
  plugins: [],
}

