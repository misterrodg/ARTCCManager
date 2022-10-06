const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.jsx",
  ],

  theme: {
    extend: {
      colors: {
        amblack: "#171c1a",
        amgrey: "#6e7a83",
        amgreen: {
          accent: "#49B265",
          white: "#c2d6ca",
          light: "#159947",
          DEFAULT: "#1F5F5B",
          dark: "#06373A",
          black: "#062315",
        },
      },
      fontFamily: {
        sans: ["Share Tech Mono", ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [require("@tailwindcss/forms")],
};
