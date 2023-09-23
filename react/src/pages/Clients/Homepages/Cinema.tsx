import React from 'react'
import { Link } from 'react-router-dom'
import Header from '../../../Layout/LayoutUser/Header'

const Cinema = () => {
  return (
    <div className='bg-zinc-900'>
        <section className="relative bg-[url(https://www.pumzithefilm.com/wp-content/uploads/2023/08/film2.jpg)] bg-cover w-full bg-center bg-no-repeat">
            <Header/>
                <div className="text-center my-10 px-10 h-screen py-[200px]  max-w-screen-xl mx-auto">
                    <h2 className="text-[#FFFFFF] mx-auto text-5xl font-bold">
                    Khám phá rạp chiếu phim của chúng tôi tại thành phố đáng yêu của bạn!
                    </h2>
                    <p className="text-[#FFFFFF] mx-auto px-20 py-10">
                    Trải nghiệm rạp chiếu phim CGV tại thành phố thân yêu của bạn
                    </p>
                </div>
            </section>
        <div>
            <div className='py-[60px] flex justify-center items-center '>
                <span className='text-[#8E8E8E] mr-[20px] '>Tìm Kiếm CGV tại</span>
                <select className='rounded-[40px] h-[40px] pl-[15px] bg-[#EE2E24] text-white hover: bg-red-600 ' name="" id="">
                    <option className='w-[150px] ' value="">Hà Nội</option>
                    <option className='' value="">Đà Nẵng</option>
                    <option className='' value="">Thành Phố Hồ Chí Minh</option>
                </select>
            </div>
            <div className="grid grid-cols-2 gap-4 justify-center w-[1050px] mx-auto">
                <div className="w-[516px]">
                    <Link to={``} className="block group ">
                        <img
                            src="https://congthuong-cdn.mastercms.vn/stores/news_dataimages/nguyentam/122020/11/14/in_article/2038_image001.jpg?rt=20201211142039"
                            alt=""
                            className="object-cover w-[540px] h-[340px] rounded-[10px] aspect-square"
                        />

                        <div className="my-2 flex justify-between">
                            <h3
                                className="font-bold text-[20px] text-white"
                            >
                                Poins Mall
                            </h3>

                            <p className="mt-1 text-[16px] text-[#8E8E8E] font-[400] ">cách đây 3 km</p>
                        </div>
                        <Link
                            className="w-full inline-block rounded-[12px] border border-[#EE2E24] bg-[#EE2E24] px-12 py-3 text-sm font-medium text-white hover:bg-red-700 hover:text-white-600 focus:outline-none focus:ring active:text-white-500"
                            to="/download"
                        >
                            <span className='pl-[150px] '>Xem Lịch Chiếu</span>
                        </Link>
                    </Link>
                </div>
                <div className="w-[516px]">
                    <Link to={``} className="block group ">
                        <img
                            src="https://congthuong-cdn.mastercms.vn/stores/news_dataimages/nguyentam/122020/11/14/in_article/2038_image001.jpg?rt=20201211142039"
                            alt=""
                            className="object-cover w-[540px] h-[340px] rounded-[10px] aspect-square"
                        />

                        <div className="my-2 flex justify-between">
                            <h3
                                className="font-bold text-[20px] text-white"
                            >
                                Poins Mall
                            </h3>

                            <p className="mt-1 text-[16px] text-[#8E8E8E] font-[400] ">cách đây 3 km</p>
                        </div>
                        <Link
                            className="w-full inline-block rounded-[12px] border border-[#EE2E24] bg-[#EE2E24] px-12 py-3 text-sm font-medium text-white hover:bg-red-700 hover:text-white-600 focus:outline-none focus:ring active:text-white-500"
                            to="/download"
                        >
                            <span className='pl-[150px] '>Xem Lịch Chiếu</span>
                        </Link>
                    </Link>
                </div>
                <div className="w-[516px]">
                    <Link to={``} className="block group ">
                        <img
                            src="https://congthuong-cdn.mastercms.vn/stores/news_dataimages/nguyentam/122020/11/14/in_article/2038_image001.jpg?rt=20201211142039"
                            alt=""
                            className="object-cover w-[540px] h-[340px] rounded-[10px] aspect-square"
                        />

                        <div className="my-2 flex justify-between">
                            <h3
                                className="font-bold text-[20px] text-white"
                            >
                                Poins Mall
                            </h3>

                            <p className="mt-1 text-[16px] text-[#8E8E8E] font-[400] ">cách đây 3 km</p>
                        </div>
                        <Link
                            className="w-full inline-block rounded-[12px] border border-[#EE2E24] bg-[#EE2E24] px-12 py-3 text-sm font-medium text-white hover:bg-red-700 hover:text-white-600 focus:outline-none focus:ring active:text-white-500"
                            to="/download"
                        >
                            <span className='pl-[150px] '>Xem Lịch Chiếu</span>
                        </Link>
                    </Link>
                </div>
                <div className="w-[516px]">
                    <Link to={``} className="block group ">
                        <img
                            src="https://congthuong-cdn.mastercms.vn/stores/news_dataimages/nguyentam/122020/11/14/in_article/2038_image001.jpg?rt=20201211142039"
                            alt=""
                            className="object-cover w-[540px] h-[340px] rounded-[10px] aspect-square"
                        />

                        <div className="my-2 flex justify-between">
                            <h3
                                className="font-bold text-[20px] text-white"
                            >
                                Poins Mall
                            </h3>

                            <p className="mt-1 text-[16px] text-[#8E8E8E] font-[400] ">cách đây 3 km</p>
                        </div>
                        <Link
                            className="w-full inline-block rounded-[12px] border border-[#EE2E24] bg-[#EE2E24] px-12 py-3 text-sm font-medium text-white hover:bg-red-700 hover:text-white-600 focus:outline-none focus:ring active:text-white-500"
                            to="/download"
                        >
                           <span className='pl-[150px] '>Xem Lịch Chiếu</span>
                        </Link>
                    </Link>
                </div>
                <div className="w-[516px]">
                    <Link to={``} className="block group ">
                        <img
                            src="https://congthuong-cdn.mastercms.vn/stores/news_dataimages/nguyentam/122020/11/14/in_article/2038_image001.jpg?rt=20201211142039"
                            alt=""
                            className="object-cover w-[540px] h-[340px] rounded-[10px] aspect-square"
                        />

                        <div className="my-2 flex justify-between">
                            <h3
                                className="font-bold text-[20px] text-white"
                            >
                                Poins Mall
                            </h3>

                            <p className="mt-1 text-[16px] text-[#8E8E8E] font-[400] ">cách đây 3 km</p>
                        </div>
                        <Link
                            className="w-full inline-block rounded-[12px] border border-[#EE2E24] bg-[#EE2E24] px-12 py-3 text-sm font-medium text-white hover:bg-red-700 hover:text-white-600 focus:outline-none focus:ring active:text-white-500"
                            to="/download"
                        >
                           <span className='pl-[150px] '>Xem Lịch Chiếu</span>
                        </Link>
                    </Link>
                </div>
                <div className="w-[516px]">
                    <Link to={``} className="block group ">
                        <img
                            src="https://congthuong-cdn.mastercms.vn/stores/news_dataimages/nguyentam/122020/11/14/in_article/2038_image001.jpg?rt=20201211142039"
                            alt=""
                            className="object-cover w-[540px] h-[340px] rounded-[10px] aspect-square"
                        />

                        <div className="my-2 flex justify-between">
                            <h3
                                className="font-bold text-[20px] text-white"
                            >
                                Poins Mall
                            </h3>

                            <p className="mt-1 text-[16px] text-[#8E8E8E] font-[400] ">cách đây 3 km</p>
                        </div>
                        <Link
                            className="w-full inline-block rounded-[12px] border border-[#EE2E24] bg-[#EE2E24] px-12 py-3 text-sm font-medium text-white hover:bg-red-700 hover:text-white-600 focus:outline-none focus:ring active:text-white-500"
                            to="/download"
                        >
                            <span className='pl-[150px] '>Xem Lịch Chiếu</span>
                        </Link>
                    </Link>
                </div>
            </div>
            <p className="flex justify-center items-center text-[16px] text-[#8E8E8E] font-[400] underline py-[40px] ">Xem thêm</p>
        </div>
    </div>
  )
}

export default Cinema
