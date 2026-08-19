<?php

class Product
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO products (user_id, product_name, product_price, product_stock, product_category, product_image, product_description, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
            $data['user_id'],
            $data['product_name'],
            $data['product_price'],
            $data['product_stock'],
            $data['product_category'],
            $data['product_image'],
            $data['product_description']
        ]);
    }


    public function update($data)
    {
        $query = "UPDATE products SET product_name = ?, product_price = ?, product_stock = ?, product_category = ?, product_description = ?, updated_at = NOW()";

        $params = [
            $data['product_name'],
            $data['product_price'],
            $data['product_stock'],
            $data['product_category'],
            $data['product_description']
        ];

        if (!empty($data['product_image'])) {
            $query .= ", product_image = ?";
            $params[] = $data['product_image'];
        }

        $query .= " WHERE id = ?";
        $params[] = $data['id'];

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function allWithFarmers()
    {
        $stmt = $this->pdo->prepare("CALL get_all_products_with_farmers()");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor(); // Required when calling multiple procedures in a row
        return $result;
    }

    public function countFilteredProducts($search = '')
    {
        $sql = "SELECT COUNT(*) FROM products p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.product_name COLLATE utf8mb4_unicode_ci LIKE ? COLLATE utf8mb4_unicode_ci";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$search%"]);
        return $stmt->fetchColumn();
    }

    public function getFilteredProducts($search = '', $limit = 12, $offset = 0)
{
    $stmt = $this->pdo->prepare("CALL get_filtered_products(?, ?, ?)");
    $stmt->bindValue(1, $search, PDO::PARAM_STR);
    $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $products;

}

}
