tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Outfit', 'ui-sans-serif', 'system-ui'],
                display: ['Outfit', 'Inter', 'ui-sans-serif', 'system-ui'],
                serif: ['Playfair Display', 'Georgia', 'serif']
            },
            colors: {
                eventin: {
                    ink: '#0f172a',
                    muted: '#64748b',
                    lime: '#bef264',
                    emerald: '#10b981',
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
