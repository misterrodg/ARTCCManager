import { useEffect, useState } from "react";
import axios from "axios";
import { DateTime } from "luxon";

import PrimaryButton from "../PrimaryButton";
import Download from "./Download";

const dtNow = DateTime.now();

const calculateDaysToNow = (date) => {
  const start = DateTime.fromSQL(date);
  if (start.isValid) {
    const startToNow = dtNow.diff(start, "days");
    startToNow.toObject();
    return Math.floor(startToNow.days);
  }
  return 0;
};

const makeDayString = (days) => {
  let day = days === 1 ? " day" : " days";
  let diff = days > 0 ? " old" : " to go";
  return "(" + Math.abs(days) + day + diff + ")";
};

const Currency = ({
  dataType,
  dataCurrent,
  dataNext,
  dataDownloadCurrent,
  dataDownloadNext,
}) => {
  //Imported State
  const [databaseCurrent, setDatabaseCurrent] = useState(dataCurrent);
  const [databaseNext, setDatabaseNext] = useState(dataNext);
  const [downloadCurrent, setDownloadCurrent] = useState(dataDownloadCurrent);
  const [downloadNext, setDownloadNext] = useState(dataDownloadNext);
  //Age Numbers
  const [currentDatabaseAge, setCurrentDatabaseAge] = useState(0);
  const [nextDatabaseAge, setNextDatabaseAge] = useState(0);
  const [currentDownloadAge, setCurrentDownloadAge] = useState(0);
  const [nextDownloadAge, setNextDownloadAge] = useState(0);
  //FAA Data Returns
  const [faaCurrent, setFaaCurrent] = useState(null);
  const [faaCurrentAvailable, setFaaCurrentAvailable] = useState(false);
  const [faaNext, setFaaNext] = useState(null);
  const [faaNextAvailable, setFaaNextAvailable] = useState(false);
  //Error Handling
  const [currentFaaErrorMessage, setFaaCurrentErrorMessage] = useState("");
  const [nextFaaErrorMessage, setFaaNextErrorMessage] = useState("");
  const [downloadErrorMessage, setDownloadErrorMessage] = useState("");
  //Processing
  const [currentFetch, setCurrentFetch] = useState(false);
  const [nextFetch, setNextFetch] = useState(false);
  const [processing, setProcessing] = useState(false);

  const LOCAL_INFO_URL = `/${dataType}/info/`;

  async function getCurrent() {
    setCurrentFetch(true);
    try {
      const res = await axios.get(`${LOCAL_INFO_URL}current`, {
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
        `Something happened when fetching the Current FAA Data. Check your ENV for the ${dataType.toUpperCase()} entry.`
      );
    }
    setCurrentFetch(false);
  }

  async function getNext() {
    setNextFetch(true);
    try {
      const res = await axios.get(`${LOCAL_INFO_URL}next`, {
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
        `Something happened when fetching the Next FAA Data. Check your ENV for the ${dataType.toUpperCase()} entry.`
      );
    }
    setNextFetch(false);
  }

  const handleCurrency = (data) => {
    if (data.editionName === "CURRENT") {
      setDownloadCurrent(data);
    } else {
      setDownloadNext(data);
    }
  };

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

  useEffect(() => {
    if (!currentFetch && !nextFetch) {
      setProcessing(false);
    }
  }, [currentFetch, nextFetch]);

  useEffect(() => {
    setCurrentDatabaseAge(calculateDaysToNow(databaseCurrent?.edition_date));
  }, [databaseCurrent]);

  useEffect(() => {
    setNextDatabaseAge(calculateDaysToNow(databaseNext?.edition_date));
  }, [databaseNext]);

  useEffect(() => {
    setCurrentDownloadAge(
      calculateDaysToNow(downloadCurrent?.editionDate.date)
    );
  }, [downloadCurrent]);

  useEffect(() => {
    setNextDownloadAge(calculateDaysToNow(downloadNext?.editionDate.date));
  }, [downloadNext]);

  return (
    <div>
      <div className="mb-2 border-b-2 border-amgreen-accent text-amgreen text-xl">
        {dataType.toUpperCase()} Data
      </div>
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-1">
        <div className="grid grid-cols-2">
          <div className="flex items-center">Database Current:</div>
          <div className="flex items-center">
            {databaseCurrent ? (
              <span className={`${currentDatabaseAge >= 28 && "text-red-500"}`}>
                {databaseCurrent?.cycle_id} {makeDayString(currentDatabaseAge)}
              </span>
            ) : (
              <span className="text-amgrey italic">No Data</span>
            )}
          </div>
        </div>
        <div className="grid grid-cols-2">
          <div className="flex items-center">Database Next:</div>
          <div className="flex items-center">
            {databaseNext ? (
              <span className={`${nextDatabaseAge >= 0 && "text-red-500"}`}>
                {databaseNext?.cycle_id} {makeDayString(nextDatabaseAge)}
              </span>
            ) : (
              <span className="text-amgrey italic">No Data</span>
            )}
          </div>
        </div>
        <div className="grid grid-cols-2">
          <div className="flex items-center">Downloaded Current:</div>
          <div className="flex items-center">
            {downloadCurrent ? (
              <span className={`${currentDownloadAge >= 28 && "text-red-500"}`}>
                {downloadCurrent?.airacId} {makeDayString(currentDownloadAge)}
              </span>
            ) : (
              <span className="text-amgrey italic">Not Downloaded</span>
            )}
          </div>
        </div>
        <div className="grid grid-cols-2">
          <div className="flex items-center">Downloaded Next:</div>
          <div className="flex items-center">
            {downloadNext ? (
              <span className={`${nextDownloadAge >= 0 && "text-red-500"}`}>
                {downloadNext?.airacId} {makeDayString(nextDownloadAge)}
              </span>
            ) : (
              <span className="text-amgrey italic">Not Downloaded</span>
            )}
          </div>
        </div>
        <div className="grid grid-cols-2">
          <div className="flex items-center">FAA Current:</div>
          <div className="flex items-center">
            {faaCurrent !== null ? (
              <div className="flex items-center">
                <div>{faaCurrent.airacId}</div>
                <div className="ml-3">
                  {faaCurrentAvailable && (
                    <Download
                      dataType={dataType}
                      faaDataObject={faaCurrent}
                      handleCurrency={handleCurrency}
                      handleErrorMessage={handleErrorMessage}
                    />
                  )}
                </div>
              </div>
            ) : (
              <span className="text-amgrey">-</span>
            )}
          </div>
        </div>
        <div className="grid grid-cols-2">
          <div className="flex items-center">FAA Next:</div>
          <div className="flex items-center">
            {faaNext !== null ? (
              <div className="flex items-center">
                <div>{faaNext.airacId}</div>
                <div className="ml-3">
                  {faaNextAvailable && (
                    <Download
                      dataType={dataType}
                      faaDataObject={faaNext}
                      handleCurrency={handleCurrency}
                      handleErrorMessage={handleErrorMessage}
                    />
                  )}
                </div>
              </div>
            ) : (
              <span className="text-amgrey">-</span>
            )}
          </div>
        </div>
      </div>
      <form onSubmit={submit}>
        <div className="flex items-center justify-end mt-4">
          <PrimaryButton className="ml-4" processing={processing}>
            Check FAA {dataType.toUpperCase()} Data
          </PrimaryButton>
        </div>
      </form>
      {currentFaaErrorMessage !== "" && (
        <div className="ml-4 text-amgrey italic">
          <div>FAA Response for Current Data:</div>
          <div>{currentFaaErrorMessage}</div>
        </div>
      )}
      {nextFaaErrorMessage != "" && (
        <div className="ml-4 text-amgrey">
          <div>FAA Response for Next Data:</div>
          <div>{nextFaaErrorMessage}</div>
          <div className="italic">
            This may happen if you request data on, or shortly after, the AIRAC
            release day.
          </div>
        </div>
      )}
      {downloadErrorMessage !== "" && (
        <div className="ml-4 text-amgrey">
          <div>Download Error:</div>
          <div>{downloadErrorMessage}</div>
        </div>
      )}
    </div>
  );
};

export default Currency;
