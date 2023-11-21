<?php

use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BlogsController;
use App\Http\Controllers\Api\Food_ticket_detailController;
use App\Http\Controllers\Api\Book_ticketController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CategoryDetailController;
use App\Http\Controllers\Api\ChairsController;
use App\Http\Controllers\Api\CinemasController;
use App\Http\Controllers\Api\Contact_infosController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\FilmController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\MovieRoomController;
use App\Http\Controllers\Api\TimeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Banner;
use App\Models\Blogs;
use App\Models\Fook_ticket_detail;
use App\Http\Controllers\Api\PassportAuthController;
use App\Http\Controllers\Api\QuerryController;
use App\Http\Controllers\Api\Time_detailController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\authController;
use App\Models\FilmMaker;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\RateStarController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Api\ForgotPasswordController;

/*u
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//quên mật khẩu
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
//nhớ nhập lại mật khẩu password_confirmation
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);
//đăng kí
Route::post('/signup', [authController::class, 'sign_up']);
Route::post('/login', [AuthController::class, 'login']);
//////
Route::group(['middleware' => ['auth:sanctum']], function () {
    //nhớ chú ý đến token khi login sai là không chạy được hết nhé 
    //nếu lỗi không chạy được thì login  lại và nhập lại token
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/send-book-ticket-details-email', [EmailController::class, 'sendBookTicketDetailsEmail']);
    Route::post('/sendEmail', [EmailController::class, 'sendEmail']); //không cần qtam cái này đừng ai xóa  
});
//////
Route::get('film_cinema/{id}', [QuerryController::class, 'film_cinema']);  // Lấy thông tin phim theo rạp
Route::get('movie_rooms/{id_cinema}/{date}/{filmId}', [QuerryController::class, 'movie_rooms']); // Lấy thông tin xuất chiếu của phim theo ngày và theo rạp
Route::get('chair_status/{id}', [QuerryController::class, 'chair_status']); // Lấy thông tin ghế đã đặt
Route::get('chair_count/{id}', [QuerryController::class, 'chair_count']);   // Lấy số ghế đã đặt (để tính còn bao nhiêu ghế trống)  
Route::get('categorie_detail_name/{id}', [QuerryController::class, 'categorie_detail_name']); // Lấy danh mục của phim (ví dụ: Hành động, Kinh điển)
Route::post('cache_seat', [QuerryController::class, 'cache_seat']);
Route::get('getReservedSeatsByTimeDetail/{id_time_detail}', [QuerryController::class, 'getReservedSeatsByTimeDetail']);
///////
Route::get('Payment', [PaymentController::class, 'vnpay_payment']); // thanh toán VNPAY
Route::post('momo_payment', [PaymentController::class, 'momo_payment']); // thanh toán momo
///////

Route::apiResource('Chairs', ChairsController::class);
Route::apiResource('Cinemas', CinemasController::class);
Route::apiResource('Category', CategoryController::class);
Route::apiResource('Banner', BannerController::class);
Route::apiResource('Blogs', BlogsController::class);
Route::apiResource('Food_ticket_detail', Food_ticket_detailController::class);
Route::apiResource('Book_ticket', Book_ticketController::class);
Route::apiResource('Contact', Contact_infosController::class);
Route::apiResource('FeedBack', FeedbackController::class);
Route::apiResource('Food', FoodController::class);

Route::resource('time', TimeController::class);
Route::resource('time_detail', Time_detailController::class); // crud cái này 
Route::resource('category_detail', CategoryDetailController::class); // cái này nx
Route::resource('filmMaker', FilmMakerController::class);
Route::resource('movieRoom', MovieRoomController::class);
Route::resource('rateStar', RateStarController::class);
Route::resource('film', FilmController::class);
Route::resource('users', UsersController::class);