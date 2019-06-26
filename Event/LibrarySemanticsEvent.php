<?php

namespace OpenLMS\H5PBundle\Event;


use Symfony\Component\EventDispatcher\Event;

/**
 * Class LibrarySemanticsEvent
 *
 * @author Hector Prats <hectorpratsortega@gmail.com>
 */
class LibrarySemanticsEvent extends Event
{
    private $semantics;
    private $name;
    private $majorVersion;
    private $minorVersion;

    /**
     * LibrarySemanticsEvent constructor.
     * @param $semantics
     * @param string $name
     * @param $majorVersion
     * @param $minorVersion
     */
    public function __construct($semantics, string $name, $majorVersion, $minorVersion)
    {
        $this->semantics = $semantics;
        $this->name = $name;
        $this->majorVersion = $majorVersion;
        $this->minorVersion = $minorVersion;
    }

    /**
     * @return mixed
     */
    public function getSemantics()
    {
        return $this->semantics;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getMajorVersion()
    {
        return $this->majorVersion;
    }

    /**
     * @return mixed
     */
    public function getMinorVersion()
    {
        return $this->minorVersion;
    }


}
