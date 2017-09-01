// we don't want to run copy for the demos, so just override the config for now
var path = require('path');

module.exports = {
  copyAssets: {
    src: [path.join(path.dirname(process.env.IONIC_APP_ENTRY_POINT), '..', 'assets', '**', '*')],
    dest: '{{WWW}}/assets'
  },
  copyIndexContent: {
    src: [path.join(process.cwd(), 'scripts', 'e2e', 'index.html')],
    dest: '{{WWW}}'
  },
  copyFonts: {
    src: [`${process.cwd()}/node_modules/ionicons/dist/fonts/**/*`, `${process.cwd()}/src/fonts/**/*`],
    dest: '{{WWW}}/assets/fonts'
  },
  copyPolyfills: {
    src: [path.join(process.cwd(), 'dist', 'e2e', 'polyfills', 'polyfills.ng.js')],
    dest: '{{BUILD}}'
  },
  sharedCss: {
    src: [path.join(process.cwd(), 'scripts', 'e2e', 'e2e.shared.css')],
    dest: `{{BUILD}}`
  }
}
