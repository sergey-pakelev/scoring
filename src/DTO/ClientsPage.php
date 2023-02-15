<?php

namespace App\DTO;

class ClientsPage
{
    private int $pages;

    private int $page;

    /**
     * @var ClientDTO[]
     */
    private array $clients;

    public function getPages(): int
    {
        return $this->pages;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;
        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function getClients(): array
    {
        return $this->clients;
    }

    public function setClients(array $clients): self
    {
        $this->clients = $clients;
        return $this;
    }
}
