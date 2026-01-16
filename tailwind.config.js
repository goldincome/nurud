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
                    blue: "#0F294D" /* Deep Blue */,
                    lightBlue: "#E6F0FF",
                    orange: "#F59E0B" /* Amber/Orange */,
                    orangeHover: "#D97706",
                    textDark: "#1F2937",
                    textGray: "#6B7280",
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
