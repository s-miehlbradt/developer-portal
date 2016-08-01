<?php
return array(
    'user1'  => array(
        'user_id'       => 1,
        'email'         => 'test@sil.org',
        'first_name'    => 'Tommy',
        'last_name'     => 'Tester',
        'display_name'  => 'Tommy_Tester',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'user2'  => array(
        'user_id'       => 2,
        'email'         => 'test2@sil.org',
        'first_name'    => 'Test',
        'last_name'     => 'User',
        'display_name'  => 'Test User',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithNoPendingKeys' => array(
        'user_id'       => 3,
        'email'         => 'userWithNoPendingKeys@jaars.net',
        'first_name'    => 'With No',
        'last_name'     => 'Pending Keys',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithApprovedKey' => array(
        'user_id'       => 4,
        'email'         => 'userWithApprovedKey@jaars.net',
        'first_name'    => 'With Approved',
        'last_name'     => 'Key',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithDeniedKey' => array(
        'user_id'       => 5,
        'email'         => 'userWithDeniedKey@jaars.net',
        'first_name'    => 'With Denied',
        'last_name'     => 'Key',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithPendingKey' => array(
        'user_id'       => 6,
        'email'         => 'userWithPendingKey@jaars.net',
        'first_name'    => 'With Pending',
        'last_name'     => 'Key',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithRevokedKey' => array(
        'user_id'       => 7,
        'email'         => 'userWithRevokedKey@jaars.net',
        'first_name'    => 'With Revoked',
        'last_name'     => 'Key',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userThatOwnsASingleApi' => array(
        'user_id'       => 8,
        'email'         => 'userThatOwnsASingleApi@jaars.net',
        'first_name'    => 'That Owns',
        'last_name'     => 'A Single API',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_ADMIN,
        'auth_provider' => 'Insite',
    ),
    'userWithRoleOfUser' => array(
        'user_id'       => 9,
        'email'         => 'userWithRoleOfUser@jaars.net',
        'first_name'    => 'With Role',
        'last_name'     => 'Of User',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithFirstKeyForApiWithTwoKeys' => array(
        'user_id'       => 10,
        'email'         => 'userWithFirstKeyForApiWithTwoKeys@jaars.net',
        'first_name'    => 'With First Key',
        'last_name'     => 'For API With Two Keys',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithSecondKeyForApiWithTwoKeys' => array(
        'user_id'       => 11,
        'email'         => 'userWithSecondKeyForApiWithTwoKeys@jaars.net',
        'first_name'    => 'With Second Key',
        'last_name'     => 'For API With Two Keys',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWith1stPKForApiWithTwoPendingKeys' => array(
        'user_id'       => 12,
        'email'         => 'userWith1stPKRForApiWithTwoPendingKeys@jaars.net',
        'first_name'    => 'With 1st Pending Key For',
        'last_name'     => 'API With 2 Pending Keys',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWith2ndPKForApiWithTwoPendingKeys' => array(
        'user_id'       => 13,
        'email'         => 'userWith2ndPKForApiWithTwoPendingKeys@jaars.net',
        'first_name'    => 'With 2nd Pending Key For',
        'last_name'     => 'API With 2 Pending Keys',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userThatDoesNotOwnAnyApis' => array(
        'user_id'       => 14,
        'email'         => 'userThatDoesNotOwnAnyApis@jaars.net',
        'first_name'    => 'That Does Not',
        'last_name'     => 'Own Any APIs',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_OWNER,
        'auth_provider' => 'Insite',
    ),
    'userWithRoleOfAdmin' => array(
        'user_id'       => 15,
        'email'         => 'userWithRoleOfAdmin@jaars.net',
        'first_name'    => 'With Role',
        'last_name'     => 'Of Admin',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_ADMIN,
        'auth_provider' => 'Insite',
    ),
    'userWithRoleOfOwner' => array(
        'user_id'       => 17,
        'email'         => 'userWithRoleOfOwner@jaars.net',
        'first_name'    => 'With Role',
        'last_name'     => 'Of Owner',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_OWNER,
        'auth_provider' => 'Insite',
    ),
    'user18' => array(
        'user_id'       => 18,
        'email'         => 'user18@jaars.net',
        'first_name'    => 'Is The Owner',
        'last_name'     => 'Of API 12',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_OWNER,
        'auth_provider' => 'Insite',
    ),
    'userWithKeyToApiOwnedByUser18' => array(
        'user_id'       => 19,
        'email'         => 'userWithKeyToApiOwnedByUser18@jaars.net',
        'first_name'    => 'With Key',
        'last_name'     => 'To API Owned By User 18',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithRoleOfAdminButNoKeys' => array(
        'user_id'       => 20,
        'email'         => 'userWithRoleOfAdminButNoKeys@jaars.net',
        'first_name'    => 'With Role Of Admin',
        'last_name'     => 'But No Keys',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_ADMIN,
        'auth_provider' => 'Insite',
    ),
    'ownerThatDoesNotOwnAnyApisOrKeys' => array(
        'user_id'       => 21,
        'email'         => 'ownerThatDoesNotOwnAnyApisOrKeys@jaars.net',
        'first_name'    => 'Owner That Does Not Own',
        'last_name'     => 'Any APIs Or Keys',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_OWNER,
        'auth_provider' => 'Insite',
    ),
    'userWithPendingKeyForApiOwnedByUser18' => array(
        'user_id'       => 22,
        'email'         => 'userWithPendingKeyForApiOwnedByUser18@jaars.net',
        'first_name'    => 'With Pending Key',
        'last_name'     => 'For API Owned By User 18',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userFromInsite' => array(
        'user_id'       => 23,
        'email'         => 'userFromInsite@jaars.net',
        'first_name'    => 'User From',
        'last_name'     => 'Insite',
        'display_name'  => 'User From Insite',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
        'auth_provider_user_identifier' => 'fake-identifier-1462913079',
    ),
    'userFromTrustedAuthProviderLackingIdentifier' => array(
        'user_id'       => 24,
        'email'         => 'userFromTrustedAuthProviderLackingIdentifier@jaars.net',
        'first_name'    => 'User From Trusted Auth Provider',
        'last_name'     => 'Lacking Identifier',
        'display_name'  => 'User From Trusted Auth Provider Lacking Identifier',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
        'auth_provider_user_identifier' => null,
    ),
    'userFromOtherAuthProviderLackingIdentifier' => array(
        'user_id'       => 25,
        'email'         => 'userFromOtherAuthProviderLackingIdentifier@jaars.net',
        'first_name'    => 'User From Other Auth Provider',
        'last_name'     => 'Lacking Identifier',
        'display_name'  => 'User From Other Auth Provider Lacking Identifier',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Google',
        'auth_provider_user_identifier' => null,
    ),
    'userIndividuallyInvitedToSeeApi' => array(
        'user_id'       => 26,
        'email'         => 'userIndividuallyInvitedToSeeApi@jaars.net',
        'first_name'    => 'Individually Invited',
        'last_name'     => 'To See API',
        'status'        => User::STATUS_ACTIVE,
        'created'       => '2016-06-07 10:10:20',
        'updated'       => '2016-06-07 10:10:20',
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Google',
        'auth_provider_user_identifier' => null,
        'verified_nonprofit' => 0,
    ),
    'userNotIndividuallyInvitedToSeeAnyApi' => array(
        'user_id'       => 27,
        'email'         => 'userNotIndividuallyInvitedToSeeAnyApi@jaars.net',
        'first_name'    => 'Not Individually Invited',
        'last_name'     => 'To See Any API',
        'status'        => User::STATUS_ACTIVE,
        'created'       => '2016-06-07 10:42:48',
        'updated'       => '2016-06-07 10:42:48',
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Google',
        'auth_provider_user_identifier' => null,
        'verified_nonprofit' => 0,
    ),
    'userWithEmailDomainNotInvitedToSeeAnyApi' => array(
        'user_id'       => 28,
        'email'         => 'userWithEmailDomainNotInvitedToSeeAnyApi@not-invited-domain.example.com',
        'first_name'    => 'With Email Domain',
        'last_name'     => 'Not Invited To See Any API',
        'status'        => User::STATUS_ACTIVE,
        'created'       => '2016-06-07 14:15:08',
        'updated'       => '2016-06-07 14:15:08',
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Google',
        'auth_provider_user_identifier' => null,
        'verified_nonprofit' => 0,
    ),
    'userWithEmailDomainInvitedToSeeApi' => array(
        'user_id'       => 29,
        'email'         => 'userWithEmailDomainInvitedToSeeApi@invited-domain.example.com',
        'first_name'    => 'Not Individually Invited',
        'last_name'     => 'To See Any API',
        'status'        => User::STATUS_ACTIVE,
        'created'       => '2016-06-07 10:42:48',
        'updated'       => '2016-06-07 10:42:48',
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Google',
        'auth_provider_user_identifier' => null,
        'verified_nonprofit' => 0,
    ),
    'userNotInvitedToSeeAnyApi' => array(
        'user_id'       => 30,
        'email'         => 'userNotInvitedToSeeAnyApi@not-invited-domain.example.com',
        'first_name'    => 'Not Invited',
        'last_name'     => 'To See Any API',
        'status'        => User::STATUS_ACTIVE,
        'created'       => '2016-06-07 15:44:28',
        'updated'       => '2016-06-07 15:44:28',
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Google',
        'auth_provider_user_identifier' => null,
        'verified_nonprofit' => 0,
    ),
    'userWithOneApprovedKeyAndTwoPendingKeys' => array(
        'user_id'       => 31,
        'email'         => 'userWithOneApprovedKeyAndTwoPendingKeys@jaars.net',
        'first_name'    => 'With One Approved Key',
        'last_name'     => 'And Two Pending Keys',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithDeniedKeyForApiOwnedByUser18' => array(
        'user_id'       => 32,
        'email'         => 'userWithDeniedKeyForApiOwnedByUser18@jaars.net',
        'first_name'    => 'With Denied Key',
        'last_name'     => 'For API Owned By User 18',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithRevokedKeyForApiOwnedByUser18' => array(
        'user_id'       => 33,
        'email'         => 'userWithRevokedKeyForApiOwnedByUser18@jaars.net',
        'first_name'    => 'With Revoked Key',
        'last_name'     => 'For API Owned By User 18',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'firstUserWithKeyDependentOnAvd3' => array(
        'user_id'       => 34,
        'email'         => 'firstUserWithKeyDependentOnAvd@1469472196.example.com',
        'first_name'    => 'First User With',
        'last_name'     => 'Key Dependent On AVD',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'secondUserWithKeyDependentOnAvd3' => array(
        'user_id'       => 35,
        'email'         => 'secondUserWithKeyDependentOnAvd@1469472196.example.com',
        'first_name'    => 'Second User With',
        'last_name'     => 'Key Dependent On AVD',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithKeyNotDependentOnAvd3' => array(
        'user_id'       => 36,
        'email'         => 'userWithKeyNotDependentOnAvd@1469472196.example.com',
        'first_name'    => 'User With',
        'last_name'     => 'Key Not Dependent On AVD',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithDeniedKeyRelatedToAvd3' => array(
        'user_id'       => 37,
        'email'         => 'userWithDeniedKeyRelatedToAvd3@1469472196.example.com',
        'first_name'    => 'User With Denied',
        'last_name'     => 'Key Related To AVD',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
    'userWithKeyAllowedByTwoApiVisibilityDomains' => array(
        'user_id'       => 38,
        'email'         => 'userWithKeyAllowedByTwoApiVisibilityDomains@two-avds.example.com',
        'first_name'    => 'User With Key Allowed By',
        'last_name'     => 'Two ApiVisibilityDomains',
        'status'        => User::STATUS_ACTIVE,
        'role'          => User::ROLE_USER,
        'auth_provider' => 'Insite',
    ),
);
