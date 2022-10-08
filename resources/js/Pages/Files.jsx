import MainLayout from "@/Layouts/MainLayout";
import Container from "@/Components/Main/Container";
import Currency from "@/Components/DataCurrency/Currency";

const Files = (props) => {
  return (
    <MainLayout title="Files" auth={props.auth} errors={props.errors}>
      <Container>
        <div className="mb-4">
          <Currency
            dataType="cifp"
            dataCurrent={props.cifpCurrent}
            dataNext={props.cifpNext}
            dataDownloadCurrent={props.cifpDownloadCurrent}
            dataDownloadNext={props.cifpDownloadNext}
          />
        </div>
        <div className="mb-4">
          <Currency
            dataType="nasr"
            dataCurrent={props.nasrCurrent}
            dataNext={props.nasrNext}
            dataDownloadCurrent={props.nasrDownloadCurrent}
            dataDownloadNext={props.nasrDownloadNext}
          />
        </div>
      </Container>
    </MainLayout>
  );
};

export default Files;
