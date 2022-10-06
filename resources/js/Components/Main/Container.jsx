const Container = ({ children }) => {
  return (
    <div className="max-w-7xl mx-auto sm:p-6 lg:p-10">
      <div className="bg-white p-6 sm:rounded-lg sm:shadow-md">{children}</div>
    </div>
  );
};

export default Container;
