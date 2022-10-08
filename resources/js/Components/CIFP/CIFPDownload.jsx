import { useState } from "react";
import axios from "axios";
import { GiRadarSweep } from "react-icons/gi";

import PrimaryButton from "../PrimaryButton";

const LOCAL_CIFP_DOWNLOAD_URL = "/cifp/download";
const LOCAL_CIFP_DECOMPRESS_URL = "/cifp/decompress";

const CIFPDownload = ({ faaDataObject, handleErrorMessage }) => {
  const [processing, setProcessing] = useState(false);
  const [spin, setSpin] = useState(false);
  const [statusMessage, setStatusMessage] = useState("");

  async function getDownload() {
    setSpin(true);
    setStatusMessage("Downloading");
    const postData = {
      version: faaDataObject.editionName,
      editionDate: faaDataObject.editionDate,
      editionNumber: faaDataObject.editionNumber,
      editionUrl: faaDataObject.editionUrl,
      airacId: faaDataObject.airacId,
    };
    try {
      const res = await axios.post(`${LOCAL_CIFP_DOWNLOAD_URL}`, postData, {
        headers: {
          "x-access-token": "token-value",
        },
      });
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
    const postData = {
      version: faaDataObject.editionName,
      editionDate: faaDataObject.editionDate,
      editionNumber: faaDataObject.editionNumber,
      editionUrl: faaDataObject.editionUrl,
      airacId: faaDataObject.airacId,
    };
    try {
      const res = await axios.post(`${LOCAL_CIFP_DECOMPRESS_URL}`, postData, {
        headers: {
          "x-access-token": "token-value",
        },
      });
      if (res.data.success === true) {
        setStatusMessage("Success");
        setSpin(false);
      } else {
        handleErrorMessage(res.data.message);
      }
    } catch {
      handleErrorMessage("Download failed.");
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

export default CIFPDownload;
