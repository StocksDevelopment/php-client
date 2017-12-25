<?php
class stockExange
{
    const DEBUG = FALSE;
    /* Valid keys while searching for transactions. */
    private static $SEARCH_CRITERIA = array('transaction_id', 'type',
        'currency', 'to', 'from', 'extOID', 'txhash');
    private $_base_url = 'https://stocks.exchange/api2';
    private $_api_key = null;
    private $_api_secret = null;
    public function __construct($api_key=NULL, $api_secret=NULL, $base_url=NULL)
    {
        if (!is_null($api_key)) {
            $this->_api_key = $api_key;
        }
        if (!is_null($api_secret)) {
            $this->_api_secret = $api_secret;
        }
        if (!is_null($base_url)) {
            $this->_base_url = $base_url;
        }
    }
    /* Make a call to the StockExange API. */
    public function request($method, $params, $sign=TRUE, $post=TRUE) {
        $headers = array();
        if ($sign) {
            $params['nonce'] = gen_nonce();
            $params['method'] = $method;
            // generate the POST data string
            $post_data = http_build_query($params, '', '&');
            $headers[] = 'Key: ' .$this->_api_key;
            $sign = hash_hmac('sha512', $post_data, $this->_api_secret);
            $headers[] = 'Sign: ' .$sign;
        } else {
            // generate the POST data string
            $post_data = http_build_query($params, '', '&');
        }
        // our curl handle (initialize if required)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_base_url);
        if($post){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, $post);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, False);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // run the query
        $res = curl_exec($ch);
        if ($res === false){
            return $data = json_encode(
                array('success' => 0, 'error' => 'Could not get reply: '.curl_error($ch))
            );
        }
        curl_close($ch);
        $data = json_decode($res, TRUE);
        if (!$data){
            $data = json_encode(
                array('success' => 0, 'error' => 'Invalid data received, please make sure connection is working and requested API exists')
            );
        }
        return $data;
    }
    /* StockExange Private API. */
    public function get_info() {
        return $this->request('GetInfo', array());
    }
    public function active_orders($pair='All', $from=null, $count=50, $from_id=null, $end_id=null, $order='DESC',
                                  $since=null, $end=null, $type='ALL', $owner='OWN') {
        $params = array(
            'pair'	 => $pair,
            'count' => $count,
            'order' => $order,
            'type'	 => $type,
            'owner'  => $owner
        );
        if (!is_null($from)) {
            $params['from'] = $from;
        }
        if (!is_null($from_id)) {
            $params['from_id'] = $from_id;
        }
        if (!is_null($end_id)) {
            $params['end_id'] = $end_id;
        }
        if (!is_null($since)) {
            $params['since'] = $since;
            $params['order'] = 'ASC';
        }
        if (!is_null($end)) {
            $params['end'] = $end;
            $params['order'] = 'ASC';
        }
        return $this->request('ActiveOrders', $params);
    }
    public function trade($type, $pair, $amount, $rate) {
        $params = array(
            'type'	 => $type,
            'pair'   => $pair,
            'amount' => $amount,
            'rate'   => $rate
        );
        return $this->request('Trade', $params);
    }
    public function cancel_order($order_id) {
        $params = array(
            'order_id'	 => $order_id
        );
        return $this->request('CancelOrder', $params);
    }
    public function trade_history($pair='All', $from=null, $count=50, $from_id=null, $end_id=null, $order='DESC',
                                  $since=null, $end=null, $status=3, $owner='OWN') {
        $params = array(
            'pair'	 => $pair,
            'count'  => $count,
            'order'  => $order,
            'owner'  => $owner,
            'status' => $status
        );
        if (!is_null($from)) {
            $params['from'] = $from;
        }
        if (!is_null($from_id)) {
            $params['from_id'] = $from_id;
        }
        if (!is_null($end_id)) {
            $params['end_id'] = $end_id;
        }
        if (!is_null($since)) {
            $params['since'] = $since;
            $params['order'] = 'ASC';
        }
        if (!is_null($end)) {
            $params['end'] = $end;
            $params['order'] = 'ASC';
        }
        return $this->request('TradeHistory', $params);
    }
    public function trade_register_history($currency='All', $since=null, $end=null) {
        $params = array(
            'currency'	 => $currency
        );
        if (!is_null($since)) {
            $params['since'] = $since;
        }
        if (!is_null($end)) {
            $params['end'] = $end;
        }
        return $this->request('TradeRegisterHistory', $params);
    }
    public function user_history($since=null, $end=null) {
        if (!is_null($since)) {
            $params['since'] = $since;
        }
        if (!is_null($end)) {
            $params['end'] = $end;
        }
        return $this->request('UserHistory', $params);
    }
    public function trans_history($currency='All', $from=null, $count=50, $from_id=null, $end_id=null, $order='DESC',
                                  $since=null, $end=null, $operation='All', $status='FINISHED') {
        $params = array(
            'currency'	 => $currency,
            'count'      => $count,
            'order'  => $order,
            'operation'  => $operation,
            'status' => $status
        );
        if (!is_null($from)) {
            $params['from'] = $from;
        }
        if (!is_null($from_id)) {
            $params['from_id'] = $from_id;
        }
        if (!is_null($end_id)) {
            $params['end_id'] = $end_id;
        }
        if (!is_null($since)) {
            $params['since'] = $since;
            $params['order'] = 'ASC';
        }
        if (!is_null($end)) {
            $params['end'] = $end;
            $params['order'] = 'ASC';
        }
        if ($params['operation']=='All') {
            $params['status'] = 'FINISHED';
        }
        return $this->request('TransHistory', $params);
    }
    public function grafic($pair, $order='DESC', $since=null, $end=null, $page=1, $count=50, $interval='1D') {
        $params = array(
            'pair'	 => $pair,
            'count'  => $count,
            'order'  => $order,
            'interval'  => $interval,
            'page' => $page
        );
        if (!is_null($since)) {
            $params['since'] = $since;
            $params['order'] = 'ASC';
        }
        if (!is_null($end)) {
            $params['end'] = $end;
            $params['order'] = 'ASC';
        }
        return $this->request('Grafic', $params);
    }
    public function generate_wallets($currency) {
        $params = array('currency' => $currency);
        return $this->request('GenerateWallets', $params);
    }
    public function make_deposit($currency) {
        $params = array('currency' => $currency);
        return $this->request('Deposit', $params);
    }
    public function make_withdraw($currency, $address, $amount) {
        $params = array(
            'currency' => $currency,
            'address'  => $address,
            'amount'   => $amount,
        );
        return $this->request('Withdraw', $params);
    }
    /* StockExange Public API. */
    public function get_currencies() {
        $client = new ApiController();
        $res = $client->getCurrencies();
        if ($res) {
            $data = json_decode($res->getContent(), TRUE);
        }
        else {
            $data = json_encode(
                array('success' => 0, 'error' => 'Invalid data received, please make sure connection is working and requested API exists')
            );
        }
        return $data;
    }
    public function get_markets() {
        $client = new ApiController();
        $res = $client->getMarkets();
        if ($res) {
            $data = json_decode($res->getContent(), TRUE);
        }
        else {
            $data = json_encode(
                array('success' => 0, 'error' => 'Invalid data received, please make sure connection is working and requested API exists')
            );
        }
        return $data;
    }
    public function get_market_summary($currency1,$currency2) {
        $client = new ApiController();
        $res = $client->getMarketSummary($currency1,$currency2);
        if ($res) {
            $data = json_decode($res->getContent(), TRUE);
        }
        else {
            $data = json_encode(
                array('success' => 0, 'error' => 'Invalid data received, please make sure connection is working and requested API exists')
            );
        }
        return $data;
    }
    public function get_prices() {
        $client = new ApiController();
        $res = $client->getPrices();
        if ($res) {
            $data = json_decode($res->getContent(), TRUE);
        }
        else {
            $data = json_encode(
                array('success' => 0, 'error' => 'Invalid data received, please make sure connection is working and requested API exists')
            );
        }
        return $data;
    }
    public function ticker() {
        //$_base_url = 'https://btc.bevolved.net/api2';
        //$this->_base_url = 'https://btc.bevolved.net/api2/ticker/'.$currency1.'/'.$currency2;
        $client = new ApiController();
        $res = $client->ticker();
        if ($res) {
            $data = json_decode($res->getContent(), TRUE);
        }
        else {
            $data = json_encode(
                array('success' => 0, 'error' => 'Invalid data received, please make sure connection is working and requested API exists')
            );
        }
        return $data;
        //return $this->request( null, null, FALSE, FALSE);
    }
    public function create_ticket($subject,$ticket_category = 5,$message) {
        $params = array(
            'subject' => $subject,
            'ticket_category_id' =>$ticket_category,
            'message'   => $message
        );
        return $this->request('Ticket', $params);
    }
    public function get_tickets($ticket_id = null,$ticket_category = null,$ticket_status= null) {
        $params = array(
            'ticket_id' => $ticket_id,
            'ticket_category_id' => $ticket_category,
            'ticket_status_id'=> $ticket_status
        );
        return $this->request('GetTickets', $params);
    }
    public function reply_ticket($id,$message) {
        $params = array(
            'ticket_id' => $id,
            'message'   => $message
        );
        return $this->request('ReplyTicket', $params);
    }
    public function remind_password($email) {
        $params = array(
            'email' => $email
        );
        return $this->request('RemindPassword', $params);
    }
    /* Error testing */
    public function get_wrong_nonce_error($nonce) {
        $headers = array();
        $params['nonce'] = $nonce;
        $params['method'] = 'GetInfo';
        // generate the POST data string
        $post_data = http_build_query($params, '', '&');
        $headers[] = 'Key: ' .$this->_api_key;
        $sign = hash_hmac('sha512', $post_data, $this->_api_secret);
        $headers[] = 'Sign: ' .$sign;
        // our curl handle (initialize if required)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_base_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, True);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, False);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // run the query
        $res = curl_exec($ch);
        if ($res === false){
            return $data = json_encode(
                array('success' => 0, 'error' => 'Could not get reply: '.curl_error($ch))
            );
        }
        curl_close($ch);
        $data = json_decode($res, TRUE);
        if (!$data){
            $data = json_encode(
                array('success' => 0, 'error' => 'Invalid data received, please make sure connection is working and requested API exists')
            );
        }
        return $data;
    }
} /* StockExange class. */
/* Auxiliary function for sending signed requests to StockExange. */
function gen_nonce($length=9) {
    $b58 = "123456789";
    $nonce = '';
    for ($i = 0; $i < $length; $i++) {
        $char = $b58[mt_rand(0, 8)];
        $nonce = $nonce . $char;
    }
    return $nonce;
}
