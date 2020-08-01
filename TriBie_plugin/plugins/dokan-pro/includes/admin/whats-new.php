<?php
$changelog = array(
    array(
        'version'  => 'Version 2.9.19',
        'released' => '2020-02-03',
        'changes'  => array(
            array(
                'title'       => 'Product Variation Exporting Issue',
                'type'        => 'Fix',
                'description' => 'Attach vendor id while exporting product variations (Import Export)'
            ),
            array(
                'title'       => 'Product Addon Template Override',
                'type'        => 'Fix',
                'description' => 'Add template overwriting support in product addon module (Product Addon)'
            ),
            array(
                'title'       => 'Proxy Bidding Option',
                'type'        => 'Fix',
                'description' => 'Enabling proxy bidding by default option is not taking effect on the frontend (Auction)'
            ),
            array(
                'title'       => 'Inclusive Tax Support for RMA',
                'type'        => 'Fix',
                'description' => 'Add inclusive tax support for RMA (RMA)'
            ),
            array(
                'title'       => 'Store Listing Page Style',
                'type'        => 'Improvement',
                'description' => 'Store listing filter styles so that it works almost any theme'
            ),
            array(
                'title'       => 'Store Listing Page Style',
                'type'        => 'Improvement',
                'description' => 'Subscription installment count issue for paypal (Subscription)'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.18',
        'released' => '2020-01-15',
        'changes'  => array(
            array(
                'title'       => 'JS Loading issue',
                'type'        => 'Fix',
                'description' => 'Dokan Pro core js loading issue in some pages'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.17',
        'released' => '2020-01-13',
        'changes'  => array(
            array(
                'title'       => 'Mapbox Support',
                'type'        => 'New',
                'description' => 'Mapbox support for geolocation. (Geolocation)'
            ),
            array(
                'title'       => 'Store List Filtering',
                'type'        => 'New',
                'description' => 'Add store filtering option in store listing page.'
            ),
            array(
                'title'       => 'Auction Stock Management',
                'type'        => 'New',
                'description' => 'Add stock management for auction product. (Auction)'
            ),
            array(
                'title'       => 'RMA Support for Bookable Product',
                'type'        => 'New',
                'description' => 'Add RMA support for bookable product. (RMA)'
            ),
            array(
                'title'       => 'Shipping Issues',
                'type'        => 'Fix',
                'description' => 'Various shipping zone issue'
            ),
            array(
                'title'       => 'Linkedin API Issue',
                'type'        => 'Fix',
                'description' => 'Update linkedin API to version 2.0 (Social Login)'
            ),
            array(
                'title'       => 'Store Support',
                'type'        => 'Fix',
                'description' => 'Make support-tickets template overridable and remove depricated warning. (Store Support)'
            ),
            array(
                'title'       => 'Verification Email Issue',
                'type'        => 'Fix',
                'description' => 'If subscription on registration option is enabled the verification email is not working for customer.'
            ),
            array(
                'title'       => 'Non Connected Vendor',
                'type'        => 'Fix',
                'description' => 'If a vendor is not connected to stripe, don\'t deduct his withdraw balance. (Stripe Connect)'
            ),
            array(
                'title'       => 'Non Connected Vendor',
                'type'        => 'Fix',
                'description' => 'Update Stripe SDK to make it compatible with PHP v7.4'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.16',
        'released' => '2019-12-05',
        'changes'  => array(
            array(
                'title'         => 'Product Addon',
                'type'          => 'New',
                'description'   => 'We have integrated WooCommerce product addon with dokan. (Product Addon)'
            ),
            array(
                'title'         => 'Store Review Edit',
                'type'          => 'Improvement',
                'description'   => 'Admin will be able to view and edit store review from now on. (Store Review)'
            ),
            array(
                'title'         => 'Single Product Multivendor Product Listing Issue',
                'type'          => 'fix',
                'description'   => 'Single product multiple vendor was showing multiple product on the category page. This issue has been fixed in this release. (SPMV)'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.15',
        'released' => '2019-11-19',
        'changes'  => array(
            array(
                'title'         => 'Dokan Shortcode Gutenberg Block',
                'type'          => 'New',
                'description'   => 'Admin now will be able to insert dokan shortcode with ease.'
            ),
            array(
                'title'         => 'Single Seller Mode Option',
                'type'          => 'New',
                'description'   => 'If this option is enabled, customer can no longer purchase product from multiple vendor at once.'
            ),
            array(
                'title'         => 'Show Warranty Policy Tab in Single Product Page',
                'type'          => 'New',
                'description'   => 'If there is any warranty policy for a product, customer will get to see the policy before placing the order (RMA).'
            ),
            array(
                'title'         => 'Shipping, Tax and Discount Data in Staff Order Email',
                'type'          => 'Fix',
                'description'   => 'Shipping, tax and discount data weren\'t included in the order email. These data will be attached with the order email from now on (Vendor Staff).'
            ),
            array(
                'title'         => 'Geolocation Search Issue',
                'type'          => 'Fix',
                'description'   => 'When store-listing page is set to home page, geolocaion search redirected to a wrong page which has been fixed in this release (Geolocation).'
            ),
            array(
                'title'         => 'Vendor Verification Issue With Social Media Account',
                'type'          => 'Fix',
                'description'   => 'Vendor verifiation with social media account wasn\'t working which has been resolved in this release (Vendor Verification).'
            ),
            array(
                'title'         => 'Booking Overlapping Issue',
                'type'          => 'Fix',
                'description'   => 'Same day bookings was overlapping in calendar day view in vendor dashboard which has been fixed now (Bookings).'
            ),
            array(
                'title'         => 'Hide Ended & Won Auction Products',
                'type'          => 'Fix',
                'description'   => 'Ended and won auction products in vendor store page will be hidden if admin wants to (Auctions).'
            ),
            array(
                'title'         => 'Warranty Add-on Price Issue',
                'type'          => 'Fix',
                'description'   => 'Warranty add-on price wasn\'t being applied on variable products which has been fixed now (RMA).'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.14',
        'released' => '2019-10-02',
        'changes'  => array(
            array(
                'title'         => 'Add Combine Commission (Percentage + Fixed)',
                'type'          => 'New',
                'description'   => 'Admin can now charge commission in both percentage and fixed fee.'
            ),
            array(
                'title'         => 'Elementor Biography Tab',
                'type'          => 'New',
                'description'   => 'Add support for vendor biography in Elementor builder (Elementor).'
            ),
            array(
                'title'         => 'Admin is only able to add 10 categories for a store in admin backend',
                'type'          => 'Fix',
                'description'   => 'Admin is only able to add 10 categories for a store in admin backend. Which is fixed now.'
            ),
            array(
                'title'         => 'Vendor verification widget doesn\'t Show Up',
                'type'          => 'Fix',
                'description'   => 'Vendor verification widget doesn\'t show up in the store page'
            ),
            array(
                'title'         => 'Vacation Mode is Not Making the Product Offline',
                'type'          => 'Fix',
                'description'   => ' Vacation mode is not making the product offline (Seller Vacation).'
            ),
            array(
                'title'         => 'Product is Not Visible in the Shop Page',
                'type'          => 'Fix',
                'description'   => 'Assigning a new product to a vendor from admin backend doesn\'t show up in the shop page.'
            ),
            array(
                'title'         => 'Stripe Subscription With Coupon',
                'type'          => 'Fix',
                'description'   => 'Apply coupon on stripe subscription if there is any (Stripe Connect).'
            ),
            array(
                'title'         => 'Store Support Messages',
                'type'          => 'Fix',
                'description'   => 'There are only 5 store support messages is appearing. (Store Support)'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.13',
        'released' => '2019-08-29',
        'changes'  => array(
            array(
                'title'         => 'Scheduled Announcement',
                'type'          => 'New',
                'description'   => 'Add scheduled announcement option for admin.'
            ),
            array(
                'title'         => 'Identity Verification in Live Chat',
                'type'          => 'New',
                'description'   => 'Add identity verification and unread message count in live chat (Live Chat Module).'
            ),
            array(
                'title'         => 'Admin Defined Default Geolocation',
                'type'          => 'New',
                'description'   => 'Add admin defined location on Geolocation map to be shown instead of default `Dhaka, Bangladesh` when there is no vendor or product found (Geolocation Module).'
            ),
            array(
                'title'         => 'Guest User Checkout',
                'type'          => 'fix',
                'description'   => 'Guest user is unable to checkout with stripe (Stripe Module).'
            ),
            array(
                'title'         => 'Stripe Certificate Missing Issue',
                'type'          => 'Fix',
                'description'   => 'Add ca-certificate file to allow certificate verification of stripe SSL (Stripe Module).'
            ),
            array(
                'title'         => 'Shipping doesn\'t Work on Variable Product',
                'type'          => 'Fix',
                'description'   => 'If variable product is created by admin for a vendor, vendor shipping method doesn\'t work.'
            ),
            array(
                'title'         => 'Payment Fields are Missing in Edit Vendor Page',
                'type'          => 'Fix',
                'description'   => 'Set default bank payment object if it\'s not found from the API response.'
            ),
            array(
                'title'         => 'Product Lot Discount on Sub Orders',
                'type'          => 'Fix',
                'description'   => 'Product lot discount is getting applied on sub-orders even though discount is disabled.'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.12',
        'released' => '2019-08-09',
        'changes'  => array(
            array(
                'title'         => 'Stripe 3D Secure and Authentication',
                'type'          => 'New',
                'description'   => 'Add stripe 3D secure and strong customer authentication (Stripe Connect Module).'
            ),
            array(
                'title'         => 'Subscription Upgrade Downgrade',
                'type'          => 'New',
                'description'   => 'Add subscription pack upgrade downgrade option for vendors (Subscription Module).'
            ),
            array(
                'title'         => 'Wholesale Options in Backend',
                'type'          => 'New',
                'description'   => 'Add wholesale options in the admin backend (Wholesale Module).'
            ),
            array(
                'title'         => 'Elementor Vendor Verification Widget',
                'type'          => 'New',
                'description'   => 'Add support for vendor verification widget (Elementor Module).'
            ),
            array(
                'title'         => 'Product Discount',
                'type'          => 'Fix',
                'description'   => 'Attach product discount in order details.'
            ),
            array(
                'title'         => 'Coupon Type Changes',
                'type'          => 'Fix',
                'description'   => 'Coupon discount type changes on coupon edit. This issue has been fixed in this release.'
            ),
            array(
                'title'         => 'Order Refund from Admin Backend',
                'type'          => 'Fix',
                'description'   => 'Refund calculation was wrong when it\'s done from the admin backend. It\'s been fixed in this release.'
            ),
            array(
                'title'         => 'Dokan Admin Settings',
                'type'          => 'Improvement',
                'description'   => 'Dokan admin settings rearrange and refactor.'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.11',
        'released' => '2019-07-02',
        'changes'  => array(
            array(
                'title'         => 'Elementor Module',
                'type'          => 'New',
                'description'   => 'Add elementor page builder widgets for Dokan.'
            ),
            array(
                'title'         => 'Single Product Multi Vendor',
                'type'          => 'Improvement',
                'description'   => 'Single product multiple vendor hide duplicates based on admin settings.'
            ),
            array(
                'title'         => 'Zone Wise Vendor Shipping',
                'type'          => 'Fix',
                'description'   => 'Limit your zone location by default was enabled, which is incorrect. It should only be enabled when admin limit the zone.'
            ),
            array(
                'title'         => 'Vendor Biography Tab',
                'type'          => 'Fix',
                'description'   => 'Line break and youtube video was not working in vendor biography tab. We have fixed the issue in this update.'
            )
        )
    ),
    array(
        'version'  => 'Version 2.9.10',
        'released' => '2019-06-19',
        'changes'  => array(
            array(
                'title'         => 'Vendor Biography Tab',
                'type'          => 'New',
                'description'   => 'Add vendor biography tab in dokan store page'
            ),
            array(
                'title'         => 'Filtering and Searching Options',
                'type'          => 'New',
                'description'   => 'Add filtering and searching option in admin report logs area'
            ),
            array(
                'title'         => 'Vendor Vacation',
                'type'          => 'New',
                'description'   => 'Add multiple vacation date system for vendor'
            ),
            array(
                'title'         => 'Refund Request Validation',
                'type'          => 'Fix',
                'description'   => 'Validate refund request in seller dashboard'
            ),
            array(
                'title'         => 'Coupon Validation',
                'type'          => 'Fix',
                'description'   => 'Ensure coupon works on vendors product not the cart'
            ),
            array(
                'title'         => 'Best Selling and Top Rated Widget',
                'type'          => 'Fix',
                'description'   => 'Remove subscription product from best selling and top rated product widget'
            ),
            array(
                'title'         => 'Subscription Renew and Cancellation',
                'type'          => 'Fix',
                'description'   => 'Subscription renew and cancellation with PayPal'
            ),
            array(
                'title'         => 'Store Progressbar',
                'type'          => 'Improvement',
                'description'   => 'Store progress serialization and congrats message on 100% profile completenes'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.9',
        'released' => '2019-05-15',
        'changes'  => array(
            array(
                'title'         => 'Translation issue',
                'type'          => 'Fix',
                'description'   => 'Make coupon strings translatable'
            ),
            array(
                'title'         => 'Report Abuse Module thumbnail',
                'type'          => 'Improvement',
                'description'   => 'Add thumbnail and description of report abuse module'
            ),
            array(
                'title'         => 'Social login and vendor verification',
                'type'          => 'Improvement',
                'description'   => 'Refactor social login and vendor verification module'
            ),
            array(
                'title'         => 'Change Moip brand to wirecard',
                'type'          => 'Improvement',
                'description'   => 'Rename Moip to Wirecard payment gateway'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.8',
        'released' => '2019-05-07',
        'changes'  => array(
            array(
                'title'         => 'Report Abuse',
                'type'          => 'New',
                'description'   => 'Customer will be able to report againts product.'
            ),
            array(
                'title'         => 'Vendor Add Edit',
                'type'          => 'New',
                'description'   => 'Admin will be able to create new Vendor from the backend'
            ),
            array(
                'title'         => 'Dokan Booking',
                'type'          => 'New',
                'description'   => 'Add restricted days functionality in dokan booking module'
            ),
            array(
                'title'         => 'Single Product Multi Vendor',
                'type'          => 'New',
                'description'   => 'Enable SPMV for admins to duplicate products from admin panel'
            ),
            array(
                'title'         => 'Store Category',
                'type'          => 'Fix',
                'description'   => 'Fix store category list table search form'
            ),
            array(
                'title'         => 'Duplicate Subscription Form',
                'type'          => 'Fix',
                'description'   => 'Subscription form is rendering twice in registration form'
            ),
            array(
                'title'         => 'Subscription Cancellation',
                'type'          => 'Fix',
                'description'   => 'Cancel subscription doesn\'t work for manually assigned subscription'
            ),
            array(
                'title'         => 'Vendor Shipping',
                'type'          => 'new',
                'description'   => 'Add wilecard and range matching for vendor shipping zone'
            ),
            array(
                'title'         => 'Depricated Functions',
                'type'          => 'Improvement',
                'description'   => 'Replace get_woocommerce_term_meta with get_term_meta as it was deprecated'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.7',
        'released' => '2019-03-25',
        'changes'  => array(
            array(
                'title'         => 'Store Category',
                'type'          => 'New',
                'description'   => 'Vendor will be able to register under specefic cateogry. ei(Furniture, Mobile)'
            ),
            array(
                'title'         => 'YITH WC Brand Compatible',
                'type'          => 'New',
                'description'   => 'Make Dokan YITH WC Brand add-on compatible'
            ),
            array(
                'title'         => 'Date and refund column in admin logs area',
                'type'          => 'New',
                'description'   => 'Add date and refund column in admin logs area to get more detaild overview.'
            ),
            array(
                'title'         => 'Product Status',
                'type'          => 'New',
                'description'   => 'Change product status according to subscription status '
            ),
            array(
                'title'         => 'Show button for non logged-in user',
                'type'          => 'Fix',
                'description'   => 'Show button for non logged-in user'
            ),
            array(
                'title'         => 'Refund Calculation Issue',
                'type'          => 'Fix',
                'description'   => 'Send refund admin commission to customer '
            ),
            array(
                'title'         => 'Error on subscription cancellation email ',
                'type'          => 'Fix',
                'description'   => 'There was an error on subscription cancellation, which has been fixed in this release.'
            ),
            array(
                'title'         => 'Trial Subscription',
                'type'          => 'Improvement',
                'description'   => 'When a vendor subscribe to a trial subscription, make all other trial to non-trial subscription for that vendor'
            ),
            array(
                'title'         => 'Social Login Issue',
                'type'          => 'Fix',
                'description'   => 'Update social login and vendor verification API'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.5',
        'released' => '2019-02-18',
        'changes'  => array(
            array(
                'title'         => 'Automate order refund process via stripe',
                'type'          => 'New',
                'description'   => 'Vendor can now send automatic refund to their customer from vendor order dashboard'
            ),
            array(
                'title'         => 'Add trial subscription (Subscription Module)',
                'type'          => 'New',
                'description'   => 'Admin can now offer trail subscription for vendors'
            ),
            array(
                'title'         => 'Product type & gallery image restriction',
                'type'          => 'New',
                'description'   => 'Admin can now restrict product type & gallery image upload for vendor subscription'
            ),
            array(
                'title'         => 'Privacy and Policy',
                'type'          => 'New',
                'description'   => 'Admin can configure privacy policy info for frontend product enquiry form'
            ),
            array(
                'title'         => 'Email notification for store follow',
                'type'          => 'Fix',
                'description'   => 'Now vendor can get email notification on store follows and unfollows'
            ),
            array(
                'title'         => 'Unable to select country or state in vendor shipping',
                'type'          => 'Fix',
                'description'   => 'Country dropdown not working in shipping and announcement'
            ),
            array(
                'title'         => 'Admin report logs calculation issue is fixed in admin dashboard',
                'type'          => 'Fix',
                'description'   => 'Some calculation issue fixed in admin reports'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.4',
        'released' => '2019-01-23',
        'changes'  => array(
            array(
                'title'         => 'Wholesale Module(Business, Enterprise Package)',
                'type'          => 'New',
                'description'   => 'Added new Wholesale module. Vendor can offer wholesale price for his/her products.'
            ),
            array(
                'title'         => 'Return and Warranty Module(Professional, Business, Enterprise Package)',
                'type'          => 'New',
                'description'   => 'Vendor can offer warranty and return system for their products and customer can take this warranty offers'
            ),
            array(
                'title'         => 'Subscription cancellation email',
                'type'          => 'New',
                'description'   => 'Now admin can get email if any subscription is cancelled by vendor'
            ),
            array(
                'title'         => 'Subscription Unlimited pack',
                'type'          => 'New',
                'description'   => 'Admin can offer unlimited package for vendor subscription'
            ),
            array(
                'title'         => 'MOIP Gateway connection issue',
                'type'          => 'Fix',
                'description'   => 'Change some gateway api params for connection moip gateway'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.9.3',
        'released' => '2018-12-18',
        'changes'  => array(
            array(
                'title'         => 'ShipStation Module(Business, Enterprise Package)',
                'type'          => 'New',
                'description'   => 'Added new ShipStation module'
            ),
            array(
                'title'         => 'Follow Store Module(Professional, Business, Enterprise Package)',
                'type'          => 'New',
                'description'   => 'Added Follow Store module'
            ),
            array(
                'title'         => 'Product Quick Edit',
                'type'          => 'New',
                'description'   => 'Added Quick edit option for product in vendor dashboard.'
            ),
            array(
                'title'         => 'Searching Option',
                'type'          => 'New',
                'description'   => 'Add searching option in dokan vendor and refund page'
            ),
            array(
                'title'         => 'Admin Tools & Subscription Page Improvement',
                'type'          => 'Improvement',
                'description'   => 'Rewrite admin tools & subscription page in vue js'
            ),
            array(
                'title'         => 'Filter form & Map in Category Page',
                'type'          => 'Fix',
                'description'   => 'Show filter form and map in product category pages (geolocation module)'
            ),
            array(
                'title'         => 'Bookable Product Commission',
                'type'          => 'Fix',
                'description'   => 'Add per product commission option for bookable product'
            ),
            array(
                'title'         => 'Refund Calculation Issue',
                'type'          => 'Fix',
                'description'   => 'Refund calculation is wrong when shipping fee recipient is set to vendor'
            ),
            array(
                'title'         => 'Bulk Refund is Not Working',
                'type'          => 'Fix',
                'description'   => 'Approving batch refund is not working in admin backend'
            ),
            array(
                'title'         => 'Product Stock Issue on Refund',
                'type'          => 'Fix',
                'description'   => 'Increase stock ammount if the product is refunded'
            ),
            array(
                'title'         => 'Category Restriction Issue',
                'type'          => 'Fix',
                'description'   => 'Booking product category restriction for subscription pack is not working'
            )
        )
    ),
    array(
        'version'  => 'Version 2.9.2',
        'released' => '2018-11-09',
        'changes'  => array(
            array(
                'title'         => 'Geolocation Module',
                'type'          => 'New',
                'description'   => 'Added zoom level settings in geolocation module.'
            ),
            array(
                'title'         => 'Zone Wise Shipping',
                'type'          => 'New',
                'description'   => 'Added shipping policy and processing time settings in zone wise shipping.'
            ),
            array(
                'title'         => 'Rest API for Store Reviews',
                'type'          => 'New',
                'description'   => 'Added rest API support for store review post type.'
            ),
            array(
                'title'         => 'Show Tax on Bookable Product',
                'type'          => 'Fix',
                'description'   => 'Show tax on bookable product for vendor'
            ),

            array(
                'title'         => 'Product Importing Issue for Subscribed Vendor',
                'type'          => 'Fix',
                'description'   => 'Allow vendor to import only allowed number of products.'
            ),
            array(
                'title'         => 'Product and Order Discount Issue',
                'type'          => 'Fix',
                'description'   => 'Product and order discount for vendor is not working.'
            ),
            array(
                'title'         => 'Shipping Class Issue',
                'type'          => 'Fix',
                'description'   => 'Shipping class is not saving for bookable product.'
            )
        )
    ),
    array(
        'version'  => 'Version 2.9.0',
        'released' => '2018-10-03',
        'changes'  => array(
            array(
                'title'         => 'Geolocation Module',
                'type'          => 'New',
                'description'   => 'Enable this module to let the customers search for a specific product or vendor using any location they want.'
            ),
            array(
                'title'         => 'Moip Payment Gateway',
                'type'          => 'New',
                'description'   => 'Use one of the most popular payment system known for it\'s efficiency with Dokan.'
            ),
            array(
                'title'         => 'Allow Vendor to crate tags',
                'type'          => 'New',
                'description'   => 'Your vendors don\'t need to rely on prebuilt tags anymore. Now they can create their own in seconds'
            ),
            array(
                'title'         => 'Responsive Admin Pages',
                'type'          => 'New',
                'description'   => 'All the admin backend pages is now responsive for all devices'
            ),
            array(
                'title'         => 'Staff email for New Order',
                'type'          => 'New',
                'description'   => 'Staff will able to get all emails for new order from customer'
            )
        )
    ),
    array(
        'version'  => 'Version 2.8.3',
        'released' => '2018-07-19',
        'changes'  => array(
            array(
                'title'         => 'Live Chat Module',
                'type'          => 'Fix',
                'description'   => 'Right now the chat box is available in customer myaccount page and also make responsive chat box window'
            ),
            array(
                'title'         => 'Statement and Refund',
                'type'          => 'Fix',
                'description'   => 'Change core table structure for refund and statements. Now its easy to understand for vendor to check her statements. Also fixed statement exporting problem'
            ),
            array(
                'title'         => 'Zonewise Shipping',
                'type'          => 'Fix',
                'description'   => 'Shipping state rendering issue fixed. If any country have no states then states not showing undefine problem'
            ),
            array(
                'title'         => 'Stripe Module',
                'type'          => 'Fix',
                'description'   => 'Card is automatically saved if customer does not want to save his/her card info during checkout'
            )
        )
    ),
    array(
        'version'  => 'Version 2.8.2',
        'released' => '2018-06-29',
        'changes'  => array(
            array(
                'title'         => 'Live Chat Module',
                'type'          => 'New',
                'description'   => 'Vendors will now be able to provide live chat support to visitors and customers through this TalkJS integration. Talk from anywhere in your store, add attachments, get desktop notifications, enable email notifications, and store all your messages safely in Vendor Inbox.<br><iframe width="560" height="315" src="https://www.youtube.com/embed/BHuTLjY78cY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'
            ),
            array(
                'title'         => 'Added Refund and Announcement REST API',
                'type'          => 'New',
                'description'   => 'Admins can now modify refund and announcement section of Dokan easily through the Rest API'
            ),
            array(
                'title'         => 'Local pickup is visible when the cost is set to zero',
                'type'          => 'Fix',
                'description'   => 'When local pickup cost in Dokan Zone-wise shipping is set to zero it will show on the cart/checkout page'
            ),
            array(
                'title'         => 'Store Support ticket is visible in customer dashboard support menu',
                'type'          => 'Fix',
                'description'   => 'Now customers can view the support tickets they create in My Account> support ticket area'
            ),
            array(
                'title'         => 'Added tax and shipping functionalities in auction product',
                'type'          => 'Fix',
                'description'   => 'Now admins can add shipping and tax rates for auctionable product'
            ),
            array(
                'title'         => 'Appearance module for admins',
                'type'          => 'Fix',
                'description'   => 'Now Admins can view Color Customizer settings in backend without any problem'
            ),
            array(
                'title'         => 'Unable to delete vendor form admin panel',
                'type'          => 'Fix',
                'description'   => 'Admin was unable to delete a vendor from admin panel'
            ),
        )
    ),
    array(
        'version'  => 'Version 2.8.0',
        'released' => '2018-05-01',
        'changes'  => array(
            array(
                'title'         => 'Introduction of REST APIs',
                'type'          => 'New',
                'description'   => 'We have introduced REST APIs in dokan'
            ),
            array(
                'title'         => 'Zone wize shipping',
                'type'          => 'New',
                'description'   => 'We have introduced zone wize shipping functionality similar to WooCommerce in dokan. <img src="https://wedevs-com-wedevs.netdna-ssl.com/wp-content/uploads/2018/04/dokan-vendor-dashboard-settings-shipping-method-settings.gif">'
            ),
            array(
                'title'         => 'Earning suggestion for variable product',
                'type'          => 'New',
                'description'   => 'As like simple product, vendor will get to see the earning suggestion for variable product as well'
            ),
            array(
                'title'         => 'Confirmation on subscription cancellation',
                'type'          => 'New',
                'description'   => 'Cancellation of a subscription pack will ask for confirmation'
            ),
            array(
                'title'         => 'Unable to login with social media',
                'type'          => 'Fix',
                'description'   => 'Customer, Seller was unable to login with social media'
            ),
            array(
                'title'         => 'CSV earning report exporting',
                'type'          => 'Fix',
                'description'   => 'There were an issue with CSV report exporting from back end'
            ),
            array(
                'title'         => 'Unable to delete vendor form admin panel',
                'type'          => 'Fix',
                'description'   => 'Admin was unable to delete a vendor from admin panel'
            ),
            array(
                'title'         => 'Seller setup wizard is missing during email verification',
                'type'          => 'Fix',
                'description'   => 'Seller setup wizard after a seller is verified by email was missing'
            ),
            array(
                'title'         => 'Subscription Free pack visibility',
                'type'          => 'Fix',
                'description'   => 'Hide subscription product type from back end when a seller can access the back end'
            ),
            array(
                'title'         => 'Disable back end access for vendor staff',
                'type'          => 'Improvement',
                'description'   => 'Disable back end access for vendor staff for security perpose'
            ),
            array(
                'title'         => 'Updated deprecated functions',
                'type'          => 'Improvement',
                'description'   => 'Updated some deprecated functions'
            ),
            array(
                'title'         => 'Statement calculation',
                'type'          => 'Improvement',
                'description'   => 'Statement calculation'
            ),
            array(
                'title'         => 'Reduction of \'dokan\' text from staff permission',
                'type'          => 'Improvement',
                'description'   => 'Reduction of \'dokan\' text from staff permission',
            ),
            array(
                'title'         => 'Various UI, UX improvement',
                'type'          => 'Improvement',
                'description'   => 'Various UI, UX improvement',
            ),
        )
    ),
    array(
        'version'  => 'Version 2.7.5',
        'released' => '2018-03-06',
        'changes'  => array(
            array(
                'title'         => 'Re-organize dokan admin menu’s',
                'type'          => 'Improvement',
                'description'   => 'We re-organized doman menus in the amdin panel'
            ),
            array(
                'title'         => 'Shipping is not working if WPML is activated',
                'type'          => 'Fix',
                'description'   => 'Dokan shipping was\'t working when the WPML plugin was activated'
            ),
            array(
                'title'         => 'Subscription pack purchase during vendor registration [Subscription Module]',
                'type'          => 'Fix',
                'description'   => 'Vendor wasn\'t able to purchase subscription pack just after the registration process'
            ),
            array(
                'title'         => 'Cannot register using social media',
                'type'          => 'Fix',
                'description'   => 'User was unable to login or register using social media'
            ),
            array(
                'title'         => 'Subcsription pack shows wrong validaty date [Subscription Module]',
                'type'          => 'Fix',
                'description'   => 'Subscription pack [for unlimited number of days] expiration date shows invalid date from vendor\'s dashboard'
            ),
            array(
                'title'         => 'Store support widget is not working on single product page [Store Support Module]',
                'type'          => 'Fix',
                'description'   => 'Store support widget is not working on single product page'
            ),
            array(
                'title'         => 'Email verification wasn\'t working',
                'type'          => 'Fix',
                'description'   => 'Registration process through email verification doesn\'t verify vendors properly'
            )
        )
    )
);

function _dokan_changelog_content( $content ) {
    $content = wpautop( $content, true );

    return $content;
}
?>

<div class="wrap dokan-whats-new">
    <h1>What's New in Dokan?</h1>

    <div class="wedevs-changelog-wrapper">

        <?php foreach ( $changelog as $release ) { ?>
            <div class="wedevs-changelog">
                <div class="wedevs-changelog-version">
                    <h3><?php echo esc_html( $release['version'] ); ?></h3>
                    <p class="released">
                        (<?php echo human_time_diff( time(), strtotime( $release['released'] ) ); ?> ago)
                    </p>
                </div>
                <div class="wedevs-changelog-history">
                    <ul>
                        <?php foreach ( $release['changes'] as $change ) { ?>
                            <li>
                                <h4>
                                    <span class="title"><?php echo esc_html( $change['title'] ); ?></span>
                                    <span class="label <?php echo strtolower( $change['type'] ); ?>"><?php echo esc_html( $change['type'] ); ?></span>
                                </h4>

                                <div class="description">
                                    <?php echo _dokan_changelog_content( $change['description'] ); ?>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        <?php } ?>
    </div>

</div>
<?php
    $versions = get_option( 'dokan_whats_new_versions', array() );

    if ( ! in_array( DOKAN_PRO_PLUGIN_VERSION, $versions ) ) {
        $versions[] = DOKAN_PRO_PLUGIN_VERSION;
    }

    update_option( 'dokan_whats_new_versions', $versions );
?>
<style type="text/css">

.error, .udpated, .info, .notice {
    display: none;
}

.dokan-whats-new h1 {
    text-align: center;
    margin-top: 20px;
    font-size: 30px;
}

.wedevs-changelog {
    display: flex;
    max-width: 920px;
    border: 1px solid #e5e5e5;
    padding: 12px 20px 20px 20px;
    margin: 20px auto;
    background: #fff;
    box-shadow: 0 1px 1px rgba(0,0,0,0.04);
}

.wedevs-changelog-wrapper .wedevs-support-help {

}

.wedevs-changelog .wedevs-changelog-version {
    width: 360px;
}

.wedevs-changelog .wedevs-changelog-version .released {
    font-style: italic;
}

.wedevs-changelog .wedevs-changelog-history {
    width: 100%;
    font-size: 14px;
}

.wedevs-changelog .wedevs-changelog-history li {
    margin-bottom: 30px;
}

.wedevs-changelog .wedevs-changelog-history h4 {
    margin: 0 0 10px 0;
    font-size: 1.3em;
    line-height: 26px;
}

.wedevs-changelog .wedevs-changelog-history p {
    font-size: 14px;
    line-height: 1.5;
}

.wedevs-changelog .wedevs-changelog-history img,
.wedevs-changelog .wedevs-changelog-history iframe {
    margin-top: 30px;
    max-width: 100%;
}

.wedevs-changelog-history span.label {
    margin-left: 10px;
    position: relative;
    color: #fff;
    border-radius: 20px;
    padding: 0 8px;
    font-size: 12px;
    height: 20px;
    line-height: 19px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    font-weight: normal;
}

span.label.new {
    background: #3778ff;
    border: 1px solid #3778ff;
}

span.label.improvement {
    background: #3aaa55;
    border: 1px solid #3aaa
}

span.label.fix {
    background: #ff4772;
    border: 1px solid #ff4772;
}

</style>
