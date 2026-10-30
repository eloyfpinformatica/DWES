import { defineConfig } from 'vitepress'
import { tabsMarkdownPlugin } from 'vitepress-plugin-tabs'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  ignoreDeadLinks: true,
  base: '/DWES/',
  outDir: '../docs',
  markdown: {
    config(md) {
      md.use(tabsMarkdownPlugin)
    }
  },
  head: [
    ['link', { rel: 'icon', href: '/DWES/img/logo.png' }]
  ],
  // Metadatos por idioma (guía i18n)
  locales: {
    root: {
      label: 'Català',
      lang: 'ca-ES',
      link: '/',
      title: 'Desenvolupament web en entorn servidor',
      description: 'Apunts de Desenvolupament web en entorn servidor (DWES)',
      themeConfig: {
        siteTitle: 'Apunts DWES',
        outline: { label: 'En aquesta pàgina' },
        docFooter: { prev: 'Anterior', next: 'Següent' },
        nav: [
          { text: '🏠 Inici', link: '/' },
          {
            text: '📚 Unitats',
            items: [
              { text: '1. Introducció', link: '/contenidos/1-introduccion' },
              { text: '2. PHP Bàsic', link: '/contenidos/2-php-basic' },
              { text: '3. PHP Avançat', link: '/contenidos/3-php-avancat' },
              { text: '4. Accés a dades', link: '/contenidos/4-acces-a-dades' },
              { text: '5. Frameworks. Laravel', link: '/contenidos/5-frameworks-laravel/' },

            ]
          },
        ],
      }
    },
  },
  // Tema por idioma
  themeConfig: {
    logo: '/img/logo.png',
    socialLinks: [
      { icon: 'github', link: 'https://github.com/eloyfpinformatica/' }
    ],
    sidebar: {
      '/': [
        {
          text: '📚 Unitats',
          collapsed: true,
          items: [
            {
              text: '1. Introducció',
              link: '/contenidos/1-introduccion'
            },
            {
              text: '2. PHP Bàsic',
              link: '/contenidos/2-php-basic'
            },
            {
              text: '3. PHP Avançat',
              link: '/contenidos/3-php-avancat'
            },
            {
              text: '4. Accés a dades',
              link: '/contenidos/4-acces-a-dades'
            },

            // Unitat 5 amb subtemes
            {
              text: '5. Frameworks. Laravel',
              link: '/contenidos/5-frameworks-laravel/',
              collapsed: true,
              items: [
                { text: 'Bloc 1. Introducció i Laravel Sail', link: '/contenidos/5-frameworks-laravel/5-1-introduccio-i-sail' },
                { text: 'Bloc 2. Rutes i vistes', link: '/contenidos/5-frameworks-laravel/5-2-rutes-i-vistes' },
                { text: 'Bloc 3. Controladors', link: '/contenidos/5-frameworks-laravel/5-3-controladors' },
                { text: 'Bloc 4. Models de dades', link: '/contenidos/5-frameworks-laravel/5-4-models-de-dades' },
                { text: 'Bloc 5. Formularis', link: '/contenidos/5-frameworks-laravel/5-5-formularis' },
                { text: 'Bloc 6. Autenticació', link: '/contenidos/5-frameworks-laravel/5-6-autenticacio' },
                { text: 'Bloc 7. Arxius, correus, sessions i cookies', link: '/contenidos/5-frameworks-laravel/5-7-arxius-correus-sessions-i-cookies' },
                { text: 'Bloc 8. IA, agents i projecte final', link: '/contenidos/5-frameworks-laravel/5-8-ia-agents-i-projecte-final' }
              ]
            },
          ]
        },

        // Logos del centre
        {
          items: [
            {
              text: '<img src="/DWES/img/logo-gva.png" class="logo" style="vertical-align:middle; height:60px; margin:0 auto;">',
              link: ''
            },
            {
              text: '<img src="/DWES/img/logo-centro.png" class="logo" style="vertical-align:middle; height:90px; margin:0 auto;">',
              link: ''
            }
          ]
        }
      ]
    },
    footer: {
      message: '<img src="/DWES/img/logo-autor.png" alt="Autor" style="height:75px; margin: 0 auto; display:block;" />',
      copyright: 'CC BY-NC-SA - 2026'
    }
  }
})
