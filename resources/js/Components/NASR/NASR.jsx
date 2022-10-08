import { useEffect, useState } from "react";
import axios from "axios";
import { DateTime } from "luxon";

import PrimaryButton from "../PrimaryButton";
import NASRDownload from "./NASRDownload";

const LOCAL_NASR_INFO_URL = "/nasr/info/";

const dtNow = DateTime.now();

const NASR = ({ nasrCurrent, nasrNext }) => {
  const [faaCurrent, setFaaCurrent] = useState(null);
  const [faaCurrentAvailable, setFaaCurrentAvailable] = useState(false);
  const [faaNext, setFaaNext] = useState(null);
  const [faaNextAvailable, setFaaNextAvailable] = useState(false);
  const [currentFaaErrorMessage, setFaaCurrentErrorMessage] = useState("");
  const [nextFaaErrorMessage, setFaaNextErrorMessage] = useState("");
  const [downloadErrorMessage, setDownloadErrorMessage] = useState("");
  const [currentFetch, setCurrentFetch] = useState(false);
  const [nextFetch, setNextFetch] = useState(false);
  const [processing, setProcessing] = useState(false);

  const currentAge = 0;
  const nextAge = 0;
  const currentStart = DateTime.fromISO(nasrCurrent?.edition_date);
  const nextStart = DateTime.fromISO(nasrNext?.edition_date);
  if (currentStart.isValid && nextStart.isValid) {
    const currentToNow = dtNow.diff(currentStart, "days");
    currentToNow.toObject();
    currentAge = Math.floor(currentToNow.days);
    const nextToNow = dtNow.diff(nextStart, "days");
    nextToNow.toObject();
    nextAge = Math.floor(nextToNow.days);
  }

  async function getCurrent() {
    setCurrentFetch(true);
    try {
      const res = await axios.get(`${LOCAL_NASR_INFO_URL}current`, {
        timeout: 5000,
      });
      if (res.data.success === true) {
        setFaaCurrent(res.data.extraData);
        setFaaCurrentAvailable(true);
      } else {
        setFaaCurrentErrorMessage(res.data.message);
      }
    } catch {
      setFaaCurrentErrorMessage(
        "Something happened when fetching the Current FAA Data. Check your ENV for the NASR entry."
      );
    }
    setCurrentFetch(false);
  }

  async function getNext() {
    setNextFetch(true);
    try {
      const res = await axios.get(`${LOCAL_NASR_INFO_URL}next`, {
        timeout: 5000,
      });
      if (res.data.success === true) {
        setFaaNext(res.data.extraData);
        setFaaNextAvailable(true);
      } else {
        setFaaNextErrorMessage(res.data.message);
      }
    } catch {
      setFaaNextErrorMessage(
        "Something happened when fetching the next FAA Data. Check your ENV for the NASR entry."
      );
    }
    setNextFetch(false);
  }

  useEffect(() => {
    if (!currentFetch && !nextFetch) {
      setProcessing(false);
    }
  }, [currentFetch, nextFetch]);

  const handleErrorMessage = (message) => {
    setDownloadErrorMessage(message);
  };

  const submit = (e) => {
    e.preventDefault();
    setProcessing(true);
    setFaaCurrentErrorMessage("");
    setFaaNextErrorMessage("");
    getCurrent();
    getNext();
  };

  return (
    <div>
      <div className="mb-2 border-b-2 border-amgreen-accent text-amgreen text-xl">
        NASR Data
      </div>
      <div className="grid grid-cols-1 sm:grid-cols-2">
        <div className="grid grid-cols-2">
          <div className="flex items-center">Database Current:</div>
          <div className="flex items-center">
            {nasrCurrent ? (
              <span className={`${currentAge >= 28 && "text-red-500"}`}>
                {nasrCurrent?.cycle_id} ({currentAge}{" "}
                {currentAge === 1 ? "day" : "days"}{" "}
                {currentAge >= 28 ? "old" : "left"})
              </span>
            ) : (
              <span className="text-amgrey italic">No Data</span>
            )}
          </div>
        </div>
        <div className="grid grid-cols-3">
          <div className="flex items-center">FAA Current:</div>
          <div className="flex items-center">
            {faaCurrent !== null ? (
              <span>{faaCurrent.airacId}</span>
            ) : (
              <span className="text-amgrey">-</span>
            )}
          </div>
          <div className="flex items-center">
            {faaCurrentAvailable && (
              <NASRDownload
                faaDataObject={faaCurrent}
                handleErrorMessage={handleErrorMessage}
              />
            )}
          </div>
        </div>
        <div className="grid grid-cols-2">
          <div className="flex items-center">Database Next:</div>
          <div className="flex items-center">
            {nasrNext ? (
              <span className={`${nextAge >= 0 && "text-red-500"}`}>
                {nasrNext?.cycle_id} ({nextAge} {nextAge === 1 ? "day" : "days"}{" "}
                {nextAge >= 0 ? "old" : "left"})
              </span>
            ) : (
              <span className="text-amgrey italic">No Data</span>
            )}
          </div>
        </div>
        <div className="grid grid-cols-3">
          <div className="flex items-center">FAA Next:</div>
          <div className="flex items-center">
            {faaNext !== null ? (
              <span>{faaNext.airacId}</span>
            ) : (
              <span className="text-amgrey">-</span>
            )}
          </div>
          <div className="flex items-center">
            {faaNextAvailable && (
              <NASRDownload
                faaDataObject={faaNext}
                handleErrorMessage={handleErrorMessage}
              />
            )}
          </div>
        </div>
      </div>
      <form onSubmit={submit}>
        <div className="flex items-center justify-end mt-4">
          <PrimaryButton className="ml-4" processing={processing}>
            Check FAA NASR Data
          </PrimaryButton>
        </div>
      </form>
      {currentFaaErrorMessage !== "" && (
        <div className="text-amgrey italic">
          <div>Current Request:</div>
          <div>{currentFaaErrorMessage}</div>
        </div>
      )}
      {nextFaaErrorMessage != "" && (
        <div className="text-amgrey italic">
          <div>Next Request:</div>
          <div>{nextFaaErrorMessage}</div>
          <div>
            This may happen if you request data on, or shortly after, the AIRAC
            release day.
          </div>
        </div>
      )}
      {downloadErrorMessage !== "" && (
        <div className="text-amgrey italic">{downloadErrorMessage}</div>
      )}
    </div>
  );
};

export default NASR;
