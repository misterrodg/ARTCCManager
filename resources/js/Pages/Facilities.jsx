import MainLayout from "@/Layouts/MainLayout";
import Container from "@/Components/Main/Container";

const Facilities = (props) => {
  return (
    <MainLayout title="Facilities" auth={props.auth} errors={props.errors}>
      <Container>This page manages the ARTCC Facilities</Container>
    </MainLayout>
  );
};

export default Facilities;
