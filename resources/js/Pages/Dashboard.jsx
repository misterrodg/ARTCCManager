import MainLayout from "@/Layouts/MainLayout";
import Container from "@/Components/Main/Container";

const Dashboard = (props) => {
  return (
    <MainLayout title="Dashboard" auth={props.auth} errors={props.errors}>
      <Container>You're logged in!</Container>
    </MainLayout>
  );
};

export default Dashboard;
