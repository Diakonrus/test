<?php
return [
    'template_access' => [
        'type' => 2,
        'description' => '������ � ��������',
    ],
    'posts_access' => [
        'type' => 2,
        'description' => '������ � ��������� / �������� ������',
    ],
    'posts_access_action' => [
        'type' => 2,
        'description' => '������ � �������������� / ��������',
    ],
    'users_api_access' => [
        'type' => 2,
        'description' => '������ � ���������� ��������������',
    ],
    'admin' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'user',
            'template_access',
            'posts_access_action',
            'users_api_access',
        ],
    ],
    'user' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'guest',
            'posts_access',
        ],
    ],
    'guest' => [
        'type' => 1,
        'ruleName' => 'userRole',
    ],
];
