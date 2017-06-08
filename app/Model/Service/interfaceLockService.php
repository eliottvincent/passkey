<?php


interface interfaceLockService {

	public function getLocks();

	public function getLock($id);

	public function saveLock($lockArray);

	public function deleteLock($id);

	public function updateLock($lockArray);

	public function checkUnicity($id);

}
