import React, { useState, useEffect } from "react";
import { updateBook, fetchBooks } from "../../api/api";
import { useNavigate, useParams } from "react-router-dom";

const EditBook = () => {
    const { id } = useParams();
    const [title, setTitle] = useState("");
    const [authorId, setAuthorId] = useState("");
    const [error, setError] = useState("");
    const navigate = useNavigate();

    useEffect(() => {
        const getBook = async () => {
            const response = await fetchBooks();
            const book = response.data.find((book) => book.id === parseInt(id));
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

    return (
        <div>
            <h2>Edit Book</h2>
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    value={title}
                    onChange={(e) => setTitle(e.target.value)}
                    placeholder="Title"
                />
                <input
                    type="number"
                    value={authorId}
                    onChange={(e) => setAuthorId(e.target.value)}
                    placeholder="Author ID"
                />
                <button type="submit">Update</button>
            </form>
            {error && <p>{error}</p>}
        </div>
    );
};

export default EditBook;
