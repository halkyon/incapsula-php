<?php

namespace Incapsula\Credentials;

use Exception;

class CredentialProvider
{
    const ENV_PROFILE = 'INCAPSULA_PROFILE';

    const ENV_ID = 'INCAPSULA_API_ID';

    const ENV_KEY = 'INCAPSULA_API_KEY';

    /**
     * Get the default profile, either defined as ENV_PROFILE then
     * falling back to "default".
     *
     * @return string
     */
    public static function defaultProfile()
    {
        return getenv(self::ENV_PROFILE) ?: 'default';
    }

    /**
     * Provider that creates credentials from environment variables
     * INCAPSULA_API_ID, and INCAPSULA_API_KEY.
     *
     * @return null|Credentials
     */
    public static function env()
    {
        $key = getenv(self::ENV_ID);
        $secret = getenv(self::ENV_KEY);
        if ($key && $secret) {
            return new Credentials($key, $secret);
        }
    }

    /**
     * Credentials provider that creates credentials using an ini file stored
     * in the current user's home directory.
     *
     * @param null|string $profile  Profile to use. If not specified will use
     *                              the "default" profile in "~/.incapsula/credentials".
     * @param null|string $filename if provided, uses a custom filename rather
     *                              than looking in the home directory
     *
     * @throws Exception
     *
     * @return Credentials
     */
    public static function ini($profile = null, $filename = null)
    {
        $filename = $filename ?: sprintf('%s/.incapsula/credentials', self::getHomeDir());
        $profile = $profile ?: self::defaultProfile();

        if (!is_readable($filename)) {
            throw new Exception(sprintf('Cannot read credentials from %s', $filename));
        }

        $data = parse_ini_file($filename, true);
        if (false === $data) {
            throw new Exception(sprintf('Invalid credentials file: %s', $filename));
        }

        if (!isset($data[$profile])) {
            throw new Exception(sprintf('Profile "%s" not found in credentials file', $profile));
        }

        if (
            !isset($data[$profile]['incapsula_api_id'])
            || !isset($data[$profile]['incapsula_api_key'])
        ) {
            throw new Exception(sprintf('Profile "%s" contains no credentials', $profile));
        }

        return new Credentials(
            $data[$profile]['incapsula_api_id'],
            $data[$profile]['incapsula_api_key']
        );
    }

    /**
     * Gets the environment's HOME directory if available.
     *
     * @return null|string
     */
    private static function getHomeDir()
    {
        // On Linux/Unix-like systems, use the HOME environment variable
        if (getenv('HOME')) {
            return getenv('HOME');
        }

        // Get the HOMEDRIVE and HOMEPATH values for Windows hosts
        $homeDrive = getenv('HOMEDRIVE');
        $homePath = getenv('HOMEPATH');

        return ($homeDrive && $homePath) ? sprintf('%s%s', $homeDrive, $homePath) : null;
    }
}
