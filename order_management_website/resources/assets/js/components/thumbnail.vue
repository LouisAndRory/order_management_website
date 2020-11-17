<template>
  <button
    @click="onClick(deleteImage, imageIndex)"
    type="button"
    class="btn border border-secondary"
    :style="getStyle(url)">
  </button>
</template>
<script>

export default {
  name: 'Thumbnail',
  props: {
    imageIndex: {
      type: Number
    },
    url: {
      type: String
    },
    deleteImage: {
      type: Function
    }
  },
  methods: {
    onClick: (deleteImage, key) => {
      if(!deleteImage) { return; }

      return deleteImage(key);
    },
    getStyle: (url) => {
      return `height: 100px; width: 100px; background-size: contain; background-repeat: no-repeat; background-position: center; background-image: url(${url});`;
    }
  }
}
</script>

