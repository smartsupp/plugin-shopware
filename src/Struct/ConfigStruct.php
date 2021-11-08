<?php


namespace Supp\SmartsuppLiveChat\Struct;


class ConfigStruct extends \Shopware\Core\Framework\Struct\Struct
{
    protected $key;

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }


}