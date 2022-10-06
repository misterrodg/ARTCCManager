import React from "react";
import { Link } from "@inertiajs/inertia-react";

export default function NavLink({ href, active, children }) {
  return (
    <Link
      href={href}
      className={
        active
          ? "inline-flex items-center px-1 pt-1 border-b-4 border-amgreen-accent text-sm font-medium leading-5 text-white focus:outline-none focus:border-amgreen-white transition duration-150 ease-in-out"
          : "inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-sm font-medium leading-5 text-amgreen-white hover:text-white hover:border-amgreen-white focus:outline-none focus:text-white focus:border-amgreen-white transition duration-150 ease-in-out"
      }
    >
      {children}
    </Link>
  );
}
