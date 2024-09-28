import React from "react";
import ReactDOM from "react-dom/client";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Login from "./components/Auth/Login";
import Register from "./components/Auth/Register";
import Dashboard from "./Pages/Dashboard";
import Books from "./Pages/Books/BookList";
import CreateBook from "./Pages/Books/CreateBook";
import EditBook from "./Pages/Books/EditBook";
import EditAuthor from "./Pages/Authors/EditAuthor";
import AuthorList from "./Pages/Authors/AuthorList";
import CreateAuthor from "./Pages/Authors/CreateAuthor";
import BookList from "./Pages/Books/BookList";
import Home from "./pages/Home";
import NotFound from "./pages/NotFound";
import ErrorBoundary from "./ErrorBoundary";

const App = () => {
    return (
        <Router>
            <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/login" element={<Login />} />
                <Route path="/register" element={<Register />} />
                <Route path="/dashboard" element={<Dashboard />} />
                <Route path="/books" element={<Books />} />
                <Route path="/books/create" element={<CreateBook />} />
                <Route path="/books/edit/:id" element={<EditBook />} />
                <Route path="/authors" element={<AuthorList />} />
                <Route path="/authors/create" element={<CreateAuthor />} />
                <Route path="/authors/edit/:id" element={<EditAuthor />} />
                {/* <Route path="*" element={<NotFound />} /> */}
            </Routes>
        </Router>
    );
};

const root = ReactDOM.createRoot(document.getElementById("app"));
root.render(
    <ErrorBoundary>
        <App />
    </ErrorBoundary>
);
