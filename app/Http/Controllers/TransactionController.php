<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\TransactionRequest;
use App\Models\Operation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function checkWalletUser(int $recipientId)
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

        //Берем получателя
        $recipient = User::find($recipientId);
        if (!$recipient){
            return $this->failer(['message'=>'Recipient not found']);
        }
        // Берем кошелек пользователя через
        $walletRecipient = $recipient->getWallet;
        if (!$walletRecipient){
            return $this->failer(['message'=>'Wallet not found']);
        }


        return [$user, $wallet, $recipient, $walletRecipient];
    }

    public function transfer(TransactionRequest $request)
    {
        // Проверка формы
        $validatedData = $request->validated();

        // Заполняем переменные
        $user = $this->checkWalletUser($validatedData['recipient_id'])[0];
        $wallet = $this->checkWalletUser($validatedData['recipient_id'])[1];
        $recipient = $this->checkWalletUser($validatedData['recipient_id'])[2];
        $recipientWallet = $this->checkWalletUser($validatedData['recipient_id'])[3];

        // Проверяем возможность перевода
        if ($wallet->balance < $validatedData['amount']){
            return $this->failer(['message'=>'You cannot transfer an amount exceeding your account balance']);
        }

        // Меняем баланс
        $wallet->balance -= $validatedData['amount'];
        $recipientWallet->balance += $validatedData['amount'];
        // Сохранение изменений
        $walletSaved = $wallet->save();
        $recipientWalletSaved = $recipientWallet->save();

        // Проверка успешности сохранения
        if (!$walletSaved || !$recipientWalletSaved) {
            return $this->failer(['message' => 'Transaction failed']);
        }

        // Подготовка описаний операции
        $departure = new Operation(['amount'=>$validatedData['amount'],
                'description'=>"You transferred the ".$validatedData['amount']." to ".$recipient->name. " his number [".$recipient->id."]",
                'type_operation'=>'Transfer']);

        $adoption = new Operation(['amount'=>$validatedData['amount'],
                'description'=>"You get the ".$validatedData['amount']." from ".$user->name. " his number [".$user->id."]",
                'type_operation'=>'Transfer']);

        // Сохраняем оперции для каждого из кошельков
        $resultSaveDepature = $wallet->getOperations()->save($departure);
        $resultSaveAdoption = $recipientWallet->getOperations()->save($adoption);

        // Итоговая проверка
        if ($resultSaveDepature && $resultSaveAdoption){
            return $this->successer(['message'=>'Transaction successful']);
        }else{
            return $this->failer(['message' => 'Transaction failed']);
        }
    }


}
