import { Fragment, useEffect, useState } from "react";
import { useForm } from "@inertiajs/inertia-react";
import { Dialog, Transition } from "@headlessui/react";

import Checkbox from "../Checkbox";
import InputLabel from "../InputLabel";
import InputError from "../InputError";
import PrimaryButton from "../PrimaryButton";
import TextInput from "../TextInput";

const AliasModal = ({
  showModal,
  setShowModal,
  aliases,
  setAliases,
  alias = null,
  setSelectedAlias,
}) => {
  const { data, setData, post, processing, errors, reset } = useForm({
    dot_command: "",
    replace_with: "",
    type: "",
    is_sup_only: false,
    is_loa_item: false,
    loa_with: "",
    has_expiration: false,
    expiration: "",
    original_dot: "",
  });

  useEffect(() => {
    if (alias !== null) {
      setData({
        dot_command: alias.dot_command,
        replace_with: alias.replace_with,
        type: alias.type !== null ? alias.type : "",
        is_sup_only: alias.is_sup_only,
        is_loa_item: alias.is_loa_item,
        loa_with: alias.loa_with !== null ? alias.loa_with : "",
        has_expiration: alias.expiration !== null ? true : false,
        expiration: alias.expiration !== null ? alias.expiration : "",
        original_dot: alias.dot_command,
      });
      setSelectedAlias(null);
    }
  }, [alias]);

  const [message, setMessage] = useState("");

  const onHandleChange = (event) => {
    const key = event.target.name;
    const value =
      event.target.type === "checkbox"
        ? event.target.checked
        : event.target.value;
    setData((values) => ({ ...values, [key]: value }));
  };

  const resetForm = () => {
    setMessage("");
    reset();
    setShowModal(false);
  };

  const cancelForm = (event) => {
    event.preventDefault();
    resetForm();
  };

  const submit = (event) => {
    event.preventDefault();
    let success = false;
    if (data.original_dot !== "") {
      const updatedAliases = [...aliases];
      const aliasIndex = updatedAliases.findIndex(
        (alias) => alias.dot_command === data.original_dot
      );
      if (aliasIndex >= 0) {
        updatedAliases[aliasIndex] = data;
        setAliases(updatedAliases);
      }
      success = true;
    } else if (
      !aliases.some((alias) => alias.dot_command === data.dot_command)
    ) {
      setAliases([...aliases, data]);
      success = true;
    }
    if (success) {
      post(route("alias.process"), { onSuccess: resetForm() });
    } else {
      setMessage("Duplicate dot commands are not allowed.");
    }
  };

  return (
    <Transition appear show={showModal} as={Fragment}>
      <Dialog
        as="div"
        className="relative z-10"
        onClose={() => setShowModal(false)}
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
                  Add Alias Command
                </Dialog.Title>
                <form onSubmit={submit}>
                  <div className="mt-4">
                    <InputLabel
                      forInput="dot_command"
                      value="Dot Command (enter without the dot)"
                    />
                    <TextInput
                      type="text"
                      name="dot_command"
                      pattern="[A-Za-z0-9]{1,}"
                      value={data.dot_command}
                      className="ml-1 mt-1 block w-full"
                      isFocused={true}
                      required
                      handleChange={onHandleChange}
                    />
                    <InputError message={errors.dot_command} className="mt-2" />
                  </div>
                  <div className="mt-4">
                    <InputLabel forInput="replace_with" value="Replace With" />
                    <textarea
                      className="mt-1 block w-full border-gray-300 focus:border-amgreen-dark focus:ring focus:ring-amgreen-light focus:ring-opacity-50 rounded-md shadow-sm"
                      name="replace_with"
                      rows="5"
                      value={data.replace_with}
                      required
                      onChange={onHandleChange}
                    ></textarea>
                    <InputError
                      message={errors.replace_with}
                      className="mt-2"
                    />
                  </div>
                  <div className="mt-4">
                    <InputLabel forInput="type" value="Alias Type" />
                    <TextInput
                      type="text"
                      name="type"
                      value={data.type}
                      className="mt-1 block w-full"
                      handleChange={onHandleChange}
                    />
                    <InputError message={errors.type} className="mt-2" />
                  </div>
                  <div className="block mt-4">
                    <label className="flex items-center">
                      <Checkbox
                        name="is_sup_only"
                        value={data.is_sup_only}
                        handleChange={onHandleChange}
                      />
                      <span className="ml-2 text-sm text-gray-600">
                        Is SUP Only
                      </span>
                    </label>
                  </div>
                  <div className="block mt-4">
                    <label className="flex items-center">
                      <Checkbox
                        name="is_loa_item"
                        value={data.is_loa_item}
                        handleChange={onHandleChange}
                      />
                      <span className="ml-2 text-sm text-gray-600">
                        Is an LOA Item
                      </span>
                    </label>
                  </div>
                  <div
                    className={`mt-4 ${
                      data.is_loa_item === true ? "block" : "hidden"
                    }`}
                  >
                    <InputLabel forInput="loa_with" value="LOA With" />
                    <TextInput
                      type="text"
                      name="loa_with"
                      value={data.loa_with}
                      className="mt-1 block w-full"
                      handleChange={onHandleChange}
                    />
                    <InputError message={errors.loa_with} className="mt-2" />
                  </div>
                  <div className="block mt-4">
                    <label className="flex items-center">
                      <Checkbox
                        name="has_expiration"
                        value={data.has_expiration}
                        handleChange={onHandleChange}
                      />
                      <span className="ml-2 text-sm text-gray-600">
                        Has an expiration
                      </span>
                    </label>
                  </div>
                  <div
                    className={`mt-4 ${
                      data.has_expiration === true ? "block" : "hidden"
                    }`}
                  >
                    <InputLabel forInput="expiration" value="Expires" />
                    <TextInput
                      type="date"
                      name="expiration"
                      value={data.expiration}
                      className="mt-1 block w-full"
                      handleChange={onHandleChange}
                    />
                    <InputError message={errors.expiration} className="mt-2" />
                  </div>
                  <div
                    className={`mt-4 text-red-500 text-sm ${
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
                      <PrimaryButton className="ml-4 bg-amgrey" type="cancel">
                        Cancel
                      </PrimaryButton>
                    </div>
                    <div className="flex items-center justify-end">
                      <PrimaryButton className="ml-4" processing={processing}>
                        {data.original_dot !== "" ? "Update" : "Submit"}
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

export default AliasModal;
