<?php

/**
 * Laravel Sms Package - Mezua
 */

namespace Codefuelcf\Mezua;

// Packages
use GuzzleHttp\Client;

// Models
use Codefuelcf\Mezua\Models\SmsModel;
use Codefuelcf\Mezua\Models\SmsApiModel;

/**
 * Laravel Sms
 */
class Sms {

    /**
     * Receiver Phone Number
     * @var string
     */
    private $receiverPhoneNumber;

    /**
     * Message
     * @var string
     */
    private $message;

    /**
     * Gateway
     * @var int
     */
    private $gateway;

    /**
     * Initialize the sms object
     */
    private function __construct($mesage, $receiverPhoneNumber)
    {
        $this->message = $mesage;

        $this->receiverPhoneNumber = $receiverPhoneNumber;
    }

    /**
     * Create a new sms object
     */
    public static function create(string $mesage, string $receiverPhoneNumber)
    {
        return new static($mesage, $receiverPhoneNumber);
    }

    /**
     * Store the message to the queue
     */
    public function send()
    {
        $sms = new SmsModel; 

        isset($this->gateway) ? $sms->sms_api_id = $this->gateway : $sms->sms_api_id = config('sms.defaultSmsGateway');

        $sms->receiver_phone = $this->receiverPhoneNumber;
        $sms->message = $this->message;
        $sms->status = 'queued';

        $sms->save();

        return $sms->id;
    }

    /**
     * Send the sms right away
     */
    public function sendNow()
    {
        $sms = $this->send();

        return $this->sendSms($sms);
    }

    /**
     * Send the smses that are in the queue
     */
    public static function sendQueued(int $limit = 250)
    {
        // Array to store all the sms ids are processed
        $smsProcessed = [];

        // Get all the queued smses
        $smses = SmsModel::where('status', 'queued')
                         ->limit($limit)
                         ->get();

        // Start sending all the smes
        foreach($smses as $sms)
        {
            self::sendSms($sms->id);

            array_push($smsProcessed, $sms->id);
        }

        return $smsProcessed;
    }
    
    /**
     * Send sms
     */
    private static function sendSms($id)
    {
        // Get the sms details
        $sms = SmsModel::find($id);

        // Set Guzzle HTTP parametes from the sms
        $method = $sms->api->type;
        $url = $sms->api->url;

        // Setting the data based on method type
        $dataToBeSent = $sms->api->data;

        // Updating the message and phone number
        array_walk($dataToBeSent, function ($value, $key) use (& $dataToBeSent, & $sms) {

            $stripedValue = str_replace(' ', '', $value);

            if($stripedValue == '{{message}}')
            {
                $dataToBeSent[$key] = $sms->message;
            }

            if($stripedValue == '{{receiverPhoneNumber}}')
            {
                $dataToBeSent[$key] = $sms->receiver_phone;
            }
            
        });

        // Set guzzle hhtp method parameter based on the method
        if($method == 'GET')
        {
            $data = [
                'query' => $dataToBeSent
            ];
        }
        else
        {
            $data = [
                'form_params' => $dataToBeSent
            ];
        }

        // Adding headers to the request, if any
        is_null($sms->api->headers) ? TRUE : $data['headers'] = $sms->api->headers;

        // Sending the sms
        $client = new Client();
        $response = $client->request($method, $url, $data);

        // Updating the status of the sms
        $sms->status = 'sent';
        $sms->response = [
            'code' => $response->getStatusCode(),
            'reason' => $response->getReasonPhrase()
        ];
        $sms->save();

        return $sms->id;
    }

    /**
     * Send the sms using a particular gateway
     */
    public function usingGateway($smsApiIdentifier)
    {
        
        // Check which column is being used (id or slug)
        is_integer($smsApiIdentifier) ? $column = 'id' : $column = 'slug';

        // If anything other than int or string is used, return
        if(! is_integer($column) && ! is_string($column))
        {
            return $this;
        }

        // Search for the sms API gateway
        $gateway = SmsApiModel::where($column, $smsApiIdentifier)
                              ->first()
                              ->id;

        // Use the id selected after search
        $this->gateway = $gateway;

        return $this;
    }

    /**
     * Count total queued smses
     */
    public static function totalQueuedCount()
    {
        return SmsModel::where('status', 'queued')
                       ->count();
    }

    /**
     * Count total sent smses
     */
    public static function totalSentCount()
    {
        return SmsModel::where('status', 'sent')
                       ->count();
    }
    
    /**
     * Count total smses
     */
    public static function totalCount()
    {
        return SmsModel::count();
    }

}