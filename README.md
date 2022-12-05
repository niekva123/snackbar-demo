# Snackbar demo

This application is a simple demo application containing the following domains:
 - Maintaining the snackbar inventory
 - Creating an order with order items

The architecture is based on Domain Driven Design with Laravel used as framework. It demonstrates the way I deal with logic that should be scalable and testable.

## Work in progress
This code is a work-in-progress. The following implementations can be expected soon:
- Unit tests
- Integrtion tests
- Behat api tests
- Simple user interface
- Authentication

## Domains
### Inventory
The inventory domain is meant for maintaining the inventory of the snackbar. With this domain the snackbar owner can:
 - Create items
 - Change items
 - Remove items

The inventory domain implements the following business rules:
- The item name should be unique for the snackbar inventory
- An item price should be higher than 0
### Order
The order domain is meant for creating orders by a public user. The user can:
 - Create an new order
 - Add order items
 - Change the amount of an order item
 - Remove order items

The order domain implements the following business rules:
- An item from the inventory can be added to the order
- The amount of an order item should be 1 or higher, but not more than 20
- An order item has the price stored that the inventory had on the moment the order item was added to the order
