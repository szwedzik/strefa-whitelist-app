<?php

namespace App\Http\Controllers\Billing;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use ProductionEnvironment.
     */
    public static function environment()
    {
        $clientId = getenv("CLIENT_ID") ?: "Ad4vRXSWmJvQaujHLRM18ZHaYH6UqrUhp8xv_GAsZTx5Z6guG07gLWdK572adSbtD6CW8MTe5L1Pkvmz";
        $clientSecret = getenv("CLIENT_SECRET") ?: "EM3Zhv46O7e-6vxYjrfVpqPd6S2vKdlJ0IVYIc5wmC6NQn1_A20hurdrVO3FsmeC8Wt3Hzm5wnAl4ql6";
        return new ProductionEnvironment($clientId, $clientSecret);
    }
}