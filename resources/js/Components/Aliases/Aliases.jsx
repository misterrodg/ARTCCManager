import { useEffect, useState } from "react";
import { HiPlus } from "react-icons/hi";

import PrimaryButton from "../PrimaryButton";
import AliasModal from "./AliasModal";
import AliasImportModal from "./AliasImportModal";
import AliasRow from "./AliasRow";

const Aliases = ({ aliasArray }) => {
  const [aliases, setAliases] = useState(
    aliasArray !== undefined && aliasArray.length > 0 ? aliasArray : []
  );
  const [selectedAlias, setSelectedAlias] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [showImportModal, setShowImportModal] = useState(false);
  const [aliasesUpdated, setAliasesUpdated] = useState(false);

  const editAlias = (alias) => {
    setSelectedAlias(alias);
    setShowModal(true);
  };

  const deleteAlias = (deleted) => {
    setAliases(
      aliases.filter((alias) => alias.dot_command !== deleted.dot_command)
    );
  };

  useEffect(() => {
    if (aliasesUpdated == true) {
      window.location.reload();
      setAliasesUpdated(false);
    }
  }, [aliasesUpdated]);

  return (
    <div>
      <div className="mb-2 border-b-2 border-amgreen-accent text-amgreen text-xl">
        Aliases
      </div>
      <div className="ml-2 mb-2">
        <div className="grid grid-cols-12 border-b border-amgreen text-sm font-bold">
          <div className="col-span-2">Dot Command</div>
          <div className="col-span-3">Replace With</div>
          <div className="col-span-1">Type</div>
          <div className="col-span-1">SUP Only</div>
          <div className="col-span-1">LOA Item</div>
          <div className="col-span-1">LOA With</div>
          <div className="col-span-1">Expiration</div>
          <div className="col-span-1 flex justify-end">Edit</div>
          <div className="col-span-1 flex justify-end">Delete</div>
        </div>
        {aliases.length > 0 ? (
          <div className="mt-2">
            {aliases.map((alias, index) => (
              <AliasRow
                key={alias.dot_command}
                alternate={index % 2 == 0}
                alias={alias}
                editAlias={editAlias}
                deleteAlias={deleteAlias}
              />
            ))}
          </div>
        ) : (
          <div className="mt-2 flex justify-center italic text-amgrey text-sm">
            No Aliases Yet
          </div>
        )}
      </div>
      <div className="grid grid-cols-2">
        <div>
          <div onClick={() => setShowImportModal(true)}>
            <PrimaryButton className="flex items-center">
              Import Aliases
              <HiPlus className="ml-1" />
            </PrimaryButton>
          </div>
        </div>
        <div className="flex justify-end">
          <div onClick={() => setShowModal(true)}>
            <PrimaryButton className="flex items-center">
              Add Alias
              <HiPlus className="ml-1" />
            </PrimaryButton>
          </div>
        </div>
      </div>
      <div className={`${showModal ? "block" : "hidden"}`}>
        <AliasModal
          showModal={showModal}
          setShowModal={setShowModal}
          alias={selectedAlias}
          aliases={aliases}
          setAliases={setAliases}
          setSelectedAlias={setSelectedAlias}
        />
      </div>
      <div className={`${showImportModal ? "block" : "hidden"}`}>
        <AliasImportModal
          showImportModal={showImportModal}
          setShowImportModal={setShowImportModal}
          setAliasesUpdated={setAliasesUpdated}
        />
      </div>
    </div>
  );
};

export default Aliases;
