<?php

namespace App\Service;

class TestService implements TestServiceInterface
{
    public function testService($firstParam,$secondParam)
    {
        echo $firstParam;

//        return response()->json([
//            'message' => 'You are maintaining quality, very nice!',
//            'data' => $firstParam,
//        ]);
    }
}
