import type { Config } from 'tailwindcss';
import animate from 'tailwindcss-animate';

export default {
  darkMode: ['class'],
  content: ['./index.html', './src/**/*.{js,ts,jsx,tsx}'],
  theme: {
    container: { center: true, padding: '2rem', screens: { '2xl': '1400px' } },
    extend: {
      colors: {
        navy: {
          50: '#E6E6F0', 100: '#B3B3D1', 200: '#8080B3',
          300: '#4D4D94', 400: '#1A1A75', 500: '#000080',
          600: '#000073', 700: '#000066', 800: '#000059',
          900: '#00004D', 950: '#000033',
        },
        saffron: {
          50: '#FFF5EB', 100: '#FFE8CC', 200: '#FFD599',
          300: '#FFC266', 400: '#FFAD33', 500: '#FF9933',
          600: '#E68A2E', 700: '#CC7A29', 800: '#B36B24', 900: '#995C1F',
        },
        india: {
          50: '#E6F4E6', 100: '#B3E0B3', 200: '#80CC80',
          300: '#4DB84D', 400: '#1AA41A', 500: '#138808',
          600: '#117A07', 700: '#0F6D06', 800: '#0D5F05',
        },
        slate: {
          50: '#F8FAFC', 100: '#F1F5F9', 200: '#E2E8F0',
          300: '#CBD5E1', 400: '#94A3B8', 500: '#64748B',
          600: '#475569', 700: '#334155', 800: '#1E293B', 900: '#0F172A',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'sans-serif'],
        display: ['Plus Jakarta Sans', 'Inter', 'system-ui', 'sans-serif'],
      },
      boxShadow: {
        'premium': '0 4px 20px -2px rgba(0,0,80,0.1), 0 2px 8px -2px rgba(0,0,80,0.06)',
        'premium-lg': '0 12px 40px -4px rgba(0,0,80,0.12), 0 4px 16px -4px rgba(0,0,80,0.08)',
        'card': '0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06)',
      },
      borderRadius: { 'xl': '0.75rem', '2xl': '1rem', '3xl': '1.5rem' },
      animation: {
        'slide-up': 'slide-up 0.3s ease-out',
        'fade-in': 'fade-in 0.2s ease-out',
        'scale-in': 'scale-in 0.2s ease-out',
        'pulse-dot': 'pulse-dot 2s infinite',
      },
      keyframes: {
        'slide-up': { '0%': { transform: 'translateY(10px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } },
        'fade-in': { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
        'scale-in': { '0%': { transform: 'scale(0.95)', opacity: '0' }, '100%': { transform: 'scale(1)', opacity: '1' } },
        'pulse-dot': { '0%, 100%': { opacity: '1' }, '50%': { opacity: '0.5' } },
      },
    },
  },
  plugins: [animate],
} satisfies Config;
