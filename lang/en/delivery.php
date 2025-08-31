<?php

return [
    'title' => 'Delivery Costs',
    'plural' => 'Delivery Costs',
    'singular' => 'Delivery Cost',
    'city' => 'City',
    'cost' => 'Cost',
    'status' => 'Status',
    'notes' => 'Notes',
    'created_at' => 'Created At',
    'active' => 'Active',
    'inactive' => 'Inactive',
    'currency' => 'SAR',
    
    'select_city' => 'Select City',
    'cost_placeholder' => 'Enter delivery cost',
    'notes_placeholder' => 'Optional notes about delivery cost',
    'back_to_suppliers' => 'Back to Suppliers',
    
    'actions' => [
        'title' => 'Actions',
        'add' => 'Add Delivery Cost',
        'edit' => 'Edit Delivery Cost',
        'delete' => 'Delete',
        'save' => 'Save',
        'update' => 'Update',
        'cancel' => 'Cancel',
        'manage' => 'Delivery Costs',
    ],
    
    'messages' => [
        'created' => 'Delivery cost created successfully',
        'updated' => 'Delivery cost updated successfully',
        'deleted' => 'Delivery cost deleted successfully',
        'already_exists' => 'Delivery cost already exists for this city',
        'error' => 'An error occurred. Please try again.',
        'validation_error' => 'Please check the form data and try again',
        'delete_confirm' => 'Are you sure?',
        'delete_warning' => 'This action cannot be undone!',
    ],
    
    'validation' => [
        'city_required' => 'City is required',
        'cost_required' => 'Cost is required',
        'cost_numeric' => 'Cost must be a number',
        'cost_min' => 'Cost must be at least 0',
        'notes_max' => 'Notes cannot exceed 500 characters',
    ],
];
