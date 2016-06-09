<?php
return array(
    'key1' => array(
        'value' => 'K1_value',
        'secret' => 'K1_secret',
        'user_id' => 1,
        'api_id' => 2,
        'queries_second' => 1,
        'queries_day' => 111,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => null,
        'processed_by' => null,
        'status' => Key::STATUS_PENDING,
        'purpose' => 'Test purpose.',
        'domain' => 'developer-portal.local',
    ),
    'approvedKey' => array(
        'value' => 'a',
        'secret' => 'b',
        'user_id' => 4,
        'api_id' => 2,
        'queries_second' => 10,
        'queries_day' => 1000,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => '2016-06-08 10:06:18',
        'processed_by' => null,
        'status' => Key::STATUS_APPROVED,
        'purpose' => 'Unit testing',
        'domain' => 'developer-portal.local',
    ),
    'firstKeyForApiWithTwoKeys' => array(
        'value' => 'firstKeyForApiWithTwoKeys_value',
        'secret' => 'firstKeyForApiWithTwoKeys_secret',
        'user_id' => 10,
        'api_id' => 9,
        'queries_second' => 10,
        'queries_day' => 1000,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => '2016-06-08 10:06:18',
        'processed_by' => null,
        'status' => Key::STATUS_APPROVED,
        'purpose' => 'Unit testing',
        'domain' => 'developer-portal.local',
    ),
    'secondKeyForApiWithTwoKeys' => array(
        'value' => 'secondKeyForApiWithTwoKeys_value',
        'secret' => 'secondKeyForApiWithTwoKeys_secret',
        'user_id' => 11,
        'api_id' => 9,
        'queries_second' => 10,
        'queries_day' => 1000,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => '2016-06-08 10:06:18',
        'processed_by' => null,
        'status' => Key::STATUS_APPROVED,
        'purpose' => 'Unit testing',
        'domain' => 'developer-portal.local',
    ),
    'keyToApiOwnedByUser18' => array(
        'value' => 'keyToApiOwnedByUser18_value',
        'secret' => 'keyToApiOwnedByUser18_secret',
        'user_id' => 19,
        'api_id' => 12,
        'queries_second' => 10,
        'queries_day' => 1000,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => '2016-06-08 10:06:18',
        'processed_by' => 18,
        'status' => Key::STATUS_APPROVED,
        'purpose' => 'Unit testing',
        'domain' => 'developer-portal.local',
    ),
    'keyToApiWithoutOwner' => array(
        'value' => 'keyToApiWithoutOwner_value',
        'secret' => 'keyToApiWithoutOwner_secret',
        'user_id' => 19,
        'api_id' => 11,
        'queries_second' => 10,
        'queries_day' => 1000,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => '2016-06-08 10:06:18',
        'processed_by' => null,
        'status' => Key::STATUS_APPROVED,
        'purpose' => 'Unit testing',
        'domain' => 'developer-portal.local',
    ),
    'deniedKeyUser5' => array(
        'user_id' => 5,
        'api_id' => 2,
        'queries_second' => 10,
        'queries_day' => 1000,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => '2016-06-08 10:06:18',
        'status' => Key::STATUS_DENIED,
        'purpose' => 'Unit testing',
        'domain' => 'developer-portal.local',
    ),
    'pendingKeyUser6' => array(
        'user_id' => 6,
        'api_id' => 2,
        'queries_second' => 10,
        'queries_day' => 1000,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => null,
        'status' => Key::STATUS_PENDING,
        'purpose' => 'Unit testing',
        'domain' => 'developer-portal.local',
    ),
    'revokedKeyUser7' => array(
        'user_id' => 7,
        'api_id' => 2,
        'queries_second' => 10,
        'queries_day' => 1000,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => '2016-06-08 10:06:18',
        'status' => Key::STATUS_REVOKED,
        'purpose' => 'Unit testing',
        'domain' => 'developer-portal.local',
    ),
    'pendingKey1_apiWithTwoPendingKeys' => array(
        'user_id' => 12,
        'api_id' => 10,
        'queries_second' => 10,
        'queries_day' => 1000,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => null,
        'status' => Key::STATUS_PENDING,
        'purpose' => 'Unit testing',
        'domain' => 'developer-portal.local',
    ),
    'pendingKey2_apiWithTwoPendingKeys' => array(
        'user_id' => 13,
        'api_id' => 10,
        'queries_second' => 10,
        'queries_day' => 1000,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => null,
        'status' => Key::STATUS_PENDING,
        'purpose' => 'Unit testing',
        'domain' => 'developer-portal.local',
    ),
    'pendingKeyForApiOwnedByUser18' => array(
        'user_id'   => 22,
        'api_id'    => 12,
        'queries_second' => 10,
        'queries_day' => 1000,
        'created' => '2016-06-08 10:06:18',
        'updated' => '2016-06-08 10:06:18',
        'requested_on' => '2016-06-08 10:06:18',
        'processed_on' => null,
        'status' => Key::STATUS_PENDING,
        'purpose' => 'Unit testing',
        'domain' => 'developer-portal.local',
    ),
);
