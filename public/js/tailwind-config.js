tailwind.config = {
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Outfit', 'ui-sans-serif', 'system-ui'],
                display: ['Outfit', 'Inter', 'ui-sans-serif', 'system-ui'],
                serif: ['Playfair Display', 'Georgia', 'serif']
            },
            colors: {
                lime: {
                    50: 'rgb(var(--theme-light-50) / <alpha-value>)',
                    100: 'rgb(var(--theme-light-100) / <alpha-value>)',
                    200: 'rgb(var(--theme-light-200) / <alpha-value>)',
                    300: 'rgb(var(--theme-light-300) / <alpha-value>)',
                    400: 'rgb(var(--theme-light-400) / <alpha-value>)',
                    500: 'rgb(var(--theme-light-500) / <alpha-value>)',
                    600: 'rgb(var(--theme-light-600) / <alpha-value>)',
                    700: 'rgb(var(--theme-light-700) / <alpha-value>)',
                    800: 'rgb(var(--theme-light-800) / <alpha-value>)',
                    900: 'rgb(var(--theme-light-900) / <alpha-value>)'
                },
                emerald: {
                    50: 'rgb(var(--theme-dark-50) / <alpha-value>)',
                    100: 'rgb(var(--theme-dark-100) / <alpha-value>)',
                    200: 'rgb(var(--theme-dark-200) / <alpha-value>)',
                    300: 'rgb(var(--theme-dark-300) / <alpha-value>)',
                    400: 'rgb(var(--theme-dark-400) / <alpha-value>)',
                    500: 'rgb(var(--theme-dark-500) / <alpha-value>)',
                    600: 'rgb(var(--theme-dark-600) / <alpha-value>)',
                    700: 'rgb(var(--theme-dark-700) / <alpha-value>)',
                    800: 'rgb(var(--theme-dark-800) / <alpha-value>)',
                    900: 'rgb(var(--theme-dark-900) / <alpha-value>)'
                },
                eventin: {
                    ink: '#0f172a',
                    muted: '#64748b',
                    lime: 'rgb(var(--theme-light-300) / <alpha-value>)',
                    emerald: 'rgb(var(--theme-dark-500) / <alpha-value>)',
                    indigo: '#6366f1',
                    purple: '#a855f7',
                    paper: '#fafaf9'
                }
            },
            boxShadow: {
                soft: '0 18px 60px rgba(15, 23, 42, 0.08)',
                lift: '0 24px 80px rgba(15, 23, 42, 0.12)',
                glow: '0 0 40px rgba(16, 185, 129, 0.28)'
            },
            animation: {
                floaty: 'floaty 7s ease-in-out infinite',
                scanner: 'scanner 2.4s ease-in-out infinite',
                fadeUp: 'fadeUp .55s ease both',
                pulseSoft: 'pulseSoft 1.8s ease-in-out infinite'
            },
            keyframes: {
                floaty: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' }
                },
                scanner: {
                    '0%': { transform: 'translateY(-115%)', opacity: '.2' },
                    '50%': { opacity: '1' },
                    '100%': { transform: 'translateY(115%)', opacity: '.2' }
                },
                fadeUp: {
                    '0%': { opacity: '0', transform: 'translateY(14px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' }
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '.55', transform: 'scale(1)' },
                    '50%': { opacity: '1', transform: 'scale(1.04)' }
                }
            }
        }
    }
};
