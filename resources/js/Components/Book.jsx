import React, { useEffect, useState } from "react";
import axios from "axios";

const Book = ({ book }) => {
    return (
        <div>
            <strong>{book.title}</strong> by {book.author.name} <br />
            Published at: {book.published_at}
        </div>
    );
};

export default Book;
