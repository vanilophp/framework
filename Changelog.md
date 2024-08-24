# Changelog

## 4.x Series

## Unreleased
##### 2024-XX-YY

- Added the Promotion module with promotions, actions, rules and coupons support
- Changed the `ConfigurableModel::hasConfiguration()` trait to return false on empty arrays as well
  (until now, only `null` was considered as no config). It affects the following models:
    - `Carrier`
    - `CartItem`
    - `Channel`
    - `OrderItem`
    - `PaymentMethod`
    - `Shipment`
    - `ShippingMethod`
    - `TaxRate`
- Added the following, v5 interface candidate methods to the Checkout implementations:
    - `addCoupon()`
    - `removeCoupon()`
    - `hasCoupon()`
    - `getCoupons()`
    - `hasAnyCoupon()`
    - `getPromotionsAmount()`
    - `setPromotionsAmount()`
- Added the following checkout events:
    - `CouponAdded` (to checkout)
    - `CouponRemoved` (from checkout)
    - `CouponUtilized` (after a successful checkout converted to an order)
- Added a listener to update coupon and promo usage after a `CouponUtilized` event (Foundation)
- Added a promotion calculation listener (on CouponAdded and CouponRemoved events)

## 4.1.0
##### 2024-07-11

- Added the `includeVariants()` method to the ProductSearch class ⚠️ DOES NOT WORK ON POSTGRES (YET) ⚠️
- Added the `priceGreaterThan`, `priceLessThan` and `priceBetween` methods to the ProductSearch class by [Matima](https://github.com/mahdirezaei-dev) in [#176](https://github.com/vanilophp/framework/pull/176)
- Added the `Macroable` trait to the `ProductSearch` class
- Added the unidirectional links feature
- Added the `isUnidirectional()`, `isOmnidirectional()` and `isEmpty()` methods to the `LinkGroup` class
- Added the `pointsTo()` method to the `LinkGroupItem` class
- Added the possibility to retrieve the link items directly using `linkItems()` method as `Get::the($type)->linkItems()->of($model)`
- Added the `link_items` helper, shortcut to Get::the()->linkItems()
- Added the possibility to force new link group creation using the `new()` method of the `Establish` class
- Added support for configurable decimal separator used by the `format_price()` helper function
- Added the discountable shipping fee calculator
- Added the `taxes_total`, `shipping_total` and `total` attribute getters to the Foundation `Order` model
- Added the follwing getters to the default Billpayer model (proxies down to the underlying address):
  - `country_id`
  - `province_id`
  - `postalcode`
  - `city`
  - `street_address` (fetches $billpayer->address->address) - can't use `address` since that collides with the address() relation
  - `address2`
  - `access_code`
- Added the following content fields to the Taxon model/table:
  - `subtitle`
  - `excerpt`
  - `description`
  - `top_content`
  - `bottom_content`
- Changed the offline payment gateway's icon from a circle to a plug+x
- Fixed the possible missing configuration copy from cart_items to order_items in the OrderFactory

## 4.0.1
##### 2024-04-25

- Removed the Doctrine DBAL dependency

## 4.0.0
##### 2024-04-25

- Dropped PHP 8.0 & PHP 8.1 Support
- Dropped Laravel 9 Support
- Dropped Enum v3 Support
- Added PHP 8.3 Support
- Added Laravel 11 Support
- Changed minimum Laravel version to v10.43
- Changed minimal Enum requirement to v4.2
- Changed minimal Address module requirement to v3.3
- Upgraded to Konekt Address, Customer and User modules to v3
- Upgraded to Laravel Media Library v11
- Removed the Vanilo v2 `Framework` namespace compatibility layer
- Removed the throwing of `CartUpdated` event when destroying a cart (`CartDeleting` and `CartDeleted` remains)
- Removed the deprecated `BuyableImageSpatieV7` and `BuyableImageSpatieV8` traits
- Added the `SimpleTaxDeduction` adjuster
- Added Cart item configuration support (different configurations constitute separate cart items) to the `Cart::addItem()` method
- Added the `currency` field to the orders table
- Added the following fields to the Channel model/table:
    - `currency`
    - `language`
    - `domain`
    - billing fields (emitter's data)
    - `billing_zone_id`
    - `shipping_zone_id`
    - `theme`
    - `color`
- Added `ProductSearch` features:
    - added the optional $limit parameter to the `getResults()` method
    - added the `orderBy()` method
    - added the `slugEquals()` method: it takes into account other query parameters as well, whereas the findBySlug solely queries by the given slug
- Added the `payment_method_id` to the orders table
- Added the processing of the `payment_method_id` field to the OrderFactory (Foundation)
- Added the `BillpayerChanged` checkout event
- Added the `is_hidden` field to the `Property` model
- Changed the behavior of assignPropertyValues/assignPropertyValue methods so that it throws an `UnknownPropertyException` when passing an inexistent property slug
- Added the `withImages` and `withChannels` methods to the product search class (eager loads media)
- Added the `channelables` table for being many-to-many polymorphic relationships with channels and arbitrary models
- Added the `mapInto()` method to the `RelationAdjustmentCollection` class, which forwards the call to the underlying Eloquent collection
- Added the `Zoneable` support to the Foundation PaymentMethod class
- Added the `Channelable` behavior to Foundation Product, MasterProduct, PaymentMethod, ShippingMethod and Taxonomy classes
- Added and extended Foundation Channel model that contains the known relationships to the "channelable" models
- Added the `withinChannel[s]` methods to the ProductSearch class
- Added the `Features` accessor class, which is a syntactic sugar for areas of the configuration
- Added `isZoneRestricted()` & `isNotZoneRestricted()` helper methods to the `ShippingMethod` class
- Added the payment dependent shipping fee calculator
- Added the `units_sold` and the `last_sale_at` attributes to the master product model (SUM/MAX from variants)
- Added the `Stockable` interface (Contracts)
- Added the `Stockable` interface to the `Product` and `MasterProductVariant` models
- Added the `backorder` field to products and product variants
- Added the `type` field to the TaxCategory model
- Added the `Taxable` interface
- Added the `Taxable` implementation to Foundation's CartItem, Product and MasterProductVariant classes
- Added the extendable `TaxEngine` (facade) that can resolve tax rates from taxables, billing/shipping addresses (a place for various country-specific taxation drivers)
- Added the `Merchant` interface
- Added the `DefaultTaxCalculator` class - calculates simply by rate
- Added the `DeductiveTaxCalculator` class similar to the default one, but it deducts the amount
- Added the `CalculateTaxes` listener to cart update and shipping address change events
- Added the `Merchant` DTO class, that complies with the common, v4 `Merchant` interface
- Added the `Address` DTO class (implements the `Address` interface)
- Added the `AdjusterAliases` class that for decoupling FQCNs from the database
- Added automatic mapping of adjuster FQCN <-> aliases when saving an adjustment into the DB and when calling the `getAdjuster()` method
- Added the `itemsPreAdjustmentTotal()` method to the Foundation's adjustable Cart model
- Added the `replacePropertyValues()` and `replacePropertyValuesByScalar()` methods to the `HasPropertyValues` trait
- BC: Added the following methods to the `PropertyValue` interface:
    - `findByPropertyAndValue()`
    - `getByScalarPropertiesAndValues()`
- BC: Added the mixed return type to the `getCastedValue` method of the `PropertyValue` interface
- BC: Added the `findBySku()` method to the `Product` and `MasterProductVariant` interfaces
- BC: The `MasterProduct` interface no longer extends the `Product` interface
- BC: The `Checkout` interface now extends the `ArrayAccess` and the `Shippable` interfaces (until here, only the concrete classes have implementation it)
- BC: Added cart and order item models to the Relation morph map: the `adjustable_type` field in the `adjustments` table needs to be converted!
- BC: Added the `?CheckoutSubject` return type to the `getCart()` method of the `Checkout` interface
- BC: Changed `Checkout::getShippingAddress()` return type to be nullable
- BC: Added the void return type to `Checkout::setShippingAddress()`
- BC: Added the following methods to the Checkout interface:
    - `removeShippingAddress()`
    - `getShipToBillingAddress()`
    - `setShipToBillingAddress()`
    - `getShippingMethodId()`
    - `setShippingMethodId()`
    - `getPaymentMethodId()`
    - `setPaymentMethodId()`
    - `getNotes()`
    - `setNotes()`
    - `clear()`
    - `getShippingAmount()`
    - `setShippingAmount()`
    - `getTaxesAmount()`
    - `setTaxesAmount()`
    - `itemsTotal()`
- BC: The unused `$config` parameter has been removed from the `RequestStore` checkout driver constructor
- BC: Removed the following traits from the Checkout module:
  - `HasCart`
  - `ComputesShipToName`
  - `FillsCommonCheckoutAttributes`
- BC: Added the `deleteByType()` and `clear()` methods to the `AdjustmentCollection` interface
- BC: Added the `getAddress2()` method to the `Address` interface
- BC: The `Adjustable::itemsTotal()` has been renamed to `preAdjustmentTotal()`
- BC: The `invalidateAdjustments()` method has been added to the `Adjustable` interface
- BC: The `exceptTypes()` method has been added to the `AdjustmentCollection` interface
- BC: The `AdjustmentType` interface extends the `EnumInterface`
- BC: Added the `isNotIncluded()` method to the `Adjustment` interface
- BC: Changed the behavior of `AdjustmentCollection::total()`:
    1. it ignores "included" adjustments by default,
    2. to incorporate the "included" adjustments pass true to the method: `$adjustments->total(withIncluded: true)`
- BC: Changed the `TaxRate` interface so that it extends the `Configurable` interface
- BC: Added the `itemsTotal()` method to the `CheckoutSubject` interface
- BC: Added argument and return types to all `Cart` and `CartManager` interface methods
- BC: Added the `findOneByZoneAndCategory` static method to the `TaxRate` interface
- BC: Changed the ShippingFeeCalculator, Carrier and Shipment interfaces to Configurable & Schematized
- BC: Changed the `CheckoutSubjectItem` interface into Configurable & Schematized
- BC: Added the `$hooks` and `$itemHooks` parameters to the `OrderFactory` interface
- BC: Changed the `OrderItem` interface into Configurable
- BC: Added 7 methods to the `OrderItem` interface
- BC: Added the `getLanguage()`, `getFulfillmentStatus()` and `itemsTotal()` methods to the `Order` interface
- BC: Added to float return type to the `total()` method of the `Order` interface
- BC: The `OrderStatus` and `FulfillmentStatus` interfaces extend the `EnumInterface`
- BC: Added the `getConfigurationSchema()` method to the `Configurable` interface
- BC: Changed the `PaymentMethod` interface into Configurable
- BC: Added the `getRemoteId()` method to the `PaymentRequest` interface
- BC: Added the following methods to the `Payment` interface:
  - `getSubtype()`
  - `hasRemoteId()`
  - `getRemoteId()`
  - `isOffline()`
- BC: Added the `getTransactionAmount()` method to the `PaymentResponse` interface
- BC: Added the `transactionHandler()` method to the `PaymentGateway` interface (**experimental** feature)
- BC: Added the `svgIcon()` static method to the `PaymentGateway` interface
- BC: Added the following methods to the `Payable` interface:
    - `getNumber()`
    - `getPayableRemoteId()`
    - `setPayableRemoteId()`
    - `findByPayableRemoteId()`
    - `hasItems()`
    - `getItems()`
- BC: The return type of the `getNumber()` method of the Order interface is no longer nullable
- BC: Added the `getCalculator()` & `estimate()` methods to the `ShippingMethod` interface
- BC: The `Channel` interface extends the `Configurable` interface
- BC: Added the following methods to the `Channel` interface:
    - `getLanguage()`
    - `getCurrency()`
    - `getDomain()`
    - `getMerchant()`
    - `getBillingCountries()`
    - `getShippingCountries()`
- Deprecated the `PaymentMethod::getConfiguration()` in favor of `configuration()`
- Deprecated the `PaymentResponse::getAmountPaid()` method in favor of `getTransactionAmount()`
- Added a series of **experimental** transaction-style interfaces. They shouldn't be used yet, but
  have been added so that they can be implemented during the v4.x lifecycle without breaking existing implementations
- Added the `Schematized` interface
- Added the nette/schema package requirement (v1.2.5+)
- Fixed possible null return type on Billpayer::getName() when is_organization is true but the company name is null
- Fixed the remnant orphan cart adjustments in the database during cart to order transformation

## 3.x Series

## 3.8.2
##### 2023-11-17

- Fixed the PHP `IteratorAggregate`, `ArrayAccess` and `Countable` interfaces-related deprecation notices

## 3.8.1
##### 2023-10-09

- Fixed the hinting of `Taxon` interface in the `ProductSearch` class (#163)
- Changed the visibility of `ProductSearch` properties  from `private` to `proteced` (#161)

## 3.8.0
##### 2023-05-24

- Added the `ofUser()` scope to the base Order model
- Added missing Payment Status magic comparison annotations to the interface/model
- Added the `hasRemoteId()`, `getRemoteId()` and `isOffline()` helper methods to the Payment model (v4 interface candidates)
- Added the `link_type_exists()` helper function (to be used in blade templates)

## 3.7.1
##### 2023-04-07

- Added the `is_master_product_variant()` helper function
- Added the `ProductSearch::findBySlugOrFail()` method
- Changed the `ProductSearch::findBySlug()` method to be static (it's backwards compatible)

## 3.7.0
##### 2023-04-04

- Added the `OrderProcessingStarted` event
- Added the following order item events: `OrderItemShipped`, `OrderItemPickedUp`, `OrderItemsIsReadyForDelivery`, `OrderItemsIsReadyForPickup` and `OrderItemHasBeenPutOnHold`
- Changed the visibility of the `OrderFactory::callHook` method from `private` to `protected`
- Added the Taxes module with: Tax Categories, Tax Rates and calculators
- Added `tax_category_id` field to products/master products/variants
- Added the `SimpleTax` adjuster (it calculates the tax amount based on a flat rate in %)
- Added the extended `CartItem` and `OrderItem` models to Foundation that are `Adjustable`
- Added the `fromKeyValuePairs` static factory and `getDetail()` methods to the DetailedAmount Dto class
- Added the `carrier_cost`, `label_url` and `label_base64` fields to the shipments table/model
- Added feature to assign shipments to order items, and vice versa (Foundation)
- Added optional hooks (callbacks) support to order items creation in the order factory class
- Added item adjustments total to the order grand total (Foundation)
- Added the `benefit` adjustment type
- Added the experimental `ProductSearch` class to replace ProductFinder with a multi-model (products + masters) variant
  - Added the required konekt/search v1.2+ dependency
- Changed the minimal Address module requirement to v2.7.1
-  Added getRemoteId to NullRequest (PaymentRequest interface v4 method candidate)
- Added testing against MySQL 8.0 (besides v5.7)

## 3.6.2
##### 2023-03-12

- Fixed the shipping adjustment's undesired JSON->string conversion when cloning the cart adjustment to the order
- Fixed the missing locking of shipping adjustments on orders
- Fixed the missing foreign key between `shipping_methods.zone_id` and the `zones` table

## 3.6.1
##### 2023-03-09

- Fixed the `ConfigurableModel` trait to handle json strings and other arrayable fields in the underlying model
- Fixed possibly incorrect zone member and shipping method list due to a bug in v2.5.0 of the Address module (bump to v2.5.1)

## 3.6.0
##### 2023-03-07

- Added Laravel 10 support
- Added the `CartCreated`, `CartUpdated`, `CartDeleted` and `CartDeleting` events
- Added master product & variant (Foundation) to the morph map
- Added the `processing` order status enum value
- Added the `ready_for_pickup` fulfillment status enum value
- Added the `DetailedAmount` interface (Contracts) and its Dto (Support)
- Added Zone support to shipping methods (optional)
- Added the `Order::getLanguage()` method
- Added the `ShippingMethodSelected` checkout event
- Added Shipping fee calculator support
- Added the flat fee shipping fee calculator (Foundation)
- Added a listener (Foundation) that calculates the shipping fee on checkout shipping method change
- Added the following fields as recognized, regular fields of the checkout:
    - `shipping_method_id`
    - `payment_method_id`
    - `ship_to_billing_address`
    - `notes`
- Added `ArrayAccess` to the CheckoutManager and to the Checkout Stores
- Added `Shippable` to the CheckoutManager and to the Checkout Stores
- Added the `vanilo.checkout.default.weight_unit` config key which defaults to 'kg'
- Added the following methods to the Checkout implementations (Manager, Stores but not the interfaces):
    - `getShipToBillingAddress()`
    - `setShipToBillingAddress()`
    - `getShippingMethodId()`
    - `setShippingMethodId()`
    - `getPaymentMethodId()`
    - `setPaymentMethodId()`
    - `getShippingAmount()`
    - `setShippingAmount()`
    - `getTaxesAmount()`
    - `setTaxesAmount()`
    - `getNotes()`
    - `setNotes()`
- Added optional hooks (callbacks) support to the `OrderFactory::createFromDataArray()` method
- Added `payable_remote_id` field with getter/setter methods to the Foundation Order model
- Added implementation of the `Adjustable` interface to the Foundation Order model
- Added the copying of checkout/cart adjustments to the order created by the Foundation OrderFactory
- Added `int` cast to OrderItem::quantity property
- Added `dimensions()` and `hasDimensions()` methods to the `MasterProductVariant` model
  Added the `Dimension` DTO class in the support module (provides a default implementation for the same name interface)
- Added the vanilo/support ^3.6 dependency to the Product module
- Added the `subtype` field and the `getSubtype()` method to the `Payment` model
- Added the `DeleteCartAdjustments` listener on `CartDeleting` event
- Added the `CalculateShippingFees` listener connected to shipping address/method change and cart updated events
- Added the `clear()` method to `AdjustmentCollection` implementations
- Added the `invalidateAdjustments()` method to `Adjustable` implementations
- Added the `shippingAdjustmentsTotal()`, `taxAdjustmentsTotal()` and `promotionAdjustmentsTotal()` methods to the Adjustable Cart model in foundation
- Refactored the internals of the Checkout stores (session and request)
- Changed the return value of `Product::dimension()` to be a `Dimension` object from the support package instead of an anonymous class object
- Changed minimum Address module requirement to v2.5
- Fixed the data loss issue when using the session checkout driver with cookie session driver in Laravel

## 3.5.1
##### 2023-02-23

- Fixed non-standard migration name in the order module

## 3.5.0
##### 2023-02-23

- Added the `description` and `state` fields to the master product variants table
- Added the Address comparison utility
- Added the `reference_number` field to the shipments model
- Added the `shippables` many-to-many, polymorphic relationship.  
  It allows all the following scenarios:  
    a) shipping one order in one shipment  
    b) shipping one order in multiple shipments  
    c) shipping multiple orders in one shipment  
- Added `Shipment::orders` and `Order::shipments` to the Foundation models
- Added the `FulfillmentStatus` enum to the Orders module
- Added `fulfillment_status` to the `orders` and `order_items` tables
- Added `language` field to orders
- Added `ordered_at` field to orders (defaults to `created_at` unless explicitly specified)
- Added `shippingMethod` and `customer` fields/relations to the extended (Foundation) Order model
- Changed the random number algo in `TimeHash` generator from `mt_rand` to `random_int` for a decreased collision probability
- Fixed the `Carrier::name()` method to overcome accessor infinite loop
- Fixed the address creation with order factory when passing an explicit address type

## 3.4.1
##### 2023-01-25

- Fixed the missing shipment module from foundation module config

## 3.4.0
##### 2023-01-25

- Added the `MasterProdcutVariant::findBySku()` method
- Added the `Product::findBySku()` method
- Added the `ConfigurableModel` trait
- Added processing of `configuration` to the order factory, if the checkout item is a configurable
- Added `Configurable` to the `CartItem` model (incl. implementing the interface)
- Added `Configurable` to the `OrderItem` model (incl. implementing the interface)
- Added the `Configurable` interface
- Added `Configurable` interface to the `PaymentMethod` model
- Added the missing `ShippingMethod` interface and proxy
- Added `Configurable` interface to the `Carrier`, `Shipment` and `ShippingMethod` models
- Changed the `Carrier`, `Shipment` and `ShippingMethod` models to use the `ConfigurableModel` trait
- Changed the `PaymentMethod` model to use the `ConfigurableModel` trait
- Fixed missing required argument from checkout drivers using the `update()` method

## 3.3.0
##### 2023-01-05

- Fixed the session checkout store persistence error
- Fixed float conversion of master product variant fields (price, original price and dimensions)
- Changed the product stock field to be cast to float
- Removed the Buyable interface/trait from `Foundation\MasterProduct` - it was conceptually wrong;
- Added `Buyable` to `Foundation\MasterProductVariant`
- Added the `Product::findBySku()` method to the base product class
- Added the `MasterProdcutVariant::findBySku()` method

## 3.2.0
##### 2022-12-08

- Added forwarding/proxying of method calls and property getters from the Checkout manager to the underlying store
- Added `is_active` flag to shipping methods
- Added `Cart::fresh()` method to the Cart facade
- Added the `is_master_product()` helper function (Foundation)
- Added the `MasterProduct::actives()` and `inactives()` query builder scopes
- Added the `Product::inactives()`query builder scope
- Added an extended `MasterProduct` model (Foundation)
- Changed checkout store resolution to happen via the Laravel DI instead of `new SpecificStoreClass()`
- Changed both built-in checkout stores to save unknown properties as custom attributes on the `update()` method call
- Changed minimum Concord version requirement to v1.12
- Fixed missing implementation parts of the checkout session store

## 3.1.0
##### 2022-11-07

- Added the Master Product module
- Added the Adjustments module
- Added the extended Adjustable Cart model to `Foundation` (original Cart model still there, intact)
- Added `channel_id` to the extended `Order` model in Foundation
- Added Shipping methods
- Added the `getGatewayName()` method to the PaymentMethod class.
  It can retrieve the gateway name without instantiating it,
  thus gateway configuration errors don't affect it
- Added the `PropertyValue::findByPropertyAndValue('color', 'red')` finder method
- Added the `valueOfProperty()`, `assignPropertyValue()` and `assignPropertyValues()` methods to the `HasPropertyValues` trait
- Added the Sluggable behavior to the Channel model
- Added configuration getter and setter methods to the Channel model
- Added PHP 8.2 Support
- Added Enum 4.0 Support
- Changed minimum Laravel requirement to 9.2
- Changed minimum Konekt module requirements to:
    - Address: 2.2
    - Concord: 1.11
    - Customer: 2.3.1
    - Enum: 3.1.1
    - Laravel Migration Compatibility: 1.5
    - User: 2.4

## 3.0.1
##### 2022-05-22

- Added `actives()` and `inactives()` scopes to the Carrier model
- Fixed the missing `Cart` alias registration when using the entire framework

## 3.0.0
##### 2022-02-28

- Dropped PHP 7.4 Support
- Dropped Laravel 6-8 Support
- Added Laravel 9 Support
- Added PHP 8.1 Support
- Removed Admin from "Framework" - it is available as an optional separate package see [vanilo/admin](https://github.com/vanilophp/admin)
- Changed minimum Laravel version to 9.0
- BC: Renamed Framework Module and Namespace to "Foundation"
- BC: Removed the `Vanilo\Foundation\Models\PaymentMethod` class. Use `Vanilo\Payment\Models\PaymentMethod` instead
- BC: Renamed `vanilo.framework.*` config values to `vanilo.foundation.*`
- BC: Added the static `findBySlug(string $slug): ?Property;` method to the Property interface
- Added the Shipment Module
- Added the Links Module
- Added `original_price` field to products
- Added product dimension (`width`, `height`, `depth`) and `weight` fields

## 2.x Series

## 2.2.0
##### 2021-09-11

- Upgrade to AppShell v2.2 (minimum requirement)
- Added order print page in admin
- Added payment history to order view page in admin
- Added payment method to order list in admin
- Added show/hide closed orders button to order list in admin
- Added `status_message` field to payment list on order view in admin
- Fixed missing query strings in admin paginators
- Improved permission migrations to add flexibility to projects that manage permissions differently
- Improved property value form handling on admin
- Changed internal CS ruleset from PSR-2 to PSR-12
- Changed all classes to declare strict types enabled

### 2.1.1
##### 2021-01-05

- Bumped min Properties module to v2.1.1 to fix type mismatch at `Property::findOneByName()` when
  using overridden Property model.

### 2.1.0
##### 2020-12-31

- Added PHP 8 support
- Added Spatie Media Library v9 Support
- Added Primary Image feature (products)
- Upgrade to AppShell 2.1
- Refactored image handling
- Added images to taxonomies and taxons
- Added option to define separate image conversions per entity type (product, taxonomy, taxon)
- Added the `HasImages` interface - extracted it from Buyable
- Added `Order` model that implements the `Payable`
- Added Payment method CRUD
- Added dispatching of order cancelled and completed events when updating their status in the admin panel
- Fixed model registration to takes into account Concord's `register_route_models` setting
- Switched CI from Travis to Github Actions

### 2.0.0
##### 2020-10-31

- BC: Upgrade to Spatie MediaLibrary v8
- Added Laravel 8 support
- Dropped Laravel 5 support
- Dropped support for PHP 7.2 and PHP 7.3 (due to MediaLibrary)
- Minimum Laravel requirement is v6.18 (due to MediaLibrary)
- Upgrade to AppShell 2.0
- Admin and admin icons are themeable (AppShell 2.0 feature)

## 1.x Series

### 1.2.1
##### 2020-05-24

- Changed minimum AppShell version to 1.7
- Fixes the "taxons" vs "taxa" permission problem caused by Doctrine Inflector 1.4+

### 1.2.0
##### 2020-03-29

- Added Laravel 7 support
- Added PHP 7.4 support
- Dropped PHP 7.1 support
- Combination of PHP 7.4 & Laravel 5.6|5.7 is not recommended
  due to improper order of `implode()` arguments in eloquent-sluggable dependency

### 1.1.1
##### 2019-12-01

- Fixed a bug with Category editing in Admin under Laravel 6

### 1.1.0
##### 2019-11-25

- Added Laravel 6 Support

### 1.0.0
##### 2019-11-11

- Added simple product stock
- Added ProductFinder pagination support
- Added Channel Module

## 0.5 Series

### 0.5.3
##### 2019-03-19

- Fixed migrations fail bug when the `admin` role is not present in the target system

### 0.5.2
##### 2019-03-17

- Technical release: fixed inexistent version constraint (other than that same as 0.5.1)

### 0.5.1
##### 2019-03-17

- Complete Laravel 5.8 support

### 0.5.0
##### 2019-02-11

- Support for Product Properties has been added (via Properties module)
- Using AppShell v1.3
- ProductFinder has been added (supports: taxons, property values and search terms in product name)

## 0.4 Series

### 0.4.2
##### 2019-01-20

- Improved SQLite compatibility in migrations (to fix certain testing scenarios)

### 0.4.1
##### 2018-11-15

- Product image upload validation has been improved

### 0.4.0
##### 2018-11-12

- Added Product Category Support
- Laravel 5.7 Compatibility
- PHP 7.3 Compatibility
- Product sales figures (units sold, last sale date)
- Using AppShell v1.2
- Admin UI fixes & improvements
- Vanilo Address module has been dropped

## 0.3 Series

### 0.3.0
##### 2018-08-19

- Product Image support via Spatie Laravel Media Library v7
- Laravel 5.4 support dropped (due to Spatie Media Library v7 conflicts)
- Concord v1.2, AppShell 0.9.9

## 0.2 Series

### 0.2.1
##### 2018-06-24

- Fixed breaking change with AppShell > v0.9.6

### 0.2.0
##### 2018-02-19

- Laravel 5.6 compatible
- Cart handles user associations
- Admin UI polishes
- Concord v1.1

## 0.1 Series

### 0.1.1
##### 2017-12-17

- Fixed Billpayer related problems
- Checkout => Order works properly
- Order CRUD on admin has been added

### 0.1.0
##### 2017-12-11

- 🚀 Yet another E-commerce platform has born 🚀
- 🚀 This will be a very good one 🚀
