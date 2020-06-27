<template>
  <div v-show="value" class="photo-form">
    <h2 class="title">Submit a photo</h2>
    <form class="form">
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
      preview: null
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
    }
  },
  reset () {
    this.preview = ''
    this.$el.querySelector('input[type="file"]').value = null
  }
}
</script>