import { useState } from "react";
import axios from "axios";
import { GiRadarSweep } from "react-icons/gi";

import PrimaryButton from "../PrimaryButton";

const Download = ({
  dataType,
  faaDataObject,
  handleCurrency,
  handleErrorMessage,
}) => {
  const [processing, setProcessing] = useState(false);
  const [spin, setSpin] = useState(false);
  const [statusMessage, setStatusMessage] = useState("");

  const LOCAL_DOWNLOAD_URL = `/${dataType.toLowerCase()}/download`;
  const LOCAL_DECOMPRESS_URL = `/${dataType.toLowerCase()}/decompress`;

  const postData = {
    editionName: faaDataObject.editionName,
    editionDate: faaDataObject.editionDate,
    editionNumber: faaDataObject.editionNumber,
    editionUrl: faaDataObject.editionUrl,
    airacId: faaDataObject.airacId,
  };

  async function getDownload() {
    setSpin(true);
    setStatusMessage("Downloading");
    try {
      const res = await axios.post(`${LOCAL_DOWNLOAD_URL}`, postData);
      if (res.data.success === true) {
        decompressDownload();
      } else {
        handleErrorMessage(res.data.message);
      }
    } catch {
      handleErrorMessage("Download failed.");
    }
  }

  async function decompressDownload() {
    setStatusMessage("Decompressing");
    try {
      const res = await axios.post(`${LOCAL_DECOMPRESS_URL}`, postData);
      if (res.data.success === true) {
        handleCurrency(postData);
        setStatusMessage("Success");
        setSpin(false);
      } else {
        handleErrorMessage(res.data.message);
      }
    } catch {
      handleErrorMessage("Decompression failed.");
    }
  }

  const submit = (e) => {
    e.preventDefault();
    setProcessing(true);
    getDownload();
  };

  return (
    <form onSubmit={submit}>
      <PrimaryButton className="h-6" processing={processing}>
        {!processing ? (
          <span>Process</span>
        ) : (
          <div className="flex items-center">
            {spin && <GiRadarSweep className="animate-spin w-4 h-4 mr-2" />}
            <span className="leading-8">{statusMessage}</span>
          </div>
        )}
      </PrimaryButton>
    </form>
  );
};

export default Download;
