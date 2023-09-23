import { createBrowserRouter, RouterProvider } from "react-router-dom";
import LayoutUser from "./Layout/LayoutUser/LayoutUser";
import HomePages from "./pages/Clients/Homepages/home";
import BookingSeat from "./pages/Clients/TICKET - SEAT LAYOUT/seat";
import Cinema from "./pages/Clients/Homepages/Cinema";
import Orther from "./pages/Clients/Homepages/Orther";

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
