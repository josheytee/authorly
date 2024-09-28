import React, { useEffect, useState } from "react";
import { fetchAuthors, fetchBooks } from "../../api/api";

const BookPage = () => {
    const [book, setBook] = useState({
        title: "Book Title",
        description: "This is a description of the book.",
        authorIds: [1, 2], // Example author IDs associated with this book
    });
    const [authors, setAuthors] = useState([]);
    const [loading, setLoading] = useState(true);

    // Fetch authors from the API based on authorIds in the book object
    useEffect(() => {
        const fetchAuthors = async () => {
            try {
                const authorPromises = book.authorIds.map(async (id) => {
                    const response = await axios.get(`/api/authors/${id}`);
                    return response.data;
                });
                const authorsData = await Promise.all(authorPromises);
                setAuthors(authorsData);
            } catch (error) {
                console.error("Error fetching authors:", error);
            } finally {
                setLoading(false);
            }
        };

        fetchAuthors();
    }, [book.authorIds]);

    if (loading) return <p>Loading...</p>;

    return (
        <div>
            <h1>{book.title}</h1>
            <p>{book.description}</p>

            <h2>Authors</h2>
            {authors.length > 0 ? (
                <ul>
                    {authors.map((author) => (
                        <li key={author.id}>{author.name}</li>
                    ))}
                </ul>
            ) : (
                <p>No authors available for this book.</p>
            )}
        </div>
    );
};

export default BookPage;
