<template>
  <div>
    <h3 class="text-center">All Books</h3>
    <br />

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>ISBN</th>
          <th>Title</th>
          <th>Description</th>
          <th>Cover Image</th>
          <th>Created At</th>
          <th>Updated At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody v-if="books.data">
        <tr v-for="book in books.data" :key="book.id">
          <td>{{ book.id }}</td>
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
    <div class="float-right">
      <Pagination :pagination="books" :onChangePage="onChangePage" :offset="books.offset" />
    </div>
  </div>
</template>

<script>
import Pagination from '../components/Pagination';

export default {
  name: 'AllBooks',
  components: {
    Pagination,
  },
  data() {
    return {
      books: {
        total: 0,
        per_page: 5,
        from: 1,
        to: 0,
        current_page: 1
      },
      offset: 4,
    };
  },
  methods: {
    onChangePage(value) {
      this.books.current_page = value;
      this.getBooks();
    },
    getBooks() {
      this.axios.get('/api/books', {
        params: {
          current_page: this.books.current_page,
          per_page: this.books.per_page,
        },
      }).then((response) => {
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
  created() {
    this.getBooks();
  },
};
</script>