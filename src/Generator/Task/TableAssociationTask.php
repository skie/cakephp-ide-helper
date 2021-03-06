<?php

namespace IdeHelper\Generator\Task;

use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;
use Cake\ORM\Table;
use IdeHelper\Generator\Directive\Override;

class TableAssociationTask extends ModelTask {

	const CLASS_TABLE = Table::class;

	/**
	 * @var string[]
	 */
	protected $aliases = [
		'\\' . self::CLASS_TABLE . '::belongsTo(0)' => BelongsTo::class,
		'\\' . self::CLASS_TABLE . '::hasOne(0)' => HasOne::class,
		'\\' . self::CLASS_TABLE . '::hasMany(0)' => HasMany::class,
		'\\' . self::CLASS_TABLE . '::belongToMany(0)' => BelongsToMany::class,
	];

	/**
	 * @return \IdeHelper\Generator\Directive\BaseDirective[]
	 */
	public function collect() {
		$models = $this->collectModels();

		$result = [];
		foreach ($this->aliases as $alias => $className) {
			$map = [];
			foreach ($models as $model => $modelClassName) {
				$map[$model] = '\\' . $className . '::class';
			}

			ksort($map);

			$directive = new Override($alias, $map);
			$result[$directive->key()] = $directive;
		}

		return $result;
	}

}
