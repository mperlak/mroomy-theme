import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import { resolve } from 'path';

export default defineConfig({
  plugins: [
    react({
      jsxRuntime: 'classic',
      babel: {
        plugins: [
          ['@babel/plugin-transform-react-jsx', {
            pragma: 'wp.element.createElement',
            pragmaFrag: 'wp.element.Fragment'
          }]
        ]
      }
    })
  ],
  build: {
    outDir: 'dist',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        main: 'assets/css/main.css',
        app: 'assets/js/app.js',
        'top-stats-editor': resolve(__dirname, 'blocks/top-stats/editor.js'),
        'categories-showcase-editor': resolve(__dirname, 'blocks/categories-showcase/editor.js')
      },
      output: {
        entryFileNames: (chunkInfo) => {
          if (chunkInfo.name.includes('editor')) {
            return 'blocks/[name].js';
          }
          return '[name].js';
        },
        chunkFileNames: '[name].js',
        assetFileNames: '[name].[ext]'
      },
      external: ['wp', 'lodash', 'jquery']
    },
    assetsDir: '',
  },
  server: {
    port: 3000,
    host: true
  },
  define: {
    'process.env': {}
  }
});
