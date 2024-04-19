import DetailField from './components/DetailField.vue'
import FormField from './components/FormField.vue'

Nova.booting((app, store) => {
  app.component('detail-localtinymce', DetailField)
  app.component('form-localtinymce', FormField)
})
