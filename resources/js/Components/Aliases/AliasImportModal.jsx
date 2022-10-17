import { Fragment, useState } from "react";
import { useForm } from "@inertiajs/inertia-react";
import { Dialog, Transition } from "@headlessui/react";

import InputLabel from "../InputLabel";
import InputError from "../InputError";
import PrimaryButton from "../PrimaryButton";
import TextInput from "../TextInput";

const AliasImportModal = ({
  showImportModal,
  setShowImportModal,
  setAliasesUpdated,
}) => {
  const { data, setData, post, processing, errors, reset } = useForm({
    aliasFile: null,
  });

  const [message, setMessage] = useState("");

  const resetForm = () => {
    setMessage("");
    reset();
    setShowImportModal(false);
  };

  const cancelForm = (event) => {
    event.preventDefault();
    resetForm();
  };

  const submit = (event) => {
    event.preventDefault();
    setMessage("Depending on the file size, this may take a while.");
    post(route("alias.import"), {
      onSuccess: () => {
        setAliasesUpdated(true);
        resetForm();
      },
    });
  };

  return (
    <Transition appear show={showImportModal} as={Fragment}>
      <Dialog
        as="div"
        className="relative z-10"
        onClose={() => setShowImportModal(false)}
      >
        <Transition.Child
          as={Fragment}
          enter="ease-out duration-300"
          enterFrom="opacity-0"
          enterTo="opacity-100"
          leave="ease-in duration-200"
          leaveFrom="opacity-100"
          leaveTo="opacity-0"
        >
          <div className="fixed inset-0 bg-black bg-opacity-50" />
        </Transition.Child>
        <div className="fixed inset-0 overflow-y-auto">
          <div className="flex min-h-full items-center justify-center p-4 text-center">
            <Transition.Child
              as={Fragment}
              enter="ease-out duration-300"
              enterFrom="opacity-0 scale-95"
              enterTo="opacity-100 scale-100"
              leave="ease-in duration-200"
              leaveFrom="opacity-100 scale-100"
              leaveTo="opacity-0 scale-95"
            >
              <Dialog.Panel className="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl">
                <Dialog.Title className="mb-2 border-b-2 border-amgreen-accent text-amgreen text-xl">
                  Import Alias File
                </Dialog.Title>
                <form onSubmit={submit}>
                  <div className="mt-4">
                    <InputLabel forInput="aliasFile" value="Alias File" />
                    <TextInput
                      type="file"
                      name="aliasFile"
                      className="ml-1 mt-1 block w-full"
                      isFocused={true}
                      required
                      handleChange={(e) =>
                        setData("aliasFile", e.target.files[0])
                      }
                    />
                    <InputError message={errors.aliasFile} className="mt-2" />
                  </div>
                  <div
                    className={`mt-4 text-amgreen-light text-sm ${
                      message === "" ? "hidden" : "block"
                    }`}
                  >
                    {message}
                  </div>
                  <div className="grid grid-cols-2 mt-4">
                    <div
                      className="flex items-center justify-start"
                      onClick={cancelForm}
                    >
                      <PrimaryButton
                        className="ml-4 bg-amgrey"
                        type="cancel"
                        processing={processing}
                      >
                        Cancel
                      </PrimaryButton>
                    </div>
                    <div className="flex items-center justify-end">
                      <PrimaryButton className="ml-4" processing={processing}>
                        {processing ? "Processing" : "Submit"}
                      </PrimaryButton>
                    </div>
                  </div>
                </form>
              </Dialog.Panel>
            </Transition.Child>
          </div>
        </div>
      </Dialog>
    </Transition>
  );
};

export default AliasImportModal;
