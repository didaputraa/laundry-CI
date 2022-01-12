<?php
	$config['menu'] = array(         
        array(
            "name" => 'dashboard',
            'title' => azlang('Dashboard'),
            'icon' => 'dashboard',
            'url' => 'home',
            'role' => array(),
            'submenu' => array(),
        ),
        array(
            "name" => "master",
            "title" => azlang("Master"),
            "icon" => "archive",
            "url" => "",
            "submenu" => array(
                array(
                    "name" => "outlet",
                    "title" => azlang("Outlet"),
                    "url" => "outlet",
                    "submenu" => array()
                ),
                array(
                    "name" => "customer",
                    "title" => azlang("Customer"),
                    "url" => "customer",
                    "submenu" => array()
                ),
                array(
                    "name" => "outlay_type",
                    "title" => azlang("Outlay Type"),
                    "url" => "outlay_type",
                    "submenu" => array()
                ),
                array(
                    "name" => "product",
                    "title" => azlang("Product"),
                    "url" => "product",
                    "submenu" => array()
                ),
            )
        ),
        array(
            "name" => "transaction",
            "title" => azlang("Transaction"),
            "icon" => "dollar",
            "url" => "sales_transaction",
            'role' => array(
                array(
                    'role_name' => 'role_add_transaction',
                    'role_title' => azlang('Add Transaction')
                ),
                array(
                    'role_name' => 'role_process_transaction',
                    'role_title' => azlang('Process Transaction')
                ),
                array(
                    'role_name' => 'role_edit_transaction',
                    'role_title' => azlang('Edit Transaction')
                ),
                array(
                    'role_name' => 'role_delete_transaction',
                    'role_title' => azlang('Delete Transaction')
                ),
            ),
            "submenu" => array()
        ),
        array(
            "name" => "outlay",
            "title" => azlang("Outlay"),
            "icon" => "external-link",
            "url" => "outlay",
            "submenu" => array()
        ),
        array(
            "name" => "report",
            "title" => azlang("Report"),
            "icon" => "file-o",
            "url" => "",
            "submenu" => array(
                array(
                    "name" => "profit_report",
                    "title" => azlang("Profit Report"),
                    "url" => "profit_report",
                    "submenu" => array(),
                )
            )
        ),
        array(
            "name" => "user",
            "title" => azlang("User"),
            "icon" => "user",
            "url" => "",
            "submenu" => array(
                array(
                    "name" => "user_role",
                    "title" => azlang("Role"),
                    "url" => "role",
                    "submenu" => array()
                ),
                array(
                    "name" => "user_user",
                    "title" => azlang("User"),
                    "url" => "user",
                    "submenu" => array()
                ),
                array(
                    "name" => "user_user_role",
                    "title" => azlang("User Role"),
                    "url" => "user_role",
                    "submenu" => array()
                ),
            )
        ),
        array(
            "name" => 'setting',
            'title' => azlang('Setting'),
            'icon' => 'gear',
            'url' => 'setting',
            'submenu' => array(),
        ),
    );

