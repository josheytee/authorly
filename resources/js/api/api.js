import axios from 'axios';
// process.env.REACT_APP_API_URL ||
// Create an Axios instance
const api = axios.create({
    baseURL:  'http://localhost:8000', // Update with your API URL
    withCredentials: true, // Needed for sending HTTPOnly cookies
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
});

// Authentication endpoints
export const login = (data) => api.post('/api/login', data);
export const register = (data) => api.post('/api/register', data);
export const fetchUser = () => api.get('/api/user');

// Book CRUD operations
export const fetchBooks = () => api.get('/api/books');
export const createBook = (data) => api.post('/api/books', data);
export const updateBook = (id, data) => api.put(`/api/books/${id}`, data);
export const deleteBook = (id) => api.delete(`/api/books/${id}`);

// Author CRUD operations
export const fetchAuthors = () => api.get('/api/authors');
export const createAuthor = (data) => api.post('/api/authors', data);
export const updateAuthor = (id, data) => api.put(`/api/authors/${id}`, data);
export const deleteAuthor = (id) => api.delete(`/api/authors/${id}`);

export default api;
