import { createBrowserRouter, RouterProvider } from "react-router-dom";
import LayoutUser from "./Layout/LayoutUser/LayoutUser";
import HomePages from "./pages/Clients/Homepages/home";
import BookingSeat from "./pages/Clients/TICKET - SEAT LAYOUT/seat";
import Movies from "./pages/Clients/Movies/Movies";
import F_B from "./pages/F&B/F&B";

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
                    path: "/movies",
                    element: <Movies />,
                },
                {
                    path: "/F&B",
                    element: <F_B />,
                },
            ],
        },
    ]);
    return <RouterProvider router={router} />;
}

export default App;
