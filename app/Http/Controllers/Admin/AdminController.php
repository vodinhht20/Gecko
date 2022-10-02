<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogOrderPay;
use App\Models\RechargePack;
use App\Repositories\ReChargePackRepository;
use App\Repositories\userRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Alert;
use App\Repositories\LogOrderRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    const TAKE = 8;

    public function __construct(
        private userRepository $userRepo,
        private ReChargePackRepository $rechargeRepo,
        private LogOrderRepository $logOrderRepo,
    ) {
    }
    public function dashboard(Request $request)
    {
        return view('cpanel.dashboard');
    }
    public function historyPay(Request $request)
    {
        $user_id = Auth::user()->id;
        $historyOrders = $this->logOrderRepo->getHistoryOrder($user_id);
        return view('cpanel.historypay', compact('historyOrders'));
    }
    public function wallet()
    {
        $surplus = $this->userRepo->surplus(Auth::user()->id);
        return view('cpanel.wallet', compact('surplus'));
    }
    public function rechargePack(Request $request)
    {
        $reCharges = $this->rechargeRepo->getRechargePack();
        return view('cpanel.rechargepack', compact('reCharges'));
    }
    public function payForm(Request $request)
    {
        if ($request->input('key')) {
            $key = RechargePack::decodeRecharge($request->input('key'));
            $reCharge = $this->rechargeRepo->find($key);
            return view('cpanel.pay', compact('reCharge'));
        }
    }
    public function createTransaction(Request $request)
    {
        $type = $request->input('type');
        $key = $request->input('key');
        if ($type && $key) {
            $id = RechargePack::decodeRecharge($key);
            $reCharge = $this->rechargeRepo->find($id);
            //check 5 cancel pay a day
            $checkCancel = LogOrderPay::where('user_id', Auth::user()->id)->where('status', 3)->get();
            $checkCancelDate = 0;
            foreach ($checkCancel as $item) {
                if (Carbon::parse($item['created_at'])->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                    ++$checkCancelDate;
                }
            }
            if ($checkCancelDate >= 5) {
                Alert::warning('Error', 'Bạn đã hủy quá 5 giao dịch 1 ngày vui lòng quay lại vào ngày mai');
                return redirect()->route('admin.wallet');
            }
            $logOrder = LogOrderPay::where('user_id', Auth::user()->id)->where('status', 0)->first();
            if ($reCharge) {
                if ($type == 1) {
                    $KeyOrder = "PM" . Str::random(8) . trim($key, '=');

                    if ($logOrder) {
                        $idcancel = $logOrder->id;
                        $KeyOrder = $logOrder->code;
                        $reCharge = $this->rechargeRepo->find($logOrder->rechange_pack_id);
                        Alert::warning('Giao dịch đang chờ: ' . $reCharge->title, 'Vui lòng hoàn tất giao dịch đã tạo trước đó hoặc chọn hủy giao dịch để tạo giao dịch mới');
                    } else {
                        DB::beginTransaction();
                        try {
                            // $KeyOrder
                            $validator = Validator::make(['code' => $KeyOrder], [
                                'code' => 'required|unique:log_order_pay',
                            ]);
                            if ($validator->fails()) {
                                $KeyOrder = "ER" . Str::random(8) . uniqid(5) . trim($key, '=');
                                $validator = Validator::make(['code' => $KeyOrder], [
                                    'code' => 'required|unique:log_order_pay',
                                ]);

                                if ($validator->fails()) {
                                    Alert::warning('Error', 'Có lỗi xảy ra vui lòng thử lại');
                                    return redirect()->route('admin.rechargePack');
                                }
                            }
                            $LogOrderPay = new LogOrderPay();
                            $LogOrderPay->user_id = Auth::user()->id;
                            $LogOrderPay->rechange_pack_id = $id;
                            $LogOrderPay->code = $KeyOrder;
                            $LogOrderPay->status = 0;
                            $LogOrderPay->type_pay = $id;
                            $LogOrderPay->save();
                            $idcancel = $LogOrderPay->id;
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            $message = '[' . date('Y-m-d H:i:s') . '] Error message \'' . $e->getMessage() . '\'' . ' in ' . $e->getFile() . ' line ' . $e->getLine();
                            Log::error($message);
                            Alert::warning('Error', $message);
                            return redirect()->route('admin.wallet');
                        }
                    }
                    return view('cpanel.directtransfer', compact('KeyOrder', 'reCharge', 'idcancel'));
                }
                return 2;
            }
        }
        return redirect()->route('admin.rechargePack');
    }

    public function cancelPay(Request $request)
    {
        $LogOrderPay = LogOrderPay::find($request->id);

        if ($LogOrderPay->status != 0) {
            return response()->json([
                "status" => false,
                "message" => "Không thể hủy giao dịch này!"
            ], 403);
        }

        if ($LogOrderPay->user_id != Auth::user()->id) {
            return response()->json([
                "status" => false,
                "message" => "Bạn không phải chủ của giao dịch này!"
            ], 403);
        }

        $LogOrderPay->status = 3;
        $result = $LogOrderPay->save();
        Log::info('cancel Log Order', array($result));

        if ($result) {
            return response()->json([
                "status" => true,
                "message" => "Hủy giao dịch mã: $LogOrderPay->code thành công!"
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Lỗi vui lòng liên hệ quản trị viên để hỗ trợ!"
        ], 403);
    }

    public function sendTransaction(Request $request)
    {
        $LogOrderPay = LogOrderPay::find($request->id);

        if ($LogOrderPay->user_id != Auth::user()->id) {
            return response()->json([
                "status" => false,
                "message" => "Bạn không phải chủ của giao dịch này!"
            ], 403);
        }

        $LogOrderPay->status = 1;
        $result = $LogOrderPay->save();
        Log::notice('Có đơn cần xác nhận: ' . $LogOrderPay->code);

        if ($result) {
            return response()->json([
                "status" => true,
                "message" => "Bộ phận kiểm duyệt của chúng tôi sẽ tiến hành kiểm tra sau khi hoàn tất kiểm tra tiền sẽ được cộng vào tài khoản của bạn. <br> có thể mất từ <span class='swal-value'>5 phút</span> đến <span class='swal-value'>3 giờ</span> để thanh toán được ghi nhận vui lòng kiên nhẫn xin cảm ơn <br> Mã giao dịch: <span class='swal-value'>$LogOrderPay->code</span>"
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Lỗi vui lòng liên hệ quản trị viên để hỗ trợ!"
        ], 403);
    }

    public function listPay(Request $request)
    {
        $listPays = json_encode($this->logOrderRepo->getHistoryOrder());
        return view('cpanel.listpay', compact('listPays'));
    }

    public function dataReponse(Request $request)
    {
        $options = [
            ...$request->all(),
            "with" => ['recharge_pack:id,value'],
            "with" => ['users:id,name'],
        ];
        $listPays = $this->logOrderRepo
            ->paginate($options, self::TAKE)
            ->appends($request->query());
        $this->logOrderRepo->formatData($listPays);
        return $listPays;

        return response()->json([
            'data' => $listPays
        ]);
    }

    public function confirmationAdmin(Request $request){
        $attributes = [];
        if (isset($request->status)) {
            $attributes['status'] = $request->status;
        }
        $result = $this->logOrderRepo->confirmedPay($request->id, $attributes);

        if ($result) {
            return response()->json([
                "status" => true,
                "message" => "Cập nhật thành công!"
            ]);
        }

        return response()->json([
            "status" => false,
            "message" => "Cập nhật thất bại!"
        ], 403);
    }
}
