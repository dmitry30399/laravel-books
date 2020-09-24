<template>
  <div>
    <h3 class="text-center">Add Book</h3>
    <div class="row">
      <div class="col-md-6">
        <BookForm :loading="loading" :onSubmit="addBook" />
      </div>
    </div>
  </div>
</template>

<script>
import BookForm from '../components/BookForm';

export default {
  name: 'AddBook',
  components: {
    BookForm,
  },
  data() {
    return {
      loading: false,
    };
  },
  methods: {
    addBook(book) {
      const formData = new FormData();
      formData.append('title', book.title);
      formData.append('isbn', book.isbn);
      formData.append('description', book.description);
      formData.append('image', book.image);
      this.loading = true;
      this.axios
        .post("/api/books", formData)
        .then((response) =>
          this.$router.push({ name: "home" })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
  },
};
</script>