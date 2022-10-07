<?php

namespace App\Repository\Eloquent\Providers;

use Symfony\Component\HttpFoundation\Response;

/**
 * @codeCoverageIgnore
 */
trait UserProvider
{
    /**
     * @return \int[][]
     */
    public function dataProviderPagination(): array
    {
        return [
            'test total 40 users page 1' => ['total' => 40, 'page' => 1, 'totalPage' => 15],
            'test total 40 users page 2' => ['total' => 40, 'page' => 2, 'totalPage' => 15],
            'test total 40 users page 3' => ['total' => 40, 'page' => 3, 'totalPage' => 10],
        ];
    }

    /**
     * @return array[]
     */
    public function dataProviderCreateUser(): array
    {
        return [
            'created successfully' => [
                'payload' => [
                    'name' => 'Matheus S. Jose',
                    'email' => 'matheus.jose@gmail.com',
                    'password' => '123456'
                ],
                'statusCode' => Response::HTTP_CREATED,
                'strucutureResponse' => [
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ],
            'invalid create' => [
                'payload' => [
                    'email' => 'matheus.jose@gmail.com',
                    'password' => '123456'
                ],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'strucutureResponse' => [
                    'message',
                    'errors' => [
                        'name'
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array[]
     */
    public function dataProviderUpdateUser(): array
    {
        return [
            'update ok' => [
                'payload' => [
                    'name' => 'Matheus S. Jose',
                    'password' => '123456'
                ],
                'statusCode' => Response::HTTP_OK,
                'strucutureResponse' => [
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ],
            'update without password' => [
                'payload' => [
                    'name' => 'Matheus S. Jose',
                ],
                'statusCode' => Response::HTTP_OK,
                'strucutureResponse' => [
                    'data' => [
                        'id',
                        'name',
                        'email'
                    ]
                ]
            ],
            'update without name' => [
                'payload' => [
                    'password' => '123456'
                ],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'strucutureResponse' => [
                    'message',
                    'errors' => [
                        'name'
                    ]
                ]
            ],
            'update without name and password' => [
                'payload' => [],
                'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
                'strucutureResponse' => [
                    'message',
                    'errors' => [
                        'name'
                    ]
                ]
            ]
        ];
    }
}
