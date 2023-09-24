import { Link } from "react-router-dom";
import Header from "../../../Layout/LayoutUser/Header";

const F_B = () => {
    return (
        <>
            <body className="bg-black">
                <section className="relative bg-[url(/banner-home.png/)] bg-cover w-full bg-center bg-no-repeat">
                    <Header />

                    <div className="text-center my-10 px-10 h-screen py-[200px]  max-w-screen-xl mx-auto">
                        <h2 className="text-[#FFFFFF] mx-auto text-5xl font-bold">
                            Hãy nâng tầm xem phim của bạn cùng chúng tôi !
                        </h2>
                        <p className="text-[#FFFFFF] mx-auto px-20 py-10">
                            Trải nghiệm rạp chiếu phim hơn bao giờ hết - chúng
                            tôi cung cấp công nghệ tiên tiến, chỗ ngồi thoải mái
                            và tuyển chọn phim đa dạng. Đặt phòng dễ dàng trên
                            trang web của chúng tôi và tận hưởng những lợi ích
                            độc quyền!
                        </p>
                        <Link
                            to={"#"}
                            className="px-10 rounded-3xl bg-red-600  py-3 text-sm font-medium text-white shadow hover:bg-red-700 focus:outline-none  active:bg-red-500 sm:w-auto"
                        >
                            Mua ngay
                        </Link>
                    </div>
                </section>
                <div className="m-auto container">
                    <div className="text-center">
                        <div className="mb-6 mt-2">
                            <a
                                className="inline-block rounded-full bg-red-500 px-8 py-3 text-sm font-medium text-white transition hover:rotate-2 hover:scale-110 focus:outline-none focus:ring active:bg-indigo-500 ml-4"
                                href=""
                            >
                                All
                            </a>
                            <a
                                className="inline-block rounded-full border border-gray-700 px-8 py-3 text-sm font-medium text-gray-600 transition hover:rotate-2 hover:scale-110 focus:outline-none focus:ring active:text-indigo-500 ml-4"
                                href=""
                            >
                                Combo
                            </a>
                            <a
                                className="inline-block rounded-full border border-gray-700 px-8 py-3 text-sm font-medium text-gray-600 transition hover:rotate-2 hover:scale-110 focus:outline-none focus:ring active:text-indigo-500 ml-4"
                                href=""
                            >
                                Popcorn
                            </a>
                            <a
                                className="inline-block rounded-full border border-gray-700 px-8 py-3 text-sm font-medium text-gray-600 transition hover:rotate-2 hover:scale-110 focus:outline-none focus:ring active:text-indigo-500 ml-4"
                                href=""
                            >
                                Bevegare
                            </a>
                            <a
                                className="inline-block rounded-full border border-gray-700 px-8 py-3 text-sm font-medium text-gray-600 transition hover:rotate-2 hover:scale-110 focus:outline-none focus:ring active:text-indigo-500 ml-4"
                                href=""
                            >
                                Food
                            </a>
                        </div>
                        <div className="grid grid-cols-4 gap-8 lg:grid-cols-4 lg:gap-8">
                            <a href="#" className="block group">
                                <img
                                    src="bong.jpg"
                                    alt=""
                                    className="object-cover w-full rounded aspect-square"
                                />

                                <div className="mt-3 ml-[-240px]">
                                    <h3 className="font-medium text-white group-hover:underline group-hover:underline-offset-4 text-xl">
                                        Simple Watch
                                    </h3>

                                    <p className="mt-1 text-sm text-gray-700 ml-[-90px]">
                                        $150
                                    </p>
                                </div>
                            </a>
                            <a href="#" className="block group">
                                <img
                                    src="bong.jpg"
                                    alt=""
                                    className="object-cover w-full rounded aspect-square"
                                />

                                <div className="mt-3 ml-[-240px]">
                                    <h3 className="font-medium text-white group-hover:underline group-hover:underline-offset-4 text-xl">
                                        Simple Watch
                                    </h3>

                                    <p className="mt-1 text-sm text-gray-700 ml-[-90px]">
                                        $150
                                    </p>
                                </div>
                            </a>
                            <a href="#" className="block group">
                                <img
                                    src="bong.jpg"
                                    alt=""
                                    className="object-cover w-full rounded aspect-square"
                                />

                                <div className="mt-3 ml-[-240px]">
                                    <h3 className="font-medium text-white group-hover:underline group-hover:underline-offset-4 text-xl">
                                        Simple Watch
                                    </h3>

                                    <p className="mt-1 text-sm text-gray-700 ml-[-90px]">
                                        $150
                                    </p>
                                </div>
                            </a>
                            <a href="#" className="block group">
                                <img
                                    src="bong.jpg"
                                    alt=""
                                    className="object-cover w-full rounded aspect-square"
                                />

                                <div className="mt-3 ml-[-240px]">
                                    <h3 className="font-medium text-white group-hover:underline group-hover:underline-offset-4 text-xl">
                                        Simple Watch
                                    </h3>

                                    <p className="mt-1 text-sm text-gray-700 ml-[-90px]">
                                        $150
                                    </p>
                                </div>
                            </a>
                            <a href="#" className="block group">
                                <img
                                    src="bong.jpg"
                                    alt=""
                                    className="object-cover w-full rounded aspect-square"
                                />

                                <div className="mt-3 ml-[-240px]">
                                    <h3 className="font-medium text-white group-hover:underline group-hover:underline-offset-4 text-xl">
                                        Simple Watch
                                    </h3>

                                    <p className="mt-1 text-sm text-gray-700 ml-[-90px]">
                                        $150
                                    </p>
                                </div>
                            </a>
                            <a href="#" className="block group">
                                <img
                                    src="bong.jpg"
                                    alt=""
                                    className="object-cover w-full rounded aspect-square"
                                />

                                <div className="mt-3 ml-[-240px]">
                                    <h3 className="font-medium text-white group-hover:underline group-hover:underline-offset-4 text-xl">
                                        Simple Watch
                                    </h3>

                                    <p className="mt-1 text-sm text-gray-700 ml-[-90px]">
                                        $150
                                    </p>
                                </div>
                            </a>
                            <a href="#" className="block group">
                                <img
                                    src="bong.jpg"
                                    alt=""
                                    className="object-cover w-full rounded aspect-square"
                                />

                                <div className="mt-3 ml-[-240px]">
                                    <h3 className="font-medium text-white group-hover:underline group-hover:underline-offset-4 text-xl">
                                        Simple Watch
                                    </h3>

                                    <p className="mt-1 text-sm text-gray-700 ml-[-90px]">
                                        $150
                                    </p>
                                </div>
                            </a>
                            <a href="#" className="block group">
                                <img
                                    src="bong.jpg"
                                    alt=""
                                    className="object-cover w-full rounded aspect-square"
                                />

                                <div className="mt-3 ml-[-240px]">
                                    <h3 className="font-medium text-white group-hover:underline group-hover:underline-offset-4 text-xl">
                                        Simple Watch
                                    </h3>

                                    <p className="mt-1 text-sm text-gray-700 ml-[-90px]">
                                        $150
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </body>
        </>
    );
};
export default F_B;
