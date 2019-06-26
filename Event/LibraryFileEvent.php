<?php

namespace OpenLMS\H5PBundle\Event;


use Symfony\Component\EventDispatcher\Event;

/**
 * Class LibraryFileEvent
 *
 * @author Hector Prats <hectorpratsortega@gmail.com>
 */
class LibraryFileEvent extends Event
{
    private $files;
    private $libraryList;
    private $mode;

    /**
     * LibraryFileEvent constructor.
     * @param $files
     * @param array $libraryList
     * @param $mode
     */
    public function __construct($files, array $libraryList, $mode)
    {
        $this->files = $files;
        $this->libraryList = $libraryList;
        $this->mode = $mode;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return array
     */
    public function getLibraryList(): array
    {
        return $this->libraryList;
    }

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }
}
