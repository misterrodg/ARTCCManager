import { useState } from "react";
import { Head, Link } from "@inertiajs/inertia-react";

import ApplicationLogo from "@/Components/ApplicationLogo";
import Dropdown from "@/Components/Dropdown";
import NavLink from "@/Components/NavLink";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink";

const links = [
  {
    title: "Facilities",
    url: "facilities",
  },
  {
    title: "Files",
    url: "files",
  },
];

const Header = ({ title, auth }) => {
  const [showingNavigationDropdown, setShowingNavigationDropdown] =
    useState(false);

  return (
    <>
      <Head title={title} />
      <nav
        className={`bg-amgreen-dark border-b-4 ${
          auth.user ? "border-amgreen-accent" : "border-amgrey"
        }`}
      >
        <div className="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 text-white text-sm">
          <div className="flex justify-between h-16">
            <div className="flex">
              <div className="shrink-0 flex items-center">
                <Link
                  href={auth.user ? "/dashboard" : "/"}
                  className="focus:outline-amgreen-accent"
                >
                  <ApplicationLogo className="w-10 h-10 fill-current text-amgreen-accent" />
                </Link>
                <span className="ml-3 text-3xl">ARTCC Manager</span>
              </div>
              {auth.user && (
                <div className="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                  {links.map((link) => (
                    <NavLink
                      key={link.url}
                      href={route(link.url)}
                      active={route().current(link.url)}
                    >
                      {link.title}
                    </NavLink>
                  ))}
                </div>
              )}
            </div>
            {/* Web Layout */}
            <div className="hidden sm:flex sm:items-center sm:ml-6">
              <div className="h-full flex items-center ml-3 relative">
                {auth.user ? (
                  <Dropdown>
                    <Dropdown.Trigger>{auth.user.first_name}</Dropdown.Trigger>
                    <Dropdown.Content>
                      <Dropdown.Link href={route("dashboard")} method="get">
                        Dashboard
                      </Dropdown.Link>
                      <Dropdown.Link
                        href={route("logout")}
                        method="post"
                        as="button"
                      >
                        Log Out
                      </Dropdown.Link>
                    </Dropdown.Content>
                  </Dropdown>
                ) : (
                  <div className="h-full flex">
                    <div className="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                      <NavLink
                        href={route("login")}
                        active={route().current("login")}
                      >
                        Log In
                      </NavLink>
                      <NavLink
                        href={route("register")}
                        active={route().current("register")}
                      >
                        Register
                      </NavLink>
                    </div>
                  </div>
                )}
              </div>
            </div>
            {/* Mobile Layout */}
            <div className="-mr-2 flex items-center sm:hidden">
              <button
                onClick={() =>
                  setShowingNavigationDropdown(
                    (previousState) => !previousState
                  )
                }
                className="inline-flex items-center justify-center p-2 rounded-md text-amgreen-accent hover:text-white hover:bg-amgreen focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
              >
                <svg
                  className="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <path
                    className={
                      !showingNavigationDropdown ? "inline-flex" : "hidden"
                    }
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                  <path
                    className={
                      showingNavigationDropdown ? "inline-flex" : "hidden"
                    }
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <div
          className={
            (showingNavigationDropdown ? "block" : "hidden") + " sm:hidden"
          }
        >
          <div className="pt-4 border-t border-gray-200 bg-amgreen-white">
            {auth.user && (
              <div className="px-4">
                <div className="font-medium text-base text-gray-800">
                  {auth.user.first_name}
                </div>
                <div className="font-medium text-sm text-gray-500">
                  {auth.user.email}
                </div>
              </div>
            )}
            <div className="mt-3 space-y-1 bg-white border-y-4 border-amgreen">
              {auth ? (
                <>
                  {links.map((link) => (
                    <ResponsiveNavLink
                      key={link.url}
                      href={route(link.url)}
                      active={route().current(link.url)}
                    >
                      {link.title}
                    </ResponsiveNavLink>
                  ))}
                  <ResponsiveNavLink method="get" href={route("dashboard")}>
                    Dashboard
                  </ResponsiveNavLink>
                  <ResponsiveNavLink
                    method="post"
                    href={route("logout")}
                    as="button"
                  >
                    Log Out
                  </ResponsiveNavLink>
                </>
              ) : (
                <ResponsiveNavLink method="get" href={route("login")}>
                  Log In
                </ResponsiveNavLink>
              )}
            </div>
          </div>
        </div>
      </nav>
    </>
  );
};

export default Header;
