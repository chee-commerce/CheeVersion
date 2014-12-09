<?php namespace Chee\Version;

class Version
{
    /**
     * Constant for any version operator
     *
     * @var string
     */
    const ANY = '*';

    /**
     * Constant for greater operator
     *
     * @var string
     */
    const  GREATER = '>';

    /**
     * Constant for greater equal operator
     *
     * @var string
     */
    const GREATER_EQUAL = '>=';

    /**
     * Constant for tilde operator
     *
     * @var string
     */
    const TILDE = '~';

    /**
     * Keep original major
     *
     * @var string
     */
    protected $major = null;

    /**
     * Keep original minor
     *
     * @var string
     */
    protected $minor = null;

    /**
     * Keep original path
     *
     * @var string
     */
    protected $path = null;

    /**
     * Keep operator of version
     *
     * @var string
     */
    protected $operator = null;

    /**
     * Keep original version
     *
     * @var string
     */
    protected $version;

    /**
     * Initialize class
     *
     * @param string $version
     * @return void
     */
    public function __construct($version)
    {
        $this->version = $version;
        $this->detectOperator();
        $this->detectVersion();
    }

    /**
     * Get original version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get major
     *
     * @return string
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Get floated major
     *
     * @return float
     */
    public function getFloatMajor()
    {
        if ($this->startsWith($this->major, '0'))
            return (float) ('0.'.substr($this->major, 1));
        return (float) $this->major;
    }

    /**
     * Get minor
     *
     * @return string
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     * Get floated minor
     *
     * @return float
     */
    public function getFloatMinor()
    {
        if ($this->startsWith($this->minor, '0'))
            return (float) ('0.'.substr($this->minor, 1));
        return (float) $this->minor;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get floated path
     *
     * @return float
     */
    public function getFloatPath()
    {
        if ($this->startsWith($this->path, '0'))
            return (float) ('0.'.substr($this->path, 1));
        return (float) $this->path;
    }

    /**
     * Get operator of version
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Is this version part of another version?
     *
     * @param Chee\Version\Version $version
     * @return bool
     */
    public function isPartOf(Version $version)
    {
        if (is_null($version->getOperator()))
        {
            if ($this->major == $version->getMajor()  && $this->minor == $version->getMinor() && $this->path == $version->getPath())
                return true;
            return false;
        }

        switch ($version->getOperator())
        {
            case self::GREATER:
                return $this->mustGreaterThan($version);
                break;

            case self::GREATER_EQUAL:
                return $this->mustGreaterEqualThan($version);
                break;
            case self::TILDE:
                return $this->mustTilde($version);
                break;
        }
    }

    /**
     * Detect operator of version
     *
     * @return void
     */
    protected function detectOperator()
    {
        if ($this->startsWith($this->version, self::GREATER_EQUAL))
        {
            $this->operator = self::GREATER_EQUAL;
            $this->version = substr($this->version, 2);
        }
        else if ($this->startsWith($this->version, self::GREATER))
        {
            $this->operator = self::GREATER;
            $this->version = substr($this->version, 1);
        }
        else if ($this->startsWith($this->version, self::TILDE))
        {
            $this->operator = self::TILDE;
            $this->version = substr($this->version, 1);
        }
    }

    /**
     * If operator is tilde(~)
     *
     * @param Chee\Version\Version $version
     * @return bool
     */
    protected function mustTilde(Version $version)
    {
        if (is_null($version->getPath()))
        {
            if (is_null($version->getMinor()))
            {
                if ($version->getFloatMajor() > $this->getFloatMajor())
                    return false;
            }

            else //Minor not null
            {
                if ($version->getFloatMinor() > $this->getFloatMinor())
                    return false;

                if ($version->getFloatMajor() != $this->getFloatMajor())
                    return false;
            }
        }
        else //Path not null
        {
            if ($version->getFloatPath() > $this->getFloatPath())
                return false;

            if ($version->getFloatMinor() != $this->getFloatMinor())
                return false;

            if ($version->getFloatMajor() != $this->getFloatMajor())
                return false;
        }

        return true;
    }

    /**
     * If operator is greater equal(>=)
     *
     * @param Chee\Version\Version $version
     * @return bool
     */
    protected function mustGreaterEqualThan(Version $version)
    {
        if ($version->getFloatMajor() > $this->getFloatMajor())
            return false;

        else if ($version->getFloatMajor() == $this->getFloatMajor())
        {
            if (is_null($version->minor))
                return true;

            if ($version->getFloatMinor() > $this->getFloatMinor())
                return false;

            else if ($version->getFloatMinor() == $this->getFloatMinor())
            {
                if (is_null($version->path))
                    return true;

                if ($version->getFloatPath() > $this->getFloatPath())
                    return false;

                else if ($version->getFloatPath() == $this->getFloatPath())
                    return true;
            }
        }

        return true;
    }

    /**
     * If operator is greater(>)
     *
     * @param Chee\Version\Version $version
     * @return bool
     */
    protected function mustGreaterThan(Version $version)
    {
        if ($version->getFloatMajor() > $this->getFloatMajor())
            return false;

        else if ($version->getFloatMajor() == $this->getFloatMajor())
        {
            if (is_null($version->minor))
                return false;

            if ($version->getFloatMinor() > $this->getFloatMinor())
                return false;

            else if ($version->getFloatMinor() == $this->getFloatMinor())
            {
                if (is_null($version->path))
                    return false;

                if ($version->getFloatPath() > $this->getFloatPath())
                    return false;

                else if ($version->getFloatPath() == $this->getFloatPath())
                    return false;
            }
        }

        return true;
    }

    /**
     * Isolation version
     *
     * @return void
     */
    protected function detectVersion()
    {
        $version = explode('.', $this->version);
        $this->major = ($version[0] != ANY) ? $version[0] : ANY;
        $this->minor = isset($version[1]) ? (($version[1] != ANY) ? $version[1] : ANY) : null;
        $this->path = isset($version[2]) ? (($version[2] != ANY) ? $version[2] : ANY) : null;
    }

    /**
     * Starts with text
     *
     * @return bool
     */
    protected function startsWith($haystack, $needle)
    {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }
}
