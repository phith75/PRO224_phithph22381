<?php

namespace App\Http\Controllers\Api;

use App\Models\UsedVoucher;
use App\Models\Vocher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UservoucherController extends Controller
{
    public function useVoucher(Request $request)
    {
        $user = $request->user();
        $voucherCode = $request->input('voucher_code');

        // Kiểm tra xem voucher đã được sử dụng chưa
        $usedVoucher = UsedVoucher::where('user_id', $user->id)
                                  ->where('voucher_code', $voucherCode)
                                  ->first();

        if ($usedVoucher) {
            return response()->json(['message' => 'Voucher đã được sử dụng.'], 403);
        } 

        // Kiểm tra xem voucher có tồn tại hay không
        $voucher = Vocher::where('code', $voucherCode)->first();
        if (!$voucher) {
            return response()->json(['message' => 'Voucher không tồn tại.'], 404);
        }

        // Kiểm tra xem voucher đã hết hạn hay không
        if ($voucher->end_time && now() > $voucher->end_time) {
            return response()->json(['message' => 'Voucher đã hết hạn.'], 403);
        }

        // Kiểm tra xem voucher có trong khoảng thời gian sử dụng hay không
        if ($voucher->start_time && now() < $voucher->start_time) {
            return response()->json(['message' => 'Chưa đến thời gian sử dụng voucher.'], 403);
        }

            // Kiểm tra số lượng sử dụng voucher
            if ($voucher->remaining_limit == 0 && $this->getUsageCount($voucher->code) >= $voucher->remaining_limit) {
                return response()->json(['message' => 'Số lượng người sử dụng voucher đã đạt tới giới hạn.'], 403);
            }
            

        // Sau khi sử dụng voucher, ghi lại vào bảng used_vouchers
        UsedVoucher::create([
            'user_id' => $user->id,
            'voucher_code' => $voucherCode,
            'used_at' => now(),
        ]);

        // Cập nhật số lượng còn lại của voucher
        $voucher->updateRemainingLimit();

        return response()->json(['message' => 'Sử dụng voucher thành công.']);
    }

    public function getUsageCount($voucherCode)
    {
        return Vocher::where('code', $voucherCode)->count();
    }
}
