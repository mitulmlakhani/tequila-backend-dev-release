<?php

return [
    'error_msg' => [
        'no_data'  => 'No record found',
        'no_permission_found' => ' No permission found.',
        'invalid_request' => ' Invalid request.',
        // Add more error messages as needed
    ],
    'success_msg' => [
        'role' => 'Role created successfully.',
        'role_update' => 'Role updated successfully.',
        'role_delete' => 'Role deleted successfully.',
        'data'=>'Data found successfully',
        'user' => 'User created successfully.',
        'user_update' => 'User updated successfully.',
        'user_delete' => 'User deleted successfully.',
        'permission' => 'Permission created successfully.',
        'permission_update' => 'Permission updated successfully.',
        'permission_delete' => 'Permission deleted successfully.',
        'role_permission_fetch' => 'Permission fetched successfully.',
        'role_permission_assign' => 'Permission assigned successfully.',
        
        // Add more success messages as needed
    ],
    'success' => [
        'status'                => 'success',
        'statusCode'            => 200,
        'insert'                => 'Record saved successfully.',
        'update'                => 'Record updated successfully.',
        'assignRole'            => 'Assigned role successfully.',
        'delete'                => 'Your record deleted successfully.',
        'changestatus'          => 'Change status successfully.',
        'payment_store'         => 'Your amount has been paid successfully.',
        'payment_canel'         => 'Your payment cancel successfully.',
        'refund'                => 'Your amount has been refunded successfully.',
        
    ],
    'error' => [
        'status'            => 'error',
        'statusCode'        => 404,
        'insert'            => 'Record not save successfully.',
        'update'                => 'Record not updated successfully.',
        'common_error'      => 'Something went wrong. Please contact Tequilas POS support.',
        'assignRole'            =>  'Assign role not successfully.',
        'delete'                => 'Your record not delete successfully.',
    ],
    'blank' => [         
        'status'                => 'blank',
        'statusCode'            => 204,
        'blank'                 =>  'The fund is insufficient.'
    ]
];
