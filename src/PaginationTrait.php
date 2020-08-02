<?php

namespace TexLab\MyDB;

trait PaginationTrait
{
    /**
     * @var int
     */
    protected $pageSize = null;

    /**
     * @param int $size
     * @return $this
     */
    public function setPageSize(int $size)
    {
        $this->pageSize = $size;
        return $this;
    }

    /**
     * @param int|null $page
     * @return string[][]
     */
    public function getPage(int $page = null): array
    {
        return $this->setPageLimit($page)->get();
    }

    /**
     * @param int|null $page
     * @return $this
     */
    protected function setPageLimit(?int $page)
    {
        if (!is_null($page)) {
            $this->queryCustom['LIMIT'] = (($page - 1) * $this->pageSize) . " , $this->pageSize";
        }
        return $this;
    }

    /**
     * @return int
     */
    public function pageCount(): int
    {
        return (int)ceil($this->rowCount() / $this->pageSize);
    }
}
