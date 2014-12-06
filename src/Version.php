<?php namespace Chee\Version;

class Version
{
    /**
     * Constant for any version symbole
     *
     * @var string
     */
    const ANY = '*';

    /**
     * This method check a version lesser than another version
     *
     * @param string $mVersion Main version
     * @param string $lVersion Version would be smaller
     * @return bool
     */
    public static function mustLesserThan($mVersion, $lVersion)
    {
        $mVersion = self::buildVersion($mVersion);
        $lVersion = self::buildVersion($lVersion);

        if ($lVersion['major'] !== ANY)

            if ((int) $mVersion['major'] < (int) $lVersion['major'])
                return false;

            elseif ((int) $mVersion['major'] === (int) $lVersion['major'])

                if ($lVersion['minor'] !== ANY && (int) $mVersion['minor'] < (int) $lVersion['minor'])
                    return false;

                elseif ((int) $mVersion['minor'] === (int) $lVersion['minor'])

                    if ($lVersion['path'] !== ANY && (int) $mVersion['path'] < (int) $lVersion['path'])
                        return false;

        return true;
    }

    /**
     * This method check a version greater than another version
     *
     * @param string $mVersion Main version
     * @param string $gVersion Version would be greater
     * @return bool
     */
    public static function mustGreaterThan($mVersion, $gVersion)
    {
        $mVersion = self::buildVersion($mVersion);
        $gVersion = self::buildVersion($gVersion);

        if ($gVersion['major'] !== ANY)

            if ((int) $gVersion['major'] < (int) $mVersion['major'])
                return false;

            elseif ((int) $gVersion['major'] === (int) $mVersion['major'])

                if ($gVersion['minor'] !== ANY && (int) $gVersion['minor'] < (int) $mVersion['minor'])
                    return false;

                elseif ((int) $gVersion['minor'] === (int) $mVersion['minor'])

                    if ($gVersion['path'] !== ANY && (int) $gVersion['path'] < (int) $mVersion['path'])
                        return false;

        return true;
    }

    /**
     * Isolation version
     *
     * @param string $version
     * @return array
     */
    protected static function buildVersion($version)
    {
        $version = explode('.', $version);
        $v = [];
        $v['major'] = ($version[0] != ANY) ? (int) $version[0] : ANY;
        $v['minor'] = isset($version[1]) ? (($version[1] != ANY) ? (int) $version[1] : ANY) : 0;
        $v['path'] = isset($version[2]) ? (($version[2] != ANY) ? (int) $version[2] : ANY) : 0;

        return $v;
    }
}
