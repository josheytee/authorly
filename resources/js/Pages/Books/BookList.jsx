import React, { useState, useEffect } from "react";
import axios from "axios";
import Book from "@/Components/Book";
import { useNavigate } from "react-router-dom";

const BookList = () => {
    const [books, setBooks] = useState([]);
    const [searchTerm, setSearchTerm] = useState(""); // Search input state
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState("");
    const navigate = useNavigate();

    // Fetch books based on searchTerm
    const fetchBooks = async (query) => {
        setLoading(true);
        try {
            const response = await axios.get("/api/books", {
                params: { search: query }, // Send search query as parameter
            });
            setBooks(response.data);
        } catch (error) {
            console.error("Error fetching books:", error);
            setMessage("Failed to load books.");
        } finally {
            setLoading(false);
        }
    };

    // Fetch books when component mounts or searchTerm changes
    useEffect(() => {
        fetchBooks(searchTerm);
    }, [searchTerm]);

    // Handle search input change
    const handleSearch = (e) => {
        setSearchTerm(e.target.value);
    };

    return (
        <div>
            <h1>Book List</h1>

            <button onClick={() => navigate("/books/create")}>
                create Book
            </button>
            {/* Search Input */}
            <div>
                <input
                    type="text"
                    placeholder="Search books..."
                    value={searchTerm}
                    onChange={handleSearch}
                />
            </div>

            {loading && <p>Loading books...</p>}

            {/* Message Display */}
            {message && <p>{message}</p>}

            {/* Book List */}
            {!loading && books.length > 0 ? (
                <ul>
                    {books.map((book) => (
                        <li key={book.id}>
                            <Book book={book} />
                        </li>
                    ))}
                </ul>
            ) : (
                !loading && <p>No books found.</p>
            )}
        </div>
    );
};

export default BookList;
