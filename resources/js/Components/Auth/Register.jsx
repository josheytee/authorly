import React, { useState, useEffect } from "react";
import { register } from "../../api/api"; // Adjust the import path based on your structure

const Register = () => {
    const [formData, setFormData] = useState({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
    });
    const [errors, setErrors] = useState({});

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    useEffect(() => {
        // Fetch CSRF token from the server
        const fetchCsrfToken = async () => {
            try {
                // const response = await fetch("/sanctum/csrf-cookie"); // Adjust the endpoint as needed
                // if (response.ok) {
                const token = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content");
                setCsrfToken(token);
                // } else {
                //     setError("Failed to fetch CSRF token.");
                // }
            } catch (err) {
                setErrors({
                    error: ["An error occurred while fetching CSRF token."],
                });
            }
        };

        fetchCsrfToken();
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await register(formData);
            // Handle successful registration, e.g., redirecting or showing a success message
        } catch (err) {
            // Display the error messages
            setErrors(err.errors || { general: err.message });
        }
    };

    return (
        <div className="">
            <h2>Register</h2>
            <form onSubmit={handleSubmit}>
                <div>
                    <label>Name</label>
                    <input
                        type="text"
                        name="name"
                        value={formData.name}
                        onChange={handleChange}
                    />
                    {errors.name && <p>{errors.name[0]}</p>}
                </div>
                <div>
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        value={formData.email}
                        onChange={handleChange}
                    />
                    {errors.email && <p>{errors.email[0]}</p>}
                </div>
                <div>
                    <label>Password</label>
                    <input
                        type="password"
                        name="password"
                        value={formData.password}
                        onChange={handleChange}
                    />
                    {errors.password && <p>{errors.password[0]}</p>}
                </div>
                <div>
                    <label>Confirm Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        value={formData.password_confirmation}
                        onChange={handleChange}
                    />
                    {errors.password_confirmation && (
                        <p>{errors.password_confirmation[0]}</p>
                    )}
                </div>
                <button type="submit">Register</button>
                {errors.general && <p>{errors.general}</p>}
            </form>
        </div>
    );
};

export default Register;
