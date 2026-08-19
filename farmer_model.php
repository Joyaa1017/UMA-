<?php

class Farmer
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM farmers");
        return $stmt->fetchAll();
    }

    public function delete($user_id)
    {
        try {
            $this->pdo->beginTransaction();

            // Delete from farmers table
            $stmt1 = $this->pdo->prepare("DELETE FROM farmers WHERE user_id = ?");
            $stmt1->execute([$user_id]);

            // Delete from users table
            $stmt2 = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt2->execute([$user_id]);

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new Exception("Failed to delete farmer: " . $e->getMessage());
        }
    }
}
