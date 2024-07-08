<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: packages/slogger/grpc/proto/collector.proto

namespace SLoggerGrpcDto\TraceCollector;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>trace.collector.TraceCreateObject</code>
 */
class TraceCreateObject extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string trace_id = 1;</code>
     */
    protected $trace_id = '';
    /**
     * Generated from protobuf field <code>.google.protobuf.StringValue parent_trace_id = 2;</code>
     */
    protected $parent_trace_id = null;
    /**
     * Generated from protobuf field <code>string type = 3;</code>
     */
    protected $type = '';
    /**
     * Generated from protobuf field <code>string status = 4;</code>
     */
    protected $status = '';
    /**
     * Generated from protobuf field <code>repeated string tags = 5;</code>
     */
    private $tags;
    /**
     * Generated from protobuf field <code>string data = 6;</code>
     */
    protected $data = '';
    /**
     * Generated from protobuf field <code>.google.protobuf.DoubleValue duration = 7;</code>
     */
    protected $duration = null;
    /**
     * Generated from protobuf field <code>.google.protobuf.DoubleValue memory = 8;</code>
     */
    protected $memory = null;
    /**
     * Generated from protobuf field <code>.google.protobuf.DoubleValue cpu = 9;</code>
     */
    protected $cpu = null;
    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp logged_at = 10;</code>
     */
    protected $logged_at = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $trace_id
     *     @type \Google\Protobuf\StringValue $parent_trace_id
     *     @type string $type
     *     @type string $status
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $tags
     *     @type string $data
     *     @type \Google\Protobuf\DoubleValue $duration
     *     @type \Google\Protobuf\DoubleValue $memory
     *     @type \Google\Protobuf\DoubleValue $cpu
     *     @type \Google\Protobuf\Timestamp $logged_at
     * }
     */
    public function __construct($data = NULL) {
        \SLoggerGrpcDto\TraceCollectorGPBMetadata\Collector::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string trace_id = 1;</code>
     * @return string
     */
    public function getTraceId()
    {
        return $this->trace_id;
    }

    /**
     * Generated from protobuf field <code>string trace_id = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setTraceId($var)
    {
        GPBUtil::checkString($var, True);
        $this->trace_id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.StringValue parent_trace_id = 2;</code>
     * @return \Google\Protobuf\StringValue
     */
    public function getParentTraceId()
    {
        return $this->parent_trace_id;
    }

    /**
     * Returns the unboxed value from <code>getParentTraceId()</code>

     * Generated from protobuf field <code>.google.protobuf.StringValue parent_trace_id = 2;</code>
     * @return string|null
     */
    public function getParentTraceIdUnwrapped()
    {
        return $this->readWrapperValue("parent_trace_id");
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.StringValue parent_trace_id = 2;</code>
     * @param \Google\Protobuf\StringValue $var
     * @return $this
     */
    public function setParentTraceId($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\StringValue::class);
        $this->parent_trace_id = $var;

        return $this;
    }

    /**
     * Sets the field by wrapping a primitive type in a Google\Protobuf\StringValue object.

     * Generated from protobuf field <code>.google.protobuf.StringValue parent_trace_id = 2;</code>
     * @param string|null $var
     * @return $this
     */
    public function setParentTraceIdUnwrapped($var)
    {
        $this->writeWrapperValue("parent_trace_id", $var);
        return $this;}

    /**
     * Generated from protobuf field <code>string type = 3;</code>
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Generated from protobuf field <code>string type = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setType($var)
    {
        GPBUtil::checkString($var, True);
        $this->type = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string status = 4;</code>
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Generated from protobuf field <code>string status = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setStatus($var)
    {
        GPBUtil::checkString($var, True);
        $this->status = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated string tags = 5;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Generated from protobuf field <code>repeated string tags = 5;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setTags($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->tags = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string data = 6;</code>
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Generated from protobuf field <code>string data = 6;</code>
     * @param string $var
     * @return $this
     */
    public function setData($var)
    {
        GPBUtil::checkString($var, True);
        $this->data = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.DoubleValue duration = 7;</code>
     * @return \Google\Protobuf\DoubleValue
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Returns the unboxed value from <code>getDuration()</code>

     * Generated from protobuf field <code>.google.protobuf.DoubleValue duration = 7;</code>
     * @return float|null
     */
    public function getDurationUnwrapped()
    {
        return $this->readWrapperValue("duration");
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.DoubleValue duration = 7;</code>
     * @param \Google\Protobuf\DoubleValue $var
     * @return $this
     */
    public function setDuration($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\DoubleValue::class);
        $this->duration = $var;

        return $this;
    }

    /**
     * Sets the field by wrapping a primitive type in a Google\Protobuf\DoubleValue object.

     * Generated from protobuf field <code>.google.protobuf.DoubleValue duration = 7;</code>
     * @param float|null $var
     * @return $this
     */
    public function setDurationUnwrapped($var)
    {
        $this->writeWrapperValue("duration", $var);
        return $this;}

    /**
     * Generated from protobuf field <code>.google.protobuf.DoubleValue memory = 8;</code>
     * @return \Google\Protobuf\DoubleValue
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * Returns the unboxed value from <code>getMemory()</code>

     * Generated from protobuf field <code>.google.protobuf.DoubleValue memory = 8;</code>
     * @return float|null
     */
    public function getMemoryUnwrapped()
    {
        return $this->readWrapperValue("memory");
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.DoubleValue memory = 8;</code>
     * @param \Google\Protobuf\DoubleValue $var
     * @return $this
     */
    public function setMemory($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\DoubleValue::class);
        $this->memory = $var;

        return $this;
    }

    /**
     * Sets the field by wrapping a primitive type in a Google\Protobuf\DoubleValue object.

     * Generated from protobuf field <code>.google.protobuf.DoubleValue memory = 8;</code>
     * @param float|null $var
     * @return $this
     */
    public function setMemoryUnwrapped($var)
    {
        $this->writeWrapperValue("memory", $var);
        return $this;}

    /**
     * Generated from protobuf field <code>.google.protobuf.DoubleValue cpu = 9;</code>
     * @return \Google\Protobuf\DoubleValue
     */
    public function getCpu()
    {
        return $this->cpu;
    }

    /**
     * Returns the unboxed value from <code>getCpu()</code>

     * Generated from protobuf field <code>.google.protobuf.DoubleValue cpu = 9;</code>
     * @return float|null
     */
    public function getCpuUnwrapped()
    {
        return $this->readWrapperValue("cpu");
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.DoubleValue cpu = 9;</code>
     * @param \Google\Protobuf\DoubleValue $var
     * @return $this
     */
    public function setCpu($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\DoubleValue::class);
        $this->cpu = $var;

        return $this;
    }

    /**
     * Sets the field by wrapping a primitive type in a Google\Protobuf\DoubleValue object.

     * Generated from protobuf field <code>.google.protobuf.DoubleValue cpu = 9;</code>
     * @param float|null $var
     * @return $this
     */
    public function setCpuUnwrapped($var)
    {
        $this->writeWrapperValue("cpu", $var);
        return $this;}

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp logged_at = 10;</code>
     * @return \Google\Protobuf\Timestamp
     */
    public function getLoggedAt()
    {
        return $this->logged_at;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp logged_at = 10;</code>
     * @param \Google\Protobuf\Timestamp $var
     * @return $this
     */
    public function setLoggedAt($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Timestamp::class);
        $this->logged_at = $var;

        return $this;
    }

}

