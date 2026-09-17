<?php

namespace moDevsome\Palmo\Generators;

use moDevsome\Palmo\Utils\DatabaseUtil;

class PalmoNet
{
    private PalmoNumber $numberGenerator;
    private PalmoBusiness $businessGenerator;

    private array $domainExtensions = [];

    /**
     * Generate a random domain name
     * @return string - A random domain name
     */
    public function domain(): string
    {
        $companySegments = explode(' ', $this->businessGenerator->company());
        $sldSegments = array();
        foreach ($companySegments as $segment) {
            $cleanSegment = preg_replace('/[^a-zA-Z0-9\s]/', '', trim($segment));
            if (strlen($cleanSegment) > 0) {
                $sldSegments[] = strtolower($cleanSegment);
            }
        }

        return implode('-', $sldSegments) . $this->domainExtensions[array_rand($this->domainExtensions)];
    }

    /**
     * Generate a random IP address with V4 format
     * @return string - A random IP
     */
    public function ipv4(): string
    {
        return implode(
            '.',
            [
                $this->numberGenerator->int(1, 255),
                $this->numberGenerator->int(0, 255),
                $this->numberGenerator->int(0, 255),
                $this->numberGenerator->int(0, 255)
            ]
        );
    }

    /**
     * Generate a random IP address with V6 format
     * @return string - A random IP
     */
    public function ipv6(): string
    {
        return implode(
            ':',
            array_map('dechex', [
                $this->numberGenerator->int(1000, 65535),
                $this->numberGenerator->int(1000, 65535),
                $this->numberGenerator->int(1000, 65535),
                $this->numberGenerator->int(1000, 65535),
                $this->numberGenerator->int(1000, 65535),
                $this->numberGenerator->int(1000, 65535),
                $this->numberGenerator->int(1000, 65535),
                $this->numberGenerator->int(1000, 65535),
            ])
        );
    }

    public function __construct(
        DatabaseUtil &$databaseUtil,
        PalmoNumber &$numberGenerator,
        PalmoBusiness &$businessGenerator
    ) {

        // Load the databases
        $dbs = $databaseUtil->get(array('domain_extensions'));
        $this->domainExtensions = $dbs['domain_extensions'];

        $this->numberGenerator = $numberGenerator;
        $this->businessGenerator = $businessGenerator;
    }
}
