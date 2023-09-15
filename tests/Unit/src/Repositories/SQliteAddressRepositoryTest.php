<?php

use Philipretl\TechnicalTestSourcetoad\Models\AddressModel;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteAddressRepository;

test('it checks if the address was created correctly', function () {
    /**
     * Arrange
     */
    $sql_expected = 'INSERT INTO addresses(line_1, line_2, city, state, zip, customer_id) '
        . 'VALUES(:line_1, :line_2, :city, :state, :zip, :customer_id)';

    $pdo_spy = Mockery::spy(PDO::class)->makePartial();

    $stmt_spy = Mockery::spy(PDOStatement::class)->makePartial();
    $stmt_spy->shouldReceive('execute')->andReturn();

    $pdo_spy->shouldReceive('prepare')->andReturn($stmt_spy);

    $pdo_spy->shouldReceive('lastInsertId')->andReturn(1);

    $address_repository = new SQliteAddressRepository($pdo_spy);

    /**
     * Act
     */
    $address_model = $address_repository->create(
        'calle false',
        'apartment 2',
        'popayan',
        'cauca',
        '190001',
        1
    );

    /**
     * Assert
     */

    expect($address_model)->toBeInstanceOf(AddressModel::class);

    $pdo_spy->shouldHaveReceived('prepare')->withArgs(function ($sql) use ($sql_expected) {
        expect($sql)->toEqual($sql_expected);
        return true;
    })->once();

    $pdo_spy->shouldHaveReceived('lastInsertId')->once();

    $stmt_spy->shouldHaveReceived('execute')->once();
});

test('it checks if the address list by customer id was loaded correctly', function () {
    /**
     * Arrange
     */
    $sql_expected = 'SELECT * FROM addresses'
        . ' WHERE customer_id = :customer_id';

    $pdo_spy = Mockery::spy(PDO::class)->makePartial();

    $stmt_spy = Mockery::spy(PDOStatement::class)->makePartial();
    $stmt_spy->shouldReceive('execute')->andReturn();

    $address_std_class = new stdClass();
    $address_std_class->id = 1;
    $address_std_class->line_1 = 'calle false 123';
    $address_std_class->line_2 = 'apartment 2';
    $address_std_class->city = 'popayan';
    $address_std_class->state = 'cauca';
    $address_std_class->zip = '190001';
    $address_std_class->customer_id = 1;

    $stmt_spy->shouldReceive('fetchObject')->andReturn($address_std_class, false);

    $pdo_spy->shouldReceive('prepare')->andReturn($stmt_spy);

    $pdo_spy->shouldReceive('lastInsertId')->andReturn(1);

    $address_repository = new SQliteAddressRepository($pdo_spy);

    /**
     * Act
     */
    $addresses = $address_repository->getAddressByCustomer(1);
    /**
     * Assert
     */

    expect($addresses)->toBeArray();
    expect($addresses[0])->toBeInstanceOf(AddressModel::class);

    $pdo_spy->shouldHaveReceived('prepare')->withArgs(function ($sql) use ($sql_expected) {
        expect($sql)->toEqual($sql_expected);
        return true;
    })->once();


    $stmt_spy->shouldHaveReceived('execute')->once();
    $stmt_spy->shouldHaveReceived('fetchObject')->twice();
});