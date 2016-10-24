<?php

namespace Craft;

/**
 * Webtexttool Core Service
 *
 * Provides a consistent API for our plugin to access the database
 */
class WebtexttoolService extends BaseApplicationComponent
{
    /**
     * Get a new blank record
     *
     * @param  array $attributes
     * @return Webtexttool_CoreModel
     */
    public function newRecord($attributes = array())
    {
        $model = new Webtexttool_CoreModel();
        $model->setAttributes($attributes);

        return $model;
    }

    /**
     * Get a new blank record
     *
     * @param  array $attributes
     * @return Webtexttool_UserModel
     */
    public function newUserRecord($attributes = array())
    {
        $model = new Webtexttool_UserModel();
        $model->setAttributes($attributes);

        return $model;
    }

    /**
     * Returns all records.
     *
     * @return array
     */
    public function getAllRecords()
    {
        $records = Webtexttool_CoreRecord::model()->findAll(array());

        return Webtexttool_CoreModel::populateModels($records);
    }

    /**
     * Returns a record by its ID.
     *
     * @param int $recordId
     *
     * @return Webtexttool_CoreRecord
     */
    public function getRecordById($recordId)
    {
        $coreRecord = Webtexttool_CoreRecord::model()->findById($recordId);

        if ($coreRecord) {
            return Webtexttool_CoreModel::populateModel($coreRecord);
        }
    }

    /**
     * Returns a record by its entry ID.
     *
     * @param int $entryId
     *
     * @return Webtexttool_CoreModel
     */
    public function getRecordByEntryId($entryId)
    {
        $coreRecord = Webtexttool_CoreRecord::model()->findByAttributes(array('entryId' => $entryId));

        if ($coreRecord) {
            return Webtexttool_CoreModel::populateModel($coreRecord);
        }
    }

    /**
     * Returns a record by its entry ID.
     *
     * @param int $userRecordId
     *
     * @return Webtexttool_UserModel
     */
    public function getAccessTokenById($userRecordId)
    {
        $userRecord = Webtexttool_UserRecord::model()->findById($userRecordId);

        if ($userRecord) {
            return Webtexttool_UserModel::populateModel($userRecord);
        }
    }

    /**
     * Returns a Webtexttool User by user ID.
     *
     * @param int $userId
     *
     * @return Webtexttool_UserModel
     */
    public function getAccessTokenByUserId($userId)
    {
        $userRecord = Webtexttool_UserRecord::model()->findByAttributes(array('userId' => $userId));

        if ($userRecord) {
            return Webtexttool_UserModel::populateModel($userRecord);
        }
    }

    /**
     * Save a new or existing record back to the database.
     *
     * @param Webtexttool_CoreModel $model
     * @return bool
     * @throws Exception
     */
    public function saveRecord(Webtexttool_CoreModel &$model)
    {
        if ($id = $model->getAttribute('id')) {
            $webtexttoolRecord = Webtexttool_CoreRecord::model()->findById($id);
            if (!$webtexttoolRecord) {
                throw new Exception(Craft::t('Can\'t find record with ID "{id}"', array('id' => $id)));
            }
        } else {
            $webtexttoolRecord = new Webtexttool_CoreRecord();
        }

        if ($model->validate()) {
            $attributes = array(
                'entryId' => $model->entryId,
                'wttKeywords' => $model->wttKeywords,
                'wttDescription' => $model->wttDescription,
                'wttLanguage' => $model->wttLanguage,
            );

            foreach ($attributes as $k => $v) {
                $webtexttoolRecord->setAttribute($k, $v);
            }

            if ($webtexttoolRecord->save()) {
                // update id on model (for new records)
                $model->setAttribute('id', $webtexttoolRecord->getAttribute('id'));
                return true;
            } else {
                $model->addErrors($webtexttoolRecord->getErrors());
                return false;
            }
        }
    }

    public function saveAccessToken(Webtexttool_UserModel &$model)
    {
        if ($id = $model->getAttribute('id')) {
            $webtexttoolRecord = Webtexttool_UserRecord::model()->findById($id);
            if (!$webtexttoolRecord) {
                throw new Exception(Craft::t('Can\'t find record with ID "{id}"', array('id' => $id)));
            }
        } else {
            $webtexttoolRecord = new Webtexttool_UserRecord();
        }

        if ($model->validate()) {
            $attributes = array(
                'userId' => $model->userId,
                'accessToken' => $model->accessToken,
            );

            foreach ($attributes as $k => $v) {
                $webtexttoolRecord->setAttribute($k, $v);
            }

            if ($webtexttoolRecord->save()) {
                // update id on model (for new records)
                $model->setAttribute('id', $webtexttoolRecord->getAttribute('id'));
                return true;
            } else {
                $model->addErrors($webtexttoolRecord->getErrors());
                return false;
            }
        }
    }

    /**
     * Delete a record from the database.
     *
     * @param  int $entryId
     * @return int The number of rows affected
     */
    public function deleteRecordByEntryId($entryId)
    {
        return Webtexttool_CoreRecord::model()->deleteAllByAttributes(array('entryId' => $entryId));
    }
}