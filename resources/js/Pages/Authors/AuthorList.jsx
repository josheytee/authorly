import React, { useState, useEffect } from "react";
import { fetchAuthors, deleteAuthor } from "../../api/api";
import { useNavigate } from "react-router-dom";

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
        return <p>Loading authors...</p>;
    }

    if (error) {
        return <p>{error}</p>;
    }

    return (
        <div>
            <h2>Authors</h2>
            <button onClick={() => navigate("/authors/create")}>
                Create Author
            </button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {authors.map((author) => (
                        <tr key={author.id}>
                            <td>{author.id}</td>
                            <td>{author.name}</td>
                            <td>
                                <button onClick={() => handleEdit(author.id)}>
                                    Edit
                                </button>
                                <button onClick={() => handleDelete(author.id)}>
                                    Delete
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default AuthorList;
