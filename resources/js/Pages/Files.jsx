import MainLayout from "@/Layouts/MainLayout";
import Container from "@/Components/Main/Container";
import CIFP from "@/Components/CIFP/CIFP";
import NASR from "@/Components/NASR/NASR";

const Files = (props) => {
  return (
    <MainLayout title="Files" auth={props.auth} errors={props.errors}>
      <Container>
        <div className="mb-2">
          <CIFP cifpCurrent={props.cifpCurrent} cifpNext={props.cifpNext} />
        </div>
        <div className="mb-2">
          <NASR nasrCurrent={props.nasrCurrent} nasrNext={props.nasrNext} />
        </div>
      </Container>
    </MainLayout>
  );
};

export default Files;
