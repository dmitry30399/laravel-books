import AllBooks from './views/AllBooks.vue';
import AddBook from './views/AddBook.vue';
import EditBook from './views/EditBook.vue';

export const routes = [
  {
    name: 'home',
    path: '/',
    component: AllBooks
  },
  {
    name: 'add',
    path: '/add',
    component: AddBook
  },
  {
    name: 'edit',
    path: '/edit/:id',
    component: EditBook
  }
];