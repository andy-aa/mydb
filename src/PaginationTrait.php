<?php

namespace TexLab\MyDB;

trait PaginationTrait
{
    protected $pageSize;

    public function setPageSize(int $size)
    {
        $this->pageSize = $size;
        return $this;
    }

    public function getPage(int $page = null): array
    {
        return $this->setPageLimit($page)->get();
    }

    protected function setPageLimit(int $page)
    {
        if (!is_null($page)) {
            $this->queryCustom['LIMIT'] = (($page - 1) * $this->pageSize) . " , $this->pageSize";
        }
        return $this;
    }

    public function pageCount(): int
    {
        return (int)ceil($this->rowCount() / $this->pageSize);
    }
}
