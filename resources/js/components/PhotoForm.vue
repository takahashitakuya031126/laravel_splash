<template>
  <div v-show="value" class="photo-form">
    <h2 class="title">Submit a photo</h2>
    <form class="form" @submit.prevent="submit">
      <input class="form__item" type="file" @change="onFileChange">
      <output class="form__output" v-if="preview">
        <img :src="preview" alt="">
      </output>
      <div class="form__button">
        <button type="submit" class="button button--inverse">submit</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: Boolean,
      required: true
    }
  },
  data () {
    return {
      preview: null,
      photo: null
    }
  },
  methods: {
    onFileChange (event) {
      if (event.target.files.length === 0) {
        this.reset()
        return false
      }

      if (! event.target.files[0].type.match('image.*')) {
        this.reset()
        return false
      }

      const reader = new FileReader()

      reader.onload = e => {
        this.preview = e.target.result
      }

      reader.readAsDataURL(event.target.files[0])

      this.photo = event.target.files[0]
    },
    reset () {
      this.preview = ''
      this.photo = null
      this.$el.querySelector('input[type="file"]').value = null
    },
    async submit () {
      const formData = new FormData()
      formData.append('photo', this.photo)
      const response = await axios.post('/api/photos', formData)

      this.reset()
      this.$emit('input', false)

      this.$router.push(`/photos/${response.data.id}`)
    }
  }
}
</script>