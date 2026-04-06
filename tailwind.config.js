import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", "sans-serif"],
            },
            colors: {
                brand: {
                    /* ===== Nurud Logo Palette ===== */
                    blue: "#2E3B82",          /* Primary Blue - headings, nav, footer */
                    blueDark: "#1E2A6E",      /* Darker shade for hover/active */
                    blueDeep: "#151F55",      /* Deepest shade for hero/footer bg */
                    blueLight: "#4A5BA0",     /* Lighter shade for secondary elements */
                    bluePale: "#E8EBF5",      /* Very light blue for backgrounds */

                    red: "#C73642",           /* Primary Red - CTA, accents */
                    redDark: "#A52D37",       /* Red hover state */
                    redLight: "#E04B57",      /* Lighter red for highlights */

                    gray: "#6B7280",          /* Neutral gray for text */
                    grayLight: "#9CA3AF",     /* Light gray for secondary text */
                    grayDark: "#374151",      /* Dark gray for primary text */

                    /* Legacy aliases (backward compat) */
                    orange: "#C73642",
                    orangeHover: "#A52D37",
                    textDark: "#1F2937",
                    textGray: "#6B7280",
                    lightBlue: "#E8EBF5",

                    /* Momondo-style accents (keeping some for gradient variety) */
                    purple: "#2E3B82",
                    purpleDark: "#1E2A6E",
                    purpleDeep: "#151F55",
                    purpleLight: "#4A5BA0",
                    purpleAccent: "#5B6CB5",
                    violet: "#3A4990",
                    magenta: "#C73642",
                    heroStart: "#151F55",
                    heroEnd: "#2E3B82",
                    green: "#C73642",         /* Search button now uses red */
                    greenDark: "#A52D37",
                },
                keyframes: {
                    'fade-in-down': {
                        '0%': {
                            opacity: '0',
                            transform: 'translateY(-10px)'
                        },
                        '100%': {
                            opacity: '1',
                            transform: 'translateY(0)'
                        },
                    }
                },
                animation: {
                    'fade-in-down': 'fade-in-down 0.3s ease-out'
                }
            },
        },
    },

    plugins: [forms],
};
