<?php

declare(strict_types=1);

namespace Sashalenz\DeliveryAuto\ApiModels\Cabinet;

use Sashalenz\DeliveryAuto\ApiModels\BaseModel;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData\GetFullReceiptInformationRequest;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData\GetUserInfoRequest;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData\GetUserPickUpRequest;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData\GetUserReceiptRequest;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData\PostDeactivateReceiptsRequest;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\RequestData\PostLoginRequest;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData\FullReceiptInformationData;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData\UserInfoData;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData\UserPickUpData;
use Sashalenz\DeliveryAuto\ApiModels\Cabinet\ResponseData\UserReceiptData;
use Sashalenz\DeliveryAuto\Exceptions\DeliveryAutoException;
use Sashalenz\DeliveryAuto\SessionStore;
use Spatie\LaravelData\DataCollection;

/**
 * §5 Особистий кабінет + §6.14 — login-session auth flow.
 *
 * Auth: PostLogin sets a session cookie via Set-Cookie; subsequent calls reuse
 * it through SessionStore. Tools shipping with config DELIVERY_API_USERNAME +
 * DELIVERY_API_PASSWORD can call ->loginFromConfig() once before issuing other
 * requests; otherwise pass a PostLoginRequest explicitly.
 *
 * The session is process-local. Web apps with concurrent users should each
 * own their own login flow per request — there is no per-user multiplexing
 * here, only a single global jar.
 */
final class Cabinet extends BaseModel
{
    /**
     * @throws DeliveryAutoException
     */
    public function postLogin(PostLoginRequest $request): bool
    {
        $this
            ->reset()
            ->method('PostLogin')
            ->params($request)
            ->post()
            ->withSession()
            ->get();

        return true;
    }

    /**
     * Convenience: log in using credentials from config('delivery-auto-api.username')
     * + config('delivery-auto-api.password'). Returns false (no exception) if creds
     * are not configured, so callers can branch cleanly.
     *
     * @throws DeliveryAutoException
     */
    public function loginFromConfig(): bool
    {
        $username = config('delivery-auto-api.username');
        $password = config('delivery-auto-api.password');

        if (! is_string($username) || $username === '' || ! is_string($password) || $password === '') {
            return false;
        }

        return $this->postLogin(new PostLoginRequest(
            UserName: $username,
            Password: $password,
        ));
    }

    /**
     * @throws DeliveryAutoException
     */
    public function postLogoff(): bool
    {
        $this
            ->reset()
            ->method('PostLogoff')
            ->post()
            ->withSession()
            ->get();

        SessionStore::clear();

        return true;
    }

    /**
     * @throws DeliveryAutoException
     */
    public function getUserInfo(?GetUserInfoRequest $request = null): ?UserInfoData
    {
        /** @var UserInfoData|null */
        return $this
            ->reset()
            ->method('GetUserInfo')
            ->params($request ?? new GetUserInfoRequest)
            ->withSession()
            ->toData(UserInfoData::class);
    }

    /**
     * @return DataCollection<int, UserReceiptData>
     *
     * @throws DeliveryAutoException
     */
    public function getUserReceipt(GetUserReceiptRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetUserReceipt')
            ->params($request)
            ->withSession()
            ->toCollection(UserReceiptData::class);
    }

    /**
     * @return DataCollection<int, UserPickUpData>
     *
     * @throws DeliveryAutoException
     */
    public function getUserPickUp(GetUserPickUpRequest $request): DataCollection
    {
        return $this
            ->reset()
            ->method('GetUserPickUp')
            ->params($request)
            ->withSession()
            ->toCollection(UserPickUpData::class);
    }

    /**
     * §5.6 Cancel reserved (state=8) TTN(s).
     *
     * @throws DeliveryAutoException
     */
    public function postDeactivateReceipts(PostDeactivateReceiptsRequest $request): bool
    {
        $this
            ->reset()
            ->method('PostDeactivateReceipts')
            ->params($request)
            ->post()
            ->withSession()
            ->get();

        return true;
    }

    /**
     * §6.14 Full receipt information (login-auth despite living in §6).
     *
     * @throws DeliveryAutoException
     */
    public function getFullReceiptInformation(GetFullReceiptInformationRequest $request): ?FullReceiptInformationData
    {
        /** @var FullReceiptInformationData|null */
        return $this
            ->reset()
            ->method('GetFullReceiptInformation')
            ->params($request)
            ->withSession()
            ->toData(FullReceiptInformationData::class);
    }
}
