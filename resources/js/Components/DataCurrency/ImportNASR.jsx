import { useState } from "react";
import axios from "axios";
import { GiRadarSweep } from "react-icons/gi";

import PrimaryButton from "../PrimaryButton";

const IMPORT_URL = "/nasr";

const ImportNASR = ({ next = false, handleCurrency, handleErrorMessage }) => {
  const [processing, setProcessing] = useState(false);
  const [spin, setSpin] = useState(false);
  const [statusMessage, setStatusMessage] = useState("");

  const editionName = next ? "NEXT" : "CURRENT";

  const handleError = (message) => {
    setSpin(false);
    setStatusMessage("Error");
    handleErrorMessage(message);
  };

  async function processAirports() {
    setSpin(true);
    setStatusMessage("Airports");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/airports`, postData);
      if (res.data.success === true) {
        processAirways();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("Airport import failed.");
    }
  }

  async function processAirways() {
    setStatusMessage("Airways");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/airways`, postData);
      if (res.data.success === true) {
        processAirwaysAts();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("Airway import failed.");
    }
  }

  async function processAirwaysAts() {
    setStatusMessage("Airways (ATS)");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/airwaysats`, postData);
      if (res.data.success === true) {
        processAwos();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("Airway (ATS) import failed.");
    }
  }

  async function processAwos() {
    setStatusMessage("AWOS");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/awos`, postData);
      if (res.data.success === true) {
        processBoundaries();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("AWOS import failed.");
    }
  }

  async function processBoundaries() {
    setStatusMessage("Boundaries");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/boundaries`, postData);
      if (res.data.success === true) {
        processCodedRoutes();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("Boundary import failed.");
    }
  }

  async function processCodedRoutes() {
    setStatusMessage("CDRs");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/codedroutes`, postData);
      if (res.data.success === true) {
        processFixes();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("CDR import failed.");
    }
  }

  async function processFixes() {
    setStatusMessage("Fixes");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/fixes`, postData);
      if (res.data.success === true) {
        processILS();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("Fix import failed.");
    }
  }

  async function processILS() {
    setStatusMessage("ILS");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/ils`, postData);
      if (res.data.success === true) {
        processNavaids();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("ILS import failed.");
    }
  }

  async function processNavaids() {
    setStatusMessage("Navaids");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/navaids`, postData);
      if (res.data.success === true) {
        processPreferredRoutes();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("Navaid import failed.");
    }
  }

  async function processPreferredRoutes() {
    setStatusMessage("PFRs");
    const postData = {
      editionName: editionName,
    };
    try {
      const res = await axios.post(`${IMPORT_URL}/preferredroutes`, postData);
      if (res.data.success === true) {
        finalize();
      } else {
        handleError(res.data.message);
      }
    } catch {
      handleError("PFR import failed.");
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
    processAirports();
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

export default ImportNASR;
