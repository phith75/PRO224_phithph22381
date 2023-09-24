import React from "react";
import Header from "../../../Layout/LayoutUser/Header";
import { Link } from "react-router-dom";
import { Input } from "antd";

const HomePages = () => {
    return (
        <>
            <section className="relative bg-[url(/banner-home.png/)] bg-cover w-full bg-center bg-no-repeat">
                <Header />

                <div className="text-center my-10 px-10 h-screen py-[200px]  max-w-screen-xl mx-auto">
                    <h2 className="text-[#FFFFFF] mx-auto text-5xl font-bold">
                        Hãy nâng tầm xem phim của bạn cùng chúng tôi !
                    </h2>
                    <p className="text-[#FFFFFF] mx-auto px-20 py-10">
                        Trải nghiệm rạp chiếu phim hơn bao giờ hết - chúng tôi
                        cung cấp công nghệ tiên tiến, chỗ ngồi thoải mái và
                        tuyển chọn phim đa dạng. Đặt phòng dễ dàng trên trang
                        web của chúng tôi và tận hưởng những lợi ích độc quyền!
                    </p>
                    <Link
                        to={"#"}
                        className="px-10 rounded-3xl bg-red-600  py-3 text-sm font-medium text-white shadow hover:bg-red-700 focus:outline-none  active:bg-red-500 sm:w-auto"
                    >
                        Đặt vé !
                    </Link>
                </div>
            </section>
            <div className="What’s On max-w-6xl px-10 my-[66px] mx-auto">
                <h2 className="text-[#FFFFFF] text-[40px] font-bold text-center">
                    Có Gì Hot!
                </h2>
                <span className="block text-[#8E8E8E] text-[17px] text-center">
                    Khám phá một bộ sưu tập các ưu đãi độc đáo và đặc biệt!
                </span>
                <div className="What’s On img grid grid-cols-2 gap-8 my-10">
                    <Link to={"#"}>
                        <img src="/w-on-1.png/" className="rounded-xl" alt="" />
                    </Link>
                    <Link to={"#"}>
                        <img src="/w-on-2.png/" className="rounded-xl" alt="" />
                    </Link>
                    <Link to={"#"}>
                        <img src="/w-on-3.png/" className="rounded-xl" alt="" />
                    </Link>
                    <Link to={"#"}>
                        <img src="/w-on-4.png/" className="rounded-xl" alt="" />
                    </Link>
                </div>
                <span className="block text-center">
                    <u className="text-[#8E8E8E] text-center text-[17px]">
                        Hiển thị thêm!
                    </u>
                </span>
            </div>
            <div className="Special Features  max-w-6xl  px-10 mx-auto my-[66px]">
                <h2 className="text-[#FFFFFF] text-[40px] font-bold text-center">
                    Tính Năng Đặc Biệt
                </h2>
                <span className="block text-[#8E8E8E] text-[17px] text-center">
                    Trải nghiệm độc quyền chỉ có tại CGV!
                </span>
                <div className="Special Features img grid grid-cols-2 gap-8 my-10">
                    <Link to={"#"}>
                        <img
                            src="/special-1.png/"
                            className="rounded-xl"
                            alt=""
                        />
                    </Link>
                    <Link to={"#"}>
                        <img
                            src="/special-2.png/"
                            className="rounded-xl"
                            alt=""
                        />
                    </Link>
                    <Link to={"#"}>
                        <img
                            src="/special-3.png/"
                            className="rounded-xl"
                            alt=""
                        />
                    </Link>
                    <Link to={"#"}>
                        <img
                            src="/special-4.png/"
                            className="rounded-xl"
                            alt=""
                        />
                    </Link>
                </div>
                <span className="block text-center">
                    <u className="text-[#8E8E8E] text-center text-[17px]">
                        Hiển thị thêm!
                    </u>
                </span>
            </div>
            <div className="Questions max-w-5xl  px-10 mx-auto my-[133px] ">
                <h2 className="text-[#FFFFFF] text-[40px] font-bold text-center">
                    Q & A
                </h2>
                <span className="block text-[#8E8E8E] text-[17px] w-[550px] mx-auto text-center ">
                    Nếu bạn cần hỗ trợ hoặc có bất kỳ thắc mắc nào, vui lòng
                    hỏi. Chúng tôi sẵn sàng hỗ trợ bạn trong suốt toàn bộ quá
                    trình.
                </span>
                <div className="flex gap-5 my-10">
                    <div className="relative w-full">
                        <input
                            type="text"
                            className="bg-transparent border-2 w-full text-[#FFFFFF] border-[#8E8E8E] rounded-3xl pl-10 pr-4 py-2 focus:outline-none focus:border-[#FFFFFF]"
                            placeholder="Email"
                        />
                        <span className="absolute inset-y-0 top-0  grid w-10 place-content-center">
                            <button
                                type="button"
                                className="text-gray-600 hover:text-gray-700"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="#8E8E8E"
                                    className="w-6 h-6"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"
                                    />
                                </svg>
                            </button>
                        </span>
                    </div>
                    <div className="relative w-full">
                        <input
                            type="text"
                            className="bg-transparent border-2 w-full text-[#FFFFFF] border-[#8E8E8E] rounded-3xl pl-10 pr-4 py-2 focus:outline-none focus:border-[#FFFFFF]"
                            placeholder="Phone"
                        />
                        <span className="absolute inset-y-0 top-0 grid w-10 place-content-center">
                            <button
                                type="button"
                                className="text-gray-600 hover:text-gray-700"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    fill="#8E8E8E"
                                    className="bi bi-telephone-fill"
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"
                                    />
                                </svg>
                            </button>
                        </span>
                    </div>
                    <Link
                        to={"#"}
                        className="px-24 w-full rounded-3xl bg-red-600  py-3 text-sm font-medium text-white shadow hover:bg-red-700 focus:outline-none  active:bg-red-500 sm:w-auto"
                    >
                        Gửi!
                    </Link>
                </div>
            </div>
        </>
    );
};

export default HomePages;
