<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories\Contracts;

use Philipretl\TechnicalTestSourcetoad\Models\CustomerModel;

interface CustomerRepository
{
    public function create(string $first_name, string $last_name): CustomerModel;
}