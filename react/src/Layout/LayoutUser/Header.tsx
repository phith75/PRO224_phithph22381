import { Link } from "react-router-dom";

const Header = () => {
    return (
        <header className="max-w-5xl mx-auto px-10">
            <div className="flex justify-between text-[18px]  items-center py-8 text-[#8E8E8E]">
                <Link to={"/"}>
                    <img srcSet="/logo.png/" alt="" />
                </Link>
                <Link to={"#"} className="text-[#EE2E24] hover:text-[#EE2E24]">
                    Home
                </Link>
                <Link to={"#"} className="hover:text-[#EE2E24]">
                    Movie
                </Link>
                <Link to={"/book-ticket"} className="hover:text-[#EE2E24]">
                    Ticket
                </Link>
                <Link to={"#"} className="hover:text-[#EE2E24]">
                    F&B
                </Link>
                <Link to={"/cinema"} className="hover:text-[#EE2E24]">
                    Cinema
                </Link>
                <Link to={"/orther"} className="hover:text-[#EE2E24]">
                    Other
                </Link>
                <Link to={"#"}>
                    <img srcSet="/person-circle.png/ 1.2x" alt="" />
                </Link>
            </div>
            <div className="flex items-center my-2">
                <div className="relative w-full">
                    <label htmlFor="Search" className="sr-only">
                        {" "}
                        Search{" "}
                    </label>

                    <input
                        type="text"
                        className="bg-transparent border-2 w-full text-[#FFFFFF] border-gray-300 rounded-full pl-8 pr-4 py-3 focus:outline-none focus:border-blue-500"
                        placeholder="Tìm kiếm..."
                    />

                    <span className="absolute inset-y-0 end-0 grid w-10 place-content-center">
                        <button
                            type="button"
                            className="text-gray-600 hover:text-gray-700"
                        >
                            <span className="sr-only">Search</span>

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                strokeWidth="2.5"
                                stroke="#8E8E8E"
                                className="h-4 w-4"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                                />
                            </svg>
                        </button>
                    </span>
                </div>
                <div className="mx-4">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        fill="#FFFFFF"
                        className="bi bi-filter"
                        viewBox="0 0 16 16"
                    >
                        <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                    </svg>
                </div>
            </div>
        </header>
    );
};

export default Header;
