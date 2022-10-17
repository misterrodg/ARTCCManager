import MainLayout from "@/Layouts/MainLayout";
import Container from "@/Components/Main/Container";
import Aliases from "@/Components/Aliases/Aliases";

const Facilities = (props) => {
  return (
    <MainLayout title="Facilities" auth={props.auth} errors={props.errors}>
      <Container>
        <Aliases aliasArray={props.aliases} />
      </Container>
    </MainLayout>
  );
};

export default Facilities;
