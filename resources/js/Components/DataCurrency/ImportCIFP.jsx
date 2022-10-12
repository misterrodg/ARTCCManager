import { useState } from "react";
import axios from "axios";
import { GiRadarSweep } from "react-icons/gi";

import PrimaryButton from "../PrimaryButton";

const IMPORT_URL = "/cifp/import";

const ImportCIFP = ({ next = false, handleCurrency, handleErrorMessage }) => {
  const [processing, setProcessing] = useState(false);
  const [spin, setSpin] = useState(false);
  const [statusMessage, setStatusMessage] = useState("");

  const editionName = next ? "NEXT" : "CURRENT";

  const handleError = (message) => {
    setSpin(false);
    setStatusMessage("Error");
    handleErrorMessage(message);
  };

  async function processControlled() {
    setSpin(true);
    setStatusMessage("Controlled");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/controlled`, postData);
      if (res.data.success === true) {
        processRestrictive();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("Controlled Airspace import failed.");
    }
  }

  async function processRestrictive() {
    setStatusMessage("Restrictive");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/restrictive`, postData);
      if (res.data.success === true) {
        processSid();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("Restrictive Airspace import failed.");
    }
  }

  async function processSid() {
    setStatusMessage("SIDs");
    const postData = {
      editionName: editionName,
      procedureType: "sid",
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/procedures`, postData);
      if (res.data.success === true) {
        processStar();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("SID import failed.");
    }
  }

  async function processStar() {
    setStatusMessage("STARs");
    const postData = {
      editionName: editionName,
      procedureType: "star",
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/procedures`, postData);
      if (res.data.success === true) {
        processIaps();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("STAR import failed.");
    }
  }

  async function processIaps() {
    setStatusMessage("IAPs");
    const postData = {
      editionName: editionName,
      procedureType: "iap",
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/procedures`, postData);
      if (res.data.success === true) {
        finalize();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("IAP import failed.");
    }
  }

  async function finalize() {
    setStatusMessage("Finalizing");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/finalize`, postData);
      if (res.data.success === true) {
        handleCurrency(res.data.extraData);
        setStatusMessage("Success");
        setSpin(false);
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("Finalization failed.");
    }
  }

  const submit = (e) => {
    e.preventDefault();
    setProcessing(true);
    processControlled();
  };

  return (
    <form onSubmit={submit}>
      <PrimaryButton className="h-6" processing={processing}>
        {!processing ? (
          <span>Import</span>
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

export default ImportCIFP;
