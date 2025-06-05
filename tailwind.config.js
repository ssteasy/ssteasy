import defaultTheme from 'tailwindcss/defaultTheme';

export default {
  content: [
    './resources/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
    './vendor/filament/**/**/*.blade.php',
  ],
  safelist: [
    'text-white',
    'bg-white',
    'bg-[var(--primary)]',
    'bg-[var(--bg-light)]',
    'text-[var(--primary)]',
    'font-bold',
    'shadow-soft',
    'rounded-2xl',
    'rounded-md',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#11456D',
        secondary: '#4DB2E1',
        accent: '#F5D443',
        background: '#DDF3FF',
        darktext: '#1D1D1D',
      },
      fontFamily: {
        sans: ['Inter', ...defaultTheme.fontFamily.sans],
      },
      boxShadow: {
        soft: '0 1px 3px rgba(0, 0, 0, 0.1)',
      },
    },
  },
};
