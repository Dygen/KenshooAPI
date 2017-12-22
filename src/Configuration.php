<?php

namespace Kenshoo;

/**
 * Configuration for the Kenshoo API.
 *
 * @author jwhulette@gmail.com
 */
class Configuration
{
    /**
     * Kenshoo username.
     *
     * @var string
     */
    protected $username;

    /**
     * Kenshoo passward.
     *
     * @var string
     */
    protected $password;

    /**
     * Kenshoo Server Id.
     *
     * @var int
     */
    protected $kenshooId;

    /**
     * Debug flag.
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * Base api url.
     *
     * @var string
     */
    protected $url = 'https://api.kenshoo.com/v2/';

    /**
     * Get the debug flag.
     *
     * @method getDebug
     *
     * @return bool The debug flag
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Set the debug flag.
     *
     * @method setDebug
     *
     * @param bool $debug The debug flag
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getKenshooId()
    {
        return $this->kenshooId;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Kenshoo Server Id.
     *
     * @param int $kenshooId
     *
     * @return $this
     */
    public function setKenshooId($kenshooId)
    {
        $this->kenshooId = $kenshooId;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
