/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./blocks/**/*.php",
    "./assets/**/*.js",
    "./js/**/*.js",
  ],
  safelist: [
    // Typography classes
    'text-headline-1', 'text-headline-2', 'text-headline-small-1', 'text-headline-small-2',
    'text-title-1', 'text-title-2', 'text-title-small-1', 'text-title-small-2',
    'text-subtitle-1', 'text-subtitle-2',
    'text-body-1', 'text-body-2', 'text-body-small-1', 'text-body-small-2',
    'text-body-super-small-1', 'text-body-super-small-2',
    'text-paragraph-20-1', 'text-paragraph-20-2', 'text-paragraph-16-1', 'text-paragraph-16-2',
    'text-paragraph-14-1', 'text-paragraph-14-2', 'text-paragraph-12-1', 'text-paragraph-12-2',
    'text-caption-14-1', 'text-caption-14-2', 'text-caption-12-1', 'text-caption-12-2',
    'text-caption-10-1', 'text-caption-10-2',
    // Font families
    'font-nunito', 'font-nunito-sans',
    // Font weights
    'font-regular', 'font-semibold', 'font-bold', 'font-extrabold',
    // Responsive utilities for mobile
    'md:hidden', 'md:block', 'hidden',
    // Button classes
    'btn', 'btn-primary', 'btn-size-lg', 'btn-cta', 'text-white',
    // Colors
    'text-neutral-text', 'text-primary', 'hover:text-primary', 'hover:text-primary-hover',
    'text-neutral-text-subtle', 'text-neutral-text-subtlest',
    'inline-flex', 'gap-2',
    'bg-primary', 'text-white', 'bg-beige-100',
    'bg-primary-hover', 'group-hover:bg-primary-hover',
    // Button Tertiary classes
    'btn-tertiary', 'btn-tertiary-xs', 'btn-tertiary-sm', 'btn-tertiary-md', 'btn-tertiary-lg',
    'btn-text', 'group', 'border-b', 'border-primary', 'border-primary-hover', 'pb-[1px]',
    // Ghost button classes
    'btn-ghost', 'btn-ghost-sm', 'btn-ghost-md', 'btn-ghost-lg',
    // Layout classes for header
    'lg:hidden', 'lg:flex', 'lg:block',
    'hidden',
    // Spacing classes
    'px-6', 'px-8', 'pt-5', 'pt-6', 'pb-5', 'py-5', 'py-8',
    'pt-[22px]', 'pt-[30px]', 'pb-5', 'lg:px-[106px]',
    'gap-2', 'gap-4', 'gap-10', 'gap-16', 'gap-[64px]', 'gap-[201px]',
    'mb-4', 'mb-6', 'mb-8', 'mb-[48px]', 'mt-[32px]', 'ml-auto', 'ml-[315px]',
    'justify-between', 'items-center', 'items-start',
    // Sizing
    'size-6', 'h-6', 'w-6', 'h-4', 'w-4',
    'h-[23.648px]', 'w-[127px]', 'h-[27px]', 'h-[88px]',
    'min-w-4', 'min-w-[16px]',
    'w-screen', 'max-w-[1440px]',
    // Colors
    'bg-[#B30A62]', 'bg-[#E0E0E0]',
    'border-[#E0E0E0]', 'border-neutral-container-border',
    // Mega menu classes
    'mega-dropdown', 'mega-menu-column',
    'invisible', 'opacity-0', 'visible', 'opacity-100',
    'group-hover:visible', 'group-hover:opacity-100', 'group-hover:rotate-180',
    'transition-all', 'transition-transform', 'duration-200',
    'grid', 'grid-cols-3', 'flex',
    'absolute', 'relative', 'static', 'fixed',
    'top-full', 'top-[88px]', 'top-[89px]', 'left-0', 'right-0', 'w-full', 'w-screen',
    '-translate-x-1/2', 'left-1/2',
    'shadow-lg', 'border-t',
    'z-50', 'z-40',
    // Arbitrary/fallback utilities used in PHP strings
    'text-[16px]', 'leading-6', 'text-[12px]', 'leading-4',
    // Font weights used explicitly
    'font-normal', 'font-semibold',
    // Top stats block classes - grid version
    'grid', 'grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3', 'gap-10', 'w-full',
    'text-4xl', 'sm:text-[56px]', 'leading-none', 'text-[#222222]',
    'text-[18px]', 'sm:text-[24px]', 'leading-[26px]', 'sm:leading-[30px]', 'text-[#3c3c3b]',
    'mt-3', 'sm:mt-[13px]', 'mt-5', 'sm:mt-[20px]', 'mt-[2px]',
    'h-6', 'sm:h-[30px]', 'w-auto', 'mx-auto',
    'flex-col', 'text-center', 'flex-wrap', 'list-none', 'p-0', 'm-0',
  ],
  theme: {
    extend: {
      container: {
        center: true,
        padding: '1rem',
      },
      colors: {
        // Primary colors
        primary: {
          DEFAULT: '#E20C7B',
          hover: '#830747',
          pressed: '#B30A62',
          text: '#220212',
          'text-subtle': '#52042D',
          'text-subtlest': '#830747',
          'on-default': '#FFFFFF',
          'container-bg': '#FEF0F8',
          'container-border': '#FCC0DF',
          'selected-bg': '#FCC0DF',
          'selected-border': '#B30A62',
          icon: '#E20C7B',
        },

        // Neutral colors
        neutral: {
          DEFAULT: '#888888',
          hover: '#555555',
          pressed: '#6F6F6F',
          text: '#222222',
          'text-subtle': '#3D3D3D',
          'text-subtlest': '#555555',
          'on-default': '#FFFFFF',
          'container-bg': '#F0F0F0',
          'container-border': '#E0E0E0',
          'field-bg': '#FFFFFF',
          'field-border': '#C4C4C4',
          card: '#FFFFFF',
          icon: '#6F6F6F',
        },

        // Information colors
        info: {
          DEFAULT: '#5AA0D3',
          hover: '#3D5F79',
          pressed: '#4E86AF',
          'on-default': '#FFFFFF',
          'container-bg': '#EFF6FB',
          'container-border': '#D6E7F4',
          text: '#283239',
          'text-subtle': '#314554',
          'text-subtlest': '#3D5F79',
          icon: '#4E86AF',
        },

        // Accent colors
        accent: {
          pink: {
            DEFAULT: '#E20C7B',
            subtle: '#F990C6',
            bolder: '#830747',
          },
          beige: {
            DEFAULT: '#C39F88',
          },
          blue: {
            DEFAULT: '#5AA0D3',
          },
          orange: {
            DEFAULT: '#FFA63C',
          },
        },

        // Semantic colors
        success: {
          DEFAULT: '#95CA52',
        },
        warning: {
          DEFAULT: '#FFA63C',
        },
        danger: {
          DEFAULT: '#ED6565',
          'on-default': '#FFFFFF',
        },
        focus: {
          DEFAULT: '#FFDF61',
        },

        // Additional colors
        beige: {
          100: '#F8F4F1',
          500: '#C39F88',
        },
        transparent: 'transparent',
      },
      fontFamily: {
        'nunito': ['Nunito', 'sans-serif'],
        'nunito-sans': ['Nunito Sans', 'sans-serif'],
      },
      fontSize: {
        // Headlines
        'headline-1': ['52px', { lineHeight: '64px', fontWeight: '600' }],
        'headline-2': ['52px', { lineHeight: '64px', fontWeight: '800' }],
        'headline-small-1': ['40px', { lineHeight: '56px', fontWeight: '600' }],
        'headline-small-2': ['40px', { lineHeight: '50px', fontWeight: '800' }],

        // Titles
        'title-1': ['32px', { lineHeight: '40px', fontWeight: '600' }],
        'title-2': ['32px', { lineHeight: '40px', fontWeight: '800' }],
        'title-small-1': ['24px', { lineHeight: '30px', fontWeight: '600' }],
        'title-small-2': ['24px', { lineHeight: '30px', fontWeight: '800' }],

        // Subtitles
        'subtitle-1': ['20px', { lineHeight: '26px', fontWeight: '600' }],
        'subtitle-2': ['20px', { lineHeight: '26px', fontWeight: '800' }],

        // Body
        'body-1': ['16px', { lineHeight: '20px', fontWeight: '600' }],
        'body-2': ['16px', { lineHeight: '20px', fontWeight: '800' }],
        'body-small-1': ['14px', { lineHeight: '18px', fontWeight: '600' }],
        'body-small-2': ['14px', { lineHeight: '18px', fontWeight: '800' }],
        'body-super-small-1': ['12px', { lineHeight: '16px', fontWeight: '600' }],
        'body-super-small-2': ['12px', { lineHeight: '16px', fontWeight: '800' }],

        // Paragraphs
        'paragraph-20-1': ['20px', { lineHeight: '28px', fontWeight: '400' }],
        'paragraph-20-2': ['20px', { lineHeight: '28px', fontWeight: '700' }],
        'paragraph-16-1': ['16px', { lineHeight: '22px', fontWeight: '400' }],
        'paragraph-16-2': ['16px', { lineHeight: '22px', fontWeight: '700' }],
        'paragraph-14-1': ['14px', { lineHeight: '20px', fontWeight: '400' }],
        'paragraph-14-2': ['14px', { lineHeight: '20px', fontWeight: '700' }],
        'paragraph-12-1': ['12px', { lineHeight: '18px', fontWeight: '400' }],
        'paragraph-12-2': ['12px', { lineHeight: '18px', fontWeight: '700' }],

        // Captions
        'caption-14-1': ['14px', { lineHeight: '18px', fontWeight: '400' }],
        'caption-14-2': ['14px', { lineHeight: '18px', fontWeight: '700' }],
        'caption-12-1': ['12px', { lineHeight: '16px', fontWeight: '400' }],
        'caption-12-2': ['12px', { lineHeight: '16px', fontWeight: '700' }],
        'caption-10-1': ['10px', { lineHeight: '14px', fontWeight: '400' }],
        'caption-10-2': ['10px', { lineHeight: '14px', fontWeight: '700' }],
      },
      fontWeight: {
        'regular': '400',
        'semibold': '600',
        'bold': '700',
        'extrabold': '800',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
