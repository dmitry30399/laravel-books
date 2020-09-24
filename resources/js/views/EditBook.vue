<template>
  <div>
    <h3 class="text-center">Edit Book</h3>
    <div class="row">
      <div class="col-md-6">
        <BookForm :defaultBook="book" :loading="loading" :onSubmit="updateBook" />
      </div>
    </div>
  </div>
</template>

<script>
import BookForm from '../components/BookForm';

export default {
  name: 'EditBook',
  components: {
    BookForm,
  },
  data() {
    return {
      book: {},
      loading: false,
    };
  },
  created() {
    this.getBook();
  },
  methods: {
    getBook() {
      this.loading = true;
      this.axios
        .get(`http://localhost:8000/api/books/${this.$route.params.id}`)
        .then((response) => {
          this.book = response.data;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    updateBook(book) {
      const formData = new FormData();
      formData.append('title', book.title);
      formData.append('isbn', book.isbn);
      formData.append('description', book.description);
      formData.append('image', book.image);
      this.loading = true;
      this.axios
        .put(`/api/books/${this.$route.params.id}`, formData)
        .then((response) =>
          this.$router.push({ name: "home" })
        )
        .catch((error) => console.log(error))
        .finally(() => (this.loading = false));
    },
  },
};
</script>