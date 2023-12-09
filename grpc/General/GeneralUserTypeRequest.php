<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: general.proto

namespace General;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>General.GeneralUserTypeRequest</code>
 */
class GeneralUserTypeRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string remoteip = 1;</code>
     */
    protected $remoteip = '';
    /**
     * Generated from protobuf field <code>string weburl = 2;</code>
     */
    protected $weburl = '';
    /**
     * Generated from protobuf field <code>string langid = 3;</code>
     */
    protected $langid = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $remoteip
     *     @type string $weburl
     *     @type string $langid
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\General::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string remoteip = 1;</code>
     * @return string
     */
    public function getRemoteip()
    {
        return $this->remoteip;
    }

    /**
     * Generated from protobuf field <code>string remoteip = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setRemoteip($var)
    {
        GPBUtil::checkString($var, True);
        $this->remoteip = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string weburl = 2;</code>
     * @return string
     */
    public function getWeburl()
    {
        return $this->weburl;
    }

    /**
     * Generated from protobuf field <code>string weburl = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setWeburl($var)
    {
        GPBUtil::checkString($var, True);
        $this->weburl = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string langid = 3;</code>
     * @return string
     */
    public function getLangid()
    {
        return $this->langid;
    }

    /**
     * Generated from protobuf field <code>string langid = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setLangid($var)
    {
        GPBUtil::checkString($var, True);
        $this->langid = $var;

        return $this;
    }

}

