import React from 'react'
import img1 from '../../../img/image 12.png'
import img10 from '../../../img/image 21.png'
import imgvector from '../../../../public/Vector (Stroke).png'
import Header from '../../../Layout/LayoutUser/Header'
import Footer from '../../../Layout/LayoutUser/Footer'
import { Link } from 'react-router-dom'


const Ticket = () => {


    return (
        <div className="h-[2658px] text-center mt-[16px] bg-primary">
            <header className=" text-secondary p-4 relative bg-[url(/ticket.png/)] bg-cover w-full bg-center bg-no-repeat">
                <Header />

                <h1 className="text-4xl font-bold text-white mt-[200px]">Đặt vé chưa bao giờ <br />thật dễ dàng!</h1>

                <p className="text-center text-white mt-[16px]">
                    Mở khóa liền mạch thế giới phim ảnh! Hãy đặt vé xem phim CGV Cinema một cách tuyệt đối <br />
                    dễ dàng thông qua trang web thân thiện với người dùng của chúng tôi. Hành trình điện ảnh của bạn chỉ cách đó vài cú nhấp chuột!
                </p>

                <div className="flex justify-center items-center mt-[50px]">
                    <Link
                        to={"#"}
                        className="px-10 rounded-3xl bg-red-600  py-3  font-medium text-white shadow hover:bg-red-700 focus:outline-none  active:bg-red-500 sm:w-auto"
                    >
                        Sách theo phim
                    </Link>
                    <Link to={'#'}>
                        <div className="px-8 border-tertiary text-white border p-2 rounded-[48px] ml-4 text-gray">Sách theo rạp chiếu phima</div>
                    </Link>
                </div>

            </header>
            <body>
                <div className='flex justify-center items-center  mt-[100px] '>
                    <div className="text-secondary">
                        Tôi muốn xem một bộ phim ở
                    </div>
                    <div className='flex justify-center items-center w-300 h-200 bg-tertiary text-white p-2 rounded-[48px]  ml-[30px]  '>
                        <div >Jakarta</div>
                        <div className=" ml-[30px]">~</div>
                    </div>

                </div>

                <h1 className="text-4xl font-bold text-white mt-[50px]">Sách theo phim</h1>

                <div className="flex justify-center items-center  mt-[15px]">
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src={img1} alt="Image 1" className="w-full h-full rounded-lg" /></Link>
                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Oppenheimer</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary">
                                <p className="">Kịch</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>
                        <Link to={'#'}> <div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>

                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src={img1} alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Oppenheimer</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary">
                                <p className="">Kịch</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>
                        <Link to={'#'}> <div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src={img1} alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Oppenheimer</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary">
                                <p className="">Kịch</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>
                        <Link to={'#'}> <div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src={img1} alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Oppenheimer</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary">
                                <p className="">Kịch</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>
                        <Link to={'#'}> <div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>


                    </div>
                </div>

                <div className="flex justify-center items-center  mt-[15px]">
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src={img1} alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Oppenheimer</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary">
                                <p className="">Kịch</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>
                        <Link to={'#'}> <div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src={img1} alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Oppenheimer</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5  text-secondary">
                                <p className="">Kịch</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>
                        <Link to={'#'}> <div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src={img1} alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Oppenheimer</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary">
                                <p className="">Kịch</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>
                        <Link to={'#'}> <div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>


                    </div>
                    <div className="w-294 h-448 rounded-lg  p-4 m-4">
                        <Link to={'#'}> <img width={302} height={454} src={img1} alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                        <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Oppenheimer</h2>

                        <div className="">
                            <div className="flex space-x-5 h-[21px] font-poppins font-normal text-base leading-5     text-secondary">
                                <p className="">Kịch</p>
                                <p className="">IMDB 8.6</p>
                                <p className="">13+</p>
                            </div>
                        </div>
                        <Link to={'#'}> <div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>


                    </div>
                </div>


                <p className="underline text-secondary mt-[30px]">Xem thêm</p>

                <div className='items-center mt-[50px]  bg-primary'>
                    <h1 className="text-4xl font-bold text-white">Sách theo phim</h1>

                    <div className="flex justify-center items-center  mt-[15px]">
                        <div className="w-516 h-468 rounded-lg  p-4 m-4">
                            <Link to={'#'}>  <img width={302} height={454} src={img10} alt="Image 1" className="w-full h-full rounded-lg" /></Link>
                            <div className="flex">
                                <div>
                                    <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Poins Mall</h2>
                                </div>
                                <div className=" text-secondary ml-[400px]">
                                    <p className="">3 km away</p>
                                </div>
                            </div>
                            <Link to={'#'}><div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>


                        </div>
                        <div className="w-516 h-468 rounded-lg  p-4 m-4">
                            <Link to={'#'}>  <img width={302} height={454} src={img10} alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                            <div className="flex">
                                <div>
                                    <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Poins Mall</h2>
                                </div>
                                <div className=" text-secondary ml-[400px]">
                                    <p className="">3 km away</p>
                                </div>
                            </div>
                            <Link to={'#'}><div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>


                        </div>
                    </div>
                    <div className="flex justify-center items-center  mt-[15px]">
                        <div className="w-516 h-468 rounded-lg  p-4 m-4">
                            <Link to={'#'}>  <img width={302} height={454} src={img10} alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                            <div className="flex">
                                <div>
                                    <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Poins Mall</h2>
                                </div>
                                <div className=" text-secondary ml-[400px]">
                                    <p className="">3 km away</p>
                                </div>
                            </div>
                            <Link to={'#'}><div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>

                        </div>  <div className="w-516 h-468 rounded-lg  p-4 m-4">
                            <Link to={'#'}>  <img width={302} height={454} src={img10} alt="Image 1" className="w-full h-full rounded-lg" /></Link>

                            <div className="flex">
                                <div>
                                    <h2 className="text-xl font-poppins font-bold text-[31px] leading-[37.2px] text-white text-left mt-0">Poins Mall</h2>
                                </div>
                                <div className=" text-secondary ml-[400px]">
                                    <p className="">3 km away</p>
                                </div>
                            </div>
                            <Link to={'#'}><div className="w-300 h-200 bg-tertiary text-white p-2 rounded-md mt-[15px]">Book by Movie</div></Link>


                        </div>

                    </div>
                    <p className="underline text-secondary mt-[30px]">Xem thêm</p>
                    <Footer />
                </div>


            </body>




        </div>
    );
}

export default Ticket