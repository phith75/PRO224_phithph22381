import React from "react";
import Header from "./Header";
import { Outlet } from "react-router-dom";
import Footer from "./Footer";

const LayoutUser = () => {
    return (
        <>
            <Outlet />
           
        </>
    );
};

export default LayoutUser;
