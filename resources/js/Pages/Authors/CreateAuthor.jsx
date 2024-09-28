import React, { useState } from "react";
import { createAuthor } from "../../api/api";
import { useNavigate } from "react-router-dom";

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

    return (
        <div>
            <h2>Create Author</h2>
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    placeholder="Name"
                />
                <button type="submit">Create</button>
            </form>
            {error && <p>{error}</p>}
        </div>
    );
};

export default CreateAuthor;
