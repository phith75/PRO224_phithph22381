import React from "react";
import { Outlet } from "react-router-dom";
import Footer from "./Footer";

const LayoutUser = () => {
    return (
        <>
            {/* <Header /> */}
            <Outlet />
            <Footer />
        </>
    );
};

export default LayoutUser;
