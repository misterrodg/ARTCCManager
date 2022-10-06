import { Link } from "@inertiajs/inertia-react";
import ApplicationLogo from "@/Components/ApplicationLogo";

const FormLayout = ({ children }) => {
  return (
    <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
      <div>
        <Link href="/">
          <ApplicationLogo className="w-20 h-20 fill-current text-amgreen-accent" />
        </Link>
      </div>
      <div className="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {children}
      </div>
    </div>
  );
};

export default FormLayout;
