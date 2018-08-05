<?php

namespace Numbers\Backend\Db\Test\UnitTests;
class Base extends \PHPUnit\Framework\TestCase {

	/**
	 * Initialize database connection
	 *
	 * @return \Db
	 */
	public function testConnect() {
		$db = \Application::get('db.default_phpunit') ?? \Application::get('db.default');
		$result = \Db::connectToServers('default', $db);
		$this->assertEquals(true, $result['success'], 'Connect failed!');
		return new \Db('default');
	}

	/**
     * @depends testConnect
     */
	public function testSequences($db_object) {
		// plain sequences
		$model = new \Numbers\Backend\Db\Test\Model\Employees();
		$this->assertEquals($model->sequence('id', 'nextval'), $model->sequence('id', 'currval'), 'Plain sequences not working!');
		// extended sequences
		$model = new \Numbers\Backend\Db\Test\Model\Employee\Tenanted();
		$this->assertEquals($model->sequence('id', 'nextval'), $model->sequence('id', 'currval'), 'Extended sequences not working!');
	}

	/**
     * @depends testConnect
     */
	public function testCRUD($db_object) {
		// test table save/insert/update/select
		$model = new \Numbers\Backend\Db\Test\Model\Employees();
		$data = [
			'first_name' => 'First name',
			'last_name' => 'Last name'
		];
		// insert
		$result = $model->collection()->merge($data);
		$this->assertEquals(true, $result['success'], 'Save failed, iteration 1!');
		$this->assertEquals(true, !empty($result['new_pk']['id']), 'Save failed, iteration 2!');
		$data['id'] = $result['new_pk']['id'];
		$data['first_name'] = 'First';
		// update
		$result = $model->collection()->merge($data);
		$this->assertEquals(true, $result['success'], 'Save failed, iteration 3!');
		$result = $model->collection()->get([
			'where' => [
				'id' => $data['id']
			]
		]); // get
		$this->assertEquals(true, $result['success'], 'Get failed!');
		$temp = current($result['data']);
		$this->assertEquals('First', $temp['first_name'], 'Save failed, iteration 3!');
		// delete
		$result = $model->collection()->merge($data, ['flag_delete_row' => true]);
		$this->assertEquals(true, $result['success'], 'Delete failed, iteration 1!');
		$this->assertEquals(true, $result['deleted'], 'Delete failed, iteration 2!');
	}

	/**
     * @depends testConnect
     */
	public function testQueryBuilder($db_object) {
		$model = new \Numbers\Backend\Db\Test\Model\Employees();
		// test delete
		$query = $model->queryBuilder()->delete();
		$query->where('AND', ['id', '>', 0]);
		$query->limit(9999999);
		$result = $query->query();
		$this->assertEquals(true, $result['success'], 'Delete failed!');
		// test insert single values
		$query = $model->queryBuilder()->insert();
		$query->columns([
			'id',
			'first_name',
			'last_name'
		]);
		$values = [];
		$values[] = [
			'id' => $model->sequence('id', 'nextval'),
			'first_name' => $model->db_object->object->randomName('first_name'),
			'last_name' => $model->db_object->object->randomName('last_name'),
		];
		$query->values($values);
		$result = $query->query();
		$this->assertEquals(true, $result['success'], 'Insert failed, iteration 1!');
		// test insert multiple values
		$query = $model->queryBuilder()->insert();
		$query->columns([
			'id',
			'first_name',
			'last_name'
		]);
		$values = [];
		$i = 1;
		while ($i++ < 100) {
			$values[] = [
				'id' => $model->sequence('id', 'nextval'),
				'first_name' => $model->db_object->object->randomName('first_name'),
				'last_name' => $model->db_object->object->randomName('last_name'),
			];
		}
		$query->values($values);
		$result = $query->query();
		$this->assertEquals(true, $result['success'], 'Insert failed, iteration 2!');
		// test update with limit
		$query = $model->queryBuilder()->update();
		$query->set([
			'first_name;=;~~' => 'UPPER(first_name)',
			'last_name;=;~~' => 'UPPER(last_name)'
		]);
		$query->where('AND', ['MOD(id, 2)', '=', 0]);
		$query->limit(9999999);
		$result = $query->query();
		$this->assertEquals(true, $result['success'], 'Update failed, iteration 1!');
		// test full table update
		$query = $model->queryBuilder()->update();
		$query->set([
			'first_name;=;~~' => 'UPPER(first_name)',
			'last_name;=;~~' => 'UPPER(last_name)'
		]);
		$query->where('AND', ['MOD(id, 2)', '=', 1]);
		$query->limit(9999999);
		$result = $query->query();
		$this->assertEquals(true, $result['success'], 'Update failed, iteration 2!');
		// select with union
		$query = $model->queryBuilder()->select();
		$query->columns([
			'first_name' => 'a.first_name',
			'full_name' => "MAX(concat_ws(' ', a.first_name, a.last_name))",
			'counter' => 'COUNT(*)'
		]);
		$query->where('AND', ['MOD(id, 2)', '=', 0]);
		$query->groupby(['a.first_name']);
		$query->having('AND', ['COUNT(*)', '>', 0]);
		// union
		$query2 = \Numbers\Backend\Db\Test\Model\View\Employees::queryBuilderStatic(['alias' => 'b'])->select();
		$query2->columns([
			'first_name' => 'b.first_name',
			'full_name' => "MAX(concat_ws(' ', b.first_name, b.last_name))",
			'counter' => 'COUNT(*)'
		]);
		$query2->where('AND', ['MOD(id, 2)', '=', 1]);
		$query2->groupby(['b.first_name']);
		$query2->having('AND', ['COUNT(*)', '>', 0]);
		$query2->orderby([
			'counter' => SORT_DESC,
			'first_name' => SORT_ASC
		]);
		$query2->offset(5);
		$query2->limit(5);
		$query->union('UNION ALL', $query2);
		$result = $query->query('first_name');
		$this->assertEquals(true, $result['success'], 'Select, iteration 1!');
		$this->assertEquals(true, $result['num_rows'] == 5, 'Select, iteration 2!');
		// select with join and distinct
		$query = $model->queryBuilder()->select();
		$query->distinct();
		$query->columns([
			'id' => 'a.id',
			'first_name' => 'a.first_name',
			'last_name' => 'a.last_name',
		]);
		$query->join('INNER', new \Numbers\Backend\Db\Test\Model\View\Employees(), 'b', 'ON', [
			['AND', ['a.id', '=', 'b.id', true]]
		]);
		$query->limit(1);
		$result = $query->query('id');
		$this->assertEquals(true, $result['success'], 'Select, iteration 3!');
		$this->assertEquals(true, $result['num_rows'] == 1, 'Select, iteration 4!');
	}

	/**
     * @depends testConnect
     */
	public function testClose($db_object) {
		// close database connection
		$result = $db_object->close();
		$this->assertEquals(true, $result['success'], 'Close failed!');
	}
}