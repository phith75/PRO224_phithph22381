<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Categories;
use App\Models\MovieRoom;
use App\Models\Cinemas;
use App\Models\Food;
use App\Models\Blogs;




class Delete_atController extends Controller
{
    //film
    public function trash_film()
    {
        $films = Film::onlyTrashed()->get();
        return response()->json($films);
    }

    public function restore_film($id)
    {
        $film = Film::withTrashed()->find($id);

        if (!$film) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy film.'], 404);
        }

        // Khôi phục bản ghi đã xóa mềm
        $film->restore();

        return response()->json(['message' => 'Film đã được khôi phục']);
    }
    //xóa cứng 
    public function hardDelete_film($id)
    {
        $film = Film::withTrashed()->find($id);

        if (!$film) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy film.'], 404);
        }

        // Xóa cứng bản ghi
        $film->forceDelete();
        return response()->json(['message' => 'Film đã được xóa']);
    }

    // category

    public function trash_category()
    {
        $Categories = Categories::onlyTrashed()->get();
        return response()->json($Categories);
    }

    public function restore_category($id)
    {
        $Categories = Categories::withTrashed()->find($id);

        if (!$Categories) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy Categories.'], 404);
        }

        // Khôi phục bản ghi đã xóa mềm
        $Categories->restore();

        return response()->json(['message' => 'Categories đã được khôi phục']);
    }
    //xóa cứng 
    public function hardDelete_category($id)
    {
        $Categories = Categories::withTrashed()->find($id);

        if (!$Categories) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy Categories.'], 404);
        }

        // Xóa cứng bản ghi
        $Categories->forceDelete();
        return response()->json(['message' => 'Categories đã được xóa']);
    }

    //MovieRoom
    public function trash_MovieRoom()
    {
        $MovieRoom = MovieRoom::onlyTrashed()->get();
        return response()->json($MovieRoom);
    }

    public function restore_MovieRoom($id)
    {
        $MovieRoom = MovieRoom::withTrashed()->find($id);

        if (!$MovieRoom) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy MovieRoom.'], 404);
        }

        // Khôi phục bản ghi đã xóa mềm
        $MovieRoom->restore();

        return response()->json(['message' => 'MovieRoom đã được khôi phục']);
    }
    //xóa cứng 
    public function hardDelete_MovieRoom($id)
    {
        $MovieRoom = MovieRoom::withTrashed()->find($id);

        if (!$MovieRoom) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy MovieRoom.'], 404);
        }

        // Xóa cứng bản ghi
        $MovieRoom->forceDelete();
        return response()->json(['message' => 'MovieRoom đã được xóa']);
    }
    //Cinemas
    public function trash_Cinemas()
    {
        $Cinemas = Cinemas::onlyTrashed()->get();
        return response()->json($Cinemas);
    }

    public function restore_Cinemas($id)
    {
        $Cinemas = Cinemas::withTrashed()->find($id);

        if (!$Cinemas) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy Cinemas.'], 404);
        }

        // Khôi phục bản ghi đã xóa mềm
        $Cinemas->restore();

        return response()->json(['message' => 'Cinemas đã được khôi phục']);
    }
    //xóa cứng 
    public function hardDelete_Cinemas($id)
    {
        $Cinemas = Cinemas::withTrashed()->find($id);

        if (!$Cinemas) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy Cinemas.'], 404);
        }

        // Xóa cứng bản ghi
        $Cinemas->forceDelete();
        return response()->json(['message' => 'Cinemas đã được xóa']);
    }
    //food
    public function trash_Food()
    {
        $Food = Food::onlyTrashed()->get();
        return response()->json($Food);
    }

    public function restore_Food($id)
    {
        $Food = Food::withTrashed()->find($id);

        if (!$Food) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy Food.'], 404);
        }

        // Khôi phục bản ghi đã xóa mềm
        $Food->restore();

        return response()->json(['message' => 'Food đã được khôi phục']);
    }
    //xóa cứng 
    public function hardDelete_Food($id)
    {
        $Food = Food::withTrashed()->find($id);

        if (!$Food) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy Food.'], 404);
        }

        // Xóa cứng bản ghi
        $Food->forceDelete();
        return response()->json(['message' => 'Food đã được xóa']);
    }
    //Blog
    public function trash_Blogs()
    {
        $Blogs = Blogs::onlyTrashed()->get();
        return response()->json($Blogs);
    }

    public function restore_Blogs($id)
    {
        $Blogs = Blogs::withTrashed()->find($id);

        if (!$Blogs) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy Blogs.'], 404);
        }

        // Khôi phục bản ghi đã xóa mềm
        $Blogs->restore();

        return response()->json(['message' => 'Blogs đã được khôi phục']);
    }
    //xóa cứng 
    public function hardDelete_Blogs($id)
    {
        $Blogs = Blogs::withTrashed()->find($id);

        if (!$Blogs) {
            return response()->json(['error_code' => 404, 'message' => 'Mã lỗi 404: Không tìm thấy Blogs.'], 404);
        }

        // Xóa cứng bản ghi
        $Blogs->forceDelete();
        return response()->json(['message' => 'Blogs đã được xóa']);
    }
}
