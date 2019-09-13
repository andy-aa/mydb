<?php

namespace TexLab\LightDB;

trait DbEntityPageTrait
{
    protected $pageSize;

    public function setPageSize(int $size): object
    {
        $this->pageSize = $size;
        return $this;
    }

    public function getPage(int $page = null): array
    {
        return $this->setPageLimit($page)->get();
    }

    protected function setPageLimit(int $page): object
    {
        if (!is_null($page)) {
            $this->queryCustom['LIMIT'] = (($page - 1) * $this->pageSize) . " , $this->pageSize";
        }
        return $this;
    }

    public function pageCount(): int
    {
        return ceil($this->rowCount() / $this->pageSize);
    }
}
