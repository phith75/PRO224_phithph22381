import { Link } from "react-router-dom"
import Header from "../../../Layout/LayoutUser/Header"
import Footer from "../../../Layout/LayoutUser/Footer"

const Movies = () => {
    return (<>
        <body className="bg-black">
            <section className="relative bg-[url(/banner-home.png/)] bg-cover  bg-center bg-no-repeat">
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

                </div>
            </section>
            <div className=" container m-auto ">


                <h1 className="text-4xl font-bold text-white mt-[50px] text-center">Danh sách Phim</h1>

                <div className="flex justify-center items-center  mt-[15px]">
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 12.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>
                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Oppenheimer</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary  text-gray-600 mt-4 ">
                                <p className="">Kịch</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 13.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Barbie</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary  text-gray-600 mt-4 ">
                                <p className="">Hài</p>
                                <p className="">IMDB 8.8</p>
                                <p className="">14+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 14.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Mission: Impossi..</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary  text-gray-600 mt-4 ">
                                <p className="">Hành Động</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">15+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 8.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">The Moon</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary text-gray-600 mt-4 ">
                                <p className="">Hành động</p>
                                <p className="">IMDB 8.68</p>
                                <p className="">16+</p>
                            </div>
                        </div>


                    </div>
                </div>

                <div className="flex justify-center items-center  mt-[15px]">
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 15.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Meg 2: The Trench</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary  text-gray-600 mt-4 ">
                                <p className="">Hài hước</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 16.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Teenage Mutant..</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5  text-secondary text-gray-600 mt-4 ">
                                <p className="">Hoạt hình</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 9.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Smugglers</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5  text-gray-600 mt-4  text-secondary">
                                <p className="">Phiêu lưu</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 22.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Detective Conan:..</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5 text-secondary text-gray-600 mt-4 ">
                                <p className="">Hoạt Hình</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>


                    </div>
                </div>
                <Link to={'#'} className="underline text-secondary text-gray-600 text-center ml-[720px] mt-[50px] text-xl">Xem thêm</Link>
                
                <h1 className="text-4xl font-bold text-white mt-[70px] text-center ">Sắp chiếu </h1>

                <div className="flex justify-center items-center  mt-[15px]">
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 23.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>
                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Oppenheimer</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary  text-gray-600 mt-4 ">
                                <p className="">Kịch</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 24.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Barbie</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary  text-gray-600 mt-4 ">
                                <p className="">Hài</p>
                                <p className="">IMDB 8.8</p>
                                <p className="">14+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 25.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Mission: Impossi..</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary  text-gray-600 mt-4 ">
                                <p className="">Hành Động</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">15+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 29.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">The Moon</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary text-gray-600 mt-4 ">
                                <p className="">Hành động</p>
                                <p className="">IMDB 8.68</p>
                                <p className="">16+</p>
                            </div>
                        </div>


                    </div>
                </div>

                <div className="flex justify-center items-center  mt-[15px]">
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 30.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Meg 2: The Trench</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary  text-gray-600 mt-4 ">
                                <p className="">Hài hước</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 31.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Teenage Mutant..</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5  text-secondary text-gray-600 mt-4 ">
                                <p className="">Hoạt hình</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 15.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Smugglers</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5  text-gray-600 mt-4  text-secondary">
                                <p className="">Phiêu lưu</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src="image 10.png" alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-2">Detective Conan:..</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5 text-secondary text-gray-600 mt-4 ">
                                <p className="">Hoạt Hình</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>


                    </div>
                </div>
                <Link to={'#'} className="underline text-secondary text-gray-600 text-center ml-[720px] mt-[50px] text-xl">Xem thêm</Link>

                <div className="mt-2 ">
                    <Footer></Footer>
                </div>
            </div>
        </body>
    </>
    )
}
export default Movies

