<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Repository\Contracts\UserRepositoryInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /** @var UserRepositoryInterface */
    protected UserRepositoryInterface $repository;

    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $users = $this->repository->paginate();

        return UserResource::collection(collect($users->items()))
            ->additional([
                'meta' => [
                    'total' => $users->total(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'first_page' => $users->firstPage(),
                    'per_page' => $users->perPage(),
                ]
            ]);
    }

    /**
     * @param string $email
     * @return UserResource
     */
    public function show(string $email): UserResource
    {
        $user = $this->repository->find($email);
        return new UserResource($user);
    }

    /**
     * @param UserStoreRequest $request
     * @return UserResource
     */
    public function store(UserStoreRequest $request): UserResource
    {
        $user = $this->repository->create($request->validated());
        return new UserResource($user);
    }

    /**
     * @param UserUpdateRequest $request
     * @param string $email
     * @return UserResource
     */
    public function update(UserUpdateRequest $request, string $email): UserResource
    {
        $user = $this->repository->update($email, $request->validated());
        return new UserResource($user);
    }

    /**
     * @param string $email
     * @return Response
     */
    public function destroy(string $email): Response
    {
        $this->repository->delete($email);
        return response()->noContent();
    }
}
