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
          collapsed: false,
          items: [
              { text: '1. Introducció', link: '/contenidos/1-introduccion' },
              { text: '2. PHP Bàsic', link: '/contenidos/2-php-basic' },

            ]
        },
        /* {
               text: '🗂️ Ejercicios',
               collapsed: true,
               items: [
                 { text: 'Inicio', link: '/ejercicios/' },
                 { text: 'Ejercicio', link: '/ejercicios/ejercicio' },
                 { text: 'Final', link: '/ejercicios/final' }
               ]
             },*/
        {
          items: [
            //{ text: '<img src="/LMSGI/img/logo-autor.png" class="logo-anim" style="vertical-align:middle; height:165px; margin:0 auto;">', link: '' },
            { text: '<img src="/DWES/img/logo-gva.png" class="logo" style="vertical-align:middle; height:60px; margin:0 auto;">', link: '' },
            { text: '<img src="/DWES/img/logo-centro.png" class="logo" style="vertical-align:middle; height:90px; margin:0 auto;">', link: '' }
          ]
        }
      ],
    },
    footer: {
      message: '<img src="/DWES/img/logo-autor.png" alt="Autor" style="height:75px; margin: 0 auto; display:block;" />',
      copyright: 'CC BY-NC-SA - 2026'
    }
  }
})
