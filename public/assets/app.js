import { jsxs, jsx } from "react/jsx-runtime";
import React, { useState, useEffect } from "react";
import ReactDOM from "react-dom/client";
import { useNavigate, useParams, BrowserRouter, Routes, Route } from "react-router-dom";
import axios from "axios";
const Login = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();
  const handleLogin = async (e) => {
    e.preventDefault();
    setError("");
    try {
      const response = await axios.post(
        "/api/login",
        {
          email,
          password
        },
        {
          withCredentials: true
          // This ensures the HTTPOnly cookie is sent
        }
      );
      if (response.status === 200) {
        navigate("/dashboard");
      }
    } catch (err) {
      setError("Invalid credentials. Please try again.");
    }
  };
  return /* @__PURE__ */ jsxs("div", { children: [
    /* @__PURE__ */ jsx("h1", { children: "Login" }),
    error && /* @__PURE__ */ jsx("p", { style: { color: "red" }, children: error }),
    /* @__PURE__ */ jsxs("form", { onSubmit: handleLogin, children: [
      /* @__PURE__ */ jsx(
        "input",
        {
          type: "email",
          value: email,
          onChange: (e) => setEmail(e.target.value),
          placeholder: "Email"
        }
      ),
      /* @__PURE__ */ jsx(
        "input",
        {
          type: "password",
          value: password,
          onChange: (e) => setPassword(e.target.value),
          placeholder: "Password"
        }
      ),
      /* @__PURE__ */ jsx("button", { type: "submit", children: "Login" })
    ] })
  ] });
};
const api = axios.create({
  baseURL: "http://localhost:8000",
  // Update with your API URL
  withCredentials: true,
  // Needed for sending HTTPOnly cookies
  headers: {
    "X-Requested-With": "XMLHttpRequest"
  }
});
const register = (data) => api.post("/api/register", data);
const fetchBooks = () => api.get("/api/books");
const updateBook = (id, data) => api.put(`/api/books/${id}`, data);
const fetchAuthors = () => api.get("/api/authors");
const createAuthor = (data) => api.post("/api/authors", data);
const updateAuthor = (id, data) => api.put(`/api/authors/${id}`, data);
const deleteAuthor = (id) => api.delete(`/api/authors/${id}`);
const Register = () => {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: ""
  });
  const [errors, setErrors] = useState({});
  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };
  useEffect(() => {
    const fetchCsrfToken = async () => {
      try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
        setCsrfToken(token);
      } catch (err) {
        setErrors({
          error: ["An error occurred while fetching CSRF token."]
        });
      }
    };
    fetchCsrfToken();
  }, []);
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await register(formData);
    } catch (err) {
      setErrors(err.errors || { general: err.message });
    }
  };
  return /* @__PURE__ */ jsxs("div", { className: "", children: [
    /* @__PURE__ */ jsx("h2", { children: "Register" }),
    /* @__PURE__ */ jsxs("form", { onSubmit: handleSubmit, children: [
      /* @__PURE__ */ jsxs("div", { children: [
        /* @__PURE__ */ jsx("label", { children: "Name" }),
        /* @__PURE__ */ jsx(
          "input",
          {
            type: "text",
            name: "name",
            value: formData.name,
            onChange: handleChange
          }
        ),
        errors.name && /* @__PURE__ */ jsx("p", { children: errors.name[0] })
      ] }),
      /* @__PURE__ */ jsxs("div", { children: [
        /* @__PURE__ */ jsx("label", { children: "Email" }),
        /* @__PURE__ */ jsx(
          "input",
          {
            type: "email",
            name: "email",
            value: formData.email,
            onChange: handleChange
          }
        ),
        errors.email && /* @__PURE__ */ jsx("p", { children: errors.email[0] })
      ] }),
      /* @__PURE__ */ jsxs("div", { children: [
        /* @__PURE__ */ jsx("label", { children: "Password" }),
        /* @__PURE__ */ jsx(
          "input",
          {
            type: "password",
            name: "password",
            value: formData.password,
            onChange: handleChange
          }
        ),
        errors.password && /* @__PURE__ */ jsx("p", { children: errors.password[0] })
      ] }),
      /* @__PURE__ */ jsxs("div", { children: [
        /* @__PURE__ */ jsx("label", { children: "Confirm Password" }),
        /* @__PURE__ */ jsx(
          "input",
          {
            type: "password",
            name: "password_confirmation",
            value: formData.password_confirmation,
            onChange: handleChange
          }
        ),
        errors.password_confirmation && /* @__PURE__ */ jsx("p", { children: errors.password_confirmation[0] })
      ] }),
      /* @__PURE__ */ jsx("button", { type: "submit", children: "Register" }),
      errors.general && /* @__PURE__ */ jsx("p", { children: errors.general })
    ] })
  ] });
};
const Dashboard = () => {
  const [user, setUser] = useState(null);
  const navigate = useNavigate();
  useEffect(() => {
    const fetchUser = async () => {
      try {
        const response = await axios.get("/api/user", {
          withCredentials: true
          // Send the HTTPOnly cookie with the request
        });
        setUser(response.data);
      } catch (error) {
        console.log("Not authenticated", error);
        navigate("/login");
      }
    };
    fetchUser();
  }, []);
  if (!user) {
    return /* @__PURE__ */ jsx("p", { children: "Loading..." });
  }
  return /* @__PURE__ */ jsxs("div", { children: [
    /* @__PURE__ */ jsxs("h1", { children: [
      "Welcome, ",
      user.name
    ] }),
    /* @__PURE__ */ jsxs("div", { className: "", children: [
      /* @__PURE__ */ jsx("button", { onClick: () => navigate("/books"), children: "Book List" }),
      /* @__PURE__ */ jsx("button", { onClick: () => navigate("/authors"), children: "Author List" })
    ] })
  ] });
};
const Book = ({ book }) => {
  return /* @__PURE__ */ jsxs("div", { children: [
    /* @__PURE__ */ jsx("strong", { children: book.title }),
    " by ",
    book.author.name,
    " ",
    /* @__PURE__ */ jsx("br", {}),
    "Published at: ",
    book.published_at
  ] });
};
const BookList = () => {
  const [books, setBooks] = useState([]);
  const [searchTerm, setSearchTerm] = useState("");
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");
  const navigate = useNavigate();
  const fetchBooks2 = async (query) => {
    setLoading(true);
    try {
      const response = await axios.get("/api/books", {
        params: { search: query }
        // Send search query as parameter
      });
      setBooks(response.data);
    } catch (error) {
      console.error("Error fetching books:", error);
      setMessage("Failed to load books.");
    } finally {
      setLoading(false);
    }
  };
  useEffect(() => {
    fetchBooks2(searchTerm);
  }, [searchTerm]);
  const handleSearch = (e) => {
    setSearchTerm(e.target.value);
  };
  return /* @__PURE__ */ jsxs("div", { children: [
    /* @__PURE__ */ jsx("h1", { children: "Book List" }),
    /* @__PURE__ */ jsx("button", { onClick: () => navigate("/books/create"), children: "create Book" }),
    /* @__PURE__ */ jsx("div", { children: /* @__PURE__ */ jsx(
      "input",
      {
        type: "text",
        placeholder: "Search books...",
        value: searchTerm,
        onChange: handleSearch
      }
    ) }),
    loading && /* @__PURE__ */ jsx("p", { children: "Loading books..." }),
    message && /* @__PURE__ */ jsx("p", { children: message }),
    !loading && books.length > 0 ? /* @__PURE__ */ jsx("ul", { children: books.map((book) => /* @__PURE__ */ jsx("li", { children: /* @__PURE__ */ jsx(Book, { book }) }, book.id)) }) : !loading && /* @__PURE__ */ jsx("p", { children: "No books found." })
  ] });
};
const CreateBook = () => {
  const [bookData, setBookData] = useState({
    title: "",
    description: "",
    author_id: "",
    // Updated to snake_case
    published_at: ""
    // Added and using snake_case
  });
  const [authors, setAuthors] = useState([]);
  const [message, setMessage] = useState("");
  const [loading, setLoading] = useState(false);
  useEffect(() => {
    const fetchAuthors2 = async () => {
      try {
        const response = await axios.get("/api/authors");
        setAuthors(response.data);
      } catch (error) {
        console.error("Error fetching authors:", error);
      }
    };
    fetchAuthors2();
  }, []);
  const handleChange = (e) => {
    const { name, value } = e.target;
    setBookData({
      ...bookData,
      [name]: value
    });
  };
  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const response = await axios.post("/api/books", bookData);
      setMessage(
        `Book "${response.data.book.title}" created successfully!`
      );
      setBookData({
        title: "",
        description: "",
        author_id: "",
        published_at: ""
      });
    } catch (error) {
      console.error("Error creating book:", error);
      setMessage("Failed to create the book. Please try again.");
    } finally {
      setLoading(false);
    }
  };
  return /* @__PURE__ */ jsxs("div", { children: [
    /* @__PURE__ */ jsx("h1", { children: "Create a New Book" }),
    /* @__PURE__ */ jsx("div", { className: "", children: "Book list" }),
    message && /* @__PURE__ */ jsx("p", { children: message }),
    /* @__PURE__ */ jsxs("form", { onSubmit: handleSubmit, children: [
      /* @__PURE__ */ jsxs("div", { children: [
        /* @__PURE__ */ jsx("label", { htmlFor: "title", children: "Title:" }),
        /* @__PURE__ */ jsx(
          "input",
          {
            type: "text",
            id: "title",
            name: "title",
            value: bookData.title,
            onChange: handleChange,
            required: true
          }
        )
      ] }),
      /* @__PURE__ */ jsxs("div", { children: [
        /* @__PURE__ */ jsx("label", { htmlFor: "description", children: "Description:" }),
        /* @__PURE__ */ jsx(
          "textarea",
          {
            id: "description",
            name: "description",
            value: bookData.description,
            onChange: handleChange,
            required: true
          }
        )
      ] }),
      /* @__PURE__ */ jsxs("div", { children: [
        /* @__PURE__ */ jsx("label", { htmlFor: "author_id", children: "Author:" }),
        /* @__PURE__ */ jsxs(
          "select",
          {
            id: "author_id",
            name: "author_id",
            value: bookData.author_id,
            onChange: handleChange,
            required: true,
            children: [
              /* @__PURE__ */ jsx("option", { value: "", children: "Select an author" }),
              authors.map((author) => /* @__PURE__ */ jsx("option", { value: author.id, children: author.name }, author.id))
            ]
          }
        )
      ] }),
      /* @__PURE__ */ jsxs("div", { children: [
        /* @__PURE__ */ jsx("label", { htmlFor: "published_at", children: "Published Date:" }),
        /* @__PURE__ */ jsx(
          "input",
          {
            type: "date",
            id: "published_at",
            name: "published_at",
            value: bookData.published_at,
            onChange: handleChange
          }
        )
      ] }),
      /* @__PURE__ */ jsx("button", { type: "submit", disabled: loading, children: loading ? "Creating..." : "Create Book" })
    ] })
  ] });
};
const EditBook = () => {
  const { id } = useParams();
  const [title, setTitle] = useState("");
  const [authorId, setAuthorId] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();
  useEffect(() => {
    const getBook = async () => {
      const response = await fetchBooks();
      const book = response.data.find((book2) => book2.id === parseInt(id));
      if (book) {
        setTitle(book.title);
        setAuthorId(book.author_id);
      }
    };
    getBook();
  }, [id]);
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await updateBook(id, { title, author_id: authorId });
      navigate("/books");
    } catch (err) {
      setError("Failed to update book.");
    }
  };
  return /* @__PURE__ */ jsxs("div", { children: [
    /* @__PURE__ */ jsx("h2", { children: "Edit Book" }),
    /* @__PURE__ */ jsxs("form", { onSubmit: handleSubmit, children: [
      /* @__PURE__ */ jsx(
        "input",
        {
          type: "text",
          value: title,
          onChange: (e) => setTitle(e.target.value),
          placeholder: "Title"
        }
      ),
      /* @__PURE__ */ jsx(
        "input",
        {
          type: "number",
          value: authorId,
          onChange: (e) => setAuthorId(e.target.value),
          placeholder: "Author ID"
        }
      ),
      /* @__PURE__ */ jsx("button", { type: "submit", children: "Update" })
    ] }),
    error && /* @__PURE__ */ jsx("p", { children: error })
  ] });
};
const EditAuthor = () => {
  const { id } = useParams();
  const [name, setName] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();
  useEffect(() => {
    const getAuthor = async () => {
      const response = await fetchAuthors();
      const author = response.data.find(
        (author2) => author2.id === parseInt(id)
      );
      if (author) {
        setName(author.name);
      }
    };
    getAuthor();
  }, [id]);
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await updateAuthor(id, { name });
      navigate("/authors");
    } catch (err) {
      setError("Failed to update author.");
    }
  };
  return /* @__PURE__ */ jsxs("div", { children: [
    /* @__PURE__ */ jsx("h2", { children: "Edit Author" }),
    /* @__PURE__ */ jsxs("form", { onSubmit: handleSubmit, children: [
      /* @__PURE__ */ jsx(
        "input",
        {
          type: "text",
          value: name,
          onChange: (e) => setName(e.target.value),
          placeholder: "Name"
        }
      ),
      /* @__PURE__ */ jsx("button", { type: "submit", children: "Update" })
    ] }),
    error && /* @__PURE__ */ jsx("p", { children: error })
  ] });
};
const AuthorList = () => {
  const [authors, setAuthors] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const navigate = useNavigate();
  useEffect(() => {
    const getAuthors = async () => {
      try {
        const response = await fetchAuthors();
        setAuthors(response.data);
        setLoading(false);
      } catch (err) {
        setError("Failed to fetch authors.");
        setLoading(false);
      }
    };
    getAuthors();
  }, []);
  const handleDelete = async (id) => {
    try {
      await deleteAuthor(id);
      setAuthors(authors.filter((author) => author.id !== id));
    } catch (err) {
      setError("Failed to delete author.");
    }
  };
  const handleEdit = (id) => {
    navigate(`/authors/edit/${id}`);
  };
  if (loading) {
    return /* @__PURE__ */ jsx("p", { children: "Loading authors..." });
  }
  if (error) {
    return /* @__PURE__ */ jsx("p", { children: error });
  }
  return /* @__PURE__ */ jsxs("div", { children: [
    /* @__PURE__ */ jsx("h2", { children: "Authors" }),
    /* @__PURE__ */ jsx("button", { onClick: () => navigate("/authors/create"), children: "Create Author" }),
    /* @__PURE__ */ jsxs("table", { children: [
      /* @__PURE__ */ jsx("thead", { children: /* @__PURE__ */ jsxs("tr", { children: [
        /* @__PURE__ */ jsx("th", { children: "ID" }),
        /* @__PURE__ */ jsx("th", { children: "Name" }),
        /* @__PURE__ */ jsx("th", { children: "Actions" })
      ] }) }),
      /* @__PURE__ */ jsx("tbody", { children: authors.map((author) => /* @__PURE__ */ jsxs("tr", { children: [
        /* @__PURE__ */ jsx("td", { children: author.id }),
        /* @__PURE__ */ jsx("td", { children: author.name }),
        /* @__PURE__ */ jsxs("td", { children: [
          /* @__PURE__ */ jsx("button", { onClick: () => handleEdit(author.id), children: "Edit" }),
          /* @__PURE__ */ jsx("button", { onClick: () => handleDelete(author.id), children: "Delete" })
        ] })
      ] }, author.id)) })
    ] })
  ] });
};
const CreateAuthor = () => {
  const [name, setName] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await createAuthor({ name });
      navigate("/authors");
    } catch (err) {
      setError("Failed to create author.");
    }
  };
  return /* @__PURE__ */ jsxs("div", { children: [
    /* @__PURE__ */ jsx("h2", { children: "Create Author" }),
    /* @__PURE__ */ jsxs("form", { onSubmit: handleSubmit, children: [
      /* @__PURE__ */ jsx(
        "input",
        {
          type: "text",
          value: name,
          onChange: (e) => setName(e.target.value),
          placeholder: "Name"
        }
      ),
      /* @__PURE__ */ jsx("button", { type: "submit", children: "Create" })
    ] }),
    error && /* @__PURE__ */ jsx("p", { children: error })
  ] });
};
const Home = () => {
  return /* @__PURE__ */ jsx("div", { children: /* @__PURE__ */ jsx("h1", { children: "Welcome to the Home Page" }) });
};
class ErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false };
  }
  static getDerivedStateFromError(error) {
    return { hasError: true };
  }
  render() {
    if (this.state.hasError) {
      return /* @__PURE__ */ jsx("h1", { children: "Something went wrong." });
    }
    return this.props.children;
  }
}
const App = () => {
  return /* @__PURE__ */ jsx(BrowserRouter, { children: /* @__PURE__ */ jsxs(Routes, { children: [
    /* @__PURE__ */ jsx(Route, { path: "/", element: /* @__PURE__ */ jsx(Home, {}) }),
    /* @__PURE__ */ jsx(Route, { path: "/login", element: /* @__PURE__ */ jsx(Login, {}) }),
    /* @__PURE__ */ jsx(Route, { path: "/register", element: /* @__PURE__ */ jsx(Register, {}) }),
    /* @__PURE__ */ jsx(Route, { path: "/dashboard", element: /* @__PURE__ */ jsx(Dashboard, {}) }),
    /* @__PURE__ */ jsx(Route, { path: "/books", element: /* @__PURE__ */ jsx(BookList, {}) }),
    /* @__PURE__ */ jsx(Route, { path: "/books/create", element: /* @__PURE__ */ jsx(CreateBook, {}) }),
    /* @__PURE__ */ jsx(Route, { path: "/books/edit/:id", element: /* @__PURE__ */ jsx(EditBook, {}) }),
    /* @__PURE__ */ jsx(Route, { path: "/authors", element: /* @__PURE__ */ jsx(AuthorList, {}) }),
    /* @__PURE__ */ jsx(Route, { path: "/authors/create", element: /* @__PURE__ */ jsx(CreateAuthor, {}) }),
    /* @__PURE__ */ jsx(Route, { path: "/authors/edit/:id", element: /* @__PURE__ */ jsx(EditAuthor, {}) })
  ] }) });
};
const root = ReactDOM.createRoot(document.getElementById("app"));
root.render(
  /* @__PURE__ */ jsx(ErrorBoundary, { children: /* @__PURE__ */ jsx(App, {}) })
);
