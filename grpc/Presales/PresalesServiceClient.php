<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Presales;

/**
 */
class PresalesServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Presales\PresalesLoginRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DoPresalesLogin(\Presales\PresalesLoginRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/Presales.PresalesService/DoPresalesLogin',
        $argument,
        ['\Presales\PresalesLoginResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Presales\PresalesViewRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DoPresalesView(\Presales\PresalesViewRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/Presales.PresalesService/DoPresalesView',
        $argument,
        ['\Presales\PresalesViewResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Presales\PresalesByIDViewRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DoPresalesByIDView(\Presales\PresalesByIDViewRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/Presales.PresalesService/DoPresalesByIDView',
        $argument,
        ['\Presales\PresalesByIDViewResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Presales\PresalesAddRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DoPresalesAdd(\Presales\PresalesAddRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/Presales.PresalesService/DoPresalesAdd',
        $argument,
        ['\Presales\PresalesAddResponse', 'decode'],
        $metadata, $options);
    }

}
