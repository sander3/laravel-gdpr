<?php
namespace Dialect\Gdpr;


/**
 * Feature tests asserting that you can anonymize fields on a model.
 *
 * @package dialect/laravel-gdpr
 * @author  Kristoffer Tennivaara <katrineholm@dialect.se>
 * @license The MIT License
 */
class AnonymizerTest extends TestCase
{

	/** @test */
	public function it_can_anonymize_model_with_fields_only() {
		$customer = $this->customer;
		$oldName = $customer->name;
		$customer->anonymize();

		$this->assertNotEquals($customer->name, $oldName);
	}

	/** @test */
	public function it_can_anonymize_related_models_fields() {
		$productoldName = $this->product->name;
		$this->customer->anonymize();

		$this->assertNotEquals($this->product->fresh()->name, $productoldName);
	}
}