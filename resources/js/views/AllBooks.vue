<template>
  <div>
    <h3 class="text-center">All Books</h3>
    <br />

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ISBN</th>
          <th>Title</th>
          <th>Description</th>
          <th>Cover Image</th>
          <th>Created At</th>
          <th>Updated At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="book in books" :key="book.id">
          <td>{{ book.isbn }}</td>
          <td>{{ book.title }}</td>
          <td>{{ book.description }}</td>
          <td>
            <img :src="book.image" :alt="book.title +  ' cover'" width="100" height="200" />
          </td>
          <td>{{ book.created_at }}</td>
          <td>{{ book.updated_at }}</td>
          <td>
            <div class="btn-group" role="group">
              <router-link
                :to="{ name: 'edit', params: { id: book.id } }"
                class="btn btn-primary"
                >Edit
              </router-link>
              <button class="btn btn-danger" @click="deleteBook(book.id)">
                Delete
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      books: [],
    };
  },
  created() {
    this.getBooks();
  },
  methods: {
    getBooks() {
      this.axios.get("/api/books").then((response) => {
        this.books = response.data;
      });
    },
    deleteBook(id) {
      this.axios
        .delete(`/api/books/${id}`)
        .then((response) => {
          let i = this.books.map((item) => item.id).indexOf(id); // find index of your object
          this.books.splice(i, 1);
        });
    },
  },
};
</script>