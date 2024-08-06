<?php

namespace App\Service;

use App\Repository\TokenRepository;
use App\Repository\TokenCategoryRepository;

class TokenService
{

    private $tokenRepository;
    private $tokenCategoryRepository;

    public function __construct(
        TokenRepository $tokenRepository,
        TokenCategoryRepository $tokenCategoryRepository
        )
    {
        $this->tokenRepository = $tokenRepository;
        $this->tokenCategoryRepository = $tokenCategoryRepository;
    }

    public function generateToken($categoryName): string
    {
        $token = '';
        do {
            $category = $this->tokenCategoryRepository->findOneBy(['name' => $categoryName]);

            if (!$category) {
                return 'category not found';
            }
            
            $token = bin2hex(random_bytes(16));
            $confirmTokenDB = $this->tokenRepository->findOneBy(['token' => $token, 'category' => $category]);
        } while ($confirmTokenDB !== null);
        return $token;
    }
}