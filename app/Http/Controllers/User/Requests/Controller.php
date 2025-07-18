<?php

namespace App\Http\Controllers\User\Requests;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Parcel\IndexRequest;
use App\Http\Resources\Parcel\IndexRequestResource;
use App\Service\TelegramUserService;

class Controller extends BaseController
{
    public function __construct(
        protected TelegramUserService $tgService,
    )
    {}

    public function index(IndexRequest $request)
    {
        $filter = $request->getFilter();
        $user = $this->tgService->getUserByTelegramId($request);
        $delivery = collect();
        $send = collect();

        if ($filter !== 'delivery') {
            $send = $user->sendRequests->map(function ($item) {
                $item->type = 'send';
                return $item;
            });;
        }

        if ($filter !== 'send') {
            $delivery = $user->deliveryRequests->map(function ($item) {
                $item->type = 'delivery';
                return $item;
            });;
        }
        $requests = $delivery->merge($send)->sortByDesc('created_at')->values();

        return IndexRequestResource::collection($requests);
    }
}
