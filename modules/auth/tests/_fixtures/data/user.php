<?php

return [
    [
        'id' => 1,
        'username' => 'bayer.hudson',
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_lR',
        //password_0
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO',
        'password_reset_token' => 'ExzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312317',
        'created_at' => '1402312317',
        'updated_at' => '1402312317',
        'email' => 'nicole.paucek@schultz.info',
    ],
    'inactive' => [
        'id' => 2,
        'username' => 'inactive.user',
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_lR',
        //password_0
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO',
        'password_reset_token' => '1xzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312317',
        'created_at' => '1402312317',
        'updated_at' => '1402312317',
        'email' => 'inactive.user@schultz.info',
        'status' => \DmitriiKoziuk\FakeRestApiModules\Auth\entities\User::STATUS_INACTIVE,
    ],
    'deleted' => [
        'id' => 3,
        'username' => 'deleted.user',
        'auth_key' => 'HP187Mvq7Mmm3CTU80dLkGmni_FUH_lR',
        //password_0
        'password_hash' => '$2y$13$EjaPFBnZOQsHdGuHI.xvhuDp1fHpo8hKRSk6yshqa9c5EG8s3C3lO',
        'password_reset_token' => '2xzkCOaYc1L8IOBs4wdTGGbgNiG3Wz1I_1402312317',
        'created_at' => '1402312317',
        'updated_at' => '1402312317',
        'email' => 'deleted.user@schultz.info',
        'status' => \DmitriiKoziuk\FakeRestApiModules\Auth\entities\User::STATUS_DELETED,
    ],
];
