import React from "react";
import { Link } from "@inertiajs/inertia-react";

export default function ResponsiveNavLink({
  method = "get",
  as = "a",
  href,
  active = false,
  children,
}) {
  return (
    <Link
      method={method}
      as={as}
      href={href}
      className={`w-full flex items-start pl-3 pr-4 py-2 border-l-4 ${
        active
          ? "border-amgreen-accent text-amgreen-dark  bg-amgreen-white focus:outline-none focus:text-white focus:bg-amgreen-accent focus:border-amgreen"
          : "border-amgreen text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-amgreen-white"
      } text-base font-medium focus:outline-none transition duration-150 ease-in-out`}
    >
      {children}
    </Link>
  );
}
