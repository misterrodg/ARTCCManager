import React, { useEffect, useRef } from "react";

export default function TextInput({
  type = "text",
  name,
  pattern,
  value,
  className,
  autoComplete,
  required,
  isFocused,
  handleChange,
}) {
  const input = useRef();

  useEffect(() => {
    if (isFocused) {
      input.current.focus();
    }
  }, []);

  return (
    <div className="flex flex-col items-start">
      <input
        type={type}
        name={name}
        pattern={pattern}
        value={value}
        className={
          `border-gray-300 focus:border-amgreen-dark focus:ring focus:ring-amgreen-light focus:ring-opacity-50 rounded-md shadow-sm ` +
          className
        }
        ref={input}
        autoComplete={autoComplete}
        required={required}
        onChange={(e) => handleChange(e)}
      />
    </div>
  );
}
