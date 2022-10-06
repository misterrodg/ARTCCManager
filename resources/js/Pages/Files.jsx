import MainLayout from "@/Layouts/MainLayout";
import Container from "@/Components/Main/Container";

const Files = (props) => {
  return (
    <MainLayout title="Files" auth={props.auth} errors={props.errors}>
      <Container>This page manages the ARTCC Files</Container>
    </MainLayout>
  );
};

export default Files;
