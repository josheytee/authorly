import React, { useState, useEffect } from "react";
import axios from "axios";

const CreateBook = () => {
    const [bookData, setBookData] = useState({
        title: "",
        description: "",
        author_id: "", // Updated to snake_case
        published_at: "", // Added and using snake_case
    });
    const [authors, setAuthors] = useState([]);
    const [message, setMessage] = useState("");
    const [loading, setLoading] = useState(false);

    // Fetch authors on component mount
    useEffect(() => {
        const fetchAuthors = async () => {
            try {
                const response = await axios.get("/api/authors"); // Replace with your API endpoint for authors
                setAuthors(response.data);
            } catch (error) {
                console.error("Error fetching authors:", error);
            }
        };

        fetchAuthors();
    }, []);

    // Handle input change
    const handleChange = (e) => {
        const { name, value } = e.target;
        setBookData({
            ...bookData,
            [name]: value,
        });
    };

    // Handle form submission
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
                published_at: "",
            });
        } catch (error) {
            console.error("Error creating book:", error);
            setMessage("Failed to create the book. Please try again.");
        } finally {
            setLoading(false);
        }
    };

    return (
        <div>
            <h1>Create a New Book</h1>
            <div className="">Book list</div>

            {message && <p>{message}</p>}

            <form onSubmit={handleSubmit}>
                <div>
                    <label htmlFor="title">Title:</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value={bookData.title}
                        onChange={handleChange}
                        required
                    />
                </div>

                <div>
                    <label htmlFor="description">Description:</label>
                    <textarea
                        id="description"
                        name="description"
                        value={bookData.description}
                        onChange={handleChange}
                        required
                    />
                </div>

                <div>
                    <label htmlFor="author_id">Author:</label>
                    <select
                        id="author_id"
                        name="author_id"
                        value={bookData.author_id}
                        onChange={handleChange}
                        required
                    >
                        <option value="">Select an author</option>
                        {authors.map((author) => (
                            <option key={author.id} value={author.id}>
                                {author.name}
                            </option>
                        ))}
                    </select>
                </div>

                <div>
                    <label htmlFor="published_at">Published Date:</label>
                    <input
                        type="date"
                        id="published_at"
                        name="published_at"
                        value={bookData.published_at}
                        onChange={handleChange}
                    />
                </div>

                <button type="submit" disabled={loading}>
                    {loading ? "Creating..." : "Create Book"}
                </button>
            </form>
        </div>
    );
};

export default CreateBook;
