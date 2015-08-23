<?php

class extMessageMessageGetListProcessor extends modObjectGetListProcessor {
	public $classKey = 'ExtMessage';
		public $languageTopics = array();
		public $defaultSortField = 'date_sent';

		public function initialize() {
				$initialized = parent::initialize();
				$this->setDefaultProperties(array(
						'search' => '',
				));
				return $initialized;
		}

		public function prepareQueryBeforeCount(xPDOQuery $c) {
				//$c->innerJoin('modUser','Sender');
				$c->where(array(
						'recipient' => $this->modx->user->get('id'),
				));
				$search = $this->getProperty('search','');
				if (!empty($search)) {
						$c->andCondition(array(
								'subject:LIKE' => '%'.$search.'%',
								'OR:message:LIKE' => '%'.$search.'%',
						),null,2);
				}
				return $c;
		}

		public function prepareQueryAfterCount(xPDOQuery $c) {
				//$c->select($this->modx->getSelectColumns('ExtMessage','ExtMessage'));
				$c->select(array(
						'ExtMessage.id',
						'ExtMessage.type',
						'ExtMessage.subject',
						'ExtMessage.date_sent',
				));
				return $c;
		}

		public function prepareRow(xPDOObject $object) {
				$objectArray = $object->toArray();
				//$objectArray['sender_name'] = $object->get('sender_username');
				$objectArray['read'] = $object->get('read') ? true : false;
				return $objectArray;
		}

}
return 'extMessageMessageGetListProcessor';