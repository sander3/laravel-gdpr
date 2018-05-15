<?php

namespace Dialect\Gdpr;

trait Anonymizable
{
	/**
	 * Convert the model instance to a GDPR compliant data anonymizable array.
	 *
	 * @return array
	 */
	public function anonymizable()
	{
		// Only anonymize the fields specified
		if(isset($this->gdprAnonymizableFields)) {
			$this->makeVisible($this->gdprAnonymizableFields);
		}

		return $this->toAnonymizableArray();
	}

	/**
	 * Get the GDPR compliant data anonymizable array for the model.
	 *
	 * @return array
	 */
	public function toAnonymizableArray()
	{
		return $this->toArray();
	}

	public function anonymize() {
		$cols = $this->anonymizable();

		foreach($cols as $colName) {
			$colType = DB::getSchemaBuilder()->getColumnType($this->table(), $colName);

			switch($colType){
				case 'string':
					$replacement = 'xxxxx';
					break;
				case 'integer':
					$replacement = mt_rand(10,100);
					break;
				case 'datetime':
					$int = mt_rand(1262055681,1262055681);
					$replacement = date("Y-m-d H:i:s",$int);
					break;
				default:
					$replacement = 'xxxxx';
			}

			$this->$colName = $replacement;
		}
		$this->save();
	}
}
