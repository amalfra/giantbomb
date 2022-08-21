<?php

namespace Amalfra\GiantBomb\Tests\API;

use \PHPUnit\Framework\TestCase;
use Amalfra\GiantBomb\Client;

class CompaniesTest extends TestCase {
  // companies() tests start

  /** @test */
  public function companiesWithInvalidToken() {
    $config = array(
      'token' => 'invalid',
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->companies();

    $this->assertEquals('Invalid API Key', $resp['error']);
  }

  /** @test */
  public function companiesWithoutOptions() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->companies();

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  /** @test */
  public function companiesWithfield_listOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->companies(
      field_list: 'name',
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(['name'], array_unique(array_keys($resp['results'][0])));
  }

  /** @test */
  public function companiesWithlimitOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->companies(
      limit: 10,
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(10, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(10, count($resp['results']));
  }

  /** @test */
  public function companiesWithoffsetOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->companies(
      offset: 20,
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(20, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  /** @test */
  public function companiesWithsortOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->companies(
      sort: 'id:desc',
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(100, count($resp['results']));
  }

  /** @test */
  public function companiesWithfilterOption() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->companies(
      filter: 'id:7',
    );

    $this->assertEquals('1.0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(100, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(1, count($resp['results']));
    $this->assertEquals(7, $resp['results'][0]['id']);
  }

  // companies() tests end

  // company() tests start

  /** @test */
  public function companyWithInvalidToken() {
    $config = array(
      'token' => 'invalid',
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->company();

    $this->assertEquals('Invalid API Key', $resp['error']);
  }

  /** @test */
  public function companyWithoutId() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->company();

    $this->assertEquals('Object Not Found', $resp['error']);
  }

  /** @test */
  public function companyWithfield_list() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->company(121, 'id,name');

    $this->assertEquals('0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(1, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(['id', 'name'], array_unique(array_keys($resp['results'])));
  }

  /** @test */
  public function company() {
    $config = array(
      'token' => getenv('GIANTBOMB_TESTS_API_KEY'),
    );
    $giantbomb = new Client($config);
    $resp = $giantbomb->company(121);

    $this->assertEquals('0', $resp['version']);
    $this->assertEquals('OK', $resp['error']);
    $this->assertEquals(1, $resp['limit']);
    $this->assertEquals(0, $resp['offset']);
    $this->assertEquals(121, $resp['results']['id']);
  }

  // company() tests end
}
