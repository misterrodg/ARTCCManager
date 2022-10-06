import React from "react";

export default function Checkbox({ name, value, handleChange }) {
  return (
    <input
      type="checkbox"
      name={name}
      value={value}
      className="rounded border-gray-300 text-amgreen-accent shadow-sm focus:border-amgreen-dark focus:ring focus:ring-amgreen-light focus:ring-opacity-50"
      onChange={(e) => handleChange(e)}
    />
  );
}
