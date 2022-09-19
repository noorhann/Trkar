<?php

return [
    [
        'name' => __('DashBoard'),
        'description' => __('DashBoard'),
        'permissions' => [
            __('Dashbord')  =>  ['admin.dashboard']
        ]
    ],
    [
        'name' => __('Permission Groups'),
        'description' => __('Permission Groups'),
        'permissions' => [
            __('view-all-permission-group')  =>  ['admin.permission-group.index'],
            __('view-one-permission-group')  =>  ['admin.permission-group.show'],
            __('create-permission-group')  =>  ['admin.permission-group.create', 'admin.permission-group.store'],
            __('edit-permission-group')  =>  ['admin.permission-group.edit', 'admin.permission-group.update'],
            // 'delete-permission-group')  =>  ['admin.permission-group.destroy']
        ]
    ],
    [
        'name' => __('Countries'),
        'description' => __('Countries'),
        'permissions' => [
            __('view-all-country')  =>  ['admin.country.index'],
            __('view-one-country')  =>  ['admin.country.show'],
            __('create-country')  =>  ['admin.country.create', 'admin.country.store'],
            __('edit-country')  =>  ['admin.country.edit', 'admin.country.update'],
            // 'delete-country')  =>  ['admin.country.destroy']
            __('Approved Status')  =>  ['admin.country.approved'],
        ]
    ],
    [
        'name' => __('City'),
        'description' => __('City'),
        'permissions' => [
            __('view-all-city')  =>  ['admin.city.index'],
            __('view-one-city')  =>  ['admin.city.show'],
            __('create-city')  =>  ['admin.city.create', 'admin.city.store'],
            __('edit-city')  =>  ['admin.city.edit', 'admin.city.update']
        ]
    ],
    [
        'name' => __('Area'),
        'description' => __('Area'),
        'permissions' => [
            __('view-all-area')  =>  ['admin.area.index'],
            __('view-one-area')  =>  ['admin.area.show'],
            __('create-area')  =>  ['admin.area.create', 'admin.area.store'],
            __('edit-area')  =>  ['admin.area.edit', 'admin.area.update'],
            __('Approved Status')  =>  ['admin.area.approved'],

        ]
    ],
    [
        'name' => __('Categories'),
        'description' => __('Categories'),
        'permissions' => [
            __('view-all-category')  =>  ['admin.category.index'],
            __('view-one-category')  =>  ['admin.category.show'],
            __('create-category')  =>  ['admin.category.create', 'admin.category.store'],
            __('edit-category')  =>  ['admin.category.edit', 'admin.category.update'],
            // 'delete-category')  =>  ['admin.category.destroy'],
            __('get Childs')  =>  ['admin.getChilds'],
            __('get Category By Id')  =>  ['admin.getcategoryByid'],
            __('get Area')  =>  ['admin.getArea'],
            __('Approved Status')  =>  ['admin.category.approved'],
        ]
    ],
    [
        'name' => __('Mades'),
        'description' => __('Mades'),
        'permissions' => [
            __('view-all-car-made')  =>  ['admin.car-made.index'],
            __('view-one-car-made')  =>  ['admin.car-made.show'],
            __('create-car-made')  =>  ['admin.car-made.create', 'admin.car-made.store'],
            __('edit-car-made')  =>  ['admin.car-made.edit', 'admin.car-made.update'],
            // 'delete-car-made')  =>  ['admin.car-made.destroy']
            __('Approved Status')  =>  ['admin.car-made.approved'],

        ]
    ],
    [
        'name' => __('Models'),
        'description' => __('Models'),
        'permissions' => [
            __('view-all-car-model')  =>  ['admin.car-model.index'],
            __('view-one-car-model')  =>  ['admin.car-model.show'],
            __('create-car-model')  =>  ['admin.car-model.create', 'admin.car-model.store'],
            __('edit-car-model')  =>  ['admin.car-model.edit', 'admin.car-model.update'],
            // 'delete-car-model')  =>  ['admin.car-model.destroy']
            __('Approved Status')  =>  ['admin.car-model.approved'],

        ]
    ],
    [
        'name' => __('Engines'),
        'description' => __('Engines'),
        'permissions' => [
            __('view-all-car-engine')  =>  ['admin.car-engine.index'],
            __('view-one-car-engine')  =>  ['admin.car-engine.show'],
            __('create-car-engine')  =>  ['admin.car-engine.create', 'admin.car-engine.store'],
            __('edit-car-engine')  =>  ['admin.car-engine.edit', 'admin.car-engine.update'],
            // 'delete-car-engine')  =>  ['admin.car-engine.destroy']
            __('Approved Status')  =>  ['admin.car-engine.approved'],

        ]
    ],
    [
        'name' => __('Manufacturers'),
        'description' => __('Manufacturers'),
        'permissions' => [
            __('view-all-manufacturer')  =>  ['admin.manufacturer.index'],
            __('view-one-manufacturer')  =>  ['admin.manufacturer.show'],
            __('create-manufacturer')  =>  ['admin.manufacturer.create', 'admin.manufacturer.store'],
            __('edit-manufacturer')  =>  ['admin.manufacturer.edit', 'admin.manufacturer.update'],
            // 'delete-manufacturer')  =>  ['admin.manufacturer.destroy']
            __('Approved Status')  =>  ['admin.manufacturer.approved'],

        ]
    ],
    [
        'name' => __('years'),
        'description' => __('years'),
        'permissions' => [
            __('view-all-year')  =>  ['admin.year.index'],
            __('view-one-year')  =>  ['admin.year.show'],
            __('create-year')  =>  ['admin.year.create', 'admin.year.store'],
            __('edit-year')  =>  ['admin.year.edit', 'admin.year.update'],
            // 'delete-year')  =>  ['admin.year.destroy']
            __('Approved Status')  =>  ['admin.year.approved'],

        ]
    ],
    [
        'name' => __('Original Countries'),
        'description' => __('Original Countries'),
        'permissions' => [
            __('view-all-original-country')  =>  ['admin.original-country.index'],
            __('view-one-original-country')  =>  ['admin.original-country.show'],
            __('create-original-country')  =>  ['admin.original-country.create', 'admin.original-country.store'],
            __('edit-original-country')  =>  ['admin.original-country.edit', 'admin.original-country.update'],
            // 'delete-original-country')  =>  ['admin.original-country.destroy']
            __('Approved Status')  =>  ['admin.original-country.approved'],

        ]
    ],
    [
        'name' => __('products'),
        'description' => __('products'),
        'permissions' => [
            __('view-all-product')  =>  ['admin.product.index'],
            __('view-one-product')  =>  ['admin.product.show'],
            // __('create-product')  =>  ['admin.product.create', 'admin.product.store'],
            // __('edit-product')  =>  ['admin.product.edit', 'admin.product.update'],
            // 'delete-product')  =>  ['admin.product.destroy'],
            __('get-Product-Approved')  =>  ['admin.product.approved']

        ]
    ],
    [
        'name' => __('Orders'),
        'description' => __('Orders'),
        'permissions' => [
            __('view-all-order')  =>  ['admin.order.index'],
            __('view-one-order')  =>  ['admin.order.show'],

        ]
    ],
    [
        'name' => __('Wholesale Orders'),
        'description' => __('Wholesale Orders'),
        'permissions' => [
            __('view-all-wholesale-order')  =>  ['admin.wholesale-order.index'],
            __('view-one-wholesale-order')  =>  ['admin.wholesale-order.show'],

        ]
    ],
    [
        'name' => __('users'),
        'description' => __('users'),
        'permissions' => [
            __('view-all-user')  =>  ['admin.user.index'],
            __('view-one-user')  =>  ['admin.user.show'],
            // __('create-user')  =>  ['admin.user.create', 'admin.user.store'],
            __('edit-user')  =>  ['admin.user.edit', 'admin.user.update'],
            // 'delete-user')  =>  ['admin.user.destroy'],
            __('get-City')  =>  ['admin.getCity'],
            __('get-Area')  =>  ['admin.getArea'],
            __('Approved Status')  =>  ['admin.city.approved'],

        ]
    ],
    [
        'name' => __('Order Status'),
        'description' => __('Order Status'),
        'permissions' => [
            __('view-all-order-status')  =>  ['admin.order-status.index'],
            __('view-one-order-status')  =>  ['admin.order-status.show'],
            __('create-order-status')  =>  ['admin.order-status.create', 'admin.order-status.store'],
            __('edit-order-status')  =>  ['admin.order-status.edit', 'admin.order-status.update'],

        ]
    ],
    [
        'name' => __('report'),
        'description' => __('report'),
        'permissions' => [
            __('view-one-report')  =>  ['admin.report.show'],
        
        ]
    ],
    [
        'name' => __('Admins'),
        'description' => __('Admins'),
        'permissions' => [
            __('view-all-admins')  =>  ['admin.admins.index'],
            __('view-one-admin')  =>  ['admin.admins.show'],
            __('create-admin')  =>  ['admin.admins.create', 'admin.admins.store'],
            __('edit-admin')  =>  ['admin.admins.edit', 'admin.admins.update'],
            // 'delete-admins')  =>  ['admin.admins.destroy']
        ]
    ],
    [
        'name' => __('Store Type'),
        'description' => __('Store Type'),
        'permissions' => [
            __('view-all-store-type')  =>  ['admin.store-type.index'],
            __('view-one-store-type')  =>  ['admin.store-type.show'],
            // __('create-store-type')  =>  ['admin.store-type.create', 'admin.store-type.store'],
            __('edit-store-type')  =>  ['admin.store-type.edit', 'admin.store-type.update'],
            // 'delete-store-type')  =>  ['admin.store-type.destroy']
        ]
    ],
    [
        'name' => __('Stores'),
        'description' => __('Stores'),
        'permissions' => [
            __('view-all-store')  =>  ['admin.store.index'],
            __('view-one-store')  =>  ['admin.store.show'],
            __('create-store')  =>  ['admin.store.create', 'admin.store.store'],
            __('edit-store')  =>  ['admin.store.edit', 'admin.store.update'],
            __('store-type-select')  =>  ['admin.store.type.permission'],
            __('store-branch-approved')  =>  ['admin.branch.approved'],
            // 'delete-store')  =>  ['admin.store.destroy']
            __('Approved Status')  =>  ['admin.store.approved'],

        ]
    ],
    [
        'name' => __('Store Branch'),
        'description' => __('Store Branch'),
        'permissions' => [
            __('view-all-store-branch')  =>  ['admin.store-branch.index'],
            __('view-one-store-branch')  =>  ['admin.store-branch.show'],
            __('create-store-branch')  =>  ['admin.store-branch.create', 'admin.store-branch.store-branch'],
            __('edit-store-branch')  =>  ['admin.store-branch.edit', 'admin.store-branch.update'],
            __('Approved Status')  =>  ['admin.store-branch.approved'],

            // 'delete-store-branch')  =>  ['admin.store-branch.destroy']
        ]
    ],
    // [
    //     'name' => __('Vendor Reject'),
    //     'description' => __('Vendor Reject'),
    //     'permissions' => [
    //         __('view-all-vendor-reject')  =>  ['admin.vendor-reject.index'],
    //         __('view-one-vendor-reject')  =>  ['admin.vendor-reject.show'],
    //         __('create-vendor-reject')  =>  ['admin.vendor-reject.create', 'admin.vendor-reject.store'],
    //         __('edit-vendor-reject')  =>  ['admin.vendor-reject.edit', 'admin.vendor-reject.update'],
    //         // 'delete-vendor-reject')  =>  ['admin.vendor-reject.destroy']
    //     ]
    // ],


    [
        'name' => __('Vendor Staff'),
        'description' => __('Vendor Staff'),
        'permissions' => [
            __('view-all-vendor-staff')  =>  ['admin.vendor-staff.index'],
            __('view-one-vendor-staff')  =>  ['admin.vendor-staff.show'],
            // __('create-vendor-staff')  =>  ['admin.vendor-staff.create', 'admin.vendor-staff.store'],
            __('edit-vendor-staff')  =>  ['admin.vendor-staff.edit', 'admin.vendor-staff.update'],
            // __('delete-vendor-staff')  =>  ['admin.vendor-staff.destroy'],
            __('approved-status-vendor-staff')  =>  ['admin.vendor-staff.approvedVendorStaff'],

        ]
    ],
    [
        'name' => __('Store Vendor Staff'),
        'description' => __('Store Vendor Staff'),
        'permissions' => [
            __('view-all-store-vendor-staff')  =>  ['admin.store-vendor-staff.index'],
            __('view-one-store-vendor-staff')  =>  ['admin.store-vendor-staff.show'],
            __('create-store-vendor-staff')  =>  ['admin.store-vendor-staff.create', 'admin.store-vendor-staff.store'],
            __('edit-store-vendor-staff')  =>  ['admin.store-vendor-staff.edit', 'admin.store-vendor-staff.update'],
            __('approved-store-vendor-staff')  =>  ['admin.store-vendor-staff.approved'],

            __('delete-store-vendor-staff')  =>  ['admin.store-vendor-staff.destroy']
        ]
    ],
    // [
    //     'name' => __('Store Audit Log'),
    //     'description' => __('Store Audit Log'),
    //     'permissions' => [
    //         __('view-all-store-audit-log')  =>  ['admin.store-audit-log.index'],
    //         __('view-one-store-audit-log')  =>  ['admin.store-audit-log.show'],
    //         __('create-store-audit-log')  =>  ['admin.store-audit-log.create', 'admin.store-audit-log.store'],
    //         __('edit-store-audit-log')  =>  ['admin.store-audit-log.edit', 'admin.store-audit-log.update'],
    //         // 'delete-store-audit-log')  =>  ['admin.store-audit-log.destroy']
    //     ]
    // ],
    [
        'name' => __('Stores are awaiting approval'),
        'description' => __('Stores are awaiting approval'),
        'permissions' => [
            __('view-all-store-reject-status')  =>  ['admin.store-reject-status.index'],
            __('view-one-store-reject-status')  =>  ['admin.store-reject-status.show'],
            __('create-store-reject-status')  =>  ['admin.store-reject-status.create', 'admin.store-reject-status.store'],
            __('edit-store-reject-status')  =>  ['admin.store-reject-status.edit', 'admin.store-reject-status.update'],
            __('approved-store-reject-status')  =>  ['admin.store-reject.approved'],
            __('Approved Status')  =>  ['admin.store-reject-status.approved'],

            // 'delete-store-reject-status')  =>  ['admin.store-reject-status.destroy']
        ]
    ],
    [
        'name' => __('Vendor'),
        'description' => __('Vendor'),
        'permissions' => [
            __('view-all-vendor')  =>  ['admin.vendor.index'],
            __('view-one-vendor')  =>  ['admin.vendor.show'],
            __('create-vendor')  =>  ['admin.vendor.create', 'admin.vendor.store'],
            __('edit-vendor')  =>  ['admin.vendor.edit', 'admin.vendor.update'],
            __('Aprroved-vendor')  =>  ['admin.vendor.approved'],
            __('Block-vendor')  =>  ['admin.vendor.approvedBlock'],
            // 'delete-vendor')  =>  ['admin.vendor.destroy']
        ]
    ],
    [
        'name' => __('Payment Method'),
        'description' => __('Payment Method'),
        'permissions' => [
            __('view-all-payment-method')  =>  ['admin.payment-method.index'],
            __('view-one-payment-method')  =>  ['admin.payment-method.show'],
            __('create-payment-method')  =>  ['admin.payment-method.create', 'admin.payment-method.store'],
            __('edit-payment-method')  =>  ['admin.payment-method.edit', 'admin.payment-method.update'],
            // 'delete-payment-method')  =>  ['admin.payment-method.destroy']
        ]
    ],
    [
        'name' => __('Shipping Company'),
        'description' => __('Shipping Company'),
        'permissions' => [
            __('view-all-shipping-company')  =>  ['admin.shipping-company.index'],
            __('view-one-shipping-company')  =>  ['admin.shipping-company.show'],
            __('create-shipping-company')  =>  ['admin.shipping-company.create', 'admin.shipping-company.store'],
            __('edit-shipping-company')  =>  ['admin.shipping-company.edit', 'admin.shipping-company.update'],
            // 'delete-shipping-company')  =>  ['admin.shipping-company.destroy']
        ]
    ],
    [
        'name' => __('attribute-tyre'),
        'description' => __('attribute-tyre'),
        'permissions' => [
            __('view-all-attribute-tyre')  =>  ['admin.attribute-tyre.index'],
            __('view-one-attribute-tyre')  =>  ['admin.attribute-tyre.show'],
            __('create-attribute-tyre')  =>  ['admin.attribute-tyre.create', 'admin.attribute-tyre.store'],
            __('edit-attribute-tyre')  =>  ['admin.attribute-tyre.edit', 'admin.attribute-tyre.update'],
            // 'delete-attribute-tyre')  =>  ['admin.attribute-tyre.destroy'],
            __('search-attribute-width')  =>  ['admin.searchAttributeWidth'],
            __('search-attribute-hight')  =>  ['admin.searchAttributeHight'],
            __('search-attribute-diameter')  =>  ['admin.searchAttributeDiameter'],
            __('search-attribute-Manufacturer')  =>  ['admin.searchAttributeManufacturer'],
            __('search-attribute-SpeadRating')  =>  ['admin.searchAttributeSpeadRating'],
            __('search-attribute-Alex')  =>  ['admin.searchAttributeAlex'],
            __('search-attribute-Load')  =>  ['admin.searchAttributeLoad'],
            __('Approved Status')  =>  ['admin.attribute-tyre.approved'],

        ]
    ],
    [
        'name' => __('attribute-oil'),
        'description' => __('attribute-oil'),
        'permissions' => [
            __('view-all-attribute-oil')  =>  ['admin.attribute-oil.index'],
            __('view-one-attribute-oil')  =>  ['admin.attribute-oil.show'],
            __('create-attribute-oil')  =>  ['admin.attribute-oil.create', 'admin.attribute-oil.store'],
            __('edit-attribute-oil')  =>  ['admin.attribute-oil.edit', 'admin.attribute-oil.update'],

            __('search-attribute-Manufacturer')  =>  ['admin.attribute-oil.searchAttributeManufacturer'],
            __('search-attribute-Sae')  =>  ['admin.attribute-oil.searchAttributeSae'],
            __('search-attribute-Specification')  =>  ['admin.attribute-oil.searchAttributeSpecification'],
            __('search-attribute-Oem')  =>  ['admin.attribute-oil.searchAttributeOem'],
            __('Approved Status')  =>  ['admin.attribute-oil.approved'],

        ]
    ],
    [
        'name' => __('Tyre Type'),
        'description' => __('Tyre Type'),
        'permissions' => [
            __('view-all-tyre-type')  =>  ['admin.tyre-type.index'],

        ]
    ],
    [
        'name' => __('Activity Log'),
        'description' => __('Activity Log'),
        'permissions' => [
            __('view-all-activity-log')  =>  ['admin.activity-log.index'],
            __('show-activity-log')  =>  ['admin.activity-log.show']


        ]
    ],
    [
        'name' => __('Attributes'),
        'description' => __('Attributes'),
        'permissions' => [
            __('view-all-attribute')  =>  ['admin.attribute.index'],
            __('Approved Status')  =>  ['admin.attribute.approved'],

            // 'delete-attribute')  =>  ['admin.attribute.destroy'],


        ]
    ],

];
