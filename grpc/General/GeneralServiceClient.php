<?php
// GENERATED CODE -- DO NOT EDIT!

namespace General;

/**
 */
class GeneralServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \General\GeneralCategoryRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DoGetCategory(\General\GeneralCategoryRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/General.GeneralService/DoGetCategory',
        $argument,
        ['\General\GeneralCategoryResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \General\GeneralSubCategoryRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DoGetSubCategory(\General\GeneralSubCategoryRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/General.GeneralService/DoGetSubCategory',
        $argument,
        ['\General\GeneralSubCategoryResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \General\GeneralSectionRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DoGetSection(\General\GeneralSectionRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/General.GeneralService/DoGetSection',
        $argument,
        ['\General\GeneralSectionResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \General\GeneralCountryRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DoGetCountry(\General\GeneralCountryRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/General.GeneralService/DoGetCountry',
        $argument,
        ['\General\GeneralCountryResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \General\GeneralUserTypeRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DoGetUserType(\General\GeneralUserTypeRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/General.GeneralService/DoGetUserType',
        $argument,
        ['\General\GeneralUserTypeResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \General\GeneralSupportTypeRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function DoGetSupportType(\General\GeneralSupportTypeRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/General.GeneralService/DoGetSupportType',
        $argument,
        ['\General\GeneralSupportTypeResponse', 'decode'],
        $metadata, $options);
    }

}
