/* eslint-env node */
require('@rushstack/eslint-patch/modern-module-resolution')

module.exports = {
  extends: [
    'eslint:recommended',
    'plugin:vue/vue3-recommended',
    '@electron-toolkit',
    '@vue/eslint-config-prettier'
  ],
  plugins: ['vue'],
  rules: {
    'vue/require-default-prop': 'off',
    'vue/multi-word-component-names': 'off',
    'no-unused-vars': 1,
    'space-before-function-paren': 0,
    'no-unreachable-loop': 0,
    camelcase: 0,
    'no-empty': 1
  }
}
