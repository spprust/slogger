<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: grpc/proto/collector.proto

namespace GRPC\Collector;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>collector.TraceProfilingItemsObject</code>
 */
class TraceProfilingItemsObject extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string main_caller = 1;</code>
     */
    protected $main_caller = '';
    /**
     * Generated from protobuf field <code>repeated .collector.TraceProfilingItemObject items = 2;</code>
     */
    private $items;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $main_caller
     *     @type \GRPC\Collector\TraceProfilingItemObject[]|\Google\Protobuf\Internal\RepeatedField $items
     * }
     */
    public function __construct($data = NULL) {
        \GRPC\GPBMetadata\Collector::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string main_caller = 1;</code>
     * @return string
     */
    public function getMainCaller()
    {
        return $this->main_caller;
    }

    /**
     * Generated from protobuf field <code>string main_caller = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setMainCaller($var)
    {
        GPBUtil::checkString($var, True);
        $this->main_caller = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated .collector.TraceProfilingItemObject items = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Generated from protobuf field <code>repeated .collector.TraceProfilingItemObject items = 2;</code>
     * @param \GRPC\Collector\TraceProfilingItemObject[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setItems($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \GRPC\Collector\TraceProfilingItemObject::class);
        $this->items = $arr;

        return $this;
    }

}
