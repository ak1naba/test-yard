<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\ReplenishRequest;
use App\Http\Resources\Wallet\WalletDefaultResource;
use App\Http\Resources\Wallet\WalletExtendedResource;
use App\Models\Operation;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function checkWalletUser()
    {
        // Берем авторизованного пользователя
        $user = Auth::user();
        // Проверка на существование авторизованного пользователя
        if (!$user){
            return $this->failer(['message'=>'User not found']);
        }

        // Берем кошелек пользователя через
        $wallet = $user->getWallet;
        if (!$wallet){
            return $this->failer(['message'=>'Wallet not found']);
        }

        return [$wallet, $user];
    }
    public function getBalance()
    {
        $wallet = $this->checkWalletUser()[0];

        return $this->successer(["wallet"=>new WalletDefaultResource($wallet)]);

    }

    public function replenishWallet(ReplenishRequest $request)
    {
        // Берем кошелек
        $wallet = $this->checkWalletUser()[0];

        // Валидируем данные
        $validatedData = $request->validated();

        // изменяем баланс
        $wallet->balance += $validatedData['amount'];

        // Проверяем работу сохранения
        if(!$wallet->save()){
            return $this->failer(["message"=>"failed to top up"]);
        }
        // Создаем операцию
        $departure = new Operation(['amount'=>$validatedData['amount'],
            'description'=>"You have replenished your balance ".$validatedData['amount'],
            'type_operation'=>'Transfer']);

        // Проверка на сохранение
        if($wallet->getOperations()->save($departure)){
            return $this->successer(["wallet"=>new WalletDefaultResource($wallet)]);
        } else{
            return $this->failer(["message"=>"failed to save operation, your money on balance"]);
        }

    }

    public function getOperationsWallet()
    {
        $user = $this->checkWalletUser()[1];

        $operations = Wallet::where('user_id', $user->id)
            ->with(['getOperations' => function($query) {
                $query->orderByDesc('created_at');
            }])
            ->get();

        return $this->successer(["history"=>WalletExtendedResource::collection($operations)]);
    }


}
