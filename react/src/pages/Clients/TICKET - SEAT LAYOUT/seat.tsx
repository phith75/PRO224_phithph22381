import React from "react";
import Header from "../../../Layout/LayoutUser/Header";

const BookingSeat = () => {
    // Tạo một mảng chứa các mã ghế J1
    const j1Seats = Array.from({ length: 20 }, (_, index) => `J${index + 1}`);
    const ISeats = Array.from({ length: 20 }, (_, index) => `I${index + 1}`);
    return (
        <>
            <Header />
            <div className="title-fim text-center mx-auto space-y-[10px] my-[66px]">
                <img
                    src="/openhemer.png/"
                    alt=""
                    className="block text-center mx-auto"
                />
                <h1 className="text-[40px]  font-bold text-[#FFFFFF]">
                    Oppenheimer
                </h1>
                <span className="text-[14px] text-[#8E8E8E] block">
                    Thứ Hai, ngày 21 tháng 8, 13:00-16:00
                </span>
                <span className="text-[14px] text-[#8E8E8E] block">
                    Trung tâm mua sắm Poins - Audi 1
                </span>
            </div>
            <section className="Screen max-w-6xl mx-auto px-5">
                <img src="/ic-screen.png/" alt="" />
                <div className="Seat space-x-2 space-y-2 text-center mx-auto my-10">
                    {j1Seats.map((seat) => (
                        <div
                            key={seat}
                            className="text-[11px] text-[#FFFFFF] px-4 py-3 font-bold bg-[#EE2E24] rounded-lg inline-block"
                        >
                            {seat}
                        </div>
                    ))}
                    {ISeats.map((seat) => (
                        <div
                            key={seat}
                            className="text-[12px] text-[#FFFFFF] px-4 py-3 font-bold bg-[#8E8E8E] rounded-lg inline-block"
                        >
                            {seat}
                        </div>
                    ))}
                    {j1Seats.map((seat) => (
                        <div
                            key={seat}
                            className="text-[11px] text-[#FFFFFF] px-4 py-3 font-bold bg-[#EE2E24] rounded-lg inline-block"
                        >
                            {seat}
                        </div>
                    ))}
                    {j1Seats.map((seat) => (
                        <div
                            key={seat}
                            className="text-[11px] text-[#FFFFFF] px-4 py-3 font-bold bg-[#EE2E24] rounded-lg inline-block"
                        >
                            {seat}
                        </div>
                    ))}
                    {j1Seats.map((seat) => (
                        <div
                            key={seat}
                            className="text-[11px] text-[#FFFFFF] px-4 py-3 font-bold bg-[#EE2E24] rounded-lg inline-block"
                        >
                            {seat}
                        </div>
                    ))}
                </div>
            </section>
            <div className="status Seat flex space-x-[20px] items-center mx-auto justify-center mt-[36px] mb-[133px] max-w-5xl">
                <div className="items-center flex">
                    <div className=" text-[#FFFFFF] px-6 py-5  bg-[#EE2E24] rounded-lg inline-block"></div>
                    <span className="text-[17px] text-[#8E8E8E] mx-2">
                        Ghế đã bán
                    </span>
                </div>
                <div className="items-center flex">
                    <div className=" text-[#FFFFFF]  px-6 py-5  bg-[#8E8E8E] rounded-lg inline-block"></div>
                    <span className="text-[17px] text-[#8E8E8E] mx-2">
                        Ghế trống
                    </span>
                </div>
                <div className="items-center flex">
                    <div className=" text-[#FFFFFF] px-6 py-5  bg-cyan-500 shadow-lg shadow-cyan-500/50 rounded-lg inline-block"></div>{" "}
                    <span className="text-[17px] text-[#8E8E8E] mx-2">
                        Ghế đang chọn
                    </span>
                </div>
            </div>
        </>
    );
};

export default BookingSeat;
