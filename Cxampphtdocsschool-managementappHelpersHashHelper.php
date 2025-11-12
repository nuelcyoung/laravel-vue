<?php

namespace App\Helpers;

use Hashids\Hashids;

/**
 * Class HashHelper
 *
 * A final utility class for encoding and decoding IDs using a custom Hashids instance.
 * This implementation uses the application's APP_KEY as the salt and caches the
 * Hashids instance to ensure performance and consistency across the application.
 *
 * @package App\Helpers
 */
final class HashHelper
{
    /**
     * The cached singleton Hashids instance.
     *
     * @var Hashids|null
     */
    private static ?Hashids $hashids = null;

    /**
     * Retrieves the singleton Hashids instance, creating it if it doesn't exist.
     *
     * This method initializes the Hashids object using the application's APP_KEY
     * as the salt and sets a default minimum hash length. The instance is then
     * cached statically to avoid re-instantiation on subsequent calls.
     *
     * @return Hashids The configured Hashids instance.
     */
    private static function getInstance(): Hashids
    {
        if (self::$hashids === null) {
            // Initialize Hashids with the APP_KEY as salt and a min length.
            // Using getenv() directly as per the original code's pattern.
            // In a typical Laravel app, config('app.key') is the preferred way.
            $salt = getenv('APP_KEY') ?: 'default-fallback-salt';
            $minLength = 10;
            self::$hashids = new Hashids($salt, $minLength);
        }

        return self::$hashids;
    }

    /**
     * Encodes a given numeric ID into a hash string.
     *
     * @param int $id The numeric ID to be encoded.
     * @return string The resulting hash string.
     */
    public static function encode(int $id): string
    {
        return self::getInstance()->encode($id);
    }

    /**
     * Decodes a given hash string into its original numeric ID.
     *
     * Note: The Hashids::decode method returns an array. This helper assumes
     * we are only interested in the first decoded number.
     *
     * @param string $hash The hash string to be decoded.
     * @return int|null The decoded numeric ID, or null if decoding fails or yields an empty result.
     */
    public static function decode(string $hash): ?int
    {
        $decoded = self::getInstance()->decode($hash);

        // Safely return the first element of the decoded array, or null if it's empty.
        return $decoded[0] ?? null;
    }
}
