<template>
	<default-field :field="field" :errors="errors" :show-help-text="showHelpText" :full-width-content="true">
		<template #field>

			<div v-if="loaded">
				<editor
				api-key="no-api-key"
				:init="editorInit"

				class="w-full form-control form-input form-input-bordered"
				:class="errorClasses"

				v-model="value"
				/>
			</div>


		</template>
	</default-field>
</template>

<script>

	import Editor from '@tinymce/tinymce-vue';
	import { FormField, HandlesValidationErrors } from 'laravel-nova';

	export default {
		mixins: [FormField, HandlesValidationErrors],

		props: ['resourceName', 'resourceId', 'field'],

		data() {
			return {
				loaded: false,
				loadingInterval: false,
				editorInit: {
					height: 500,
					width: '100%',
					menubar: false,
					plugins: [
					'advlist autolink lists link image charmap print preview anchor',
					'searchreplace visualblocks code fullscreen',
					'insertdatetime media table paste code help wordcount'
					],
					toolbar: false,

					relative_urls : false,
					remove_script_host : false,
					convert_urls : true,
					// automatic_uploads: true,
				}
			}
		},

		methods: {

			images_upload_handler(blobInfo, success, failure) {
				let formData = new FormData();
				formData.append('attachment', blobInfo.blob(), blobInfo.filename());

				let headers = {
					'Content-Type': 'multipart/form-data'
				};

				Nova.request().post(this.field.upload_url, formData, { headers })
				.then(response => {
					console.log(response.data);

					success(response.data.url);
				})
				.catch(err => {
					console.log(err);
					failure(err.responseText);
				});
			},

			setInitialValue() {
				this.value = this.field.value || ''
			},

			fill(formData) {
				formData.append(this.field.attribute, this.value || '')
			},

		},

		mounted() {
			this.editorInit.toolbar = this.field.toolbar;

			// if upload url is defined, prepare for uploading
			if (this.field.upload_url) {
				this.editorInit.images_upload_handler = this.images_upload_handler.bind(this);
			}

			// now do ridiculous stuff because it's hard to load external resources in nova
			this.loadingInterval = window.setInterval(() => {
				if (window.tinyMCE && window.tinyMCE.majorVersion == 5) {
					this.loaded = true;
					window.clearInterval(this.loadingInterval);

					return;
				}
			}, 1000);

			if (window.injectedTinyMCEScript) {
				return;
			}

			window.injectedTinyMCEScript = true;

			(function(d, that) {
				var script = d.createElement('script');
				script.type = 'text/javascript';
				script.src = that.field.script_url;
				d.getElementsByTagName('head')[0].appendChild(script);
			}(document, this));
		},

		components: {
			'editor': Editor
		}
	}
</script>