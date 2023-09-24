import React from "react";
import { Outlet } from "react-router-dom";
import Footer from "./Footer";

const LayoutUser = () => {
    return (
        <div className="bg-[#121212]">
            {/* <Header /> */}
            <Outlet />
            <Footer />
        </div>
    );
};

export default LayoutUser;
