<?php
namespace roach\http;

use roach\Container;
use roach\events\Event;
use roach\events\EventObject;
use roach\Roach;

/**
 * Class Request
 * @package roach\http
 * @datetime 2020/7/1 6:29 下午
 * @author   roach
 * @email    jhq0113@163.com
 */
class Request extends Roach
{
    const GET     = 1;
    const POST    = 2;
    const PUT     = 3;
    const DELETE  = 4;

    const EVENT_BEFORE_REQUEST = 'before:request';
    const EVENT_AFTER_REQUEST  = 'after:request';

    use Event;

    /**
     * @var array
     * @datetime 2020/6/25 3:47 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public static $METHOD_MAP = [
        self::GET    => 'GET',
        self::POST   => 'POST',
        self::PUT    => 'PUT',
        self::DELETE => 'DELETE',
    ];

    /**请求地址
     * @var string
     * @datetime 2020/6/25 3:27 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $url;

    /**请求头
     * @var array
     * @datetime 2020/6/25 3:27 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $headers = [];

    /**请求方法
     * @var int
     * @datetime 2020/6/25 3:27 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $method = self::GET;

    /**
     * @var array
     * @datetime 2020/6/25 3:49 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $options;

    /**请求参数
     * @var array
     * @datetime 2020/6/25 3:28 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $params;

    /**单位s
     * @var int
     * @datetime 2020/6/25 3:32 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public $timeout = 10;

    /**
     * @param string $url
     * @return $this
     * @datetime 2020/6/25 3:37 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param int $method
     * @return $this
     * @datetime 2020/6/25 4:53 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     * @datetime 2020/6/25 3:40 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function addHeader($key, $value)
    {
        $this->headers[ $key ] = $value;
        return $this;
    }

    /**
     * @param array $headers  如：['User-Agent' => 'Mozilla/5.0 (Linux; X11)']
     * @return $this
     * @datetime 2020/6/25 3:41 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function addHeaders($headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * @param int $timeout
     * @return $this
     * @datetime 2020/6/25 3:43 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     * @datetime 2020/6/25 3:50 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     * @datetime 2020/6/25 4:43 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function setParams($params = [])
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return resource
     * @datetime 2020/6/25 4:37 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    protected function _createHandler()
    {
        $handler = curl_init();
        $options = [
            CURLOPT_TIMEOUT        => $this->timeout,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CUSTOMREQUEST  => self::$METHOD_MAP[ $this->method ],
        ];

        //请求参数
        $params = $this->params;
        if(!empty($params)) {
            if(is_array($params)) {
                $params = http_build_query($params);
            }

            if($this->method === self::GET) {
                if(strpos($this->url, '?') === false) {
                    $this->url .= '?';
                }
                $this->url .= $params;
            } else {
                $options[ CURLOPT_POSTFIELDS ] = $params;
            }
        }

        //请求地址
        $options[ CURLOPT_URL ]  = $this->url;

        //请求头
        if(!empty($this->headers)) {
            $headers = [];
            foreach ($this->headers as $key => $value) {
                array_push($headers, $key.': '. $value);
            }
            $options[ CURLOPT_HTTPHEADER ] = $headers;
        }

        //https请求
        if(substr($this->url, 0, 5) === 'https') {
            $options[ CURLOPT_SSL_VERIFYPEER ] = false;
            $options[ CURLOPT_SSL_VERIFYHOST ] = false;
        }
        curl_setopt_array($handler, $options);

        //用户自定义选项
        if(isset($this->options)) {
            curl_setopt_array($handler, $this->options);
        }

        return $handler;
    }

    /**
     * @param array|string $params
     * @return Response
     * @datetime 2020/6/25 4:07 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    protected function _request()
    {
        $handler = $this->_createHandler();
        //发送请求
        $response = new Response();

        /**
         * @var $event \roach\events\EventObject
         */
        $event = Container::insure([
            'class' => EventObject::class,
            'sender' => $this,
        ]);

        $this->trigger(self::EVENT_BEFORE_REQUEST, $event);

        $response->setBody(curl_exec($handler))
            ->setInfo(curl_getinfo($handler));
        curl_close($handler);

        $event->data = $response;
        $this->trigger(self::EVENT_AFTER_REQUEST, $event);

        return $response;
    }

    /**
     * @return Response
     * @example
     * $response = (new Request())->setUrl('http://10.16.49.66:9200')
     *   ->get();
     *   if(!$response->success()) {
     *   throw new \Exception('request failed,http_code:'.$response->get('http_code'));
     *   }
     * var_dump($response->jsonBody(true));
     * @datetime 2020/6/25 4:08 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function get()
    {
        return $this->setMethod(self::GET)
            ->_request();
    }

    /**
     * @return Response
     * @example
     * $response = (new Request())->setUrl('http://10.16.49.66:9200')
     *   ->setParams([
     *      'key' => 'value'
     *   ])
     *   ->post();
     * if(!$response->success()) {
     *   throw new \Exception('request failed,http_code:'.$response->get('http_code'));
     * }
     * var_dump($response->jsonBody(true));
     * @datetime 2020/6/25 4:08 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function post()
    {
        return $this->setMethod(self::POST)
            ->_request();
    }

    /**
     * @return Response
     * @example
     * $response = (new Request())->setUrl('http://10.16.49.66:9200')
     *   ->setParams(json_encode([
     *      'key' => 'value'
     *   ]))
     *   ->put();
     * if(!$response->success()) {
     *   throw new \Exception('request failed,http_code:'.$response->get('http_code'));
     * }
     * var_dump($response->jsonBody(true));
     * @datetime 2020/6/25 4:09 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function put()
    {
        return $this->setMethod(self::PUT)
            ->_request();
    }

    /**
     * @return Response
     * @example
     * $response = (new Request())->setUrl('http://10.16.49.66:9200')
     *   ->delete();
     * if(!$response->success()) {
     *   throw new \Exception('request failed,http_code:'.$response->get('http_code'));
     * }
     * var_dump($response->jsonBody(true));
     * @datetime 2020/6/25 4:09 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    public function delete()
    {
        return $this->setMethod(self::DELETE)
            ->_request();
    }

    /**
     * @param Request $request1
     * @param Request $request2
     * @param Request $requestN
     * @example
     *  $request1 = (new Request())->setUrl('http://10.16.49.66:9200')->setMethod(Request::GET);
     *  $request2 = (new Request())->setUrl('http://www.baidu.com')->setMethod(Request::GET);
     *  $request3 = (new Request())->setUrl('http://www.sina.com')->setMethod(Request::POST);
     *
     *  $responseList = Request::multiRequest($request1, $request2, $request3);
     *  foreach ($responseList as $response) {
     *      echo $response->getBody().PHP_EOL.PHP_EOL;
     *  }
     * @return array
     * @datetime 2020/6/25 4:50 下午
     * @author   roach
     * @email    jhq0113@163.com
     */
    static public function multiRequest(Request $request1, Request $request2)
    {
        $list = func_get_args();
        $multiHandler = curl_multi_init();
        $handlers = [];
        foreach ($list as $index => $request) {
            /**
             * @var Request $request
             */
            $handlers[ $index ] = $request->_createHandler();
            curl_multi_add_handle($multiHandler, $handlers[ $index ]);
        }

        //发请求
        do {
            curl_multi_exec($multiHandler, $running);
            curl_multi_select($multiHandler);
        } while ($running > 0);

        $responseList = [];
        foreach ($list as $index => $request) {
            $response = new Response();
            $response->setBody(curl_multi_getcontent($handlers[ $index ]))
                ->setInfo(curl_getinfo($handlers[ $index ]));
            array_push($responseList, $response);
            curl_multi_remove_handle($multiHandler, $handlers[ $index ]);
        }
        curl_multi_close($multiHandler);

        return $responseList;
    }
}