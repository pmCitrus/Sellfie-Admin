<?php

namespace App\Services;

use GuzzleHttp\Client;
use Log;

class CitrusRefund
{
    public function __construct()
    {
        $this->client           = new Client();
        $this->wallet_url       = config('base_urls.citrus_wallet_url');
        $this->access_key       = config('wallet_tokens.access_key');
        $this->secret_key       = config('wallet_tokens.secret_key');
    }
    
    public function refund($user_data)
    {
        $api_url    = $this->wallet_url."api/v2/txn/refund";
        $data       = "merchantAccessKey=".$this->access_key
                        ."&transactionId=".$user_data['TxId']
                        ."&amount=".$user_data['amount'];
        $signature  = hash_hmac('sha1', $data, $this->secret_key);
        $options    = [
                        'headers'       => [
                                            'signature'     => $signature,
                                            'access_key'    => $this->access_key,
                                            'Accept'        => 'application/json'
                                            ],
                        'json'          => [
                                            'merchantTxnId' => $user_data['TxId'],
                                            'pgTxnId'       => $user_data['pgTxnNo'],
                                            'rrn'           => $user_data['issuerRefNo'],
                                            'authIdCode'    => $user_data['authIdCode'],
                                            'currencyCode'  => $user_data['currency'],
                                            'amount'        => $user_data['amount'],
                                            'txnType'       => 'Refund'
                                            ],
                        'exceptions' =>  false
                        ];
        $response       = $this->client->post($api_url, $options);
        
        
        $data =  $response->getBody();
        
         $r = json_decode($data);
         
         
         print_r($r);

         
         
     
  
        
//        $json_decoded = json_decode($data);
//        
//        
//        dd($json_decoded);
        
        
        
//        print_r($response->getBody()->getContents());
        
//        echo ($response->getStatusCode());
//        echo "<br> <pre>";
////        echo($response->getBody());
//        $response_arr   = $response->getBody();
//        print_r($response_arr);
//        die;
//        Log::info('citrus-transaction-refund', ['response' => $response->getBody()]);
//        return $response;
    }
}
