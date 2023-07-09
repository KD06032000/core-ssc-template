<?php
/** set your paypal credential **/

$config['client_id'] = 'ARH42U5-p5ZHmcumSN0SoS15BznMxNKVXpt1nzuQ_Q2-rovPEVLfoy7IOrYJCdwQRxfCq2wsnOPiDQ0a';
$config['secret'] = 'EOelT8KLYj8C6yX5Hyzb3hbNMfkgiv2s897gtKLvT8GGqzPYxVx33I4mgdyLQoKmi43qh7rsfptNYNSb';

/**
 * SDK configuration
 */
/**
 * Available option 'sandbox' or 'live'
 */
$config['paypal'] = array(

    'mode' => 'live',
    /**
     * Specify the max request time in seconds
     */
    'http.ConnectionTimeOut' => 1000,
    /**
     * Whether want to log to a file
     */
    'log.LogEnabled' => true,
    /**
     * Specify the file that want to write on
     */
    'log.FileName' => 'application/logs/paypal.log',
    /**
     * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
     *
     * Logging is most verbose in the 'FINE' level and decreases as you
     * proceed towards ERROR
     */
    'log.LogLevel' => 'FINE'
);