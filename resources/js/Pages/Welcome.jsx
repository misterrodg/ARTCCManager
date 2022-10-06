import MainLayout from "@/Layouts/MainLayout";

import { GiRadarSweep } from "react-icons/gi";

export default function Welcome(props) {
  return (
    <MainLayout title="ARTCC Manager" auth={props.auth} errors={props.errors}>
      <div className="max-w-7xl h-screen -mt-20 mx-auto flex justify-center items-center sm:p-6 lg:p-10">
        <div className="text-3xl font-semibold italic text-amgreen-accent">
          <div className="flex">
            <GiRadarSweep className="w-20 h-20" />
            <div className="ml-5">
              <div className="">Welcome to the</div>
              <div className="">{props.artccId} ARTCC Data Manager</div>
            </div>
          </div>
        </div>
      </div>
    </MainLayout>
  );
}
