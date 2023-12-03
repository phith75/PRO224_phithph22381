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
use App\Http\Controllers\Api\UservoucherController;
use App\Http\Controllers\Api\VoucherController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\TicketController;

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

//đăng nhập bằng tk gg
// Route cho việc chuyển hướng đến Google để đăng nhập
// Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->middleware('auth.basic');
// Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->middleware('auth.basic');
//để ở view đăng nhập của gg
// <a href="{{ url('/login/google') }}">Đăng nhập bằng Google</a> dành cho mấy ông fe

Route::get('print-ticket/{ticketId}', [TicketController::class, 'printTicket']);
//////
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('refund_coin/{id}', [QuerryController::class, 'refund_coin']); // Hoàn tiền vào ví coin 70%

    //nhớ chú ý đến token khi login sai là không chạy được hết nhé 
    //nếu lỗi không chạy được thì login  lại và nhập lại token
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/send-book-ticket-details-email', [EmailController::class, 'sendBookTicketDetailsEmail']); // router cho vào sau khi thanh toán
    Route::post('/sendEmail', [EmailController::class, 'sendEmail']); //không cần qtam cái này đừng ai xóa  
    //check khi nhập voucher
    Route::post('/usevoucher', [UservoucherController::class, 'useVoucher']);
});
//////
Route::get('film_cinema/{id}', [QuerryController::class, 'film_cinema']);  // Lấy thông tin phim theo rạp
Route::get('time_detail_get_by_id/{id}', [QuerryController::class, 'time_detail_get_by_id']);
Route::get('check_time_detail_by_film_id/{id_cinema}', [QuerryController::class, 'check_time_detail_by_film_id']); /////
Route::get('chair_count', [QuerryController::class, 'chair_count']);   // Lấy số ghế đã đặt (để tính còn bao nhiêu ghế trống)  
Route::get('categorie_detail_name', [QuerryController::class, 'categorie_detail_name']); // Lấy danh mục của phim (ví dụ: Hành động, Kinh điển)
Route::get('chair_by_time_detail/{id}', [QuerryController::class, 'chair_by_time_detail']);

Route::post('cache_seat', [QuerryController::class, 'cache_seat']); // Thêm, xóa giữ ghế
Route::get('getReservedSeatsByTimeDetail/{id_time_detail}', [QuerryController::class, 'getReservedSeatsByTimeDetail']); // check xem có bao nhiêu ghế đang được giữ

Route::get('purchase_history_ad', [QuerryController::class, 'purchase_history_ad']); // chi tiết vé admin
Route::get('purchase_history_user/{id}', [QuerryController::class, 'purchase_history_user']); // chi tiết vé user

Route::get('QR_book/{id}', [QuerryController::class, 'QR_book_tiket']);
Route::post('Revenue', [QuerryController::class, 'Revenue']);
Route::post('Revenue_cinema', [QuerryController::class, 'Revenue_cinema']);
Route::get('getShiftRevenue/{id}', [QuerryController::class, 'getShiftRevenue']);
Route::get('get_used_vouchers_by_id_user/{id}', [QuerryController::class, 'get_used_vouchers_by_id_user']); // lấy voucher sử dụng r
Route::get('get_room_by_id_cinema/{id}', [QuerryController::class, 'get_room_by_id_cinema']);





///////
Route::post('Payment', [PaymentController::class, 'vnpay_payment']); // thanh toán VNPAY

Route::post('momo_payment', [PaymentController::class, 'momo_payment']); // thanh toán momo

Route::post('post_money', [PaymentController::class, 'post_money']); //napj tien qua momo


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
// <<<<<<< HEAD
// Route::resource('user', UsersController::class);
// //api add vocher
// Route::resource('vocher', VocherController::class);
// =======
Route::resource('voucher', VoucherController::class);
Route::resource('user', UsersController::class);
Route::apiResource('member', MemberController::class);
