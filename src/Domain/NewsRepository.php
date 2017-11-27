<?php

namespace PozytywneInicjatywy\Dashboard\Domain;

interface NewsRepository
{
    /**
     * @param int $id
     *
     * @throws Exception\NewsNotFoundException
     *
     * @return News
     */
    public function byId(int $id): News;

    /**
     * @return News[]
     */
    public function all(): array;

    /**
     * @param int $howMany
     *
     * @return News[]
     */
    public function newest(int $howMany): array;

    /**
     * @param News $news
     */
    public function save(News $news): void;

    /**
     * @param News $news
     */
    public function delete(News $news): void;
}
