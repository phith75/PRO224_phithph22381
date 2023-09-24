import { Link } from "react-router-dom";
import Header from "../../../Layout/LayoutUser/Header";

const Movies = () => {
    return (
        <>
            <body className="bg-black">
                <section className="relative bg-[url(/banner-home.png/)] bg-cover  bg-center bg-no-repeat">
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
                    </div>
                </section>
                <div className=" container m-auto w-full">
                    <div className="text-center">
                        <h1 className="text-center ml-[-50px] text-3xl mb-6 text-white">
                            Now Playing
                        </h1>
                        <div className="grid grid-cols-4 gap-12 lg:grid-cols-4 lg:gap-8">
                            <div className="h-32 rounded-lg ">
                                <img src="Frame 27.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg ">
                                <img src="Frame 28.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg ">
                                <img src="Frame 29.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg ">
                                <img src="Frame 25.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg mt-[280px]   ">
                                <img src="Frame 25.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg mt-[280px] ">
                                <img src="Frame 25.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg mt-[280px]  ">
                                <img src="Frame 25.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg mt-[280px]  ">
                                <img src="Frame 25.png" alt="" />
                            </div>
                        </div>
                    </div>
                    <div className="text-center mt-[380px]">
                        <h1 className="text-center ml-[-50px] text-3xl mb-8 text-white">
                            Upcoming
                        </h1>
                        <div className="grid grid-cols-4 gap-8 lg:grid-cols-4 lg:gap-8">
                            <div className="h-32 rounded-lg ">
                                <img src="Frame 27.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg ">
                                <img src="Frame 28.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg ">
                                <img src="Frame 29.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg ">
                                <img src="Frame 25.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg mt-[280px]   ">
                                <img src="Frame 25.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg mt-[280px] ">
                                <img src="Frame 25.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg mt-[280px]  ">
                                <img src="Frame 25.png" alt="" />
                            </div>
                            <div className="h-32 rounded-lg mt-[280px]  ">
                                <img src="Frame 25.png" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            </body>
        </>
    );
};
export default Movies;
