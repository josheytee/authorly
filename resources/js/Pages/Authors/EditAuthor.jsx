import React, { useState, useEffect } from "react";
import { updateAuthor, fetchAuthors } from "../../api/api";
import { useNavigate, useParams } from "react-router-dom";

const EditAuthor = () => {
    const { id } = useParams();
    const [name, setName] = useState("");
    const [error, setError] = useState("");
    const navigate = useNavigate();

    useEffect(() => {
        const getAuthor = async () => {
            const response = await fetchAuthors();
            const author = response.data.find(
                (author) => author.id === parseInt(id)
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

    return (
        <div>
            <h2>Edit Author</h2>
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    placeholder="Name"
                />
                <button type="submit">Update</button>
            </form>
            {error && <p>{error}</p>}
        </div>
    );
};

export default EditAuthor;
