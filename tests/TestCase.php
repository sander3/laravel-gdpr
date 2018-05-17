<?php
namespace Dialect\Gdpr;

use Dialect\Gdpr\Anonymizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

/**
 * The base test case class, responsible for bootstrapping the testing environment.
 *
 * @package dialect/laravel-gdpr
 * @author  Kristoffer Tennivaara <katrineholm@dialect.se>
 * @license The MIT License
 */
class TestCase extends \Orchestra\Testbench\TestCase
{
	/**
	 * A dummy product model.
	 *
	 * @var product
	 */
	protected $product;

	/**
	 * A dummy shipment model.
	 *
	 * @var shipment
	 */
	protected $shipment;

	/**
	 * A dummy customer model.
	 *
	 * @var customer
	 */
	protected $customer;

	/**
	 * A dummy order model.
	 *
	 * @var order
	 */
	protected $order;

	/**
	 * Setup the test environment.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->loadLaravelMigrations();
		$this->runTestMigrations();

		$this->product = Product::create()->fresh();
		$this->shipment = Shipment::create(['product_id' => $this->product->id])->fresh();
		$this->customer = Customer::create()->fresh();
		$this->order = Order::create(['product_id' => $this->product->id, 'customer_id' => $this->customer->id]);
	}

	/**
	 * Run migrations for tables used for testing purposes.
	 *
	 * @return void
	 */
	private function runTestMigrations()
	{
		$schema = $this->app['db']->connection()->getSchemaBuilder();

		if (! $schema->hasTable('users')) {
			$schema->create('users', function (Blueprint $table) {
				$table->increments('id');
				$table->string('name')->nullable();
				$table->unsignedInteger('order_id');
				$table->timestamps();
			});
		} else {
			$schema->table('users', function (Blueprint $table) {
				$table->unsignedInteger('order_id')->nullable();
			});
		}
		if (! $schema->hasTable('products')) {
			$schema->create('products', function (Blueprint $table) {
				$table->increments('id');
				$table->string('name')->nullable();
				$table->timestamps();
			});
		}
		if (! $schema->hasTable('shipments')) {
			$schema->create('shipments', function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('product_id');
				$table->timestamps();
			});
		}
		if (! $schema->hasTable('orders')) {
			$schema->create('orders', function (Blueprint $table) {
				$table->increments('id');
				$table->unsignedInteger('product_id');
				$table->unsignedInteger('customer_id');
				$table->timestamps();
			});
		}
		if (! $schema->hasTable('customers')) {
			$schema->create('customers', function (Blueprint $table) {
				$table->increments('id');
				$table->string('name')->nullable();
				$table->timestamps();
			});
		}
	}

	/**
	 * @param \Illuminate\Foundation\Application $app
	 *
	 * @return array
	 */
	protected function getPackageProviders($app)
	{
		return [GdprServiceProvider::class];
	}

	/**
	 * @param array $data
	 * @param array $anonymizeableFields
	 *
	 * @return mixed
	 */
	public function createUser($data = [], $anonymizeableFields = []) {
		$user = factory(User::class)->create($data);
		$user->anonymizeableFields = $anonymizeableFields;

		return $user;
	}
}

class Product extends Model
{
	protected $guarded = [];
	protected $table = 'products';
	protected $gdprWith = ['shipments'];

	protected $gdprAnonymizableFields = ['name'];
	use Anonymizable;
	public function shipments()
	{
		return $this->hasMany(Shipment::class);
	}
	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}

class Shipment extends Model
{
	protected $guarded = [];
	protected $table = 'shipments';
	protected $casts = [
		'product_id' => 'int',
	];
	use Anonymizable;
	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}

class Order extends Model
{
	protected $guarded = [];
	protected $table = 'orders';
	protected $casts = [
		'product_id' => 'int',
		'customer_id' => 'int',
	];
	protected $gdprWith = ['product'];
	use Anonymizable;
	public function product()
	{
		return $this->belongsTo(Product::class);
	}
	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}
}

class Customer extends Model
{
	protected $guarded = [];
	protected $table = 'customers';
	protected $gdprWith = ['orders'];

	use Anonymizable;

	protected $gdprAnonymizableFields = ['name' => 'Anonymized User'];

	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
