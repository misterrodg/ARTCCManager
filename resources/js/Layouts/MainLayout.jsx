import Header from "@/Components/Header/Header";

const MainLayout = ({ title, auth, children }) => {
  return (
    <div className="min-h-screen">
      <Header title={title} auth={auth} />
      <main>{children}</main>
    </div>
  );
};

export default MainLayout;
