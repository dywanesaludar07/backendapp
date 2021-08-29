<?php

require_once 'braintree_php/lib/Braintree.php';



parse_str(file_get_contents("php://input"),$req);

if(isset($req['nonce'])){
    
    $payment = $req['payment'];
    $nonceFromTheClient = $req['nonce'];
    $deviceDataFromTheClient = $req['device'];

    // Instantiate a Braintree Gateway either like this:
    $gateway = new Braintree\Gateway([
        'environment' => 'sandbox',
        'merchantId' => 'x8w6jtn4jjcn6cqv',
        'publicKey' => 'ncxr87rgh3fytx2n',
        'privateKey' => 'fdbb812cfc9c896fb086614d5debf4fe'
    ]);

    // or like this:
    $config = new Braintree\Configuration([
        'environment' => 'sandbox',
        'merchantId' => 'x8w6jtn4jjcn6cqv',
        'publicKey' => 'ncxr87rgh3fytx2n',
        'privateKey' => 'fdbb812cfc9c896fb086614d5debf4fe'
    ]);
    $gateway = new Braintree\Gateway($config);

    // Then, create a transaction:-
    $result = $gateway->transaction()->sale([
        'amount' => $payment,
        'paymentMethodNonce' => $nonceFromTheClient,
        'deviceData' => $deviceDataFromTheClient,
        'options' => [ 'submitForSettlement' => True ]
    ]);

    if ($result->success) {
        print(json_encode(array("success!: " . $result->transaction->id)));
    } else if ($result->transaction) {
        print(json_encode(array("Error processing transaction:",$result->transaction->processorResponseCode, $result->transaction->processorResponseText)));
    } else {
        foreach($result->errors->deepAll() AS $error) {
        print(json_encode(array($error->message)));
        }
    }

}


