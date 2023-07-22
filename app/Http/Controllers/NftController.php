<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NftModel;
use App\Models\TransactionHistory;
use App\Models\ActionDescription;
use App\Models\StatusDescription;
use App\Models\WalletModel;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NftController extends Controller
{

    public function mint(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid('meta_img_') . '.' . $extension;
            $file->storeAs('public/uploads', $fileName);

            $name = $request->name;
            $description = $request->description;
            $link = $request->link;
            $assets_url = $request->assets_url;
            $image = $assets_url . $fileName;

            $data = [
                'image' => $image,
                'name' => $name,
                'description' => $description,
                'link' => $link,
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
            ];

            $id = NftModel::insertGetId($data);

            Storage::disk('public')->put('/json/nft_json_' . $id . '.json', json_encode($data));
            return response()->json(['status' => true, 'id' => $id]);

        } else {
            return response()->json(['status' => false], 500);
        }
    }

    public function addHistory(Request $request)
    {
        $data = [
            'token_id' => $request->id,
            'from' => $request->from,
            'action' => $request->action,
            'tx_link' => $request->tx_link,
            'price' => $request->price,
            'paytype' => $request->paytype
        ];

        $txHistory = TransactionHistory::create($data);
        return response()->json($txHistory);
    }

    public function getToken(Request $request, $id)
    {
        return response()->file(Storage::path("/public/json/nft_json_" . $id . ".json"));
    }

    public function getHistories(Request $request)
    {
        $data = TransactionHistory::with('action_description')->get();
        $status_ids = ActionDescription::all()->pluck('id');
        $status_descriptions = ActionDescription::all()->pluck('description');
        $result = [
            'data' => $data,
            'status_ids' => $status_ids,
            'status_descriptions' => $status_descriptions,
        ];
        return response()->json($result);
    }
    public function getHistoryActionLabel(Request $request, $id)
    {
        $label = ActionDescription::find($id)->description;
        return response()->json($label);
    }

    public function getNFTStatusLabel(Request $request, $id)
    {
        $label = StatusDescription::find($id)->description;
        return response()->json($label);
    }

    public function addWalletsAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wallet' => ['required', 'string', 'min:42', 'unique:wallet_tables']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = Auth::user();

        $data = [
            'user_id' => $user->id,
            'wallet' => $request->wallet
        ];
        $wallet = WalletModel::create($data);

        return response()->json($wallet);
    }

    public function getWalletsAddress(Request $request)
    {
        $user = User::find($request->user_id);
        $wallets = User::find($request->user_id)->wallets->pluck('wallet');
        $result = [
            'user' => $user,
            'wallets' => $wallets
        ];
        return response()->json($result);
    }

    public function getUserByAddress(Request $request)
    {
        $user = WalletModel::where('wallet', $request->wallet)->first()->user;
        return response()->json($user);
    }
}