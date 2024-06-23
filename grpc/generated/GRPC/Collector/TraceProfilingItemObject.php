<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: grpc/proto/collector.proto

namespace GRPC\Collector;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>collector.TraceProfilingItemObject</code>
 */
class TraceProfilingItemObject extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string raw = 1;</code>
     */
    protected $raw = '';
    /**
     * Generated from protobuf field <code>string calling = 2;</code>
     */
    protected $calling = '';
    /**
     * Generated from protobuf field <code>string callable = 3;</code>
     */
    protected $callable = '';
    /**
     * Generated from protobuf field <code>repeated .collector.TraceProfilingItemDataItemObject data = 4;</code>
     */
    private $data;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $raw
     *     @type string $calling
     *     @type string $callable
     *     @type \GRPC\Collector\TraceProfilingItemDataItemObject[]|\Google\Protobuf\Internal\RepeatedField $data
     * }
     */
    public function __construct($data = NULL) {
        \GRPC\GPBMetadata\Collector::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string raw = 1;</code>
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Generated from protobuf field <code>string raw = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setRaw($var)
    {
        GPBUtil::checkString($var, True);
        $this->raw = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string calling = 2;</code>
     * @return string
     */
    public function getCalling()
    {
        return $this->calling;
    }

    /**
     * Generated from protobuf field <code>string calling = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setCalling($var)
    {
        GPBUtil::checkString($var, True);
        $this->calling = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string callable = 3;</code>
     * @return string
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * Generated from protobuf field <code>string callable = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setCallable($var)
    {
        GPBUtil::checkString($var, True);
        $this->callable = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .collector.TraceProfilingItemDataItemObject data = 4;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Generated from protobuf field <code>repeated .collector.TraceProfilingItemDataItemObject data = 4;</code>
     * @param \GRPC\Collector\TraceProfilingItemDataItemObject[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setData($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \GRPC\Collector\TraceProfilingItemDataItemObject::class);
        $this->data = $arr;

        return $this;
    }

}

