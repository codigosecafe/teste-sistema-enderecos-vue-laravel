<?php

/**
 * Created by Reliese Model.
 */

namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ZipCode
 *
 * @property int $id
 * @property string $zip_code
 * @property string|null $street
 * @property string|null $complement
 * @property string|null $neighborhood
 * @property string|null $city
 * @property string|null $state
 * @property string|null $ibge
 * @property string|null $ddd
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Entities
 */
class ZipCodeEntities extends Entity
{
	use SoftDeletes;
	const ID = 'id';
	const ZIP_CODE = 'zip_code';
	const STREET = 'street';
	const COMPLEMENT = 'complement';
	const NEIGHBORHOOD = 'neighborhood';
	const CITY = 'city';
	const STATE = 'state';
	const IBGE = 'ibge';
	const DDD = 'ddd';
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';
	const DELETED_AT = 'deleted_at';
	protected $connection = 'mysql';
	protected $table = 'zip_code';

	protected $casts = [
		self::ID => 'int'
	];

	protected $dates = [
		self::CREATED_AT,
		self::UPDATED_AT
	];

	protected $fillable = [
		self::ZIP_CODE,
		self::STREET,
		self::COMPLEMENT,
		self::NEIGHBORHOOD,
		self::CITY,
		self::STATE,
		self::IBGE,
		self::DDD
	];
}
