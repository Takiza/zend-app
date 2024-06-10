<?php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class SqlExamplesController extends AbstractActionController
{
    public function indexAction()
    {
        $sqlQueries = [
            [
                'description' => 'Select all users with their orders using INNER JOIN:',
                'query' => '
SELECT 
    users.user_id, 
    users.name, 
    users.email, 
    orders.order_id, 
    orders.product_name, 
    orders.order_date
FROM 
    users
INNER JOIN 
    orders ON users.user_id = orders.user_id
ORDER BY 
    users.user_id, orders.order_date;',
            ],
            [
                'description' => 'Select all users with their orders using LEFT JOIN:',
                'query' => '
SELECT 
    users.user_id, 
    users.name, 
    users.email, 
    orders.order_id, 
    orders.product_name, 
    orders.order_date
FROM 
    users
LEFT JOIN 
    orders ON users.user_id = orders.user_id
ORDER BY 
    users.user_id, orders.order_date;',
            ],
            [
                'description' => 'Select users who have placed orders (DISTINCT):',
                'query' => '
SELECT DISTINCT
    users.user_id, 
    users.name, 
    users.email
FROM 
    users
INNER JOIN 
    orders ON users.user_id = orders.user_id;',
            ],
            [
                'description' => 'Select orders placed in the last 30 days:',
                'query' => '
SELECT 
    orders.order_id, 
    orders.product_name, 
    orders.order_date
FROM 
    orders
WHERE 
    orders.order_date >= CURDATE() - INTERVAL 30 DAY
ORDER BY 
    orders.order_date DESC;',
            ],
            [
                'description' => 'Count the number of orders per user:',
                'query' => '
SELECT 
    users.user_id, 
    users.name, 
    COUNT(orders.order_id) as order_count
FROM 
    users
LEFT JOIN 
    orders ON users.user_id = orders.user_id
GROUP BY 
    users.user_id, users.name
ORDER BY 
    order_count DESC;',
            ],
            [
                'description' => 'Analyze the query using EXPLAIN:',
                'query' => '
EXPLAIN SELECT 
    users.user_id, 
    users.name, 
    users.email, 
    orders.order_id, 
    orders.product_name, 
    orders.order_date
FROM 
    users
INNER JOIN 
    orders ON users.user_id = orders.user_id
ORDER BY 
    users.user_id, orders.order_date;',
            ],
            [
                'description' => 'Optimize the query with indexes (adding indexes to users and orders tables):',
                'query' => '
CREATE INDEX idx_users_user_id ON users(user_id);
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_order_date ON orders(order_date);',
            ],
            [
                'description' => 'Optimized select all users with their orders using INNER JOIN:',
                'query' => 'SELECT 
    users.user_id, 
    users.name, 
    users.email, 
    orders.order_id, 
    orders.product_name, 
    orders.order_date
FROM 
    users
INNER JOIN 
    orders ON users.user_id = orders.user_id
ORDER BY 
    users.user_id, orders.order_date;',
            ],
        ];

        return new ViewModel([
            'sqlQueries' => $sqlQueries,
        ]);
    }
}
