<template>
  <form @submit.prevent="submit">
    <div class="form-group">
      <label>Title*</label>
      <input type="text" class="form-control" name="title" v-model="book.title" required />
    </div>
    <div class="form-group">
      <label>ISBN*</label>
      <input type="text" class="form-control" name="isbn" v-model="book.isbn" required />
    </div>
    <div class="form-group">
      <label>Description*</label>
      <input type="text" class="form-control" name="description" v-model="book.description" required />
    </div>
    <div class="form-group">
      <input type="file" name="image" accept="image/*" @change="onImageChange" required />
    </div>
    <button type="submit" class="btn btn-primary" :disabled="loading">
      Submit
    </button>
  </form>
</template>

<script>
export default {
  name: 'BookForm',
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    defaultBook: {
      type: Object,
      default: null
    },
    onSubmit: {
      type: Function,
      default: () => {},
    },
  },
  data() {
    return {
      book: {},
    };
  },
  watch:{
    defaultBook: function(newVal, oldVal) { // watch it
      if (oldVal !== newVal) {
        this.setBook(newVal);
      }
    }
  },
  methods: {
    setBook(bookData) {
      if (bookData) {
        this.book = bookData;
      }
    },
    onImageChange(e) {
      const files = e.target.files || e.dataTransfer.files;
      if (files.length) {
        this.book.image = files[0];
      }
    },
    submit() {
      this.onSubmit(this.book);
    },
  },
};
</script>