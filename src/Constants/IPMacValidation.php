<?php

namespace Shergela\Validations\Constants;

class IPMacValidation
{
    /**
     * The field under validation must be an IP address.
     */
    public const IP = 'ip';

    /**
     * The field under validation must be an IPv4 address.
     */
    public const IPV4 = 'ipv4';

    /**
     * The field under validation must be an IPv6 address.
     */
    public const IPV6 = 'ipv6';

    /**
     * The field under validation must be a MAC address.
     */
    public const MAC_ADDRESS = 'mac_address';
}
