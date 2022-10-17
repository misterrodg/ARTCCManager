import { useForm } from "@inertiajs/inertia-react";
import { HiPencilAlt, HiX } from "react-icons/hi";

const AliasRow = ({ alternate, alias, editAlias, deleteAlias }) => {
  const { data, setData, post, processing, errors, reset } = useForm({
    dot_command: alias.dot_command,
    replace_with: alias.replace_with,
    type: alias.type,
    is_sup_only: alias.is_sup_only ? true : false,
    is_loa_item: alias.is_loa_item ? true : false,
    loa_with: alias.loa_with,
    expiration: alias.expiration,
  });

  const handleDelete = (alias) => {
    post(route("alias.delete"), { onSuccess: deleteAlias(alias) });
  };

  return (
    <div
      className={`grid grid-cols-12 items-center text-sm ${
        alternate ? "bg-amgreen-white" : ""
      }`}
    >
      <div className="col-span-2">.{alias.dot_command}</div>
      <div className="col-span-3 truncate">{alias.replace_with}</div>
      <div className="col-span-1">{alias.type}</div>
      <div className="col-span-1">{alias.is_sup_only ? "Yes" : ""}</div>
      <div className="col-span-1">{alias.is_loa_item ? "Yes" : ""}</div>
      <div className="col-span-1">{alias.loa_with}</div>
      <div className="col-span-1">{alias.expiration}</div>
      <div className="col-span-1 flex justify-end">
        <div className="cursor-pointer" onClick={() => editAlias(alias)}>
          <HiPencilAlt className="text-amgrey h-5 w-5" />
        </div>
      </div>
      <div className="col-span-1 flex justify-end">
        <div className="cursor-pointer" onClick={() => handleDelete(alias)}>
          <HiX className="text-red-500 h-5 w-5" />
        </div>
      </div>
    </div>
  );
};

export default AliasRow;
