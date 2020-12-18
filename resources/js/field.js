Nova.booting((Vue, router, store) => {
  Vue.component('detail-localtinymce', require('./components/DetailField'))
  Vue.component('form-localtinymce', require('./components/FormField'))
})
