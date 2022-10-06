import React, { useEffect, useState } from "react";
import PrimaryButton from "@/Components/PrimaryButton";
import FormLayout from "@/Layouts/FormLayout";
import TextInput from "@/Components/TextInput";
import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";
import { Link, useForm } from "@inertiajs/inertia-react";
import { HiEye, HiEyeOff } from "react-icons/hi";

export default function Register() {
  const { data, setData, post, processing, errors, reset } = useForm({
    firstName: "",
    lastName: "",
    email: "",
    email_confirmation: "",
    password: "",
    password_confirmation: "",
  });

  const [passwordShown, setPasswordShown] = useState(false);

  useEffect(() => {
    return () => {
      reset("password", "password_confirmation");
    };
  }, []);

  const onHandleChange = (event) => {
    setData(
      event.target.name,
      event.target.type === "checkbox"
        ? event.target.checked
        : event.target.value
    );
  };

  const togglePassword = (e) => {
    e.preventDefault();
    setPasswordShown(!passwordShown);
  };

  const submit = (e) => {
    e.preventDefault();
    post(route("register"));
  };

  return (
    <FormLayout>
      <InputError errors={errors} />
      <form onSubmit={submit}>
        <div>
          <InputLabel forInput="firstName" value="First Name" />
          <TextInput
            type="text"
            name="firstName"
            value={data.firstName}
            className="mt-1 block w-full"
            autoComplete="firstName"
            isFocused={true}
            handleChange={onHandleChange}
            required
          />
        </div>
        <div className="mt-4">
          <InputLabel forInput="lastName" value="Last Name" />
          <TextInput
            type="text"
            name="lastName"
            value={data.lastName}
            className="mt-1 block w-full"
            autoComplete="lastName"
            handleChange={onHandleChange}
            required
          />
        </div>
        <div className="mt-4">
          <InputLabel forInput="email" value="Email" />
          <TextInput
            type="email"
            name="email"
            value={data.email}
            className="mt-1 block w-full"
            autoComplete="username"
            handleChange={onHandleChange}
            required
          />
        </div>
        <div className="mt-4">
          <InputLabel forInput="email_confirmation" value="Confirm Email" />
          <TextInput
            type="email"
            name="email_confirmation"
            value={data.email_confirmation}
            className="mt-1 block w-full"
            handleChange={onHandleChange}
            required
          />
        </div>
        <div className="mt-4 relative">
          <InputLabel forInput="password" value="Password" />
          <TextInput
            type={passwordShown ? "text" : "password"}
            name="password"
            value={data.password}
            className="mt-1 block w-full"
            autoComplete="new-password"
            handleChange={onHandleChange}
            required
          />
          <div
            onClick={togglePassword}
            className="absolute top-9 right-2 cursor-pointer"
          >
            {" "}
            {passwordShown ? (
              <HiEyeOff className="w-5 h-5" />
            ) : (
              <HiEye className="w-5 h-5" />
            )}{" "}
          </div>
        </div>
        <div className="mt-4 relative">
          <InputLabel
            forInput="password_confirmation"
            value="Confirm Password"
          />
          <TextInput
            type={passwordShown ? "text" : "password"}
            name="password_confirmation"
            value={data.password_confirmation}
            className="mt-1 block w-full"
            handleChange={onHandleChange}
            required
          />
          <div onClick={togglePassword} className="absolute top-9 right-2">
            {" "}
            {passwordShown ? (
              <HiEyeOff className="w-5 h-5" />
            ) : (
              <HiEye className="w-5 h-5" />
            )}{" "}
          </div>
        </div>
        <div className="flex items-center justify-end mt-4">
          <Link
            href={route("login")}
            className="underline text-sm text-gray-600 hover:text-gray-900"
          >
            Already registered?
          </Link>
          <PrimaryButton className="ml-4" processing={processing}>
            Register
          </PrimaryButton>
        </div>
      </form>
    </FormLayout>
  );
}
