<?php

namespace Philipretl\TechnicalTestSourcetoad\DTO;

final class TableDTO
{
    public function __construct(
        public readonly array $cells,
        public readonly array $data
    ){}
}