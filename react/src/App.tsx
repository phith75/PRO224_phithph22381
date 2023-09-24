import { createBrowserRouter, RouterProvider } from "react-router-dom";
import LayoutUser from "./Layout/LayoutUser/LayoutUser";
import HomePages from "./pages/Clients/Homepages/home";
import BookingSeat from "./pages/Clients/TICKET - SEAT LAYOUT/seat";
import Cinema from "./pages/Clients/Cinema/Cinema";
import Orther from "./pages/Clients/Orther/Orther";
import Movie_About from "./pages/Clients/MOVIE-ABOUT/Movie-About";
import Ticket from "./pages/Clients/TICKET/Ticket";
import Movies from "./pages/Clients/Movies/Movies";
import F_B from "./pages/Clients/F&B/F&B";

function App() {
    const router = createBrowserRouter([
        {
            path: "/",
            element: <LayoutUser />,
            children: [
                {
                    path: "/",
                    element: <HomePages />,
                },
                {
                    path: "/book-ticket",
                    element: <BookingSeat />,
                },
                {
                    path: " ",
                    element: <Movie_About />,
                },
                {
                    path: "/ticket",
                    element: <Ticket />,
                },
                {
                    path: "/movies",
                    element: <Movies />,
                },
                {
                    path: "/F&B",
                    element: <F_B />,
                },
                {
                    path: "/cinema",
                    element: <Cinema />,
                },
                {
                    path: "/orther",
                    element: <Orther />,
                },
            ],
        },
    ]);
    return <RouterProvider router={router} />;
}

export default App;
